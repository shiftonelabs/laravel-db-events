<?php
namespace ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors;

use ShiftOneLabs\LaravelDbEvents\Traits\ConnectorConnectTrait;
use Illuminate\Database\Connectors\MySqlConnector as BaseMySqlConnector;

class MySqlConnector extends BaseMySqlConnector
{
    use ConnectorConnectTrait;
}
