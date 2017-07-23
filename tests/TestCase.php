<?php

namespace Otinsoft\Toolkit\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('filesystems.disks.photos', [
            'driver' => 'local',
            'visibility' => 'public',
            'root' => __DIR__.'/temp/photos',
        ]);
    }

    /**
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            \Otinsoft\Toolkit\ToolkitServiceProvider::class,
        ];
    }
}
