<?php

namespace Otinsoft\Toolkit\Tests;

use Otinsoft\Toolkit\Users\Role;
use Otinsoft\Toolkit\Users\HasRole;
use Illuminate\Support\Facades\Schema;
use Otinsoft\Toolkit\Users\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoleTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        include_once __DIR__.'/../migrations/create_roles_table.php.stub';
        (new \CreateRolesTable())->up();

        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('role_id')->unsigned()->nullable();
        });
    }

    /** @test */
    public function it_provides_a_role_relation()
    {
        $this->assertInstanceOf(BelongsTo::class, (new User)->role());
    }

    /** @test */
    public function assign_a_role_by_id()
    {
        $role = $this->createRole();
        $user = $this->createUser();

        $user->assignRole($role->id);

        $this->assertEquals($role->id, $user->role_id);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    /** @test */
    public function determine_if_user_has_role()
    {
        $role = $this->createRole();
        $user = $this->createUser();

        $this->assertFalse($user->hasRole($role));
        $this->assertFalse($user->hasRole($role->id));
        $this->assertFalse($user->hasRole($role->name));

        $user->update(['role_id' => $role->id]);
        $user->load('role');

        $this->assertTrue($user->hasRole($role));
        $this->assertTrue($user->hasRole($role->id));
        $this->assertTrue($user->hasRole($role->name));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    /** @test */
    public function determine_if_the_user_has_any_role()
    {
        $role1 = $this->createRole();
        $role2 = $this->createRole();
        $user = $this->createUser();

        $user->assignRole($role1);

        $this->assertTrue($user->hasAnyRole($role1, $role2));
    }

    /** @test */
    public function assigne_role()
    {
        $user = $this->createUser();

        $role = $this->createRole();
        $user->assignRole($role);
        $this->assertTrue($user->hasRole($role));

        $role = $this->createRole();
        $user->assignRole($role->id);
        $this->assertTrue($user->hasRole($role));

        $role = $this->createRole();
        $user->assignRole($role->name);
        $this->assertTrue($user->hasRole($role));
    }

    /** @test */
    public function query_users_with_a_cenrtain_role()
    {
        $role = $this->createRole();

        $this->createUser();
        $this->createUser(['role_id' => $role->id]);
        $this->createUser(['role_id' => $role->id]);

        $this->assertCount(2, User::whereRole($role)->get());
        $this->assertCount(2, User::whereRole($role->id)->get());
        $this->assertCount(2, User::whereRole($role->name)->get());
    }

    protected function createRole()
    {
        return Role::create(['name' => 'role_'.mt_rand()]);
    }

    protected function createUser(array $attributes = [])
    {
        return User::create(['name' => mt_rand()] + $attributes);
    }
}

class User extends Authenticatable
{
    use HasRole;

    public $timestamps = false;
}
