<?php

namespace Otinsoft\Toolkit\Tests;

use Otinsoft\Toolkit\Users\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->createUsersTable();

        include_once __DIR__.'/../migrations/create_roles_table.php.stub';
        (new \CreateRolesTable())->up();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_provides_a_role_relation()
    {
        $this->assertInstanceOf(BelongsTo::class, (new User)->role());
    }

    /** @test */
    public function assign_a_role_by_id()
    {
        $role = factory(Role::class)->create();

        $this->user->assignRole($role->id);

        $this->assertEquals($role->id, $this->user->role_id);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'role_id' => $role->id,
        ]);
    }

    /** @test */
    public function determine_if_user_has_role()
    {
        $role = factory(Role::class)->create();
        $this->user = factory(User::class)->create();

        $this->assertFalse($this->user->hasRole($role));
        $this->assertFalse($this->user->hasRole($role->id));
        $this->assertFalse($this->user->hasRole($role->name));

        $this->user->update(['role_id' => $role->id]);
        $this->user->load('role');

        $this->assertTrue($this->user->hasRole($role));
        $this->assertTrue($this->user->hasRole($role->id));
        $this->assertTrue($this->user->hasRole($role->name));

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'role_id' => $role->id,
        ]);
    }

    /** @test */
    public function determine_if_the_user_has_any_role()
    {
        [$role1, $role2] = factory(Role::class, 2)->create();
        $this->user = factory(User::class)->create();

        $this->user->assignRole($role1);

        $this->assertTrue($this->user->hasAnyRole($role1, $role2));
    }

    /** @test */
    public function assign_role()
    {
        $this->user = factory(User::class)->create();

        $role = factory(Role::class)->create();
        $this->user->assignRole($role);
        $this->assertTrue($this->user->hasRole($role));

        $role = factory(Role::class)->create();
        $this->user->assignRole($role->id);
        $this->assertTrue($this->user->hasRole($role));

        $role = factory(Role::class)->create();
        $this->user->assignRole($role->name);
        $this->assertTrue($this->user->hasRole($role));
    }

    /** @test */
    public function query_users_with_a_cenrtain_role()
    {
        $role = factory(Role::class)->create();

        factory(User::class)->create();
        factory(User::class, 2)->create(['role_id' => $role->id]);

        $this->assertCount(2, User::whereRole($role)->get());
        $this->assertCount(2, User::whereRole($role->id)->get());
        $this->assertCount(2, User::whereRole($role->name)->get());
    }
}
