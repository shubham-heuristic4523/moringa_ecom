<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;

class NewOrderPlaced extends Notification
{
    public function __construct(private readonly Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_name' => $this->order->user?->name,
            'total' => $this->order->total,
            'message' => ($this->order->user?->name ?? 'A customer') . ' placed order ' . ($this->order->order_number ?? ('#' . $this->order->id)),
        ];
    }
}
