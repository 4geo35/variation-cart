<?php

namespace GIS\VariationCart\Facades;

use GIS\ProductVariation\Interfaces\ProductVariationInterface;
use GIS\VariationCart\Helpers\CartActionsManager;
use GIS\VariationCart\Interfaces\CartInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static CartInterface initCart()
 * @method static CartInterface|null getCart()
 *
 * @method static CartInterface addToCart(ProductVariationInterface $variation, $quantity = 1, CartInterface $customCart = null)
 * @method static CartInterface|null changeQuantity(ProductVariationInterface $variation, $quantity = 1, CartInterface $customCart = null)
 * @method static void deleteItem(ProductVariationInterface $variation, CartInterface $customCart = null)
 * @method static void clearCart(CartInterface $customCart = null)
 *
 * @method static void recalculateTotal(CartInterface $cart)
 *
 * @method static object getCartInfo(CartInterface $cart = null)
 * @method static array|null getCartItems(CartInterface $cart = null)
 * @method static int getCartItemQuantity(int $variationId, CartInterface $cart = null)
 *
 * @method static void setCookie(CartInterface $cart)
 * @method static void forgetCookie()
 * @method static void clearCartCache(CartInterface $cart)
 *
 * @see CartActionsManager
 */
class CartActions extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "cart-actions";
    }
}
