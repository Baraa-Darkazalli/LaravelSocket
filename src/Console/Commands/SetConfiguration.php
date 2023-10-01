<?php

namespace BaraaDark\LaravelSocket\Console\Commands;

use Illuminate\Console\Command;

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
        $port = $this->ask('Enter the port number for Laravel Socket (default: 3030):') ?: 3030;
        $mysqlHost = $this->ask('Enter the MySQL host (default: 127.0.0.1):') ?: '127.0.0.1';

        // Update the environment variables with user-provided values
        putenv("SOCKET_PORT={$port}");
        putenv("SOCKET_HOST={$mysqlHost}");

        $this->info('Environment variables updated successfully.');
    }
}
