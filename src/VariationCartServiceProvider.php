<?php

namespace GIS\VariationCart;

use GIS\Fileable\Traits\ExpandTemplatesTrait;
use GIS\ProductVariation\Events\VariationDeletedEvent;
use GIS\ProductVariation\Events\VariationPriceChangedEvent;
use GIS\ProductVariation\Events\VariationUnpublishedEvent;
use GIS\VariationCart\Helpers\CartActionsManager;
use GIS\VariationCart\Listeners\RemoveDeletedVariationFromCartsListener;
use GIS\VariationCart\Listeners\RemoveUnpublishedVariationFromCartsListener;
use GIS\VariationCart\Listeners\UpdateCartTotalOnVariationPriceChangedListener;
use GIS\VariationCart\Livewire\Web\Catalog\AddVariationToCartWire;
use GIS\VariationCart\Livewire\Web\Catalog\CartIcoWire;
use GIS\VariationCart\Livewire\Web\Catalog\CartInfoWire;
use GIS\VariationCart\Livewire\Web\Catalog\CartListItemWire;
use GIS\VariationCart\Livewire\Web\Catalog\CartListWire;
use GIS\VariationCart\Livewire\Web\Catalog\CheckoutWire;
use GIS\VariationCart\Models\Cart;
use GIS\VariationCart\Observers\CartObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class VariationCartServiceProvider extends ServiceProvider
{
    use ExpandTemplatesTrait;

    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . "/database/migrations");
        $this->mergeConfigFrom(__DIR__ . "/config/variation-cart.php", "variation-cart");

        $this->initFacades();
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . "/resources/views", "vc");
        $this->loadRoutesFrom(__DIR__ . "/routes/web.php");

        $this->expandConfiguration();
        $this->observeModels();

        $this->addLivewireComponents();

        $this->listenEvents();
    }

    protected function observeModels(): void
    {
        $cartObserverClass = config("variation-cart.customCartObserver") ?? CartObserver::class;
        $cartModelClass = config("variation-cart.customCartModel") ?? Cart::class;
        $cartModelClass::observe($cartObserverClass);
    }

    protected function addLivewireComponents(): void
    {
        $component = config("variation-cart.customAddVariationToCartComponent");
        Livewire::component(
            "vc-add-variation-to-cart",
            $component ?? AddVariationToCartWire::class
        );

        $component = config("variation-cart.customCartIcoComponent");
        Livewire::component(
            "vc-cart-ico",
            $component ?? CartIcoWire::class
        );

        $component = config("variation-cart.customCartListComponent");
        Livewire::component(
            "vc-cart-list",
            $component ?? CartListWire::class
        );

        $component = config("variation-cart.customCartListItemComponent");
        Livewire::component(
            "vc-cart-list-item",
            $component ?? CartListItemWire::class
        );

        $component = config("variation-cart.customCartInfoComponent");
        Livewire::component(
            "vc-cart-info",
            $component ?? CartInfoWire::class
        );

        $component = config("variation-cart.customCheckoutComponent");
        Livewire::component(
            "vc-checkout",
            $component ?? CheckoutWire::class
        );
    }

    protected function initFacades(): void
    {
        $this->app->singleton("cart-actions", function () {
            $cartActionsManagerClass = config("variation-cart.customCartActionsManager") ??
                CartActionsManager::class;
            return new $cartActionsManagerClass();
        });
    }

    protected function expandConfiguration(): void
    {
        $vc = app()->config["variation-cart"];
        $this->expandTemplates($vc);
    }

    protected function listenEvents(): void
    {
        $listenerClass = config("variation-cart.customRemoveDeletedVariationFromCartsListener") ??
            RemoveDeletedVariationFromCartsListener::class;
        Event::listen(VariationDeletedEvent::class, $listenerClass);

        $listenerClass = config("variation-cart.customRemoveUnpublishedVariationFromCartsListener") ??
            RemoveUnpublishedVariationFromCartsListener::class;
        Event::listen(VariationUnpublishedEvent::class, $listenerClass);

        $listenerClass = config("variation-cart.customUpdateCartTotalOnVariationPriceChangedListener") ??
            UpdateCartTotalOnVariationPriceChangedListener::class;
        Event::listen(VariationPriceChangedEvent::class, $listenerClass);
    }
}
