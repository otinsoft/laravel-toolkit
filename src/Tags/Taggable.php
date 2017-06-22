<?php

namespace Otinsoft\Toolkit\Tags;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Taggable
{
    /**
     * @var array
     */
    private $queuedTags = [];

    /**
     * Boot has tags trait.
     *
     * @return void
     */
    public static function bootTaggable()
    {
        static::created(function ($taggable) {
            $taggable->attachTags($taggable->queuedTags);

            $taggable->queuedTags = [];
        });
    }

    /**
     * Get the tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(config('toolkit.models.tag'), 'taggable');
    }

    /**
     * Set the tags attribute.
     *
     * @param \Otinsoft\Toolkit\Tags\Tag|string|array $tags
     * @return void
     */
    public function setTagsAttribute($tags)
    {
        if (! $this->exists) {
            $this->queuedTags = $tags;

            return;
        }

        $this->attachTags($tags);
    }

    /**
     * Get the tags as array of strings.
     *
     * @return array
     */
    public function getTagsArrayAttribute(): array
    {
        return $this->relationLoaded('tags')
            ? $this->tags->pluck('name')->toArray()
            : [];
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Database\Eloquent\Collection|array $tags
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAllTags(Builder $query, $tags): Builder
    {
        $tags = static::convertToTags($tags);

        collect($tags)->each(function ($tag) use ($query) {
            $query->whereHas('tags', function ($query) use ($tag) {
                return $query->where('id', $tag ? $tag->id : 0);
            });
        });

        return $query;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Database\Eloquent\Collection|array $tags
     * @param  string $operator
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAnyTags(Builder $query, $tags, string $operator = 'and'): Builder
    {
        $tags = static::convertToTags($tags);

        $method = $operator === 'or' ? 'orWhereHas' : 'whereHas';

        return $query->$method('tags', function ($query) use ($tags) {
            $tagIds = collect($tags)->pluck('id');

            $query->whereIn('id', $tagIds);
        });
    }

    /**
     * Attach multipe tags.
     *
     * @param  \Illuminate\Database\Eloquent\Collection|array $tags
     * @return $this
     */
    public function attachTags($tags)
    {
        Tag::withUser($this->user ?? null, function () use ($tags) {
            $tags = collect(Tag::findOrCreate($tags));

            $this->tags()->syncWithoutDetaching($tags->pluck('id'));
        });

        return $this;
    }

    /**
     * Attach a single tag.
     *
     * @param  \Otinsoft\Toolkit\Tags\Tag|string $tag
     * @return $this
     */
    public function attachTag($tag)
    {
        return $this->attachTags([$tag]);
    }

    /**
     * Detach a collection of tags.
     *
     * @param  \Illuminate\Database\Eloquent\Collection|array $tags
     * @return $this
     */
    public function detachTags($tags)
    {
        $tags = static::convertToTags($tags);

        collect($tags)
            ->filter()
            ->each(function ($tag) {
                $this->tags()->detach($tag);
            });

        return $this;
    }

    /**
     * Detach a single tag.
     *
     * @param  \Otinsoft\Toolkit\Tags\Tag|string $tag
     * @return $this
     */
    public function detachTag($tag)
    {
        return $this->detachTags([$tag]);
    }

    /**
     * Sync tags.
     *
     * @param  \Illuminate\Database\Eloquent\Collection|array $tags
     * @return $this
     */
    public function syncTags($tags)
    {
        Tag::withUser($this->user ?? null, function () use ($tags) {
            $tags = collect(Tag::findOrCreate($tags));

            $this->tags()->sync($tags->pluck('id'));
        });

        return $this;
    }

    /**
     * @param  mixed $values
     * @return \Illuminate\Support\Collection
     */
    protected static function convertToTags($values)
    {
        return collect($values)->map(function ($value) {
            return $value instanceof Tag ? $value : Tag::findFromString($value);
        });
    }
}
