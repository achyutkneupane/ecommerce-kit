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
        Schema::table('users', function (Blueprint $blueprint): void {
            if (! Schema::hasColumn('users', 'loyalty_points')) {
                $blueprint->integer('loyalty_points')->default(0)->after('password');
            }
        });

        Schema::table('products', function (Blueprint $blueprint): void {
            $blueprint->string('loyalty_mode')->nullable()->after('title');
            $blueprint->integer('loyalty_amount')->nullable()->after('loyalty_mode');
        });

        Schema::table('skus', function (Blueprint $blueprint): void {
            $blueprint->string('loyalty_mode')->nullable()->after('price');
            $blueprint->integer('loyalty_amount')->nullable()->after('loyalty_mode');
        });

        Schema::table('orders', function (Blueprint $blueprint): void {
            $blueprint->index('status');
        });

        Schema::table('order_logs', function (Blueprint $blueprint): void {
            $blueprint->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_logs', function (Blueprint $blueprint): void {
            $blueprint->dropIndex(['status']);
        });

        Schema::table('orders', function (Blueprint $blueprint): void {
            $blueprint->dropIndex(['status']);
        });

        Schema::table('skus', function (Blueprint $blueprint): void {
            $blueprint->dropColumn(['loyalty_mode', 'loyalty_amount']);
        });

        Schema::table('products', function (Blueprint $blueprint): void {
            $blueprint->dropColumn(['loyalty_mode', 'loyalty_amount']);
        });

        Schema::table('users', function (Blueprint $blueprint): void {
            $blueprint->dropColumn('loyalty_points');
        });
    }
};
