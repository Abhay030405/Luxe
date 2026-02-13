<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Order\Models\Order;
use App\Modules\Product\Models\Product;
use App\Shared\Enums\OrderStatus;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with business statistics.
     */
    public function index(): View
    {
        $stats = [
            'total_users' => User::where('is_admin', false)->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', OrderStatus::Pending)->count(),
            'confirmed_orders' => Order::where('status', OrderStatus::Confirmed)->count(),
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'active')->count(),
            'low_stock_products' => Product::where('stock_quantity', '<=', 10)
                ->where('stock_quantity', '>', 0)
                ->count(),
            'out_of_stock_products' => Product::where('stock_quantity', 0)->count(),
            'total_revenue' => Order::whereIn('status', [
                OrderStatus::Confirmed,
                OrderStatus::Processing,
                OrderStatus::Shipped,
                OrderStatus::Delivered,
            ])->sum('total_amount'),
        ];

        // Get latest orders
        $latestOrders = Order::with(['user', 'items'])
            ->latest()
            ->limit(10)
            ->get();

        // Get low stock products
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity', 'asc')
            ->limit(10)
            ->get();

        return view('pages.admin.dashboard', compact('stats', 'latestOrders', 'lowStockProducts'));
    }
}
