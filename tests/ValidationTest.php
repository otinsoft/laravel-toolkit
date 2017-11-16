<?php

namespace Otinsoft\Toolkit\Tests;

use Otinsoft\Toolkit\Validation\HashValidator;
use Otinsoft\Toolkit\Validation\RequiredIfRule;
use Otinsoft\Toolkit\Validation\ReCaptchaValidator;

class ValidationTest extends TestCase
{
    /** @test */
    function required_if_rule()
    {
        $rule = new RequiredIfRule('otherfield', ['foo', 'bar']);

        $this->assertEquals('required_if:otherfield,foo,bar', (string) $rule);
    }

    /** @test */
    function hash_validator()
    {
        $v = new HashValidator;

        $this->assertEquals('hash', HashValidator::NAME);
        $this->assertTrue($v->validate('password', 'secret', [bcrypt('secret')]));
        $this->assertFalse($v->validate('password', 'secret', ['secret']));
    }

    /** @test */
    function recaptcha_validator()
    {
        $v = new ReCaptchaValidator;

        $this->assertEquals('recaptcha', ReCaptchaValidator::NAME);
    }
}
