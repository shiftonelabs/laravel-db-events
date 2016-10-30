<?php
namespace ShiftOneLabs\LaravelDbEvents;

use Illuminate\Support\ServiceProvider;

class LaravelDbEventsServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConnector('mysql', 'ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors\MySqlConnector');
        $this->registerConnector('pgsql', 'ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors\PostgresConnector');
        $this->registerConnector('sqlite', 'ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors\SQLiteConnector');
        $this->registerConnector('sqlsrv', 'ShiftOneLabs\LaravelDbEvents\Extension\Database\Connectors\SqlServerConnector');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function registerConnector($driver, $class)
    {
        $this->app->bind('db.connector.'.$driver, function ($app) use ($class) {
            $connector = new $class();

            if ($app->bound('events')) {
                $connector->setEventDispatcher($app['events']);
            }

            return $connector;
        });
    }
}
