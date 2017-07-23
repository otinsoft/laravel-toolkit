<?php

namespace Otinsoft\Toolkit\Tests;

use Otinsoft\Toolkit\Users\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Otinsoft\Toolkit\Http\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Otinsoft\Toolkit\Http\Settings\UpdatesPhoto;
use Otinsoft\Toolkit\Http\Settings\UpdatesPassword;
use Illuminate\Foundation\Validation\ValidatesRequests;

class SettingsTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('photo')->nullable();
            $table->string('password');
            $table->timestamps();
        });

        $this->user = User::create(['password' => bcrypt('secret')]);

        Route::middleware('web')->namespace(__NAMESPACE__)->group(function () {
            Route::post('photo', 'PhotoController@update');
            Route::delete('photo', 'PhotoController@destroy');
            Route::post('password', 'PasswordController@update');
        });
    }

    /** @test */
    public function can_update_user_photo()
    {
        $photo = new UploadedFile(__DIR__.'/stubs/photo.png', 'photo.png', 'image/png', 1665, null, true);

        $this->actingAs($this->user)
            ->postJson('/photo', ['photo' => $photo])
            ->assertSuccessful();

        $this->assertNotNull($photo = $this->user->fresh()->photo);

        $size = getimagesizefromstring(Storage::disk('photos')->get($photo));

        $this->assertEquals(300, $size[0]);
        $this->assertEquals(300, $size[1]);

        Storage::disk('photos')->delete($this->user->photo);
    }

    /** @test */
    public function can_delete_user_photo()
    {
        $this->user->update(['photo' => 'photo.png']);

        $this->actingAs($this->user)
            ->deleteJson('/photo')
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'photo' => null,
        ]);
    }

    /** @test */
    public function can_update_password()
    {
        $this->actingAs($this->user)
            ->postJson('/password', [
                'password' => 'updated',
                'password_confirmation' => 'updated',
                'current_password' => 'secret',
            ])
            ->assertSuccessful();

        $this->assertTrue(Hash::check('updated', $this->user->password));
    }
}

class PhotoController extends Controller
{
    use ValidatesRequests, UpdatesPhoto;
}

class PasswordController extends Controller
{
    use ValidatesRequests, UpdatesPassword;
}
