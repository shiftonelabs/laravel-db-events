<?php
namespace ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors;

use ShiftOneLabs\LaravelDbEvents\Traits\ConnectorConnectTrait;
use Illuminate\Database\Connectors\PostgresConnector as BasePostgresConnector;

class PostgresConnector extends BasePostgresConnector
{
    use ConnectorConnectTrait;
}
