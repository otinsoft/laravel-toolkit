<?php

namespace Otinsoft\Toolkit\Users;

use Otinsoft\Toolkit\Database\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    const USER = 'user';
    const ADMIN = 'admin';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * Get the users for the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(config('app.auth.providers.users.model'));
    }

    /**
     * Find a role by name.
     *
     * @param  string $name
     * @return \App\Users\Role|null
     */
    public static function findByName(string $name)
    {
        return static::where('name', $name)->first();
    }
}
