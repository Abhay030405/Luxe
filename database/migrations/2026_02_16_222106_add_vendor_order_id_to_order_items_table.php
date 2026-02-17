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
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('vendor_order_id')->nullable()->after('order_id')->constrained('vendor_orders')->cascadeOnDelete()->comment('Links item to vendor-specific order');
            $table->index('vendor_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['vendor_order_id']);
            $table->dropIndex(['vendor_order_id']);
            $table->dropColumn('vendor_order_id');
        });
    }
};
