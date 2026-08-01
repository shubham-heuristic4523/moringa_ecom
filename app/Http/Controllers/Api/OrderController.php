<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * List the authenticated customer's orders.
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|string',
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->orderService->listForUser(
                $request->user(),
                $request->query('status')
            ),
        ]);
    }

    /**
     * Place a new order.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'required|integer',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = $this->orderService->createOrder(
            $request->user(),
            $validated['address_id'],
            $validated['items'],
            $validated['notes'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully.',
            'data' => $order,
        ], 201);
    }

    /**
     * Show a single order belonging to the authenticated customer.
     */
    public function show(Request $request, int $id)
    {
        return response()->json([
            'success' => true,
            'data' => $this->orderService->findForUser($request->user(), $id),
        ]);
    }

    /**
     * Cancel an order (only while pending/confirmed).
     */
    public function cancel(Request $request, int $id)
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        $order = $this->orderService->findForUser($request->user(), $id);

        $order = $this->orderService->cancel($order, $validated['reason'] ?? null);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully.',
            'data' => $order,
        ]);
    }
}
