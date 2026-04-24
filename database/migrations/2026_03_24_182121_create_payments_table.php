<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\PaymentMethod;
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
            $blueprint->foreignIdFor(Order::class)
                ->constrained()
                ->cascadeOnDelete();
            $blueprint->foreignIdFor(PaymentMethod::class)
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
