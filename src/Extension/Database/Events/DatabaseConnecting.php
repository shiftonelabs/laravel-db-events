<?php
namespace ShiftOneLabs\LaravelDbEvents\Extension\Database\Events;

class DatabaseConnecting extends ConnectorEvent
{
    /**
     * The config array used to connect to the database.
     *
     * @var array
     */
    public $config;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Database\Connectors\Connector  $connector
     * @param string  $name
     * @param array  &$config
     */
    public function __construct($connector, $name, &$config)
    {
        parent::__construct($connector, $name);

        $this->config = &$config;
    }
}
