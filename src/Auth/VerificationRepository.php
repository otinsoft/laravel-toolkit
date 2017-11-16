<?php

namespace Otinsoft\Toolkit\Auth;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Auth\Authenticatable;

class VerificationRepository
{
    const LINK_SENT = 'verification.sent';
    const VERIFIED = 'verification.verified';
    const INVALID_USER = 'verification.user';
    const INVALID_TOKEN = 'verification.token';
    const ALREADY_VERIFIED = 'verification.already_verified';

    /**
     * The active database connection.
     *
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $conn;

    /**
     * The table containing the users.
     *
     * @var string
     */
    protected $table = 'verification_tokens';

    /**
     * The user provider implementation.
     *
     * @var \Illuminate\Contracts\Auth\UserProvider
     */
    protected $users;

    /**
     * Create a new instance.
     *
     * @param  \Illuminate\Database\ConnectionInterface $conn
     * @return void
     */
    public function __construct(ConnectionInterface $conn)
    {
        $this->conn = $conn;
        $this->users = Auth::createUserProvider('users');
    }

    /**
     * Send a verification link to a user.
     *
     * @param  array $credentials
     * @return string
     */
    public function sendVerificationLink(array $credentials)
    {
        $user = $this->users->retrieveByCredentials($credentials);

        if (is_null($user)) {
            return static::INVALID_USER;
        }

        if ($user->isVerified()) {
            return self::ALREADY_VERIFIED;
        }

        $user->sendVerificationNotification(
            $this->createToken($user)
        );

        return self::LINK_SENT;
    }

    /**
     * Verify user by token.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $token
     * @return string|\Illuminate\Contracts\Auth\Authenticatable
     */
    public function verify($token)
    {
        $userId = $this->conn->table($this->table)
            ->where('token', $token)->value('user_id');

        if (is_null($userId)) {
            return self::INVALID_TOKEN;
        }

        $user = $this->users->retrieveById($userId);

        if (is_null($user)) {
            return static::INVALID_USER;
        }

        $this->deleteTokens($user);

        return tap($user)->verify();
    }

    /**
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @return string
     */
    protected function createToken(Authenticatable $user): string
    {
        $this->deleteTokens($user);

        $token = hash_hmac('sha256', Str::random(40), config('app.key'));

        $this->conn->table($this->table)->insert([
            'token' => $token,
            'user_id' => $user->getAuthIdentifier(),
            'created_at' => Carbon::now(),
        ]);

        return $token;
    }

    /**
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @return void
     */
    protected function deleteTokens(Authenticatable $user)
    {
        $this->conn->table($this->table)
            ->where('user_id', $user->getAuthIdentifier())
            ->delete();
    }
}
