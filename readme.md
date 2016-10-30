# laravel-db-events

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.txt)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This Laravel/Lumen package provides additional events for the Illuminate Database package. Currently, this package adds:

- a `DatabaseConnecting` event that is fired before connecting to the database, which can modify the configuration or cancel the connection
- a `DatabaseConnected` event that is fired after connecting to the database
- a `ConnectingException` runtime exception that is thrown if the database connection is cancelled by the `DatabaseConnecting` event

Additional events may be added be added as requested/submitted.

## Install

Via Composer

```bash
$ composer require shiftonelabs/laravel-db-events
```

Once composer has been updated and the package has been installed, the service provider will need to be loaded.

For Laravel 4, open `app/config/app.php` and add following line to the providers array:

```php
'ShiftOneLabs\LaravelDbEvents\LaravelDbEventsServiceProvider',
```

For Laravel 5, open `config/app.php` and add following line to the providers array:
```php
ShiftOneLabs\LaravelDbEvents\LaravelDbEventsServiceProvider::class,
```

For Lumen 5, open `bootstrap/app.php` and add following line under the "Register Service Providers" section:
```php
$app->register(ShiftOneLabs\LaravelDbEvents\LaravelDbEventsServiceProvider::class);
```

## Usage

#### DatabaseConnecting Event
The `ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnecting` event allows you to hook into the database connection lifecycle before the connection is established. Additionally, this event provides you the ability to modify the configuration used for the connection, as well as completely cancel the connection attempt.

**Attributes**

The `DatabaseConnecting` event provides three public attributes:

|Attribute|Description|
|---------|-----------|
|`public $connector`|The `Connector` object making the connection. This package extends each of the built-in connectors.|
|`public $connectionName`|The name of the selected database connection configuration.|
|`public $config`|The configuration array used to connect to the database.|

Example:

```php
app('events')->listen('ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnecting', function ($event) {
    app('log')->info('Connector class: '.get_class($event->connector));
    app('log')->info('Connection name: '.$event->connectionName);
    app('log')->info('Configuration: '.print_r($event->config, true));
});
```

**Modifying Connection Configuration**

The configuration for your database connections is usually stored in your `config/database.php` file (in conjunction with your `.env` file). If, however, you need to dynamically modify the configuration used for the connection, this can be done inside a `DatabaseConnecting` event listener. Any changes made to the configuration in the event listener will be used for the database connection.

Example:

```php
app('events')->listen('ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnecting', function ($event) {
    // don't connect to mysql in strict mode if you like zeroed out dates
    if (i_like_zero_dates()) {
        $event->config['strict'] = false;
    }
});
```

**Cancelling the Connection**

There may be situations where you would like to prevent the database from attempting the connection. In this case, the database connection attempt can be cancelled by returning `false` from a `DatabaseConnecting` event listener. If the database connection is cancelled, a `ShiftOneLabs\LaravelDbEvents\Exceptions\ConnectingException` runtime exception will be thrown.

Example:

```php
app('events')->listen('ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnecting', function ($event) {
    if (not_todaaay()) {
        return false;
    }
});
```

#### DatabaseConnected Event
The `ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnected` event allows you to hook into the database connection lifecycle after the connection is established. Additionally, this event provides access to the final configuration used for the connection, as well as the connection itself.

**Attributes**

The `DatabaseConnected` event provides four public attributes:

|Attribute|Description|
|---------|-----------|
|`public $connector`|The `Connector` object making the connection. This package extends each of the built-in connectors.|
|`public $connectionName`|The name of the selected database connection configuration.|
|`public $config`|The configuration array that was used to connect to the database.|
|`public $pdo`|The connected `PDO` (or potentially `Doctrine\DBAL\Driver\PDOConnection`, as of 5.3) object.|

Example:

```php
app('events')->listen('ShiftOneLabs\LaravelDbEvents\Extension\Database\Events\DatabaseConnected', function ($event) {
    app('log')->info('Connector class: '.get_class($event->connector));
    app('log')->info('Connection name: '.$event->connectionName);
    app('log')->info('Configuration: '.print_r($event->config, true));
    app('log')->info('PDO class: '.get_class($event->pdo));
});
```

## Contributing

Contributions are very welcome. Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email patrick@shiftonelabs.com instead of using the issue tracker.

## Credits

- [Patrick Carlo-Hickman][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.txt) for more information.

[ico-version]: https://img.shields.io/packagist/v/shiftonelabs/laravel-db-events.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/shiftonelabs/laravel-db-events/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/shiftonelabs/laravel-db-events.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/shiftonelabs/laravel-db-events.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/shiftonelabs/laravel-db-events.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/shiftonelabs/laravel-db-events
[link-travis]: https://travis-ci.org/shiftonelabs/laravel-db-events
[link-scrutinizer]: https://scrutinizer-ci.com/g/shiftonelabs/laravel-db-events/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/shiftonelabs/laravel-db-events
[link-downloads]: https://packagist.org/packages/shiftonelabs/laravel-db-events
[link-author]: https://github.com/patrickcarlohickman
[link-contributors]: ../../contributors
