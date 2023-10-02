<?php

namespace BaraaDark\LaravelSocket\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class StartNodeServer extends Command
{
    protected $signature = 'socket:run';
    protected $description = 'Run the Node.js server for the Laravel Socket package';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $serverJsPath = __DIR__ . '/../../Nodejs/server.js';

        if (!file_exists($serverJsPath)) {
            $this->error('server.js file not found.');
            return 1;
        }

        // Use Symfony Process to run the Node.js server
        $process = new Process(['node', $serverJsPath]);

        // Redirect the output to the console
        $process->setTty(true);

        // Run the Node.js server
        $process->run(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        if ($process->isSuccessful()) {
            $this->info('Node.js server started successfully.');
            return 0;
        } else {
            $this->error('Error starting Node.js server.');
            return 1;
        }
    }
}
