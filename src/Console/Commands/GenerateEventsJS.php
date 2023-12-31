<?php

namespace BaraaDark\LaravelSocket\Console\Commands;

use BaraaDark\LaravelSocket\Facades\LaravelSocket;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class GenerateEventsJS extends Command
{
    protected $signature = 'socket:events';

    protected $description = 'Generate events.js based on Laravel routes events.php';

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

        if (LaravelSocket::serverNotInit())
        {
            return $this->warn('Please init server first by running ' .
                '\'php artisan socket:init\'');
        }

        if (LaravelSocket::routesNotPublished())
        {
            return $this->warn('Please publish events route file first by running ' .
                '\'php artisan vendor:publish --tags=socket-routes\'');
        }

        // Load the events.js content
        $baseUrl = $_SERVER['APP_URL'];
        $eventsJs = "const axios = require('axios');\n";
        $eventsJs .= "module.exports = function (socket, connection, io) {\n";
        foreach (Route::getRoutes() as $key=>$route) {
            $uri = $route->uri();
            $methods = $route->methods();

            // Check if the route URI starts with the "socket" prefix and if it's either GET or POST
            if (strpos($uri, 'socket/') === 0 && (in_array('GET', $methods) || in_array('POST', $methods))) {
                $eventName = 'fetch/' . $uri;
                $routeUrl = "'$baseUrl/' + '$uri'";
                $axiosMethod = in_array('GET', $methods) ? 'get' : 'post'; // Use appropriate axios method

                $eventsJs .= "socket.on('$eventName', (data) => {\n";
                $eventsJs .= "  axios.$axiosMethod($routeUrl)\n"; // Use the determined axios method
                $eventsJs .= "    .then(response => {\n";
                $eventsJs .= "      const results = response.data;\n";
                $eventsJs .= "      io.emit('load/$uri', results);\n";
                $eventsJs .= "    })\n";
                $eventsJs .= "    .catch(error => {\n";
                $eventsJs .= "      console.error(error);\n";
                $eventsJs .= "    });\n";
                $eventsJs .= "});\n\n";
            }
        }
        $eventsJs .= "};\n";


        // Write the events.js file
        $eventsJsPath = __DIR__.'/../../Nodejs/events.js';

        if (file_exists($eventsJsPath))
        {
            // Delete the existing events.js file
            unlink($eventsJsPath);
        }

        File::put($eventsJsPath, $eventsJs);

        $this->info('events.js file generated successfully.');

        return 0;
    }
}
