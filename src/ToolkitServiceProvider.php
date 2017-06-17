<?php

namespace Otinsoft\Toolkit;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Otinsoft\Toolkit\Validation\HashValidator;
use Otinsoft\Toolkit\Validation\RequiredIfRule;
use Otinsoft\Toolkit\Validation\ReCaptchaValidator;

class ToolkitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->defineResources();

        $this->registerValidators();
    }

    /**
     * @return void
     */
    protected function defineResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'toolkit');
    }

    /**
     * @return void
     */
    protected function registerValidators()
    {
        Validator::extend(HashValidator::NAME, HashValidator::class);

        Validator::extend(ReCaptchaValidator::NAME, ReCaptchaValidator::class);

        Rule::macro('requiredIf', function ($otherfield, $values) {
            return new RequiredIfRule($otherfield, $values);
        });
    }
}
