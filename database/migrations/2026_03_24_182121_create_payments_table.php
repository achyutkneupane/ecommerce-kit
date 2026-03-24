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
        Schema::create('payments', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignIdFor(\App\Models\Order::class)
                ->constrained()
                ->cascadeOnDelete();
            $blueprint->foreignIdFor(\App\Models\PaymentMethod::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $blueprint->unsignedInteger('amount');
            $blueprint->string('transaction_id')->nullable();
            $blueprint->json('payload')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
