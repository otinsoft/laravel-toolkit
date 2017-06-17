<?php

namespace Otinsoft\Toolkit\Database\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait DeleteOrFail
{
    /**
     * Delete a model by its primary key or throw an exception.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed $id
     * @return mixed
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function scopeDeleteOrFail(Builder $query, $id)
    {
        return static::findOrFail($id)->delete();
    }
}
