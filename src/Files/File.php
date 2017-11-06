<?php

namespace Otinsoft\Toolkit\Files;

use Illuminate\Support\Str;
use Otinsoft\Toolkit\Database\Model;
use Illuminate\Support\Facades\Storage;
use Otinsoft\Toolkit\Users\BelongsToUser;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    use BelongsToUser;

    const IMAGE = 'image';
    const VIDEO = 'video';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
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
     * Get all of the owning fileable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function fileable(): MorphTo
    {
        return $this->morphTo();
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
