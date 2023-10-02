<?php

namespace BaraaDark\LaravelSocket\Console\Commands;

use BaraaDark\LaravelSocket\Facades\LaravelSocket;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InitNodeJsServer extends Command
{
    protected $signature = 'socket:init';

    protected $description = 'Initialize Node.js server for Laravel Socket package';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if (LaravelSocket::jsConfigNotPublished())
        {
            return $this->warn('Please set server config first by running ' .
                '\'php artisan socket:config\'');
        }

        // Change directory to your package's nodejs directory
        $nodeJsDir = base_path('vendor/baraadark/laravelsocket/src/Nodejs');
        chdir($nodeJsDir);

        // Run npm init
        $this->info('Running npm init...');
        exec('npm init -y', $output);

        // Add dependencies to package.json
        $this->info('Adding dependencies to package.json...');
        $packageJson = json_decode(File::get('package.json'), true);
        $packageJson['dependencies'] = [
            "express" => "^4.17.1",
            "mysql" => "^2.18.1",
            "socket.io" => "^4.1.3",
            "uuid" => "^8.3.2",
            "dotenv" => "^10.0.0",
            "axios"=> "^0.24.0"
        ];
        File::put('package.json', json_encode($packageJson, JSON_PRETTY_PRINT));

        // Run npm install
        $this->info('Running npm install...');
        exec('npm install', $output);

        $this->info('Node.js server initialization complete.');

        return 0;
    }
}
