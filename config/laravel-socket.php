<?php

return [
    'port' => '3030',

    'mysql' => [
        'host' => env('DB_HOST', 'localhost'),
        'user' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'database' => env('DB_DATABASE', 'laravel'),
    ],

    'prefix' => 'socket',
];
