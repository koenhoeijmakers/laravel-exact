<?php

declare(strict_types=1);

namespace App\Providers;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
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
            __DIR__ . '/../config/exact.php' => config_path('exact.php'),
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

        /** @var Repository $config */
        $config = $this->app->make(Repository::class);

        $this->app->singleton(ClientConfig::class, function () use ($config) {
            return new ClientConfig($config->get('exact'));
        });

        $this->app->singleton(Client::class, function (Container $container) {
            return new Client(new HttpClient(), $container->make(ClientConfig::class));
        });
    }
}
