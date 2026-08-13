<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Concerns\ScopesByOwner;
use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    use ScopesByOwner;

    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * The current admin's id if they should be scoped to their own orders,
     * or null (unscoped) for super_admin.
     */
    private function scopeId(Request $request): ?int
    {
        return $this->isScopedAdmin($request->user()) ? $request->user()->id : null;
    }

    /**
     * List all orders (search + status filter).
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|string',
            'search' => 'nullable|string|max:255',
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->orderService->listAll(
                $request->query('status'),
                $request->query('search'),
                15,
                $this->scopeId($request)
            ),
        ]);
    }

    /**
     * Create an order on behalf of a customer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'address_id' => 'required|integer',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.variant_id' => 'nullable|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = $this->orderService->createOrderForUser(
            $validated['user_id'],
            $validated['address_id'],
            $validated['items'],
            $validated['notes'] ?? null,
            $this->scopeId($request)
        );

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully.',
            'data' => $order,
        ], 201);
    }

    /**
     * Show any order.
     */
    public function show(Request $request, int $id)
    {
        return response()->json([
            'success' => true,
            'data' => $this->orderService->findAny($id, $this->scopeId($request)),
        ]);
    }

    /**
     * Update an order's status and/or admin notes.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => ['sometimes', Rule::enum(OrderStatus::class)],
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $order = $this->orderService->findAny($id, $this->scopeId($request));

        $order = $this->orderService->updateStatus(
            $order,
            $validated['status'] ?? null,
            $validated['admin_notes'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully.',
            'data' => $order,
        ]);
    }

    /**
     * Delete an order.
     */
    public function destroy(Request $request, int $id)
    {
        $order = $this->orderService->findAny($id, $this->scopeId($request));

        $this->orderService->delete($order);

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully.',
        ]);
    }
}
