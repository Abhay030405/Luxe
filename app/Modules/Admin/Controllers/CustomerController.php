<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $customers = User::where('is_admin', false)
            ->with(['profile', 'addresses'])
            ->withCount('orders')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        return view('pages.admin.customers.index', compact('customers', 'search'));
    }

    /**
     * Display the specified customer details.
     */
    public function show(int $id): View
    {
        $customer = User::where('is_admin', false)
            ->with(['profile', 'addresses', 'orders.items'])
            ->withCount('orders')
            ->findOrFail($id);

        return view('pages.admin.customers.show', compact('customer'));
    }
}
