<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Models\Inventory;
use App\Modules\Inventory\Requests\UpdateStockRequest;
use App\Modules\Inventory\Services\InventoryService;
use App\Modules\Product\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    /**
     * Display a listing of inventory items.
     */
    public function index(Request $request): View
    {
        $query = Inventory::with(['product.category']);

        // Search functionality
        if ($search = $request->input('search')) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by stock status
        if ($status = $request->input('status')) {
            match ($status) {
                'out_of_stock' => $query->outOfStock(),
                'low_stock' => $query->lowStock(),
                'in_stock' => $query->inStock(),
                default => null,
            };
        }

        // Sort by specified column
        $sortBy = $request->input('sort_by', 'quantity_available');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $inventories = $query->paginate(50)->withQueryString();

        return view('pages.admin.inventory.index', [
            'inventories' => $inventories,
            'lowStockCount' => Inventory::lowStock()->count(),
            'outOfStockCount' => Inventory::outOfStock()->count(),
        ]);
    }

    /**
     * Show the form for editing inventory.
     */
    public function edit(Inventory $inventory): View
    {
        $inventory->load('product');

        return view('pages.admin.inventory.edit', [
            'inventory' => $inventory,
        ]);
    }

    /**
     * Update the specified inventory.
     */
    public function update(UpdateStockRequest $request, Inventory $inventory): RedirectResponse
    {
        $validated = $request->validated();

        $inventory->update([
            'quantity_available' => $validated['quantity_available'],
            'low_stock_threshold' => $validated['low_stock_threshold'],
        ]);

        // Sync with product stock_quantity if needed
        $this->inventoryService->syncProductStockQuantity($inventory->product);

        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Inventory updated successfully.');
    }

    /**
     * Create inventory record for a product.
     */
    public function create(): View
    {
        // Get products that don't have inventory records yet
        $productsWithoutInventory = Product::whereDoesntHave('inventory')
            ->orderBy('name')
            ->get();

        return view('pages.admin.inventory.create', [
            'products' => $productsWithoutInventory,
        ]);
    }

    /**
     * Store a newly created inventory.
     */
    public function store(UpdateStockRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->inventoryService->createOrUpdateInventory(
            productId: $validated['product_id'],
            quantityAvailable: $validated['quantity_available'],
            lowStockThreshold: $validated['low_stock_threshold']
        );

        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Inventory created successfully.');
    }

    /**
     * Show detailed inventory information.
     */
    public function show(Inventory $inventory): View
    {
        $inventory->load(['product.category', 'product.images']);

        return view('pages.admin.inventory.show', [
            'inventory' => $inventory,
        ]);
    }

    /**
     * Bulk update inventory thresholds.
     */
    public function bulkUpdateThresholds(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'threshold' => ['required', 'integer', 'min:0'],
        ]);

        Inventory::query()->update([
            'low_stock_threshold' => $validated['threshold'],
        ]);

        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'All inventory thresholds updated successfully.');
    }
}
