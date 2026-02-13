<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Fetch featured products (for hero section showcase)
        $featuredProducts = Product::with(['images', 'primaryImage'])
            ->where('is_featured', true)
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0)
            ->latest()
            ->take(4)
            ->get();

        // Fetch latest/new arrival products
        $newArrivals = Product::with(['images', 'primaryImage'])
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        // Fetch main categories for category highlights
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        return view('welcome', compact('featuredProducts', 'newArrivals', 'categories'));
    }
}
