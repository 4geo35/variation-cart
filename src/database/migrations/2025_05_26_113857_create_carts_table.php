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
        Schema::create('carts', function (Blueprint $table) {
            $table->uuid("id")
                ->primary()
                ->comment("Идентификатор корзины");

            $table->unsignedBigInteger("user_id")
                ->nullable()
                ->comment("Владелец корзины");

            $table->decimal("total", 12, 2)
                ->default(0)
                ->comment("Итого");

            $table->dateTime("notify_at")
                ->nullable()
                ->comment("Дата отправления уведомления об устаревшей корзине");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
