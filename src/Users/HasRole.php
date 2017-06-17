<?php

namespace Otinsoft\Toolkit\Users;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasRole
{
    /**
     * Get the user's role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Assign the given role to the user.
     *
     * @param  int|string|\App\Models\Role $role
     * @return $this
     */
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::findByName($role);
        } else if (is_numeric($role)) {
            $role = Role::findOrFail($role);
        }

        $this->role()->associate($role);
        $this->save();

        return $this;
    }

    /**
     * Determine if the user has the given role.
     *
     * @param  int|string|\App\Users\Role $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        if ($role instanceof Role) {
            $role = $role->name;
        } else if (is_numeric($role)) {
            $role = Role::findOrFail($role)->name;
        }

        return $this->role && $this->role->name === $role;
    }

    /**
     * Determine if the user has any of the given roles.
     *
     * @param  string ...$roles
     * @return bool
     */
    public function hasAnyRole(...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Scope a query to only users with a given role.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param  string|\App\Users\Role $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereRole(Builder $query, $role): Builder
    {
        if (is_string($role)) {
            $role = Role::findByName($role);
        } else if (is_numeric($role)) {
            $role = Role::findOrFail($role);
        }

        return $query->where('role_id', $role->id);
    }
}
