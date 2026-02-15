<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Product\Models\Category;
use App\Modules\Product\Services\ProductService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function index(): View
    {
        // Fetch featured products (for hero section showcase)
        $featuredProducts = $this->productService->getFeaturedProducts(limit: 4);

        // Fetch latest/new arrival products
        $newArrivals = $this->productService->getLatestProducts(limit: 8);

        // Fetch main categories for category highlights
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        return view('welcome', compact('featuredProducts', 'newArrivals', 'categories'));
    }
}
