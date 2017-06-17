<?php

namespace Otinsoft\Toolkit\Users;

use Illuminate\Support\Str;
// use Tymon\JWTAuth\Contracts\JWTSubject;
// use Illuminate\Support\Facades\Storage;
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    // protected $appends = [
    //     'photo_url'
    // ];

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
     * Get the photo url attribute.
     *
     * @return string
     */
    public function getPhotoUrlAttribute(): string
    {
        if (Str::startsWith($this->photo, 'http')) {
            return $this->photo;
        }

        if (! empty($this->photo)) {
            return Storage::disk('photos')->url($this->photo);
        }

        return asset('img/default-photo.png');

        // $id = md5(strtolower(trim($this->email)));

        // return "https://www.gravatar.com/avatar/{$id}/?d=identicon&s=300&r=g";
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
}
