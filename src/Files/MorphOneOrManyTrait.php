<?php

namespace Otinsoft\Toolkit\Files;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;

trait MorphOneOrManyTrait
{
    /**
     * @var string
     */
    protected $collectionName;

    /**
     * @param  \Illuminate\Database\Eloquent\Relations\MorphOneOrMany $morph
     * @param  string $collectionName
     * @return static
     */
    public static function fromMorph(MorphOneOrMany $morph, string $collectionName)
    {
        $morph->where('collection_name', $collectionName);

        return (new static(
            $morph->getQuery(),
            $morph->getParent(),
            $morph->getQualifiedMorphType(),
            $morph->getQualifiedForeignKeyName(),
            $morph->getParent()->getKeyName()
        ))->withCollection($collectionName);
    }

    /**
     * Set the collection name.
     *
     * @param  string $collectionName
     * @return $this
     */
    public function withCollection(string $collectionName)
    {
        $this->collectionName = $collectionName;

        return $this;
    }

    /**
     * Attach a model instance to the parent model.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(Model $model)
    {
        $model->setAttribute($this->getMorphType(), $this->morphClass);
        $model->setAttribute('collection_name', $this->collectionName);

        return parent::save($model);
    }
}
