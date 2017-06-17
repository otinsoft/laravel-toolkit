<?php

namespace App\Validation;

class RequiredIfRule
{
    /**
     * The name of the rule.
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
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->rule.':'.$this->otherfield.','.implode(',', $this->values);
    }
}
