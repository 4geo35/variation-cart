<?php

namespace GIS\VariationCart\Facades;

use GIS\VariationCart\Helpers\CartActionsManager;
use Illuminate\Support\Facades\Facade;

/**
 * @see CartActionsManager
 */
class CartActions extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "cart-actions";
    }
}
