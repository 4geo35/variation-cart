<?php

namespace GIS\VariationCart\Listeners;

use GIS\ProductVariation\Events\VariationDeletedEvent;
use GIS\VariationCart\Facades\CartActions;
use GIS\VariationCart\Interfaces\CartInterface;
use GIS\VariationCart\Models\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveDeletedVariationFromCartsListener implements ShouldQueue
{
    public function __construct() {}

    public function handle(VariationDeletedEvent $event): void
    {
        $modelClass = config("variation-cart.customCartModel") ?? Cart::class;

        $carts = $modelClass::query()
            ->select("carts.*")
            ->leftJoin("cart_product_variation", "cart_product_variation.cart_id", "=", "carts.id")
            ->where("cart_product_variation.product_variation_id", $event->variationId)
            ->get();

        foreach ($carts as $cart) {
            /**
             * @var CartInterface $cart
             */
            $cart->variations()->detach($event->variationId);
            CartActions::recalculateTotal($cart);
        }
    }
}
