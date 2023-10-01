<?php

namespace App\Console\Commands;

use BaraaDark\LaravelSocket\Facades\LaravelSocket;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateEventsJs extends Command
{
    protected $signature = 'generate:events-js';

    protected $description = 'Generate events.js based on routes in events.php';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if (LaravelSocket::routesNotPublished())
        {
            return $this->warn('Please publish the routes files by running ' .
                '\'php artisan vendor:publish --tag=socket-routes\'');
        }

        // Load the events.php routes file
        $routesFile = base_path('routes/events.php');

        $routes = include $routesFile;

        // Create the events.js content
        $eventsJs = "const axios = require('axios');\n";
        $eventsJs .= "const url = window.location.protocol + '//' + window.location.hostname;\n\n";

        foreach ($routes as $route) {
            list($method, $uri, $handler) = $route;

            $eventName = 'fetch' . str_replace('/', '-', $uri);
            $routeUrl = "url + '$uri'";

            $eventsJs .= "socket.on('$eventName', (data) => {\n";
            $eventsJs .= "  axios.$method($routeUrl)\n";
            $eventsJs .= "    .then(response => {\n";
            $eventsJs .= "      const results = response.data;\n";
            $eventsJs .= "      io.emit('load$eventName', results);\n";
            $eventsJs .= "    })\n";
            $eventsJs .= "    .catch(error => {\n";
            $eventsJs .= "      console.error(error);\n";
            $eventsJs .= "    });\n";
            $eventsJs .= "});\n\n";
        }

        // Write the events.js file
        $eventsJsPath = __DIR__.'../../Nodejs/events.js';
        File::put($eventsJsPath, $eventsJs);

        $this->info('events.js file generated successfully.');

        return 0;
    }
}
