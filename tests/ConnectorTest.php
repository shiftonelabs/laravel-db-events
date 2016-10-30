<?php
namespace ShiftOneLabs\LaravelDbEvents\Tests;

use Mockery as m;

class ConnectorTest extends TestCase
{

    public function testDbFactoryMakesExtendedMysqlConnector()
    {
        $connector = $this->getDbFactoryConnector('mysql');

        $this->assertInstanceOf('ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors\MySqlConnector', $connector);
    }

    public function testDbFactoryMakesExtendedPgsqlConnector()
    {
        $connector = $this->getDbFactoryConnector('pgsql');

        $this->assertInstanceOf('ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors\PostgresConnector', $connector);
    }

    public function testDbFactoryMakesExtendedSqliteConnector()
    {
        $connector = $this->getDbFactoryConnector('sqlite');

        $this->assertInstanceOf('ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors\SQLiteConnector', $connector);
    }

    public function testDbFactoryMakesExtendedSqlsrvConnector()
    {
        $connector = $this->getDbFactoryConnector('sqlsrv');

        $this->assertInstanceOf('ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors\SqlServerConnector', $connector);
    }

    public function testMysqlConnectorUsesEventsByDefault()
    {
        $this->driverConnectorUsesEventsByDefault('mysql');
    }

    public function testPgsqlConnectorUsesEventsByDefault()
    {
        $this->driverConnectorUsesEventsByDefault('pgsql');
    }

    public function testSqliteConnectorUsesEventsByDefault()
    {
        $this->driverConnectorUsesEventsByDefault('sqlite');
    }

    public function testSqlsrvConnectorUsesEventsByDefault()
    {
        $this->driverConnectorUsesEventsByDefault('sqlsrv');
    }

    public function driverConnectorUsesEventsByDefault($driver)
    {
        $connector = $this->getConnector($driver);

        $this->assertTrue($connector->usingEvents());
    }

    public function testMysqlReboundConnectorUsesPdoStub()
    {
        $this->driverReboundConnectorUsesPdoStub('mysql');
    }

    public function testPgsqlReboundConnectorUsesPdoStub()
    {
        $this->driverReboundConnectorUsesPdoStub('pgsql');
    }

    public function testSqliteReboundConnectorUsesPdoStub()
    {
        $this->driverReboundConnectorUsesPdoStub('sqlite');
    }

    public function testSqlsrvReboundConnectorUsesPdoStub()
    {
        $this->driverReboundConnectorUsesPdoStub('sqlsrv');
    }

    public function driverReboundConnectorUsesPdoStub($driver)
    {
        $connector = $this->getPdoStubConnector($driver, false);

        $this->assertInstanceOf('ShiftOneLabs\LaravelDbEvents\Tests\Stubs\PdoStub', $connector->connect([]));
    }

    public function testMysqlFiresConnectingEventWhenUsingEvents()
    {
        $this->driverFiresConnectingEventWhenUsingEvents('mysql');
    }

    public function testPgsqlFiresConnectingEventWhenUsingEvents()
    {
        $this->driverFiresConnectingEventWhenUsingEvents('pgsql');
    }

    public function testSqliteFiresConnectingEventWhenUsingEvents()
    {
        $this->driverFiresConnectingEventWhenUsingEvents('sqlite');
    }

    public function testSqlsrvFiresConnectingEventWhenUsingEvents()
    {
        $this->driverFiresConnectingEventWhenUsingEvents('sqlsrv');
    }

    public function driverFiresConnectingEventWhenUsingEvents($driver)
    {
        if (!$this->app->bound('events')) {
            $this->markTestSkipped('The Illuminate Event Dispatcher is not available.');
            return;
        }

        $_SERVER['__event.test.DatabaseConnecting'] = false;
        $events = $this->app['events'];
        $events->listen('ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnecting', function () {
            $_SERVER['__event.test.DatabaseConnecting'] = true;
        });

        $connector = $this->getPdoStubConnector($driver);
        $connector->connect(['name' => $driver]);
        $this->assertTrue($_SERVER['__event.test.DatabaseConnecting']);
    }

    public function testMysqlFiresConnectedEventWhenUsingEvents()
    {
        $this->driverFiresConnectedEventWhenUsingEvents('mysql');
    }

    public function testPgsqlFiresConnectedEventWhenUsingEvents()
    {
        $this->driverFiresConnectedEventWhenUsingEvents('pgsql');
    }

    public function testSqliteFiresConnectedEventWhenUsingEvents()
    {
        $this->driverFiresConnectedEventWhenUsingEvents('sqlite');
    }

    public function testSqlsrvFiresConnectedEventWhenUsingEvents()
    {
        $this->driverFiresConnectedEventWhenUsingEvents('sqlsrv');
    }

    public function driverFiresConnectedEventWhenUsingEvents($driver)
    {
        if (!$this->app->bound('events')) {
            $this->markTestSkipped('The Illuminate Event Dispatcher is not available.');
            return;
        }

        $_SERVER['__event.test.DatabaseConnected'] = false;
        $events = $this->app['events'];
        $events->listen('ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnected', function () {
            $_SERVER['__event.test.DatabaseConnected'] = true;
        });

        $connector = $this->getPdoStubConnector($driver);

        $connector->connect(['name' => $driver]);

        $this->assertTrue($_SERVER['__event.test.DatabaseConnected']);
    }

    public function testMysqlConnectingListenerCausesException()
    {
        $this->driverConnectingListenerCausesException('mysql');
    }

    public function testPgsqlConnectingListenerCausesException()
    {
        $this->driverConnectingListenerCausesException('pgsql');
    }

    public function testSqliteConnectingListenerCausesException()
    {
        $this->driverConnectingListenerCausesException('sqlite');
    }

    public function testSqlsrvConnectingListenerCausesException()
    {
        $this->driverConnectingListenerCausesException('sqlsrv');
    }

    public function driverConnectingListenerCausesException($driver)
    {
        if (!$this->app->bound('events')) {
            $this->markTestSkipped('The Illuminate Event Dispatcher is not available.');
            return;
        }

        $events = $this->app['events'];
        $events->listen('ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnecting', function () {
            return false;
        });

        $connector = $this->getPdoStubConnector($driver);

        $this->setExpectedException('ShiftOneLabs\LaravelDbEvents\Exceptions\ConnectingException');

        $connector->connect(['name' => $driver]);
    }
}
