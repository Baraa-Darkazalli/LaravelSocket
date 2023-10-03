# LaravelSocket
BaraaDark Laravel Socket is a powerful Laravel package that simplifies the integration of real-time WebSocket functionality into your Laravel projects. With this package, you can effortlessly create and manage a Node.js WebSocket server inside your Laravel application and utilize the Elephant.io library for seamless communication with Socket.io in PHP


# BaraaDark/LaravelSocket Package

## Installation

You can install the LaravelSocket package using Composer by running the following command:

```shell
composer require baraadark/laravelsocket:dev-master
```

After installation, configure the server settings by running:

```shell
php artisan socket:config
```

Enter the host and port for Socket event listening.

Next, initialize the Node.js server by running:

```shell
php artisan socket:init
```

This will set up the necessary Node.js dependencies.

Define Socket.IO Events
To define Socket.IO events, you need to publish the events.php route file by running:

```shell
php artisan vendor:publish --tag=socket-route
```

Edit the events.php file and define your events logic as Laravel routes. Each route you define here will correspond to two Socket.IO events:

A 'fetch' event for fetching data.
A 'load' event for loading data.
The event names will match the route name with 'fetch/' or 'load/' prefixed to it. For example:

```php
Route::get('students/index', [SocketController::class, 'index']);
```

'fetch/students/index' event for fetching data.
'load/students/index' event for loading data.
Ensure that your route action returns JSON data using the response()->json() method, as this is required for Socket.IO communication. Example:

```php
return response()->json([
    'data' => $your_data,
    // ... other JSON data
]);
```

Update Socket.IO Events
To update your Socket.IO events based on your routes, run:

```shell
php artisan socket:events
```
This command will generate the necessary JavaScript code to handle the Socket.IO events.

Using LaravelSocket Facade
You can use the LaravelSocket Facade object to emit events from anywhere in your code. For example, you can emit events in an observer:

```php
use BaraaDark\LaravelSocket\Facades\LaravelSocket;

class StudentObserver
{
    // ...

    private function reloadData(): void
    {
        LaravelSocket::emit('students/index');
    }

    public function created(Student $student): void
    {
        self::reloadData();
    }

    // ...

    public function forceDeleted(Student $student): void
    {
        self::reloadData();
    }
}
```
You can also pass data to the emit function:

```php
LaravelSocket::emit('your/route/endpoint', $yourDataAsArray);
```

This allows you to send specific data to clients when emitting events.

Feel free to customize and expand upon this package to suit your application's real-time communication needs.
