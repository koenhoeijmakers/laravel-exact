<?php

namespace App\Providers;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;
use KoenHoeijmakers\LaravelExact\Client;
use KoenHoeijmakers\LaravelExact\ClientConfig;

class ExactServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/exact.php' => config_path('exact.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/exact.php', 'exact'
        );

        $this->app->singleton(ClientConfig::class, function ($app) {
            return new ClientConfig($app['config']['exact']);
        });

        $this->app->singleton(Client::class, function ($app) {
            return new Client(new HttpClient(), $app[ClientConfig::class]);
        });
    }
}
