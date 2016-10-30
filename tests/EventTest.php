<?php
namespace ShiftOneLabs\LaravelDbEvents\Tests;

use Mockery as m;
use ShiftOneLabs\LaravelDbEvents\Tests\Stubs\PdoStub;

class EventTest extends TestCase
{

    public function testMysqlConnectingEventGetsParameters()
    {
        $this->driverConnectingEventGetsParameters('mysql');
    }

    public function testPgsqlConnectingEventGetsParameters()
    {
        $this->driverConnectingEventGetsParameters('pgsql');
    }

    public function testSqliteConnectingEventGetsParameters()
    {
        $this->driverConnectingEventGetsParameters('sqlite');
    }

    public function testSqlsrvConnectingEventGetsParameters()
    {
        $this->driverConnectingEventGetsParameters('sqlsrv');
    }

    public function driverConnectingEventGetsParameters($driver)
    {
        if (!$this->app->bound('events')) {
            $this->markTestSkipped('The Illuminate Event Dispatcher is not available.');
            return;
        }

        $config = ['name' => $driver];

        $_SERVER['__event.test.DatabaseConnecting'] = null;
        $events = $this->app['events'];
        $events->listen('ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnecting', function ($event) {
            $_SERVER['__event.test.DatabaseConnecting'] = $event;
        });

        $connector = $this->getPdoStubConnector($driver);
        $connector->connect($config);
        $event = $_SERVER['__event.test.DatabaseConnecting'];

        $this->assertInstanceOf('ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnecting', $event);
        $this->assertSame($connector, $event->connector);
        $this->assertEquals($driver, $event->connectionName);
        $this->assertEquals($config, $event->config);
    }

    public function testMysqlConnectedEventGetsParameters()
    {
        $this->driverConnectedEventGetsParameters('mysql');
    }

    public function testPgsqlConnectedEventGetsParameters()
    {
        $this->driverConnectedEventGetsParameters('pgsql');
    }

    public function testSqliteConnectedEventGetsParameters()
    {
        $this->driverConnectedEventGetsParameters('sqlite');
    }

    public function testSqlsrvConnectedEventGetsParameters()
    {
        $this->driverConnectedEventGetsParameters('sqlsrv');
    }

    public function driverConnectedEventGetsParameters($driver)
    {
        if (!$this->app->bound('events')) {
            $this->markTestSkipped('The Illuminate Event Dispatcher is not available.');
            return;
        }

        $config = ['name' => $driver];

        $_SERVER['__event.test.DatabaseConnected'] = null;
        $events = $this->app['events'];
        $events->listen('ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnected', function ($event) {
            $_SERVER['__event.test.DatabaseConnected'] = $event;
        });

        $connector = $this->getPdoStubConnector($driver);
        $connector->connect($config);
        $event = $_SERVER['__event.test.DatabaseConnected'];

        $this->assertInstanceOf('ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnected', $event);
        $this->assertSame($connector, $event->connector);
        $this->assertEquals($driver, $event->connectionName);
        $this->assertEquals($config, $event->config);
        $this->assertInstanceOf('ShiftOneLabs\LaravelDbEvents\Tests\Stubs\PdoStub', $event->pdo);
    }

    public function testMysqlConnectingEventCanModifyConfig()
    {
        $this->driverConnectingEventCanModifyConfig('mysql');
    }

    public function testPgsqlConnectingEventCanModifyConfig()
    {
        $this->driverConnectingEventCanModifyConfig('pgsql');
    }

    public function testSqliteConnectingEventCanModifyConfig()
    {
        $this->driverConnectingEventCanModifyConfig('sqlite');
    }

    public function testSqlsrvConnectingEventCanModifyConfig()
    {
        $this->driverConnectingEventCanModifyConfig('sqlsrv');
    }

    public function driverConnectingEventCanModifyConfig($driver)
    {
        if (!$this->app->bound('events')) {
            $this->markTestSkipped('The Illuminate Event Dispatcher is not available.');
            return;
        }

        $events = $this->app['events'];
        $events->listen('ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnecting', function ($event) {
            $event->config['modified'] = true;
        });

        $_SERVER['__event.test.parameter'] = [];
        $connector = $this->getMockedConnector($driver);
        $connector->shouldReceive('parentConnect')
            ->with(m::on(function ($config) {
                $_SERVER['__event.test.parameter'] = $config;
                return true;
            }))
            ->andReturn(new PdoStub());

        $connector->connect(['name' => $driver]);

        $this->assertTrue(array_key_exists('modified', $_SERVER['__event.test.parameter']));
    }
}
