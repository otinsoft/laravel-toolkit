<?php

namespace Otinsoft\Toolkit\Users;

use Illuminate\Database\Eloquent\Builder;

trait UserScope
{
    /**
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int|\Illuminate\Database\Eloquent\Model $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereUser(Builder $query, $user): Builder
    {
        return $query->where('user_id', $user->id ?: $user);
    }
}
