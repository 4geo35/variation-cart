<?php

use Illuminate\Support\Facades\Route;

Route::middleware(["web"])
    ->as("web.")
    ->group(function () {
        Route::get("/cart", function () {
            return "cart";
        })->name("cart");
        Route::get("/checkout", function () {
            return "checkout";
        });
    });
