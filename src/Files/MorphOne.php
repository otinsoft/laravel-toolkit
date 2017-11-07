<?php

namespace Otinsoft\Toolkit\Files;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne as Relation;

class MorphOne extends Relation
{
    use MorphOneOrManyTrait;

    /**
     * Create a polymorphic one-to-one relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $collectionName
     * @return static
     */
    public static function fromModel(Model $model, string $collectionName)
    {
        return static::fromMorph(
            $model->morphOne(config('toolkit.models.file'), 'model'),
            $collectionName
        );
    }
}
