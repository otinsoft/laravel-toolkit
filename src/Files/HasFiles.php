<?php

namespace Otinsoft\Toolkit\Files;

trait HasFiles
{
    /**
     * Define a polymorphic one-to-one relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $collectionName
     * @return static
     */
    public function morphOneFile(string $collectionName = 'default')
    {
        return MorphOne::fromModel($this, $collectionName);
    }

    /**
     * Define a polymorphic one-to-many relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $collectionName
     * @return static
     */
    public function morphManyFiles(string $collectionName = 'default')
    {
        return MorphMany::fromModel($this, $collectionName);
    }

    /**
     * Get all of the model's files.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function files()
    {
        return $this->morphMany(config('toolkit.models.file'), 'model');
    }
}
