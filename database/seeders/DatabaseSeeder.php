<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@ecommerce.com',
            'password' => bcrypt('admin123'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Seed Categories (Men/Women with subcategories)
        $this->call([
            CategorySeeder::class,
        ]);

        $this->command->info('✓ Admin user created: admin@ecommerce.com / admin123');
        $this->command->info('✓ Fashion categories created: Men & Women');
    }
}
