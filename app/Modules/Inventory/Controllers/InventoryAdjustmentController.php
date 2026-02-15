<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Models\Inventory;
use App\Modules\Inventory\Requests\AdjustInventoryRequest;
use App\Modules\Inventory\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class InventoryAdjustmentController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    /**
     * Manually adjust inventory quantity.
     */
    public function adjust(AdjustInventoryRequest $request, Inventory $inventory): RedirectResponse
    {
        $validated = $request->validated();
        $quantityChange = $validated['quantity_change'];
        $reason = $validated['reason'] ?? 'manual_adjustment';

        try {
            $this->inventoryService->adjustInventory(
                $inventory->product_id,
                $quantityChange,
                $reason
            );

            Log::info('Inventory adjusted by admin', [
                'product_id' => $inventory->product_id,
                'admin_id' => auth()->id(),
                'quantity_change' => $quantityChange,
                'reason' => $reason,
            ]);

            return redirect()->back()->with('success', 'Inventory adjusted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to adjust inventory', [
                'product_id' => $inventory->product_id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to adjust inventory. Please try again.');
        }
    }
}
