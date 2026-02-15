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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained('products')->cascadeOnDelete();

            // Stock management fields
            $table->integer('quantity_available')->default(0)->comment('Stock available for sale');
            $table->integer('quantity_reserved')->default(0)->comment('Stock reserved for confirmed orders');
            $table->integer('low_stock_threshold')->default(10)->comment('Alert threshold for low stock');

            $table->timestamps();

            // Indexes for performance
            $table->index('quantity_available');
            $table->index(['quantity_available', 'low_stock_threshold']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
