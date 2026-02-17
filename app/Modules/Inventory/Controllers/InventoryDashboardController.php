<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryDashboardController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    /**
     * Display inventory dashboard with low stock alerts.
     */
    public function index(Request $request): View
    {
        $lowStockProducts = $this->inventoryService->getLowStockProducts();
        $outOfStockProducts = $this->inventoryService->getOutOfStockProducts();

        return view('pages.admin.inventory.dashboard', [
            'lowStockProducts' => $lowStockProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'lowStockCount' => $lowStockProducts->count(),
            'outOfStockCount' => $outOfStockProducts->count(),
        ]);
    }

    /**
     * Show inventory management page.
     */
    public function manage(): View
    {
        $inventories = \App\Modules\Inventory\Models\Inventory::with('product')
            ->orderBy('quantity_available', 'asc')
            ->paginate(50);

        return view('pages.admin.inventory.index', [
            'inventories' => $inventories,
            'lowStockCount' => Inventory::lowStock()->count(),
            'outOfStockCount' => Inventory::outOfStock()->count(),
        ]);
    }
}
