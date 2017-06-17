<?php

namespace Otinsoft\Toolkit\JavaScript;

use Illuminate\Support\HtmlString;

class Polyfill
{
    private static $features = [
        'Promise',
        'Object.assign',
        'Object.values',
        'Array.prototype.find',
        'Array.prototype.findIndex',
        'Array.prototype.includes',
        'String.prototype.includes',
        'String.prototype.startsWith',
        'String.prototype.endsWith',
    ];

    /**
     * Set the features to polyfill.
     *
     * @param  array $features
     * @return void
     */
    public function set(array $features)
    {
        static::$features = $features;
    }

    /**
     * Add features to polyfill.
     *
     * @param  string|array $features
     * @return void
     */
    public static function add($features)
    {
        static::$features = array_merge(
            static::$features,
            (array) $features
        );
    }

    /**
     * Remove features.
     *
     * @param  string|array $features
     * @return void
     */
    public function remove($features)
    {
        foreach ((array) $features as $feature) {
            unset(static::$features[$feature]);
        }
    }

    /**
     * Render the polyfill script.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function render(): HtmlString
    {
        return new HtmlString(
            '<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features='.implode(',', static::$features).'"></script>'
        );
    }
}
