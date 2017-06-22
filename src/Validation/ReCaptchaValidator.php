<?php

namespace Otinsoft\Toolkit\Validation;

use ReCaptcha\ReCaptcha;

class ReCaptchaValidator
{
    /**
     * The name of the validator.
     *
     * @var string
     */
    const NAME = 'recaptcha';

    /**
     * Validate that an attribute is a valid hash.
     *
     * @param  string $attribute
     * @param  string $value
     * @return bool
     */
    public function validate($attribute, $value): bool
    {
        $recaptcha = new ReCaptcha(config('services.recaptcha.secret'));

        return $recaptcha->verify($value, request()->ip())->isSuccess();
    }
}
