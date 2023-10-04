<?php

namespace BaraaDark\LaravelSocket;

use ElephantIO\Client;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpParser\Node\Scalar\MagicConst\Dir;

class LaravelSocket
{
    protected string $protocol;
    protected string $host;
    protected string $port;
    protected string $prefix;
    protected Client $client;
    protected bool   $initDone;

    public function __construct()
    {
        $this->prefix = 'socket';
        $this->protocol = 'http';
        $this->host = '127.0.0.1';
        $this->port = '3030';
        $this->initDone = false;
    }

    public function initElephintIo()
    {
        $this->client = new Client(Client::engine(Client::CLIENT_4X, $this->getSocketFullUrl()));
        $this->client->initialize();
        $this->initDone = true;
    }

    public function getProtocol()
    {
        return $this->protocol;
    }

    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPort($port)
    {
        $this->port = $port;
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

    public function getSocketFullUrl()
    {
        return  $this->protocol . '://' . $this->host . ':' . $this->port;
    }

    public function emit($eventRouteEndPoint, $data=[])
    {
        if(!$this->initDone)
        {
            throw new Exception("Please run php artisan socket:init first");
        }
        $this->client->emit('fetch/'. $this->prefix . '/' . $eventRouteEndPoint, $data);
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

    public function serverNotInit()
    {
        $packageJsonFile =  __DIR__ . './Nodejs/package.json';

        return !File::exists($packageJsonFile);
    }

    public function configNotPublished()
    {
        return is_null(config('laravel-socket'));
    }
}
