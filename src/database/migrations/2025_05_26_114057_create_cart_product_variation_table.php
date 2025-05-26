<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_product_variation', function (Blueprint $table) {
            $table->uuid("cart_id")
                ->comment("Корзина");

            $table->unsignedBigInteger("product_variation_id")
                ->comment("Вариация");

            $table->unsignedInteger("quantity")
                ->default(1)
                ->comment("Количество");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_product_variation');
    }
};
