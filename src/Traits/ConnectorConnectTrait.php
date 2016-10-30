<?php
namespace ShiftOneLabs\LaravelDbEvents\Traits;

use ShiftOneLabs\LaravelDbEvents\Exceptions\ConnectingException;
use ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnected;
use ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnecting;

trait ConnectorConnectTrait
{

    /**
     * Establish a database connection.
     *
     * @param  array  $config
     * @return \PDO
     */
    public function connect(array $config)
    {
        if ($this->fireEvent(new DatabaseConnecting($this, array_get($config, 'name'), $config), true) === false) {
            throw new ConnectingException();
        }

        $connection = $this->parentConnect($config);

        $this->fireEvent(new DatabaseConnected($this, array_get($config, 'name'), $config, $connection));

        return $connection;
    }

    /**
     * Call connect on the parent class to establish a database connection.
     *
     * @param  array  $config
     * @return \PDO
     */
    protected function parentConnect(array $config)
    {
        return parent::connect($config);
    }
}
