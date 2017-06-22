<?php

namespace Otinsoft\Toolkit\Validation;

use Illuminate\Validation\Rule;

class RequiredIfRule
{
    /**
     * The name of the macro.
     *
     * @var string
     */
    const MACRO_NAME = 'requiredIf';

    /**
     * The name of the rule.
     *
     * @var string
     */
    protected $rule = 'required_if';

    /**
     * The other field.
     *
     * @var array
     */
    protected $otherfield;

    /**
     * The accepted values.
     *
     * @var array
     */
    protected $values;

    /**
     * Create a new in rule instance.
     *
     * @param  string $otherfield
     * @param  string|array $values
     * @return void
     */
    public function __construct(string $otherfield, $values)
    {
        $this->otherfield = $otherfield;
        $this->values = (array) $values;
    }

    /**
     * Register the rule mcro.
     *
     * @return void
     */
    public static function registerMacro()
    {
        Rule::macro(self::MACRO_NAME, function ($otherfield, $values) {
            return new static($otherfield, $values);
        });
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->rule.':'.$this->otherfield.','.implode(',', $this->values);
    }
}
