<?php

namespace Otinsoft\Toolkit\Users;

use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Otinsoft\Toolkit\Database\Concerns\DeleteOrFail;
use Otinsoft\Toolkit\Database\Concerns\SerializeDate;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Tymon\JWTAuth\Contracts\JWTSubject;
// use Otinsoft\Toolkit\Auth\HasVerification;

class User extends Authenticatable // implements JWTSubject
{
    use Notifiable,
        DeleteOrFail,
        SerializeDate;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'last_login',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * Accessor for the first name.
     *
     * @return string
     */
    public function getFirstNameAttribute()
    {
        return explode(' ', $this->name)[0] ?? $this->name;
    }

    /**
     * Accessor for the first name.
     *
     * @return string
     */
    public function getLastNameAttribute()
    {
        return explode(' ', $this->name)[1] ?? $this->name;
    }

    /**
     * Update the last login timestamp.
     *
     * @return $this
     */
    public function updateLastLogin()
    {
        return tap($this)->update(['last_login' => $this->freshTimestamp()]);
    }

    /**
     * Verify if the given password matches the current one.
     *
     * @param  string $password
     * @return bool
     */
    public function verifyPassword($password)
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Find user by email.
     *
     * @param  string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::where('email', $email)->first();
    }

    /**
     * Find user by username.
     *
     * @param  string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::where('username', $username)->first();
    }
}
