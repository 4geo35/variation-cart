<?php

namespace GIS\VariationCart\Traits;

use GIS\VariationCart\Facades\CartActions;

trait ChangeVariationCartQuantityTrait
{
    public int $quantity = 0;

    public function increaseQuantity(): void
    {
        $this->quantity++;
        $cart = CartActions::changeQuantity($this->variation, $this->quantity);
        $this->quantity = $cart->lastQuantity ?? 0;
        $this->dispatch("change-cart");
        $this->dispatch("change-variation-quantity", $this->variationId, $this->product->id, $this->quantity);
    }

    public function decreaseQuantity(): void
    {
        $this->quantity--;
        if ($this->quantity <= 0) {
            CartActions::deleteItem($this->variation);
        } else {
            $cart = CartActions::changeQuantity($this->variation, $this->quantity);
            $this->quantity = $cart->lastQuantity ?? 0;
        }
        $this->dispatch("change-cart");
        $this->dispatch("change-variation-quantity", $this->variationId, $this->product->id, $this->quantity);
    }

    public function addToCart(): void
    {
        $cart = CartActions::addToCart($this->variation);
        $this->quantity = $cart->lastQuantity ?? 0;
        $this->dispatch("change-cart");
        $this->dispatch("change-variation-quantity", $this->variationId, $this->product->id, $this->quantity);
    }
}
