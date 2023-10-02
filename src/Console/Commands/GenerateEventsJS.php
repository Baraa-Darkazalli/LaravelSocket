<?php

namespace BaraaDark\LaravelSocket\Console\Commands;

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
        // Load the events.js content
        $eventsJs = "const axios = require('axios');\n";
        $eventsJs .= "module.exports = function (socket, connection, io) {\n";
        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();
            $methods = $route->methods();

            // Check if the route URI starts with the "socket" prefix and if it's either GET or POST
            if (strpos($uri, 'socket/') === 0 && (in_array('GET', $methods) || in_array('POST', $methods))) {
                $eventName = 'fetch/' . $uri;
                $routeUrl = "'http://192.168.0.53/socket/' + '$uri'";
                $axiosMethod = in_array('GET', $methods) ? 'get' : 'post'; // Use appropriate axios method

                $eventsJs .= "socket.on('$eventName', (data) => {\n";
                $eventsJs .= "  axios.$axiosMethod($routeUrl)\n"; // Use the determined axios method
                $eventsJs .= "    .then(response => {\n";
                $eventsJs .= "      const results = response.data;\n";
                $eventsJs .= "      io.emit('load/$eventName', results);\n";
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
