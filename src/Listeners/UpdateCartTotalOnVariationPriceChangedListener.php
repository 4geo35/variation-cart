<?php

namespace GIS\VariationCart\Listeners;

use GIS\ProductVariation\Events\VariationPriceChangedEvent;
use GIS\VariationCart\Facades\CartActions;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateCartTotalOnVariationPriceChangedListener implements ShouldQueue
{
    public function __construct() {}

    public function handle(VariationPriceChangedEvent $event): void
    {
        $carts = $event->variation->carts;
        foreach ($carts as $cart) {
            CartActions::recalculateTotal($cart);
        }
    }
}
