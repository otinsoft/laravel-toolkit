<?php

namespace App\Validation;

use ReCaptcha\ReCaptcha;

class ReCaptchaValidator
{
    const NAME = 'hash';

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
