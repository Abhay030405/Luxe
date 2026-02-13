<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\Requests\UpdateOrderStatusRequest;
use App\Modules\Admin\Services\AdminOrderService;
use App\Modules\Order\Models\Order;
use App\Shared\Enums\OrderStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private readonly AdminOrderService $adminOrderService
    ) {}

    /**
     * Display a listing of all orders.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $orders = Order::with(['user', 'items'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search, function ($query) use ($search) {
                $query->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(20);

        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('status', OrderStatus::Pending)->count(),
            'confirmed' => Order::where('status', OrderStatus::Confirmed)->count(),
            'processing' => Order::where('status', OrderStatus::Processing)->count(),
            'shipped' => Order::where('status', OrderStatus::Shipped)->count(),
            'delivered' => Order::where('status', OrderStatus::Delivered)->count(),
            'cancelled' => Order::where('status', OrderStatus::Cancelled)->count(),
        ];

        return view('pages.admin.orders.index', compact('orders', 'statusCounts', 'status', 'search'));
    }

    /**
     * Display the specified order details.
     */
    public function show(int $id): View
    {
        $order = Order::with(['user.profile', 'items.product'])
            ->findOrFail($id);

        return view('pages.admin.orders.show', compact('order'));
    }

    /**
     * Update the order status.
     */
    public function updateStatus(UpdateOrderStatusRequest $request, int $id): RedirectResponse
    {
        try {
            $this->adminOrderService->updateOrderStatus(
                $id,
                OrderStatus::from($request->validated('status')),
                $request->validated('admin_notes')
            );

            return redirect()
                ->route('admin.orders.show', $id)
                ->with('success', 'Order status updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to update order status: '.$e->getMessage());
        }
    }

    /**
     * Cancel an order.
     */
    public function cancel(Request $request, int $id): RedirectResponse
    {
        try {
            $this->adminOrderService->cancelOrder($id, $request->input('reason'));

            return redirect()
                ->route('admin.orders.show', $id)
                ->with('success', 'Order cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to cancel order: '.$e->getMessage());
        }
    }
}
