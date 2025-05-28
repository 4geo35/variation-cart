<?php

use Illuminate\Support\Facades\Route;
use GIS\VariationCart\Http\Controllers\Web\CartController;

Route::middleware(["web"])
    ->as("web.")
    ->group(function () {
        $controllerClass = config("variation-cart.customCartWebController") ?? CartController::class;
        Route::get("/cart", [$controllerClass, "page"])->name("cart");
        Route::get("/checkout", [$controllerClass, "checkout"])->name("checkout");
    });
