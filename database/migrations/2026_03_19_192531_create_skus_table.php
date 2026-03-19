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
        Schema::create('skus', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignIdFor(\App\Models\Product::class)
                ->index()
                ->constrained()
                ->cascadeOnDelete();
            $blueprint->string('code')->unique();
            $blueprint->unsignedInteger('price');
            $blueprint->unsignedInteger('quantity')
                ->default(0);
            $blueprint->json('specifications')->nullable();
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skus');
    }
};
