<?php

namespace BaraaDark\LaravelSocket\Console\Commands;

use BaraaDark\LaravelSocket\Facades\LaravelSocket;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetConfiguration extends Command
{
    protected $signature = 'socket:config';

    protected $description = 'Configure Laravel Socket package connection settings';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $host = $this->ask('Enter the your host (like: http://127.0.0.1):') ?: 'http://127.0.0.1';
        $port = $this->ask('Enter the listening port number for Laravel Socket (like: 3030):') ?: 3030;
        $parsedUrl = parse_url($host);

        LaravelSocket::setProtocol($parsedUrl['scheme']);
        LaravelSocket::setHost($parsedUrl['host']);
        LaravelSocket::setPort($port);

        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        // Define the JavaScript configuration content
        $configJs = "module.exports = {
            SOCKET_PORT: $port,
            SOCKET_HOST: '$host',
            DB_DATABASE: '$database',
            DB_USERNAME: '$username',
            DB_PASSWORD: '$password',
        };";


        // Write the events.js file
        $eventsJsPath = __DIR__.'/../../Nodejs/config.js';
        File::put($eventsJsPath, $configJs);

        $this->info('Configuration updated successfully.');
    }
}
