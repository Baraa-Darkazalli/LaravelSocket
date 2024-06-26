<?php

namespace BaraaDark\LaravelSocket;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use BaraaDark\LaravelSocket\Facades\LaravelSocket;

class LaravelSocketServerProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
            $this->registerPublishing();
        }
        exec('php artisan vendor:publish --tag=socket-routes');

        $this->registerResources();
    }

    /**
     * Register the package resources.
     *
     * @return void
     */
    private function registerResources()
    {
        $this->registerFacades();
        $this->registerRoutes();
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(base_path('routes\\events.php'));
        });
    }


    /**
     * Register any bindings to the app.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $this->app->singleton('LaravelSocket', function ($app) {
            return new \BaraaDark\LaravelSocket\LaravelSocket();
        });
    }

    /**
     * Get the LaravelSocket route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration()
    {
        return [
            'prefix' => 'socket',
        ];
    }


    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        // $this->publishes([
        //     __DIR__.'/../config/laravel-socket.php' =>  config_path('laravel-socket.php'),
        //  ], 'socket-config');

        $this->publishes([
            __DIR__.'/../routes/events.php' =>  base_path('routes/events.php'),
         ], 'socket-routes');
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands([
            Console\Commands\InitNodeJsServer::class,
            Console\Commands\SetConfiguration::class,
            Console\Commands\GenerateEventsJS::class,
            Console\Commands\StartNodeServer::class,
        ]);
    }
}
