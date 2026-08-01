<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case PROCESSING = 'processing';
    case PACKED = 'packed';
    case SHIPPED = 'shipped';
    case OUT_FOR_DELIVERY = 'out_for_delivery';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
    case RETURNED = 'returned';
    case REFUNDED = 'refunded';

    /**
     * Statuses a customer is still allowed to cancel from.
     */
    public static function cancellable(): array
    {
        return [self::PENDING->value, self::CONFIRMED->value];
    }
}
