<?php
namespace ShiftOneLabs\LaravelDbEvents\Tests;

class ServiceProviderTest extends TestCase
{

    public function testMysqlConnectorBound()
    {
        $this->assertTrue($this->app->bound('db.connector.mysql'));
    }

    public function testPgsqlConnectorBound()
    {
        $this->assertTrue($this->app->bound('db.connector.pgsql'));
    }

    public function testSqliteConnectorBound()
    {
        $this->assertTrue($this->app->bound('db.connector.sqlite'));
    }

    public function testSqlsrvConnectorBound()
    {
        $this->assertTrue($this->app->bound('db.connector.sqlsrv'));
    }

    public function testContainerMakesExtendedMysqlConnector()
    {
        $connector = $this->getConnector('mysql');

        $this->assertInstanceOf('ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors\MySqlConnector', $connector);
    }

    public function testContainerMakesExtendedPgsqlConnector()
    {
        $connector = $this->getConnector('pgsql');

        $this->assertInstanceOf('ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors\PostgresConnector', $connector);
    }

    public function testContainerMakesExtendedSqliteConnector()
    {
        $connector = $this->getConnector('sqlite');

        $this->assertInstanceOf('ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors\SQLiteConnector', $connector);
    }

    public function testContainerMakesExtendedSqlsrvConnector()
    {
        $connector = $this->getConnector('sqlsrv');

        $this->assertInstanceOf('ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors\SqlServerConnector', $connector);
    }
}
