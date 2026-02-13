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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();

            // Snapshot of product details at time of order - CRITICAL
            // Products may be edited or deleted later, but order history must remain accurate
            $table->string('product_name')->comment('Product name at time of purchase');
            $table->string('product_sku')->nullable()->comment('Product SKU at time of purchase');

            // Price snapshot
            $table->decimal('price', 10, 2)->comment('Price per unit at time of purchase');
            $table->unsignedInteger('quantity')->comment('Quantity ordered');
            $table->decimal('subtotal', 10, 2)->comment('price * quantity');

            $table->timestamps();

            // Indexes for performance
            $table->index('order_id');
            $table->index('product_id');
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
