<?php

namespace BaraaDark\LaravelSocket;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpParser\Node\Scalar\MagicConst\Dir;

class LaravelSocket
{
    public function routesNotPublished()
    {
        $routesFile = base_path('routes/events.php');

        return !File::exists($routesFile);
    }

    public function jsConfigNotPublished()
    {
        $configJsFile =  __DIR__ . './Nodejs/config.js';

        return !File::exists($configJsFile);
    }

    public function configNotPublished()
    {
        return is_null(config('laravel-socket'));
    }

    public function getPort()
    {
        return env('SOCKET_PORT');
    }

    public function getMysqlHost()
    {
        return env('SOCKET_HOST');
    }

    public function getMysqlUser()
    {
        return env('DB_USERNAME');
    }

    public function getMysqlPassword()
    {
        return env('DB_PASSWORD');
    }

    public function getMysqlDatabase()
    {
        return env('DB_DATABASE');
    }

    public function prefix()
    {
        return config('laravel-socket.prefix', 'socket');
    }
}
