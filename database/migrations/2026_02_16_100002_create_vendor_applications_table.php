<?php

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
        Schema::create('vendor_applications', function (Blueprint $table) {
            $table->id();

            // Applicant Information
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone');

            // Business Details
            $table->string('shop_name');
            $table->string('business_address');
            $table->string('city');
            $table->string('state');
            $table->string('pincode');

            // Operational Details
            $table->string('product_category');
            $table->integer('estimated_products')->default(0);
            $table->text('pickup_address')->nullable();

            // Optional Fields
            $table->string('gst_number')->nullable();
            $table->string('id_proof')->nullable(); // file path

            // Application Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();

            // Review Information
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            // Created vendor reference (after approval)
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();

            $table->timestamps();

            // Indexes
            $table->index('email');
            $table->index('status');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_applications');
    }
};
