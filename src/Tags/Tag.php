<?php

namespace Otinsoft\Toolkit\Tags;

use Otinsoft\Toolkit\Database\Model;

class Tag extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    private static $user;

    /**
     * Find or create tag(s).
     *
     * @param  \Illuminate\Database\Eloquent\Collection|array $values
     * @return \Otinsoft\Toolkit\Tags\Tag|\Illuminate\Support\Collection
     */
    public static function findOrCreate($values)
    {
        $tags = collect($values)->map(function ($value) {
            if ($value instanceof Tag) {
                return $value;
            }

            return static::findOrCreateFromString($value);
        });

        return is_string($values) ? $tags->first() : $tags;
    }

    /**
     * Find a tag by it's name.
     *
     * @param  string $name
     * @return \Otinsoft\Toolkit\Tags\Tag|null
     */
    public static function findFromString(string $name)
    {
        return static::where(compact('name'))->first();
    }

    /**
     * Find or create a new tag.
     *
     * @param  string $name
     * @return \Otinsoft\Toolkit\Tags\Tag
     */
    protected static function findOrCreateFromString(string $name)
    {
        if (! $tag = static::findFromString($name)) {
            $tag = static::create([
                'name' => $name,
                'user_id' => static::$user->id ?? null,
            ]);
        }

        return $tag;
    }

    /**
     * Create tags with the given user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  callable $callback
     * @return void
     */
    public static function withUser($user, callable $callback)
    {
        static::$user = $user;

        $callback();

        static::$user = null;
    }
}
