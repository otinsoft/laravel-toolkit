<?php

namespace Otinsoft\Toolkit\Files;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany as Relation;

class MorphMany extends Relation
{
    use MorphOneOrManyTrait;

    /**
     * Create a polymorphic one-to-many relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $collectionName
     * @return static
     */
    public static function fromModel(Model $model, string $collectionName)
    {
        return static::fromMorph(
            $model->morphMany(config('toolkit.models.file'), 'model'),
            $collectionName
        );
    }
}
