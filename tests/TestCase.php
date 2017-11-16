<?php

namespace Otinsoft\Toolkit\Tests;

use Illuminate\Support\Facades\Schema;
use Otinsoft\Toolkit\ToolkitServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/factories');
    }

    protected function createUsersTable()
    {
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('photo')->nullable();
            $table->string('password');
            $table->integer('role_id')->unsigned()->nullable();
            $table->boolean('verified')->default(true);
        });
    }

    /**
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.disks.photos', [
            'driver' => 'local',
            'visibility' => 'public',
            'root' => __DIR__.'/temp/photos',
        ]);

        $app['config']->set('auth.providers.users.model', User::class);
    }

    /**
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [ToolkitServiceProvider::class];
    }
}
