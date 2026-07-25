{{--
 | Email Template: emails/order/confirmation.blade.php
 | Layout: layouts/email (inline-styled for email clients)
 | Source: order_confirmation_success/code.html (adapted)
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Order Confirmed — LUXE</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f9f9ff; color: #141b2b; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .header { background-color: #9d4300; padding: 32px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 28px; font-weight: 700; margin: 0; letter-spacing: -0.02em; }
        .header p { color: rgba(255,255,255,0.8); font-size: 14px; margin: 8px 0 0; }
        .body { padding: 40px 32px; }
        .success-icon { text-align: center; margin-bottom: 24px; }
        .success-icon .circle { display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background-color: #7cf994; border-radius: 50%; }
        h2 { font-size: 24px; font-weight: 600; color: #141b2b; margin: 0 0 8px; }
        p { font-size: 16px; line-height: 1.6; color: #584237; margin: 0 0 16px; }
        .order-info { background: #f1f3ff; border-radius: 8px; padding: 20px; margin: 24px 0; }
        .order-info p { margin: 0 0 8px; font-size: 14px; }
        .order-info p:last-child { margin: 0; }
        .order-info strong { color: #141b2b; }
        .item-row { display: flex; align-items: center; gap: 16px; padding: 16px 0; border-bottom: 1px solid #e0c0b1; }
        .item-row:last-child { border-bottom: none; }
        .item-img { width: 60px; height: 60px; background: #e9edff; border-radius: 8px; object-fit: cover; }
        .item-name { font-size: 14px; font-weight: 600; color: #141b2b; }
        .item-variant { font-size: 12px; color: #584237; margin-top: 4px; }
        .summary-row { display: flex; justify-content: space-between; font-size: 14px; color: #584237; margin-bottom: 8px; }
        .summary-total { display: flex; justify-content: space-between; font-size: 18px; font-weight: 700; color: #141b2b; padding-top: 16px; border-top: 2px solid #e0c0b1; }
        .cta-btn { display: block; text-align: center; background-color: #9d4300; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 600; padding: 14px 32px; border-radius: 6px; margin: 32px auto 0; max-width: 200px; }
        .footer { background: #dce2f7; padding: 24px 32px; text-align: center; font-size: 12px; color: #584237; }
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <h1>LUXE</h1>
        <p>Order Confirmation</p>
    </div>

    <div class="body">

        <div class="success-icon">
            <div class="circle">✓</div>
        </div>

        <h2>Order Confirmed!</h2>
        <p>Hi {{ $order->customer->name }},<br>Thank you for your order. We'll send you a shipping confirmation once your order is on its way.</p>

        <div class="order-info">
            <p><strong>Order:</strong> #{{ $order->order_number }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
            <p><strong>Payment:</strong> {{ ucfirst($order->payment_method) }}</p>
            <p style="margin:0;"><strong>Shipping to:</strong> {{ $order->shippingAddress->full_address }}</p>
        </div>

        <h3 style="font-size:16px;font-weight:600;margin:0 0 16px;">Items Ordered</h3>

        @foreach($order->items as $item)
        <div class="item-row">
            <img class="item-img" src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}"/>
            <div style="flex:1;">
                <div class="item-name">{{ $item->product->name }}</div>
                @if($item->variant) <div class="item-variant">{{ $item->variant }}</div> @endif
                <div class="item-variant">Qty: {{ $item->quantity }}</div>
            </div>
            <div style="font-weight:600;font-size:14px;color:#141b2b;">{{ money($item->price * $item->quantity) }}</div>
        </div>
        @endforeach

        <div style="margin-top:24px;">
            <div class="summary-row"><span>Subtotal</span><span>{{ money($order->subtotal) }}</span></div>
            <div class="summary-row"><span>Shipping</span><span>{{ $order->shipping > 0 ? money($order->shipping) : 'Free' }}</span></div>
            <div class="summary-row"><span>Tax</span><span>{{ money($order->tax) }}</span></div>
            <div class="summary-total"><span>Total</span><span>{{ money($order->total) }}</span></div>
        </div>

        <a class="cta-btn" href="{{ route('customer.orders.show', $order->id) }}">View Order</a>

    </div>

    <div class="footer">
        <p>© {{ date('Y') }} LUXE Ecommerce. All rights reserved.</p>
        <p style="margin-top:8px;"><a href="#" style="color:#9d4300;">Unsubscribe</a> · <a href="#" style="color:#9d4300;">Privacy Policy</a></p>
    </div>

</div>
</body>
</html>
