<?php
namespace ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors;

use ShiftOneLabs\LaravelDbEvents\Traits\ConnectorConnectTrait;
use Illuminate\Database\Connectors\SQLiteConnector as BaseSQLiteConnector;

class SQLiteConnector extends BaseSQLiteConnector
{
    use ConnectorConnectTrait;
}
