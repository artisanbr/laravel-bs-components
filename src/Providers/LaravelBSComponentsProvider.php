<?php

namespace ArtisanLabs\LaravelBSComponents\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class LaravelBSComponentsProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'bs');

        $this->publishes(
            [__DIR__ . '/../../config/bootstrap-components.php' => config_path('bootstrap-components.php')],
            ['laravel-bs-components', 'laravel-bs-components:config']
        );

        $this->publishes(
            [__DIR__ . '/../../resources/views' => resource_path('views/vendor/bs')],
            ['laravel-bs-components', 'laravel-bs-components:views']
        );
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/bootstrap-components.php', 'bootstrap-components');

        Blade::anonymousComponentPath(__DIR__.'/../../resources/views/components', 'bs');
    }
}
