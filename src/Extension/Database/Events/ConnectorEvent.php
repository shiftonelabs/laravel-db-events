<?php
namespace ShiftOneLabs\LaravelDbEvents\Extension\Database\Events;

abstract class ConnectorEvent
{
    /**
     * The database connector instance.
     *
     * @var \Illuminate\Database\Connectors\Connector
     */
    public $connector;

    /**
     * The name of the connection.
     *
     * @var string
     */
    public $connectionName;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Database\Connectors\Connector  $connector
     * @param string  $name
     * @return void
     */
    public function __construct($connector, $name)
    {
        $this->connector = $connector;
        $this->connectionName = $name;
    }
}
