<?php

namespace Otinsoft\Toolkit\Files;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasFiles
{
    public function morphOneFile(string $collectionName = 'default'): MorphOne
    {
        return $this->morphOne(config('toolkit.models.file'), 'fileable')
                    ->where('collection_name', $collectionName);
    }

    public function morphManyFiles(string $collectionName = 'default'): MorphMany
    {
        return $this->morphMany(config('toolkit.models.file'), 'fileable')
                    ->where('collection_name', $collectionName);
    }

    public function files(string $collectionName = 'default'): MorphMany
    {
        return $this->morphManyFiles($collectionName);
    }
}
