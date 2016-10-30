<?php

namespace ShiftOneLabs\LaravelDbEvents\Tests;

use Mockery as m;
use ReflectionMethod;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use ShiftOneLabs\LaravelDbEvents\Tests\Stubs\PdoStub;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = new Application();
        $app->register('Illuminate\Database\DatabaseServiceProvider');
        $app->register('ShiftOneLabs\LaravelDbEvents\LaravelDbEventsServiceProvider');

        // Laravel 4 uses the LoaderInterface, Laravel 5 does not.
        if (interface_exists('Illuminate\Config\LoaderInterface')) {
            $app->instance('config', $config = new Repository(m::mock('Illuminate\Config\LoaderInterface'), 'testing'));
            $config->getLoader()->shouldReceive('load')->andReturn([]);
        } else {
            $app->instance('config', $config = new Repository([]));
        }

        $app['config']['database'] = [
            'default' => 'valid',
            'connections' => [
                'valid' => [
                    'driver' => 'sqlite',
                    'database' => ':memory:',
                ],
                'invalid' => [
                    'driver' => 'sqlite',
                    'database' => 'memory',
                ],
            ],
        ];

        return $app;
    }

    protected function getConnection($name = null) {
        return $this->app['db']->connection($name);
    }

    protected function getConnector($driver)
    {
        return $this->app->make('db.connector.'.$driver);
    }

    protected function getDbFactoryConnector($driver)
    {
        return $this->app['db.factory']->createConnector(['driver' => $driver]);
    }

    protected function bindMockedConnector($driver)
    {
        $original = $this->getConnector($driver);

        $this->app->bind('db.connector.'.$driver, function ($app) use ($original) {
            $connector = m::mock(get_class($original))->shouldAllowMockingProtectedMethods()->makePartial();
            if ($original->usingEvents()) {
                $connector->setEventDispatcher($original->getEventDispatcher());
            }

            return $connector;
        });
    }

    protected function getMockedConnector($driver, $withEvents = true)
    {
        $this->bindMockedConnector($driver);
        $connector = $this->getConnector($driver);
        if ($withEvents && !$connector->usingEvents() && $this->app->bound('events')) {
            $connector->setEventDispatcher($this->app['events']);
        }

        return $connector;
    }

    protected function getPdoStubConnector($driver, $withEvents = true)
    {
        $connector = $this->getMockedConnector($driver, $withEvents);
        $connector->shouldReceive('parentConnect')->andReturn(new PdoStub());

        return $connector;
    }

    protected function callRestrictedMethod($object, $method, array $args = [])
    {
        $reflectionMethod = new ReflectionMethod($object, $method);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invokeArgs($object, $args);
    }
}
