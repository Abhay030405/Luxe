<?php

namespace App\Http\View\Composers;

use App\Modules\Cart\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartComposer
{
    public function __construct(
        private readonly CartService $cartService
    ) {}

    public function compose(View $view): void
    {
        $count = 0;

        if (Auth::check()) {
            try {
                $count = $this->cartService->getCartItemCount(Auth::id());
            } catch (\Exception $e) {
                // Fail silently if cart service has issues, to not break the whole UI
                $count = 0;
            }
        }

        $view->with('cartCount', $count);
    }
}
