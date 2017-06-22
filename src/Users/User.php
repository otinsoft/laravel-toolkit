<?php

namespace Otinsoft\Toolkit\Users;

use Illuminate\Support\Str;
// use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Otinsoft\Toolkit\Database\Concerns\DeleteOrFail;
use Otinsoft\Toolkit\Database\Concerns\SerializeDate;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable //implements JWTSubject
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
     * Update the last login timestamp.
     *
     * @return $this
     */
    public function updateLastLogin()
    {
        return tap($this)->update(['last_login' => $this->freshTimestamp()]);
    }
}
