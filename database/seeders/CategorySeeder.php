<?php

namespace Database\Seeders;

use App\Modules\Product\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Men',
                'description' => 'Men\'s fashion collection',
                'subcategories' => [
                    'T-Shirts',
                    'Shirts',
                    'Jeans',
                    'Trousers',
                    'Shorts',
                    'Jackets',
                    'Hoodies',
                    'Shoes',
                    'Sneakers',
                    'Formal Shoes',
                    'Bags',
                    'Accessories',
                ],
            ],
            [
                'name' => 'Women',
                'description' => 'Women\'s fashion collection',
                'subcategories' => [
                    'T-Shirts',
                    'Tops',
                    'Shirts',
                    'Jeans',
                    'Trousers',
                    'Skirts',
                    'Dresses',
                    'Jackets',
                    'Hoodies',
                    'Shoes',
                    'Heels',
                    'Sneakers',
                    'Bags',
                    'Handbags',
                    'Accessories',
                ],
            ],
        ];

        foreach ($categories as $index => $categoryData) {
            $parent = Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
                'is_active' => true,
                'sort_order' => ($index + 1) * 10,
            ]);

            // Create subcategories
            foreach ($categoryData['subcategories'] as $subIndex => $subName) {
                Category::create([
                    'name' => $subName,
                    'slug' => Str::slug($parent->name.'-'.$subName), // Make unique with parent
                    'parent_id' => $parent->id,
                    'is_active' => true,
                    'sort_order' => ($subIndex + 1) * 10,
                ]);
            }
        }
    }
}
