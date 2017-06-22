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
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->defineResources();

        $this->registerValidators();

        if ($this->app->runningInConsole()) {
            $this->definePublishing();
        }
    }

    /**
     * Define the resources for the package.
     *
     * @return void
     */
    protected function defineResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'toolkit');
    }

    /**
     * Register custom validators and rules.
     *
     * @return void
     */
    protected function registerValidators()
    {
        RequiredIfRule::registerMacro();

        Validator::extend(HashValidator::NAME, HashValidator::class);

        Validator::extend(ReCaptchaValidator::NAME, ReCaptchaValidator::class);
    }

    /**
     * Define the publishing configuration.
     *
     * @return void
     */
    protected function definePublishing()
    {
        $this->publishes([
            __DIR__.'/../config/toolkit.php' => config_path('toolkit.php'),
        ], 'config');

        $timestamp = date('Y_m_d_His', time());

        if (! class_exists('CreateTagsTable')) {
            $this->publishes([
                __DIR__.'/../migrations/create_tags_table.php.stub' => database_path("/migrations/{$timestamp}_create_tags_table.php"),
            ], 'migrations');
        }

        if (! class_exists('CreateRolesTable')) {
            $this->publishes([
                __DIR__.'/../migrations/create_roles_table.php.stub' => database_path("/migrations/{$timestamp}_create_roles_table.php"),
            ], 'migrations');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/toolkit.php', 'toolkit');
    }
}
