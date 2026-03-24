<?php

declare(strict_types=1);

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
        Schema::create('order_items', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignIdFor(App\Models\Order::class)
                ->constrained()
                ->cascadeOnDelete();
            $blueprint->foreignIdFor(App\Models\Sku::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $blueprint->string('product');
            $blueprint->string('sku_code');
            $blueprint->json('properties')->nullable();
            $blueprint->unsignedInteger('unit_price');
            $blueprint->unsignedInteger('quantity');
            $blueprint->unsignedInteger('subtotal');
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
