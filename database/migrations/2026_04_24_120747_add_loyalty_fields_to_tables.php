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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'loyalty_points')) {
                $table->integer('loyalty_points')->default(0)->after('password');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('loyalty_mode')->nullable()->after('title');
            $table->integer('loyalty_amount')->nullable()->after('loyalty_mode');
        });

        Schema::table('skus', function (Blueprint $table) {
            $table->string('loyalty_mode')->nullable()->after('price');
            $table->integer('loyalty_amount')->nullable()->after('loyalty_mode');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('order_logs', function (Blueprint $table) {
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_logs', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('skus', function (Blueprint $table) {
            $table->dropColumn(['loyalty_mode', 'loyalty_amount']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['loyalty_mode', 'loyalty_amount']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('loyalty_points');
        });
    }
};
