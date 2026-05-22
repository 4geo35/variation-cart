<?php

return [
    // Settings
    "enableCart" => true,
    "showDiscount" => true,

    // Web
    "customCartWebController" => null,
    "useBreadcrumbs" => true,
    "useH1" => true,
    "cartPageTitle" => "Корзина",
    "checkoutPageTitle" => "Оформление заказа",
    "checkoutCartHasBeenChangedMessage" => "Корзина была изменена",

    // Listeners
    "customRemoveDeletedVariationFromCartsListener" => null,
    "customRemoveUnpublishedVariationFromCartsListener" => null,
    "customUpdateCartTotalOnVariationPriceChangedListener" => null,
    "customCheckVariationMinimalQuantityInCartsListener" => null,

    // Admin
    "customCartModel" => null,
    "customCartActionsManager" => null,
    "customCartObserver" => null,

    // Components
    "customAddVariationToCartComponent" => null,
    "customCartIcoComponent" => null,
    "customCartListComponent" => null,
    "customCartListItemComponent" => null,
    "customCartInfoComponent" => null,
    "customCheckoutComponent" => null,

    // Templates
    "templates" => [
        "cart-teaser" => \GIS\VariationCart\Templates\CartTeaser::class,
    ],
];
