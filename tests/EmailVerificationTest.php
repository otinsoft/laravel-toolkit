<?php

namespace Otinsoft\Toolkit\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Otinsoft\Toolkit\Http\Controller;
use Illuminate\Support\Facades\Notification;
use Otinsoft\Toolkit\Auth\EmailVerification;
use Otinsoft\Toolkit\Auth\VerificationRepository;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Otinsoft\Toolkit\Notifications\Verification as VerificationNotification;

class EmailVerificationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->createUsersTable();

        include_once __DIR__.'/../migrations/create_verification_tokens_table.php.stub';
        (new \CreateVerificationTokensTable())->up();

        Route::middleware('web')->namespace(__NAMESPACE__)->group(function () {
            Route::post('verification/email', 'VerificationController@sendVerificationLinkEmail');
            Route::post('verification/{token}', 'VerificationController@verify');
            Route::get('verification/{token}', 'VerificationController@verify')->name('verification');
        });
    }

    /** @test */
    public function email_must_be_valid()
    {
        $this->postJson('/verification/email', ['email' => 'invalid'])
            ->assertStatus(422);
    }

    /** @test */
    public function a_user_with_the_given_email_must_exist()
    {
        $this->postJson('/verification/email', ['email' => 'test@example.com'])
            ->assertStatus(422)
            ->assertJsonFragment(['email' => [VerificationRepository::INVALID_USER]]);
    }

    /** @test */
    public function can_not_verify_if_already_verified()
    {
        $user = factory(User::class)->create();

        $this->postJson('/verification/email', ['email' => $user->email])
            ->assertStatus(422)
            ->assertJsonFragment(['email' => [VerificationRepository::ALREADY_VERIFIED]]);
    }

    /** @test */
    public function creates_token_and_sends_notification()
    {
        Notification::fake();

        $user = factory(User::class)->create(['verified' => false]);

        $this->postJson('/verification/email', ['email' => $user->email])
            ->assertSuccessful()
            ->assertJson(['status' => VerificationRepository::LINK_SENT]);

        $this->assertNotNull($token = DB::table('verification_tokens')->value('token'));

        Notification::assertSentTo(
            $user,
            VerificationNotification::class,
            function ($notification, $channels) use ($token) {
                return $notification->token === $token;
            }
        );
    }

    /** @test */
    public function deletes_previous_tokens_if_a_new_one_is_created()
    {
        Notification::fake();

        $user = factory(User::class)->create(['verified' => false]);

        $this->postJson('/verification/email', ['email' => $user->email]);
        $this->postJson('/verification/email', ['email' => $user->email]);

        $this->assertCount(1, DB::table('verification_tokens')->get());
    }

    /** @test */
    public function can_verify_user()
    {
        Notification::fake();

        $user = factory(User::class)->create(['verified' => false]);
        $this->postJson('/verification/email', ['email' => $user->email]);

        $token = DB::table('verification_tokens')->value('token');

        $this->postJson("/verification/$token")
            ->assertSuccessful();

        $this->assertDatabaseHas('users', ['verified' => true]);

        $this->assertCount(0, DB::table('verification_tokens')->get());
    }

    /** @test */
    public function can_not_verify_invalid_token()
    {
        $this->postJson('/verification/invalid-token')
            ->assertStatus(400)
            ->assertJson(['error' => VerificationRepository::INVALID_TOKEN]);
    }
}

class VerificationController extends Controller
{
    use ValidatesRequests, EmailVerification;

    protected function sendVerificationResponse(Request $request, $user)
    {
        return $user;
    }
}
