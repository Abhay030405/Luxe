<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // No dummy products - Admin will add products via admin panel
        $this->command->info('ProductSeeder: No products seeded. Products should be added via admin panel.');
    }
}
