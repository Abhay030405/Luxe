<?php

declare(strict_types=1);

namespace App\Modules\Cart\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cart\Requests\AddToCartRequest;
use App\Modules\Cart\Requests\UpdateCartItemRequest;
use App\Modules\Cart\Services\CartService;
use App\Modules\Wishlist\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly WishlistService $wishlistService
    ) {}

    /**
     * Display the cart page.
     */
    public function index(): View
    {
        $userId = auth()->id();
        $cart = $this->cartService->getUserCart($userId);
        $validationIssues = $this->cartService->validateCart($userId);

        return view('pages.cart.index', compact('cart', 'validationIssues'));
    }

    /**
     * Add a product to cart.
     */
    public function store(AddToCartRequest $request): RedirectResponse
    {
        try {
            $userId = auth()->id();

            $this->cartService->addToCart(
                userId: $userId,
                productId: $request->integer('product_id'),
                quantity: $request->integer('quantity', 1)
            );

            // Check if this is a "Buy Now" action
            if ($request->boolean('buy_now')) {
                return redirect()->route('checkout.index')->with('success', 'Proceeding to checkout!');
            }

            return redirect()->back()->with('success', 'Product added to cart successfully!');
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    /**
     * Add a product to cart via AJAX.
     */
    public function storeAjax(AddToCartRequest $request): JsonResponse
    {
        try {
            $userId = auth()->id();

            $cartItem = $this->cartService->addToCart(
                userId: $userId,
                productId: $request->integer('product_id'),
                quantity: $request->integer('quantity', 1)
            );

            $cartCount = $this->cartService->getCartItemCount($userId);
            $cartTotal = $this->cartService->getUserCart($userId)->grandTotal;

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'data' => [
                    'cart_item' => $cartItem->toArray(),
                    'cart_count' => $cartCount,
                    'cart_total' => $cartTotal,
                ],
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Update cart item quantity.
     */
    public function update(UpdateCartItemRequest $request, int $cartItemId): RedirectResponse
    {
        try {
            $userId = auth()->id();

            $this->cartService->updateCartItemQuantity(
                userId: $userId,
                cartItemId: $cartItemId,
                quantity: $request->integer('quantity')
            );

            return redirect()
                ->route('cart.index')
                ->with('success', 'Cart updated successfully!');
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Update cart item quantity via AJAX.
     */
    public function updateAjax(UpdateCartItemRequest $request, int $cartItemId): JsonResponse
    {
        try {
            $userId = auth()->id();

            $cartItem = $this->cartService->updateCartItemQuantity(
                userId: $userId,
                cartItemId: $cartItemId,
                quantity: $request->integer('quantity')
            );

            $cart = $this->cartService->getUserCart($userId);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'data' => [
                    'cart_item' => $cartItem->toArray(),
                    'grand_total' => $cart->grandTotal,
                ],
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Remove item from cart.
     */
    public function destroy(int $cartItemId): RedirectResponse
    {
        try {
            $userId = auth()->id();

            $this->cartService->removeFromCart($userId, $cartItemId);

            return redirect()
                ->route('cart.index')
                ->with('success', 'Item removed from cart successfully!');
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove item from cart via AJAX.
     */
    public function destroyAjax(int $cartItemId): JsonResponse
    {
        try {
            $userId = auth()->id();

            $this->cartService->removeFromCart($userId, $cartItemId);

            $cart = $this->cartService->getUserCart($userId);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully!',
                'data' => [
                    'cart_count' => $cart->totalItems,
                    'grand_total' => $cart->grandTotal,
                ],
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Remove item from cart and optionally add to wishlist.
     */
    public function removeWithWishlistOption(Request $request, int $cartItemId): RedirectResponse
    {
        try {
            $userId = auth()->id();

            // Get cart item before removing to get product_id
            $cart = $this->cartService->getUserCart($userId);
            $cartItem = $cart->items->firstWhere('id', $cartItemId);

            if (! $cartItem) {
                return redirect()->back()->with('error', 'Cart item not found.');
            }

            $productId = $cartItem->productId;

            // Add to wishlist if requested
            if ($request->boolean('add_to_wishlist')) {
                $this->wishlistService->addToWishlist($userId, $productId);
            }

            // Remove from cart
            $this->cartService->removeFromCart($userId, $cartItemId);

            $message = $request->boolean('add_to_wishlist')
                ? 'Item moved to wishlist successfully!'
                : 'Item removed from cart successfully!';

            return redirect()->route('cart.index')->with('success', $message);
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Clear entire cart.
     */
    public function clear(): RedirectResponse
    {
        $userId = auth()->id();
        $this->cartService->clearCart($userId);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Cart cleared successfully!');
    }

    /**
     * Get cart count for header.
     */
    public function count(): JsonResponse
    {
        $userId = auth()->id();
        $count = $this->cartService->getCartItemCount($userId);

        return response()->json([
            'count' => $count,
        ]);
    }
}
