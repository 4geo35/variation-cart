<?php

namespace GIS\VariationCart\Listeners;

use GIS\ProductVariation\Events\VariationMinimalOrderChangedEvent;
use GIS\VariationCart\Facades\CartActions;
use GIS\VariationCart\Interfaces\CartInterface;
use GIS\VariationCart\Models\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckVariationMinimalQuantityInCartsListener implements ShouldQueue
{
    public function __construct() {}

    public function handle(VariationMinimalOrderChangedEvent $event): void
    {
        $variationId = $event->variation->id;
        $minimalQuantity = $event->variation->minimal_order;
        if (empty($minimalQuantity)) { return; }

        $modelClass = config("variation-cart.customCartModel") ?? Cart::class;

        $carts = $modelClass::query()
            ->whereHas("variations", function ($query) use ($variationId, $minimalQuantity) {
                $query->where('id', $variationId);
                $query->where('quantity', '<', $minimalQuantity);
            })
            ->get();

        foreach ($carts as $cart) {
            /**
             * @var CartInterface $cart
             */
            CartActions::changeQuantity($event->variation, $minimalQuantity, $cart);
        }
    }
}
