<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Models\User;
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
        Schema::create('orders', function (Blueprint $blueprint): void {
            $blueprint->id();

            $blueprint->string('code')
                ->nullable()
                ->unique();
            $blueprint->foreignIdFor(User::class)
                ->nullable()
                ->index()
                ->constrained()
                ->nullOnDelete();
            $blueprint->string('full_name');
            $blueprint->string('email');
            $blueprint->string('phone');
            $blueprint->string('address');
            $blueprint->text('delivery_instructions')->nullable();

            $blueprint->string('status')->default(OrderStatus::INITIATED);

            $blueprint->unsignedInteger('gross_total')->default(0);
            $blueprint->unsignedInteger('discount')->default(0);
            $blueprint->unsignedInteger('delivery_charge')->default(0);
            $blueprint->unsignedInteger('tax')->default(0);
            $blueprint->unsignedInteger('net_total')->default(0);

            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
