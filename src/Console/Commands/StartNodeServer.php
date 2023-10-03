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

    // public function handle()
    // {
    //     $serverJsPath = __DIR__ . '/../../Nodejs/server.js';

    //     if (!file_exists($serverJsPath)) {
    //         $this->error('server.js file not found.');
    //         return 1;
    //     }

    //     // Use Symfony Process to run the Node.js server without TTY mode
    //     $process = Process::fromShellCommandline('node ' . $serverJsPath);

    //    // Redirect the output and error streams
    //     $process->run(function ($type, $buffer) {
    //         $this->output->write($buffer);
    //     });

    //     if ($process->isSuccessful()) {
    //         $this->info('Node.js server started successfully.');
    //         return 0;
    //     } else {
    //         $this->error('Error starting Node.js server:');
    //         $this->error($process->getErrorOutput());
    //         return 1;
    //     }
    // }

    // In your Laravel Artisan command
    public function handle()
    {
        $serverJsPath = __DIR__ . '/../../Nodejs/server.js';

        // $packagePath = base_path('vendor/your-package-name/src/Nodejs/server.js');

        exec('npm start ' . $serverJsPath, $output, $exitCode);

        if ($exitCode !== 0) {
            $this->error('Error starting Node.js server.');
        } else {
            $this->info('Node.js server started successfully:');
            foreach ($output as $line) {
                $this->line($line);
            }
        }
    }

}
