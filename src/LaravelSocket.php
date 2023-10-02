<?php

namespace BaraaDark\LaravelSocket;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpParser\Node\Scalar\MagicConst\Dir;

class LaravelSocket
{
    protected string $host;
    protected string $prefix;

    public function __construct()
    {
        $this->host = '127.0.0.1';
        $this->prefix = 'socket';
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

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
