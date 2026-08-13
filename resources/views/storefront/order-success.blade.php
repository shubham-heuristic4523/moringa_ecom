@extends('layouts.storefront')

@section('title', 'Order Confirmed')

@section('content')

<section class="sf-section" style="min-height:60vh;">
    <div class="container" style="max-width:40rem;">

        <div class="text-center mb-4">
            <div class="mx-auto mb-3" style="width:4.5rem; height:4.5rem; border-radius:50%; background:color-mix(in srgb, var(--brand-primary) 15%, transparent); display:flex; align-items:center; justify-content:center; font-size:2rem; color:var(--brand-primary);">
                <i class="fa-solid fa-check"></i>
            </div>
            <h1 class="fw-bold" style="font-size:1.7rem;">Order Placed Successfully!</h1>
            <p class="text-muted">Thank you for your order — we'll deliver it soon.</p>
        </div>

        <div id="orderSummaryCard" class="sf-testimonial" style="padding:1.5rem;">
            <div class="sf-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading order details…</div>
        </div>

        <div class="text-center mt-4 d-flex gap-2 justify-content-center flex-wrap">
            <a href="{{ route('account') }}#orders" class="sf-btn sf-btn-primary">View My Orders</a>
            <a href="{{ route('home') }}#featured" class="sf-btn sf-btn-outline">Continue Shopping</a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {
    const orderId = @json($order ?? null);

    if (!SF.isLoggedIn()) {
        window.location.href = SF.loginUrl();
        return;
    }

    async function loadOrder() {
        const card = document.getElementById('orderSummaryCard');

        try {
            const res = await fetch('/api/orders/' + orderId, { headers: SF.authHeaders() });
            const payload = await res.json();

            if (!payload.success) {
                card.innerHTML = `<div class="sf-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not find that order.</div>`;
                return;
            }

            const order = payload.data;

            card.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <p class="text-muted small mb-0">Order Number</p>
                        <p class="fw-bold mb-0">${SF.escapeHtml(order.order_number)}</p>
                    </div>
                    <span class="badge bg-success-subtle text-success text-capitalize">${SF.escapeHtml(order.status)}</span>
                </div>

                <div class="d-flex flex-column gap-2 mb-3">
                    ${(order.items ?? []).map(item => `
                        <div class="d-flex justify-content-between small">
                            <span>${SF.escapeHtml(item.product_name)} × ${item.quantity}</span>
                            <span>${SF.money(item.line_total)}</span>
                        </div>
                    `).join('')}
                </div>

                <div class="pt-3 border-top">
                    <div class="d-flex justify-content-between small mb-1"><span class="text-muted">Subtotal</span><span>${SF.money(order.subtotal)}</span></div>
                    ${parseFloat(order.discount) > 0 ? `<div class="d-flex justify-content-between small mb-1 text-success"><span>Discount ${order.coupon_code ? '(' + SF.escapeHtml(order.coupon_code) + ')' : ''}</span><span>-${SF.money(order.discount)}</span></div>` : ''}
                    <div class="d-flex justify-content-between fw-bold mt-2"><span>Total</span><span>${SF.money(order.total)}</span></div>
                </div>

                <div class="pt-3 mt-3 border-top small">
                    <p class="text-muted mb-1"><i class="fa-solid fa-location-dot me-1"></i> Delivering to</p>
                    <p class="mb-0">${SF.escapeHtml(order.address?.full_name)}, ${SF.escapeHtml(order.address?.address_line_1)}, ${SF.escapeHtml(order.address?.city)}</p>
                    <p class="text-muted mt-2 mb-0"><i class="fa-solid fa-money-bill-wave me-1"></i> Payment: Cash on Delivery</p>
                </div>
            `;
        } catch (err) {
            card.innerHTML = `<div class="sf-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load order details.</div>`;
        }
    }

    loadOrder();
})();
</script>
@endpush
