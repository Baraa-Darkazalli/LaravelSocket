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
        $this->info('This command not ready yet .. please run server manuly by write npm server.js on serverFolder');
        return 0;
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
