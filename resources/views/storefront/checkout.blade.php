@extends('layouts.storefront')

@section('title', 'Checkout')

@section('content')

<section class="sf-section pt-4 pb-5" style="min-height:60vh;">
    <div class="container">
        <h1 class="fw-bold mb-4" style="font-size:1.8rem;">Checkout</h1>

        <div id="checkoutGuard" class="sf-empty d-none">
            <i class="fa-solid fa-lock mb-2 d-block fs-3"></i>
            Please <a id="checkoutLoginLink" href="/login">log in</a> to continue to checkout.
        </div>

        <div id="checkoutContent" class="row g-4">
            <div class="col-lg-7">

                <div class="sf-testimonial mb-4" style="padding:1.5rem;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="fa-solid fa-location-dot me-2"></i>Delivery Address</h5>
                        <button type="button" class="sf-btn sf-btn-outline sf-btn-sm" id="toggleAddressForm"><i class="fa-solid fa-plus"></i> Add New</button>
                    </div>
                    <div id="addressList" class="d-flex flex-column gap-2"></div>

                    <form id="addressForm" class="d-none mt-3 pt-3 border-top">
                        <div class="row g-2">
                            <div class="col-md-6"><input class="form-control form-control-sm" name="full_name" placeholder="Full name" required></div>
                            <div class="col-md-6"><input class="form-control form-control-sm" name="phone" placeholder="Phone" required></div>
                            <div class="col-12"><input class="form-control form-control-sm" name="address_line_1" placeholder="Address line 1" required></div>
                            <div class="col-12"><input class="form-control form-control-sm" name="address_line_2" placeholder="Address line 2 (optional)"></div>
                            <div class="col-md-4"><input class="form-control form-control-sm" name="city" placeholder="City" required></div>
                            <div class="col-md-4"><input class="form-control form-control-sm" name="state" placeholder="State" required></div>
                            <div class="col-md-4"><input class="form-control form-control-sm" name="postal_code" placeholder="Postal code" required></div>
                            <div class="col-md-6">
                                <select class="form-select form-select-sm" name="address_type">
                                    <option value="home">Home</option>
                                    <option value="office">Office</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6"><input class="form-control form-control-sm" name="country" placeholder="Country" value="India"></div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="sf-btn sf-btn-primary sf-btn-sm" id="saveAddressBtn">Save Address</button>
                            <button type="button" class="sf-btn sf-btn-outline sf-btn-sm" id="cancelAddressForm">Cancel</button>
                        </div>
                    </form>
                </div>

                <div class="sf-testimonial mb-4" style="padding:1.5rem;">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-credit-card me-2"></i>Payment Method</h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" id="payCod" value="cod" checked>
                        <label class="form-check-label" for="payCod"><i class="fa-solid fa-money-bill-wave me-1"></i> Cash on Delivery</label>
                    </div>
                    <div class="form-check text-muted">
                        <input class="form-check-input" type="radio" disabled id="payOnline">
                        <label class="form-check-label" for="payOnline">
                            <i class="fa-solid fa-credit-card me-1"></i> Online Payment
                            <span class="badge bg-secondary ms-1" style="font-size:.65rem;">Coming Soon</span>
                        </label>
                    </div>
                </div>

                <div class="sf-testimonial" style="padding:1.5rem;">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-note-sticky me-2"></i>Order Notes (optional)</h5>
                    <textarea class="form-control" id="orderNotes" rows="2" placeholder="Delivery instructions, preferred time, etc."></textarea>
                </div>

            </div>

            <div class="col-lg-5">
                <div class="sf-testimonial" style="padding:1.5rem;">
                    <h5 class="fw-bold mb-3">Order Summary</h5>
                    <div id="checkoutItems" class="d-flex flex-column gap-2 mb-3" style="max-height:16rem; overflow-y:auto;"></div>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Subtotal</span>
                        <span id="coSubtotal">₹0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small text-success" id="coDiscountRow" hidden>
                        <span>Discount</span>
                        <span id="coDiscount">-₹0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pt-2 border-top fw-bold">
                        <span>Total</span>
                        <span id="coTotal">₹0.00</span>
                    </div>

                    <button type="button" class="sf-btn sf-btn-primary w-100" id="placeOrderBtn">
                        <span id="placeOrderBtnText">Place Order</span>
                    </button>
                    <p class="small text-muted mt-2 mb-0 text-center">By placing your order you agree to pay on delivery.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {
    let selectedAddressId = null;

    function readCoupon() { try { return JSON.parse(localStorage.getItem('sf_coupon')); } catch { return null; } }

    // ─── Auth guard ─────────────────────────────────────────────────────────
    if (!SF.isLoggedIn()) {
        document.getElementById('checkoutContent').classList.add('d-none');
        document.getElementById('checkoutGuard').classList.remove('d-none');
        document.getElementById('checkoutLoginLink').href = SF.loginUrl();
        return;
    }

    if (!SF.cartCount()) {
        document.getElementById('checkoutContent').classList.add('d-none');
        document.getElementById('checkoutGuard').classList.remove('d-none');
        document.getElementById('checkoutGuard').innerHTML = `<i class="fa-solid fa-bag-shopping mb-2 d-block fs-3"></i>Your cart is empty. <a href="{{ route('home') }}#featured">Start shopping</a>.`;
        return;
    }

    // ─── Order summary ────────────────────────────────────────────────────────
    function renderSummary() {
        const items = SF.readCart();
        const subtotal = SF.cartSubtotal();
        const coupon = readCoupon();
        const discount = coupon ? Math.min(coupon.discount, subtotal) : 0;

        document.getElementById('checkoutItems').innerHTML = items.map(item => `
            <div class="d-flex justify-content-between small">
                <span>${SF.escapeHtml(item.name)} <span class="text-muted">× ${item.qty}</span></span>
                <span>${SF.money(item.price * item.qty)}</span>
            </div>
        `).join('');

        document.getElementById('coSubtotal').textContent = SF.money(subtotal);
        document.getElementById('coTotal').textContent = SF.money(Math.max(subtotal - discount, 0));
        document.getElementById('coDiscountRow').hidden = !coupon;
        if (coupon) document.getElementById('coDiscount').textContent = '-' + SF.money(discount) + ` (${coupon.code})`;
    }

    // ─── Addresses ────────────────────────────────────────────────────────────
    async function loadAddresses() {
        const list = document.getElementById('addressList');
        list.innerHTML = `<div class="text-muted small"><i class="fa-solid fa-spinner fa-spin"></i> Loading addresses…</div>`;

        try {
            const res = await fetch('/api/customer/addresses', { headers: SF.authHeaders() });
            const payload = await res.json();
            const addresses = payload.data ?? [];

            if (!addresses.length) {
                list.innerHTML = `<div class="small text-muted">No saved addresses yet — add one below.</div>`;
                return;
            }

            list.innerHTML = addresses.map(addr => `
                <label class="d-flex gap-2 p-2 border rounded" style="cursor:pointer;">
                    <input type="radio" name="address_choice" value="${addr.id}" ${addr.is_default ? 'checked' : ''}>
                    <span class="small">
                        <strong>${SF.escapeHtml(addr.full_name)}</strong> ${addr.is_default ? '<span class="badge bg-success-subtle text-success" style="font-size:.6rem;">Default</span>' : ''}<br>
                        ${SF.escapeHtml(addr.address_line_1)}${addr.address_line_2 ? ', ' + SF.escapeHtml(addr.address_line_2) : ''}<br>
                        ${SF.escapeHtml(addr.city)}, ${SF.escapeHtml(addr.state)} ${SF.escapeHtml(addr.postal_code)}<br>
                        Phone: ${SF.escapeHtml(addr.phone)}
                    </span>
                </label>
            `).join('');

            const checked = list.querySelector('input[name="address_choice"]:checked') || list.querySelector('input[name="address_choice"]');
            if (checked) { checked.checked = true; selectedAddressId = parseInt(checked.value); }

            list.querySelectorAll('input[name="address_choice"]').forEach(r => r.addEventListener('change', () => {
                selectedAddressId = parseInt(r.value);
            }));
        } catch (err) {
            list.innerHTML = `<div class="small text-danger">Could not load addresses.</div>`;
        }
    }

    document.getElementById('toggleAddressForm').addEventListener('click', () => {
        document.getElementById('addressForm').classList.remove('d-none');
    });
    document.getElementById('cancelAddressForm').addEventListener('click', () => {
        document.getElementById('addressForm').classList.add('d-none');
    });

    document.getElementById('addressForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('saveAddressBtn');
        if (btn.disabled) return;
        btn.disabled = true;
        btn.textContent = 'Saving…';

        const formData = new FormData(e.target);
        const body = Object.fromEntries(formData.entries());

        try {
            const res = await fetch('/api/customer/address', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', ...SF.authHeaders() },
                body: JSON.stringify(body),
            });
            const data = await res.json();

            if (!data.success) {
                SF.toast(data.message || 'Could not save address.', 'warning');
                return;
            }

            SF.toast('Address saved.');
            e.target.reset();
            e.target.classList.add('d-none');
            await loadAddresses();
        } catch (err) {
            SF.toast('Something went wrong. Please try again.', 'warning');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Save Address';
        }
    });

    // ─── Place order ──────────────────────────────────────────────────────────
    document.getElementById('placeOrderBtn').addEventListener('click', async () => {
        const btn = document.getElementById('placeOrderBtn');
        const btnText = document.getElementById('placeOrderBtnText');

        if (btn.disabled) return;

        if (!selectedAddressId) {
            SF.toast('Please select or add a delivery address.', 'warning');
            return;
        }

        btn.disabled = true;
        btnText.textContent = 'Placing Order…';

        const coupon = readCoupon();
        const items = SF.readCart().map(i => ({ product_id: i.id, variant_id: i.variant_id ?? null, quantity: i.qty }));

        try {
            const res = await fetch('/api/orders', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', ...SF.authHeaders() },
                body: JSON.stringify({
                    address_id: selectedAddressId,
                    notes: document.getElementById('orderNotes').value || null,
                    coupon_code: coupon?.code ?? null,
                    payment_method: 'cod',
                    items,
                }),
            });
            const data = await res.json();

            if (!data.success) {
                // Stock/validation failures come back as a 422 with `errors.items`
                // (e.g. "Only 2 left of ..."); surface that over the generic message.
                SF.toast(data.errors?.items?.[0] || data.message || 'Could not place order.', 'warning');
                return;
            }

            SF.clearCart();
            localStorage.removeItem('sf_coupon');
            window.location.href = '/order-success/' + data.data.id;
        } catch (err) {
            SF.toast('Something went wrong. Please try again.', 'warning');
        } finally {
            btn.disabled = false;
            btnText.textContent = 'Place Order';
        }
    });

    renderSummary();
    loadAddresses();
})();
</script>
@endpush
