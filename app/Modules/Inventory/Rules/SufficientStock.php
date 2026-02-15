<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Rules;

use App\Modules\Inventory\Services\InventoryService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SufficientStock implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(
        protected int $productId,
        protected int $requestedQuantity
    ) {}

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $inventoryService = app(InventoryService::class);

        if (! $inventoryService->checkAvailability($this->productId, $this->requestedQuantity)) {
            $fail('Insufficient stock available for the requested quantity.');
        }
    }
}
