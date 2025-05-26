<?php

namespace GIS\VariationCart;

use GIS\VariationCart\Helpers\CartActionsManager;
use GIS\VariationCart\Models\Cart;
use GIS\VariationCart\Observers\CartObserver;
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
        $cartObserverClass = config("variation-cart.customCartObserver") ?? CartObserver::class;
        $cartModelClass = config("variation-cart.customCartModel") ?? Cart::class;
        $cartModelClass::observe($cartObserverClass);
    }

    protected function addLivewireComponents(): void
    {

    }

    protected function initFacades(): void
    {
        $this->app->singleton("cart-actions", function () {
            $cartActionsManagerClass = config("variation-cart.customCartActionsManager") ?? CartActionsManager::class;
            return new $cartActionsManagerClass();
        });
    }
}
