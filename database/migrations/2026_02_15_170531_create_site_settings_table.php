<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('setting_key')->unique();
            $table->text('setting_value')->nullable();
            $table->string('setting_type')->default('string'); // string, text, boolean, json
            $table->timestamps();
        });

        // Insert default settings
        DB::table('site_settings')->insert([
            [
                'setting_key' => 'site_name',
                'setting_value' => 'Luxe Fashion',
                'setting_type' => 'string',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'site_tagline',
                'setting_value' => 'Premium Fashion Store',
                'setting_type' => 'string',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'footer_about',
                'setting_value' => 'Premium fashion and lifestyle products delivered to your doorstep.',
                'setting_type' => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'footer_email',
                'setting_value' => 'support@luxefashion.com',
                'setting_type' => 'string',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'footer_phone',
                'setting_value' => '+91 98765 43210',
                'setting_type' => 'string',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'footer_address',
                'setting_value' => 'Mumbai, Maharashtra, India',
                'setting_type' => 'string',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'currency_symbol',
                'setting_value' => '₹',
                'setting_type' => 'string',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'currency_code',
                'setting_value' => 'INR',
                'setting_type' => 'string',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
