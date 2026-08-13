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
     * A customer is "mine" if they registered on my storefront, or if
     * they've bought from me before (e.g. after browsing in from
     * somewhere else). Either is enough to show up in my customer list.
     */
    private function scopeToAdmin($query, int $adminId)
    {
        return $query->where(function ($outer) use ($adminId) {
            $outer->where('registered_via_admin_id', $adminId)
                ->orWhereHas('orders', fn ($q) => $q->where('admin_id', $adminId));
        });
    }

    /**
     * List all customers (search + status filter). A scoped admin only
     * sees their own customers, and order count/spend totals only
     * reflect orders placed with that admin.
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:active,inactive',
            'search' => 'nullable|string|max:255',
        ]);

        $adminId = $this->isScopedAdmin($request->user()) ? $request->user()->id : null;

        $customers = User::where('role', 'user')
            ->with('registeredViaAdmin:id,name')
            ->withCount(['orders' => fn ($query) => $query->when($adminId, fn ($q) => $q->where('admin_id', $adminId))])
            ->withSum(['orders as total_spent' => fn ($query) => $query->when($adminId, fn ($q) => $q->where('admin_id', $adminId))], 'total')
            ->when($adminId, fn ($query) => $this->scopeToAdmin($query, $adminId))
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
     * Show a single customer with their addresses and order history.
     * A scoped admin sees only their own recent orders (10); super_admin
     * sees the customer's full order history across every admin/store.
     */
    public function show(Request $request, $id)
    {
        $adminId = $this->isScopedAdmin($request->user()) ? $request->user()->id : null;

        $customer = User::where('role', 'user')
            ->with('registeredViaAdmin:id,name')
            ->withCount(['orders' => fn ($query) => $query->when($adminId, fn ($q) => $q->where('admin_id', $adminId))])
            ->withSum(['orders as total_spent' => fn ($query) => $query->when($adminId, fn ($q) => $q->where('admin_id', $adminId))], 'total')
            ->when($adminId, fn ($query) => $this->scopeToAdmin($query, $adminId))
            ->with([
                'customerProfile',
                'customerAddresses' => fn ($query) => $query->orderByDesc('is_default')->latest(),
                'orders' => fn ($query) => $query
                    ->with('admin:id,name', 'items.product')
                    ->when($adminId, fn ($q) => $q->where('admin_id', $adminId))
                    ->latest()
                    ->when($adminId, fn ($q) => $q->limit(10)),
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
            ->when($adminId, fn ($query) => $this->scopeToAdmin($query, $adminId))
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
