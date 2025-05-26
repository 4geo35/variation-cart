<?php

namespace GIS\VariationCart;

use Illuminate\Support\ServiceProvider;

class VariationCartServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->observeModels();
        $this->loadViewsFrom(__DIR__ . "/resources/views", "vc");
        $this->addLivewireComponents();
    }

    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . "/database/migrations");
        $this->loadRoutesFrom(__DIR__ . "/routes/web.php");
        $this->mergeConfigFrom(
            __DIR__ . "/config/variation-cart.php", "variation-cart"
        );
        $this->initFacades();
    }

    protected function observeModels(): void
    {

    }

    protected function addLivewireComponents(): void
    {

    }

    protected function initFacades(): void
    {

    }
}
