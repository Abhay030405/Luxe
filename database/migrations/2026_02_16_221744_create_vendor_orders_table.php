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
        Schema::create('vendor_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->comment('Parent customer order');
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete()->comment('Vendor who must fulfill this order');
            $table->string('vendor_order_number', 50)->unique()->comment('Unique identifier like VND-A-000145');

            // Vendor-specific status workflow
            $table->string('status', 20)->default('pending')->index()->comment('pending, accepted, packed, shipped, delivered, cancelled, rejected');

            // Financial breakdown for this vendor
            $table->decimal('subtotal', 10, 2)->comment('Sum of items for this vendor');
            $table->decimal('commission_rate', 5, 2)->default(0)->comment('Platform commission percentage');
            $table->decimal('commission_amount', 10, 2)->default(0)->comment('Calculated commission');
            $table->decimal('vendor_earnings', 10, 2)->comment('Amount vendor will receive');

            // Shipping info
            $table->string('tracking_number')->nullable()->comment('Shipping tracking number');
            $table->string('shipping_carrier')->nullable()->comment('Courier service name');
            $table->timestamp('accepted_at')->nullable()->comment('When vendor accepted the order');
            $table->timestamp('packed_at')->nullable()->comment('When vendor packed the items');
            $table->timestamp('shipped_at')->nullable()->comment('When vendor shipped the order');
            $table->timestamp('delivered_at')->nullable()->comment('When order was delivered');
            $table->timestamp('cancelled_at')->nullable()->comment('When order was cancelled');

            // Notes
            $table->text('vendor_notes')->nullable()->comment('Vendor notes about fulfillment');
            $table->text('cancellation_reason')->nullable()->comment('Why order was cancelled/rejected');

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('order_id');
            $table->index('vendor_id');
            $table->index('vendor_order_number');
            $table->index(['vendor_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_orders');
    }
};
