<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert social media links settings
        DB::table('site_settings')->insert([
            [
                'setting_key' => 'social_facebook',
                'setting_value' => '',
                'setting_type' => 'string',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'social_instagram',
                'setting_value' => '',
                'setting_type' => 'string',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'social_twitter',
                'setting_value' => '',
                'setting_type' => 'string',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'link_about_us',
                'setting_value' => '',
                'setting_type' => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'link_contact',
                'setting_value' => '',
                'setting_type' => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'link_faqs',
                'setting_value' => '',
                'setting_type' => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'link_return_policy',
                'setting_value' => '',
                'setting_type' => 'text',
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
        DB::table('site_settings')
            ->whereIn('setting_key', [
                'social_facebook',
                'social_instagram',
                'social_twitter',
                'link_about_us',
                'link_contact',
                'link_faqs',
                'link_return_policy',
            ])
            ->delete();
    }
};
