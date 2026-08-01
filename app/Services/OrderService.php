<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Customer\CustomerAddress;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    /**
     * Place a new order for the given user, validating stock and
     * snapshotting product details onto the order items.
     */
    public function createOrder(User $user, int $addressId, array $items, ?string $notes = null): Order
    {
        return DB::transaction(function () use ($user, $addressId, $items, $notes) {

            $address = CustomerAddress::where('user_id', $user->id)
                ->findOrFail($addressId);

            $subtotal = 0;
            $orderItemsData = [];

            foreach ($items as $item) {
                $product = Product::whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw ValidationException::withMessages([
                        'items' => "Product #{$item['product_id']} does not exist.",
                    ]);
                }

                if ($product->quantity < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => "Insufficient stock for \"{$product->name}\". Only {$product->quantity} left.",
                    ]);
                }

                $lineTotal = $product->price * $item['quantity'];
                $subtotal += $lineTotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'line_total' => $lineTotal,
                ];

                $product->decrement('quantity', $item['quantity']);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'status' => OrderStatus::PENDING->value,
                'subtotal' => $subtotal,
                'shipping' => 0,
                'tax' => 0,
                'total' => $subtotal,
                'notes' => $notes,
            ]);

            $order->update([
                'order_number' => 'ORD-' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT),
            ]);

            $order->items()->createMany($orderItemsData);

            return $order->load('items.product', 'address');
        });
    }

    /**
     * List orders belonging to a single user.
     */
    public function listForUser(User $user, ?string $status = null, int $perPage = 10)
    {
        return Order::where('user_id', $user->id)
            ->when($status, fn ($query) => $query->where('status', $status))
            ->with('items.product', 'address')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Fetch a single order owned by the given user.
     */
    public function findForUser(User $user, int $orderId): Order
    {
        return Order::where('user_id', $user->id)
            ->with('items.product', 'address')
            ->findOrFail($orderId);
    }

    /**
     * Cancel an order on behalf of its owner, restocking items.
     * Only allowed while the order is still pending/confirmed.
     */
    public function cancel(Order $order, ?string $reason = null): Order
    {
        if (!in_array($order->status, OrderStatus::cancellable(), true)) {
            throw ValidationException::withMessages([
                'status' => 'This order can no longer be cancelled.',
            ]);
        }

        return DB::transaction(function () use ($order, $reason) {
            $this->restockItems($order);

            $order->update([
                'status' => OrderStatus::CANCELLED->value,
                'cancelled_at' => now(),
                'cancellation_reason' => $reason,
            ]);

            return $order->fresh(['items.product', 'address']);
        });
    }

    /**
     * Admin: list every order, optionally filtered/searched.
     */
    public function listAll(?string $status = null, ?string $search = null, int $perPage = 15)
    {
        return Order::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->with('user', 'items.product', 'address')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Admin: fetch any order regardless of owner.
     */
    public function findAny(int $orderId): Order
    {
        return Order::with('user', 'items.product', 'address')
            ->findOrFail($orderId);
    }

    /**
     * Admin: create an order on behalf of a customer.
     */
    public function createOrderForUser(int $userId, int $addressId, array $items, ?string $notes = null): Order
    {
        $user = User::findOrFail($userId);

        return $this->createOrder($user, $addressId, $items, $notes);
    }

    /**
     * Admin: update status and/or notes. Restocks items when the
     * order transitions into cancelled/returned from a non-terminal state.
     */
    public function updateStatus(Order $order, ?string $status, ?string $adminNotes = null): Order
    {
        return DB::transaction(function () use ($order, $status, $adminNotes) {

            $terminal = [OrderStatus::CANCELLED->value, OrderStatus::RETURNED->value, OrderStatus::REFUNDED->value];

            if ($status && $status !== $order->status && in_array($status, $terminal, true)
                && !in_array($order->status, $terminal, true)) {
                $this->restockItems($order);
            }

            $order->update(array_filter([
                'status' => $status,
                'admin_notes' => $adminNotes,
            ], fn ($value) => $value !== null));

            return $order->fresh(['user', 'items.product', 'address']);
        });
    }

    /**
     * Admin: permanently delete an order, restocking items first
     * unless it was already in a terminal (non-fulfilling) state.
     */
    public function delete(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $terminal = [OrderStatus::CANCELLED->value, OrderStatus::RETURNED->value, OrderStatus::REFUNDED->value];

            if (!in_array($order->status, $terminal, true)) {
                $this->restockItems($order);
            }

            $order->delete();
        });
    }

    /**
     * Return each item's quantity back to product stock.
     */
    private function restockItems(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_id) {
                Product::whereKey($item->product_id)->increment('quantity', $item->quantity);
            }
        }
    }
}
