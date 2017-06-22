<?php

namespace Otinsoft\Toolkit\Validation;

use Illuminate\Support\Facades\Hash;

class HashValidator
{
    /**
     * The name of the validator.
     *
     * @var string
     */
    const NAME = 'hash';

    /**
     * Validate that an attribute is a valid hash.
     *
     * @param  string $attribute
     * @param  string $value
     * @param  array  $parameters
     * @return bool
     */
    public function validate($attribute, $value, $parameters): bool
    {
        return Hash::check($value, $parameters[0]);
    }
}
