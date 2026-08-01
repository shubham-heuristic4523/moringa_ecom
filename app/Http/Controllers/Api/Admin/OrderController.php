<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
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
                $request->query('search')
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
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = $this->orderService->createOrderForUser(
            $validated['user_id'],
            $validated['address_id'],
            $validated['items'],
            $validated['notes'] ?? null
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
    public function show(int $id)
    {
        return response()->json([
            'success' => true,
            'data' => $this->orderService->findAny($id),
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

        $order = $this->orderService->findAny($id);

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
    public function destroy(int $id)
    {
        $order = $this->orderService->findAny($id);

        $this->orderService->delete($order);

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully.',
        ]);
    }
}
