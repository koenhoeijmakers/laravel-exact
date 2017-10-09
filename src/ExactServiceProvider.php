<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use KoenHoeijmakers\LaravelExact\Exact;

class ExactServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $config = $this->app['config']['exact'];

        if ($config['enabled']) {
            $this->app->bind(Exact::class, function () use ($config) {
                return new Exact(new Client());
            });
        }
    }
}