<?php
namespace ShiftOneLabs\LaravelDbEvents\Extension\Database\Events;

use PDO;

class DatabaseConnected extends ConnectorEvent
{
    /**
     * The config array used to connect to the database.
     *
     * @var array
     */
    public $config;

    /**
     * The connected PDO connection.
     *
     * @var \PDO
     */
    public $pdo;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Database\Connectors\Connector  $connector
     * @param string  $name
     * @param array  $config
     * @param \PDO  $pdo
     * @return void
     */
    public function __construct($connector, $name, $config, PDO $pdo)
    {
        parent::__construct($connector, $name);

        $this->config = $config;
        $this->pdo = $pdo;
    }
}
