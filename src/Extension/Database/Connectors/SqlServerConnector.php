<?php
namespace ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors;

use ShiftOneLabs\LaravelDbEvents\Traits\SupportsEvents;
use ShiftOneLabs\LaravelDbEvents\Traits\ConnectorConnectTrait;
use Illuminate\Database\Connectors\SqlServerConnector as BaseSqlServerConnector;

class SqlServerConnector extends BaseSqlServerConnector
{
    use SupportsEvents, ConnectorConnectTrait;
}
