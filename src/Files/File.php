<?php

namespace Otinsoft\Toolkit\Files;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Otinsoft\Toolkit\Users\BelongsToUser;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Otinsoft\Toolkit\Database\Concerns\DeleteOrFail;
use Otinsoft\Toolkit\Database\Concerns\SerializeDate;

class File extends Model
{
    use DeleteOrFail,
        SerializeDate,
        BelongsToUser;

    const IMAGE = 'image';
    const VIDEO = 'video';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'model_id' => 'integer',
        'size' => 'integer',
        'duration' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url',
    ];

    /**
     * Get the owning model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Save the owning model.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $collectionName
     * @return $this
     */
    public function saveModel(Model $model, string $collectionName = 'default')
    {
        $this->model()->associate($model);
        $this->update(['collection_name' => $collectionName]);

        return $this;
    }

    /**
     * Get the file url attribute.
     *
     * @return string|null
     */
    public function getUrlAttribute(): ?string
    {
        if (Str::startsWith($this->path, 'http')) {
            return $this->path;
        }

        return $this->disk()->url($this->path);
    }

    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function disk()
    {
        return Storage::disk($this->disk);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::deleted(function ($file) {
            $file->disk()->delete($file->path);
        });
    }
}
