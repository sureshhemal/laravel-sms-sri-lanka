<?php

namespace Sureshhemal\SmsSriLanka\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Sureshhemal\SmsSriLanka\Providers\SmsServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            SmsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        // Merge the package config before providers are registered
        $config = require __DIR__.'/../config/sms-sri-lanka.php';
        $config['providers']['hutch']['config']['username'] = 'test_user';
        $config['providers']['hutch']['config']['password'] = 'test_pass';
        $config['providers']['hutch']['config']['default_mask'] = 'TestMask';
        $app['config']->set('sms-sri-lanka', $config);
    }
}
