<?php

declare(strict_types=1);

namespace App\Modules\Wishlist\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Wishlist\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(
        private readonly WishlistService $wishlistService
    ) {}

    /**
     * Display the wishlist page.
     */
    public function index(): View
    {
        $userId = auth()->id();
        $wishlistItems = $this->wishlistService->getUserWishlist($userId);

        return view('pages.wishlist.index', compact('wishlistItems'));
    }

    /**
     * Add a product to wishlist (AJAX).
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $userId = auth()->id();
        $result = $this->wishlistService->addToWishlist($userId, $request->integer('product_id'));

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Toggle wishlist status (add/remove).
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $userId = auth()->id();
        $result = $this->wishlistService->toggleWishlist($userId, $request->integer('product_id'));

        return response()->json($result);
    }

    /**
     * Remove a product from wishlist.
     */
    public function destroy(int $productId): RedirectResponse
    {
        $userId = auth()->id();
        $result = $this->wishlistService->removeFromWishlist($userId, $productId);

        return redirect()
            ->back()
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /**
     * Remove a product from wishlist via AJAX.
     */
    public function destroyAjax(int $productId): JsonResponse
    {
        $userId = auth()->id();
        $result = $this->wishlistService->removeFromWishlist($userId, $productId);

        return response()->json($result);
    }

    /**
     * Check if a product is in wishlist.
     */
    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $userId = auth()->id();
        $isInWishlist = $this->wishlistService->isInWishlist($userId, $request->integer('product_id'));

        return response()->json([
            'in_wishlist' => $isInWishlist,
        ]);
    }

    /**
     * Get wishlist count.
     */
    public function count(): JsonResponse
    {
        $userId = auth()->id();
        $count = $this->wishlistService->getWishlistCount($userId);

        return response()->json([
            'count' => $count,
        ]);
    }

    /**
     * Clear entire wishlist.
     */
    public function clear(): RedirectResponse
    {
        $userId = auth()->id();
        $this->wishlistService->clearWishlist($userId);

        return redirect()
            ->route('wishlist.index')
            ->with('success', 'Wishlist cleared successfully.');
    }
}
