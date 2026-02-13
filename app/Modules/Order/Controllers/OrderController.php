<?php

declare(strict_types=1);

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Requests\CancelOrderRequest;
use App\Modules\Order\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * OrderController handles order history and order details.
 * Allows users to view their orders and manage them.
 */
class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {}

    /**
     * Display user's order history.
     */
    public function index(): View
    {
        $userId = Auth::id();

        $orders = $this->orderService->getUserOrders($userId, 10);
        $stats = $this->orderService->getUserOrderStats($userId);

        return view('pages.orders.index', [
            'orders' => $orders,
            'stats' => $stats,
        ]);
    }

    /**
     * Display order details.
     */
    public function show(int $id): View|RedirectResponse
    {
        $userId = Auth::id();

        try {
            $data = $this->orderService->getOrderWithSummary($id, $userId);

            return view('pages.orders.show', [
                'order' => $data['order'],
                'summary' => $data['summary'],
            ]);
        } catch (\InvalidArgumentException) {
            return redirect()->route('orders.index')
                ->with('error', 'Order not found or access denied.');
        }
    }

    /**
     * Cancel an order.
     */
    public function cancel(int $id, CancelOrderRequest $request): RedirectResponse
    {
        $userId = Auth::id();

        try {
            $reason = $request->getReason();

            $cancelled = $this->orderService->cancelOrder($id, $userId, $reason);

            if ($cancelled) {
                return redirect()->route('orders.show', $id)
                    ->with('success', 'Order cancelled successfully.');
            }

            return redirect()->back()
                ->with('error', 'Failed to cancel order.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Track order by order number.
     */
    public function track(string $orderNumber): View|RedirectResponse
    {
        $userId = Auth::id();

        $trackingInfo = $this->orderService->trackOrder($orderNumber, $userId);

        if (! $trackingInfo) {
            return redirect()->route('orders.index')
                ->with('error', 'Order not found or access denied.');
        }

        return view('pages.orders.track', [
            'tracking' => $trackingInfo,
        ]);
    }
}
