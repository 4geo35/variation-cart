<?php

namespace GIS\VariationCart\Listeners;

use GIS\ProductVariation\Events\VariationUnpublishedEvent;
use GIS\VariationCart\Facades\CartActions;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveUnpublishedVariationFromCartsListener implements ShouldQueue
{
    public function __construct() {}

    public function handle(VariationUnpublishedEvent $event): void
    {
        $carts = $event->variation->carts;
        $event->variation->carts()->detach();
        foreach ($carts as $cart) {
            CartActions::recalculateTotal($cart);
        }
    }
}
