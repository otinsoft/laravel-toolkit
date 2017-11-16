<?php

namespace Otinsoft\Toolkit\Tests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Otinsoft\Toolkit\Validation\HashValidator;
use Otinsoft\Toolkit\Validation\RequiredIfRule;
use Otinsoft\Toolkit\Validation\ReCaptchaValidator;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function can_register_validators()
    {
        $v = Validator::make([], []);
        $this->assertArrayHasKey(HashValidator::NAME, $v->extensions);
        $this->assertArrayHasKey(ReCaptchaValidator::NAME, $v->extensions);
    }

    /** @test */
    public function can_register_rules()
    {
        $this->assertTrue(Rule::hasMacro(RequiredIfRule::MACRO_NAME));
    }
}
