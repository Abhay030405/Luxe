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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('order_number', 50)->unique()->comment('Unique order identifier like ORD-2026-000145');
            $table->string('status', 20)->default('pending')->index()->comment('Order status: pending, confirmed, processing, shipped, delivered, cancelled');

            // Price fields
            $table->decimal('subtotal', 10, 2)->comment('Sum of all items');
            $table->decimal('tax', 10, 2)->default(0)->comment('Tax amount');
            $table->decimal('shipping_fee', 10, 2)->default(0)->comment('Shipping cost');
            $table->decimal('total_amount', 10, 2)->comment('Final total amount');

            // Address snapshot - critical for order history
            $table->json('address_snapshot')->comment('Complete address at time of order - never rely on addresses table');

            // Optional fields
            $table->text('customer_notes')->nullable()->comment('Special delivery instructions from customer');
            $table->text('admin_notes')->nullable()->comment('Internal notes for admins');

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('user_id');
            $table->index('order_number');
            $table->index(['status', 'created_at']);
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
