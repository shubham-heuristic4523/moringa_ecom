@extends('layouts.storefront')

@section('title', 'Your Cart')

@section('content')

<section class="sf-section pt-4 pb-5" style="min-height:60vh;">
    <div class="container">
        <h1 class="fw-bold mb-4" style="font-size:1.8rem;">Your Cart</h1>

        <div class="row g-4">
            <div class="col-lg-8">
                <div id="cartItemsList" class="d-flex flex-column gap-3"></div>
            </div>

            <div class="col-lg-4">
                <div class="sf-testimonial" style="padding:1.5rem;">
                    <h5 class="fw-bold mb-3">Order Summary</h5>

                    <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;">
                        <span class="text-muted">Subtotal</span>
                        <span id="sumSubtotal">₹0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success" style="font-size:.9rem;" id="sumDiscountRow" hidden>
                        <span>Discount <span id="sumCouponLabel"></span></span>
                        <span id="sumDiscount">-₹0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pt-2 border-top fw-bold">
                        <span>Total</span>
                        <span id="sumTotal">₹0.00</span>
                    </div>

                    <div id="couponBox" class="mb-3">
                        <label class="form-label small fw-semibold">Have a coupon code?</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="couponInput" placeholder="e.g. SAVE20" style="text-transform:uppercase;">
                            <button type="button" class="btn sf-btn sf-btn-outline" id="applyCouponBtn">Apply</button>
                        </div>
                        <div id="couponMsg" class="small mt-2"></div>
                        <div id="couponApplied" class="d-none align-items-center justify-content-between mt-2 p-2" style="background:var(--sf-bg); border-radius:.5rem;">
                            <span class="small"><i class="fa-solid fa-tag me-1 text-success"></i><strong id="couponAppliedCode"></strong></span>
                            <button type="button" class="btn btn-sm text-danger" id="removeCouponBtn"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    </div>

                    <button type="button" class="sf-btn sf-btn-primary w-100" id="cartCheckoutBtn">
                        Proceed to Checkout <i class="fa-solid fa-arrow-right ms-1"></i>
                    </button>
                    <a href="{{ route('home') }}#featured" class="sf-btn sf-btn-outline w-100 mt-2">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {
    const COUPON_KEY = 'sf_coupon';

    function readCoupon() { try { return JSON.parse(localStorage.getItem(COUPON_KEY)); } catch { return null; } }
    function writeCoupon(coupon) { coupon ? localStorage.setItem(COUPON_KEY, JSON.stringify(coupon)) : localStorage.removeItem(COUPON_KEY); }

    function renderItems() {
        const list = document.getElementById('cartItemsList');
        const items = SF.readCart();

        list.innerHTML = items.length ? items.map(item => `
            <div class="sf-testimonial d-flex gap-3 align-items-center" style="padding:1rem;">
                ${item.image ? `<img src="${item.image}" style="width:5rem;height:5rem;border-radius:.7rem;object-fit:cover;">` : `<div style="width:5rem;height:5rem;border-radius:.7rem;background:var(--sf-bg);display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-seedling"></i></div>`}
                <div class="flex-grow-1">
                    <p class="fw-semibold mb-1">${SF.escapeHtml(item.name)}</p>
                    ${item.variant_label ? `<p class="text-muted mb-1 small">${SF.escapeHtml(item.variant_label)}</p>` : ''}
                    <p class="text-muted mb-2 small">${SF.money(item.price)} each</p>
                    <div class="sf-qty-control">
                        <button type="button" data-qty-down="${item.id}" data-variant="${item.variant_id ?? ''}">−</button>
                        <span>${item.qty}</span>
                        <button type="button" data-qty-up="${item.id}" data-variant="${item.variant_id ?? ''}">+</button>
                    </div>
                </div>
                <div class="text-end">
                    <p class="fw-bold mb-2">${SF.money(item.price * item.qty)}</p>
                    <button type="button" class="btn btn-sm text-danger" data-remove="${item.id}" data-variant="${item.variant_id ?? ''}"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
        `).join('') : `<div class="sf-empty"><i class="fa-solid fa-bag-shopping mb-2 d-block fs-3"></i>Your cart is empty. <a href="{{ route('home') }}#featured">Start shopping</a>.</div>`;

        list.querySelectorAll('[data-qty-up]').forEach(b => b.addEventListener('click', () => {
            const variantId = b.dataset.variant ? parseInt(b.dataset.variant) : null;
            const item = SF.readCart().find(i => i.id == b.dataset.qtyUp && (i.variant_id ?? null) === variantId);
            if (!item) return;
            if (item.max_stock != null && item.qty >= item.max_stock) {
                SF.toast(`Only ${item.max_stock} of "${item.name}" available.`, 'warning');
                return;
            }
            SF.setQty(parseInt(b.dataset.qtyUp), item.qty + 1, variantId);
            renderItems();
            renderSummary();
        }));
        list.querySelectorAll('[data-qty-down]').forEach(b => b.addEventListener('click', () => {
            const variantId = b.dataset.variant ? parseInt(b.dataset.variant) : null;
            const item = SF.readCart().find(i => i.id == b.dataset.qtyDown && (i.variant_id ?? null) === variantId);
            SF.setQty(parseInt(b.dataset.qtyDown), (item?.qty ?? 1) - 1, variantId);
            renderItems();
            renderSummary();
        }));
        list.querySelectorAll('[data-remove]').forEach(b => b.addEventListener('click', () => {
            SF.removeFromCart(parseInt(b.dataset.remove), b.dataset.variant ? parseInt(b.dataset.variant) : null);
            renderItems();
            renderSummary();
        }));
    }

    function renderSummary() {
        const subtotal = SF.cartSubtotal();
        const coupon = readCoupon();
        const discount = coupon ? Math.min(coupon.discount, subtotal) : 0;

        document.getElementById('sumSubtotal').textContent = SF.money(subtotal);
        document.getElementById('sumTotal').textContent = SF.money(Math.max(subtotal - discount, 0));

        document.getElementById('sumDiscountRow').hidden = !coupon;
        if (coupon) {
            document.getElementById('sumDiscount').textContent = '-' + SF.money(discount);
            document.getElementById('sumCouponLabel').textContent = `(${coupon.code})`;
        }

        document.getElementById('couponBox').classList.toggle('d-none', !!coupon && false);
        document.getElementById('couponInput').closest('.input-group').style.display = coupon ? 'none' : '';
        document.getElementById('couponApplied').classList.toggle('d-none', !coupon);
        document.getElementById('couponApplied').classList.toggle('d-flex', !!coupon);
        if (coupon) document.getElementById('couponAppliedCode').textContent = `${coupon.code} applied`;
    }

    document.getElementById('applyCouponBtn').addEventListener('click', async () => {
        const input = document.getElementById('couponInput');
        const msg = document.getElementById('couponMsg');
        const code = input.value.trim();

        if (!code) return;

        if (!SF.isLoggedIn()) {
            msg.innerHTML = `<span class="text-danger">Please <a href="${SF.loginUrl()}">log in</a> to apply a coupon.</span>`;
            return;
        }

        const btn = document.getElementById('applyCouponBtn');
        btn.disabled = true;
        msg.textContent = '';

        try {
            const res = await fetch('/api/coupons/validate', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', ...SF.authHeaders() },
                body: JSON.stringify({ code, subtotal: SF.cartSubtotal() }),
            });
            const data = await res.json();

            if (!data.status) {
                msg.innerHTML = `<span class="text-danger">${SF.escapeHtml(data.message)}</span>`;
                return;
            }

            writeCoupon(data.data);
            input.value = '';
            msg.innerHTML = `<span class="text-success">${SF.escapeHtml(data.message)}</span>`;
            renderSummary();
        } catch (err) {
            msg.innerHTML = `<span class="text-danger">Something went wrong. Please try again.</span>`;
        } finally {
            btn.disabled = false;
        }
    });

    document.getElementById('removeCouponBtn').addEventListener('click', () => {
        writeCoupon(null);
        document.getElementById('couponMsg').textContent = '';
        renderSummary();
    });

    document.getElementById('cartCheckoutBtn').addEventListener('click', () => {
        if (!SF.cartCount()) { SF.toast('Your cart is empty.', 'warning'); return; }
        window.location.href = SF.isLoggedIn() ? '/checkout' : SF.loginUrl();
    });

    // Coupon becomes irrelevant if cart empties out.
    if (!SF.cartCount()) writeCoupon(null);

    renderItems();
    renderSummary();
})();
</script>
@endpush
