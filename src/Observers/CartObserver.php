<?php

namespace GIS\VariationCart\Observers;

use GIS\VariationCart\Facades\CartActions;
use GIS\VariationCart\Interfaces\CartInterface;
use GIS\VariationCart\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartObserver
{
    public function creating(CartInterface $cart): void
    {
        $uuid = Str::uuid();
        $cartModelClass = config("variation-cart.customCartModel") ?? Cart::class;
        while ($cartModelClass::find($uuid)) { $uuid = Str::uuid(); }
        $cart->id = $uuid;

        if (Auth::check()) { $cart->user_id = Auth::id(); }
    }

    public function created(CartInterface $cart): void
    {
        CartActions::setCookie($cart);
    }

    public function updated(CartInterface $cart): void
    {
        CartActions::setCookie($cart);
        CartActions::clearCartCache($cart);
    }

    public function deleted(CartInterface $cart): void
    {
        $cart->variations()->sync([]);
        CartActions::clearCartCache($cart);
    }
}
