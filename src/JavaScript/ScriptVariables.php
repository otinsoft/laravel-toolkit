<?php

namespace Otinsoft\Toolkit\JavaScript;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

class ScriptVariables
{
    /**
     * @var array
     */
    protected static $variables = [];

    /**
     * Add a variable.
     *
     * @param array|string|\Closure $key
     * @param mixed                 $value
     * @return void
     */
    public static function add($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $innerKey => $innerValue) {
                Arr::set(static::$variables, $innerKey, $innerValue);
            }
        } elseif ($key instanceof Closure) {
            static::$variables[] = $key;
        } else {
            Arr::set(static::$variables, $key, $value);
        }
    }

    /**
     * Clear all the variables.
     *
     * @return void
     */
    public static function clear()
    {
        static::$variables = [];
    }

    /**
     * Render as a HTML string.
     *
     * @param  string $namespace
     * @return \Illuminate\Support\HtmlString
     */
    public static function render($namespace = 'config'): HtmlString
    {
        foreach (static::$variables as $key => $variable) {
            if ($variable instanceof Closure) {
                $variable = $variable();

                if (is_array($variable)) {
                    static::add($variable);
                }
            }
        }

        $variables = array_filter(static::$variables, function ($variable) {
            return ! $variable instanceof Closure;
        });

        return new HtmlString(
            '<script>window.'.$namespace.' = '.json_encode($variables).';</script>'
        );
    }
}
