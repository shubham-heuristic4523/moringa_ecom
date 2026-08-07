<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Concerns\ScopesByOwner;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    use ScopesByOwner;

    /**
     * List all customers (search + status filter). A scoped admin only
     * sees customers who have bought from them, and their order
     * count/spend totals only reflect orders placed with that admin.
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:active,inactive',
            'search' => 'nullable|string|max:255',
        ]);

        $adminId = $this->isScopedAdmin($request->user()) ? $request->user()->id : null;

        $customers = User::where('role', 'user')
            ->withCount(['orders' => fn ($query) => $query->when($adminId, fn ($q) => $q->where('admin_id', $adminId))])
            ->withSum(['orders as total_spent' => fn ($query) => $query->when($adminId, fn ($q) => $q->where('admin_id', $adminId))], 'total')
            ->when($adminId, fn ($query) => $query->whereHas('orders', fn ($q) => $q->where('admin_id', $adminId)))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($inner) use ($request) {
                    $inner->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }

    /**
     * Show a single customer with their addresses and recent orders.
     */
    public function show(Request $request, $id)
    {
        $adminId = $this->isScopedAdmin($request->user()) ? $request->user()->id : null;

        $customer = User::where('role', 'user')
            ->withCount(['orders' => fn ($query) => $query->when($adminId, fn ($q) => $q->where('admin_id', $adminId))])
            ->withSum(['orders as total_spent' => fn ($query) => $query->when($adminId, fn ($q) => $q->where('admin_id', $adminId))], 'total')
            ->when($adminId, fn ($query) => $query->whereHas('orders', fn ($q) => $q->where('admin_id', $adminId)))
            ->with([
                'customerProfile',
                'customerAddresses' => fn ($query) => $query->orderByDesc('is_default')->latest(),
                'orders' => fn ($query) => $query->when($adminId, fn ($q) => $q->where('admin_id', $adminId))->latest()->limit(10),
            ])
            ->find($id);

        if (! $customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $customer,
        ]);
    }

    /**
     * Activate or deactivate a customer account.
     */
    public function update(Request $request, $id)
    {
        $adminId = $this->isScopedAdmin($request->user()) ? $request->user()->id : null;

        $customer = User::where('role', 'user')
            ->when($adminId, fn ($query) => $query->whereHas('orders', fn ($q) => $q->where('admin_id', $adminId)))
            ->find($id);

        if (! $customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $customer->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully.',
            'data' => $customer,
        ]);
    }
}
