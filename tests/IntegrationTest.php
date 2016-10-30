<?php
namespace ShiftOneLabs\LaravelDbEvents\Tests;

class IntegrationTest extends TestCase
{

    public function testValidConnectionIsValid()
    {
        $connection = $this->getConnection('valid');
        $connection->getPdo();

        $this->assertTrue(true, 'Valid connection does not throw an exception.');
    }

    public function testInvalidConnectionIsInvalid()
    {
        $this->expectException('InvalidArgumentException');

        $connection = $this->getConnection('invalid');
        $connection->getPdo();
    }

    public function testConnectingEventModifiesConfig()
    {
        if (!$this->app->bound('events')) {
            $this->markTestSkipped('The Illuminate Event Dispatcher is not available.');
            return;
        }

        $this->expectException('InvalidArgumentException');

        $events = $this->app['events'];
        $events->listen('ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnecting', function ($event) {
            $event->config = $this->app['config']['database.connections.invalid'];
        });

        $connection = $this->getConnection('valid');
        $connection->getPdo();
    }
}
