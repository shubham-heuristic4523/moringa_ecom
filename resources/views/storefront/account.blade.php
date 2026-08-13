@extends('layouts.storefront')

@section('title', 'My Account')

@section('content')

<section class="sf-section pt-4 pb-5" style="min-height:60vh;">
    <div class="container">

        <div id="accountGuard" class="sf-empty d-none">
            <i class="fa-solid fa-lock mb-2 d-block fs-3"></i>
            Please <a id="accountLoginLink" href="/login">log in</a> to view your account.
        </div>

        <div id="accountContent" class="d-none">
            <h1 class="fw-bold mb-4" style="font-size:1.8rem;">My Account</h1>

            <ul class="nav nav-pills mb-4 gap-2" id="accountTabs">
                <li class="nav-item"><button class="nav-link active" data-tab="profile" type="button">Profile &amp; Referral</button></li>
                <li class="nav-item"><button class="nav-link" data-tab="addresses" type="button">Addresses</button></li>
                <li class="nav-item"><button class="nav-link" data-tab="orders" type="button">My Orders</button></li>
            </ul>

            {{-- ── Profile & Referral ─────────────────────────────────────── --}}
            <div class="account-pane" id="pane-profile">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="sf-testimonial" style="padding:1.5rem;">
                            <h5 class="fw-bold mb-3"><i class="fa-regular fa-user me-2"></i>Your Details</h5>
                            <p class="mb-1"><strong id="profName">—</strong></p>
                            <p class="text-muted mb-0" id="profEmail">—</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="sf-testimonial" style="padding:1.5rem;">
                            <h5 class="fw-bold mb-2"><i class="fa-solid fa-gift me-2"></i>Refer &amp; Earn</h5>
                            <p class="small text-muted mb-3">Share your link — when someone signs up and completes their first order, you get a reward coupon automatically.</p>
                            <label class="form-label small fw-semibold">Your referral code</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="referralCode" readonly>
                                <button type="button" class="btn sf-btn sf-btn-outline" id="copyCodeBtn"><i class="fa-solid fa-copy"></i></button>
                            </div>
                            <label class="form-label small fw-semibold">Shareable link</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="referralLink" readonly>
                                <button type="button" class="btn sf-btn sf-btn-outline" id="copyLinkBtn"><i class="fa-solid fa-copy"></i></button>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="#" id="shareWhatsapp" target="_blank" class="sf-btn sf-btn-sm sf-btn-primary"><i class="fa-brands fa-whatsapp"></i> Share</a>
                                <a href="#" id="shareEmail" class="sf-btn sf-btn-sm sf-btn-outline"><i class="fa-solid fa-envelope"></i> Email</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sf-testimonial mt-4" style="padding:1.5rem;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <h5 class="fw-bold mb-0"><i class="fa-solid fa-trophy me-2"></i>My Rewards</h5>
                        <span class="small text-muted" id="rewardsSummary"></span>
                    </div>
                    <div id="rewardsList"></div>
                </div>
            </div>

            {{-- ── Addresses ──────────────────────────────────────────────── --}}
            <div class="account-pane d-none" id="pane-addresses">
                <div class="sf-testimonial" style="padding:1.5rem;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="fa-solid fa-location-dot me-2"></i>Saved Addresses</h5>
                        <button type="button" class="sf-btn sf-btn-outline sf-btn-sm" id="toggleAddressForm"><i class="fa-solid fa-plus"></i> Add New</button>
                    </div>
                    <div id="addressList" class="row g-3"></div>

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
            </div>

            {{-- ── Orders ──────────────────────────────────────────────────── --}}
            <div class="account-pane d-none" id="pane-orders">
                <div id="ordersList" class="d-flex flex-column gap-3"></div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {
    if (!SF.isLoggedIn()) {
        document.getElementById('accountGuard').classList.remove('d-none');
        document.getElementById('accountLoginLink').href = SF.loginUrl();
        return;
    }
    document.getElementById('accountContent').classList.remove('d-none');

    // ─── Tabs ───────────────────────────────────────────────────────────────
    document.querySelectorAll('#accountTabs [data-tab]').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#accountTabs [data-tab]').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.account-pane').forEach(p => p.classList.add('d-none'));
            document.getElementById('pane-' + btn.dataset.tab).classList.remove('d-none');
        });
    });
    if (window.location.hash === '#orders') {
        document.querySelector('[data-tab="orders"]')?.click();
    }

    // ─── Profile + referral ───────────────────────────────────────────────────
    async function loadProfile() {
        try {
            const res = await fetch('/api/profile', { headers: SF.authHeaders() });
            const payload = await res.json();
            const user = payload.user;
            if (!user) return;

            document.getElementById('profName').textContent = user.name;
            document.getElementById('profEmail').textContent = user.email;
            document.getElementById('referralCode').value = user.referral_code ?? '';

            const link = window.location.origin + '/register?ref=' + (user.referral_code ?? '');
            document.getElementById('referralLink').value = link;
            document.getElementById('shareWhatsapp').href = 'https://wa.me/?text=' + encodeURIComponent(`Join Moringa and get a discount on your first order! ${link}`);
            document.getElementById('shareEmail').href = 'mailto:?subject=' + encodeURIComponent('Join me on Moringa') + '&body=' + encodeURIComponent(`Use my referral link to sign up: ${link}`);
        } catch (err) {
            SF.toast('Could not load your profile.', 'warning');
        }
    }

    document.getElementById('copyCodeBtn').addEventListener('click', () => copyField('referralCode'));
    document.getElementById('copyLinkBtn').addEventListener('click', () => copyField('referralLink'));
    function copyField(id) {
        const el = document.getElementById(id);
        el.select();
        navigator.clipboard?.writeText(el.value).then(() => SF.toast('Copied to clipboard!'));
    }

    const REWARD_STATUS_BADGE = { active: 'success', scheduled: 'warning', expired: 'danger', inactive: 'secondary' };

    async function loadRewards() {
        const list = document.getElementById('rewardsList');
        list.innerHTML = `<div class="text-muted small"><i class="fa-solid fa-spinner fa-spin"></i> Loading…</div>`;

        try {
            const res = await fetch('/api/my-referrals', { headers: SF.authHeaders() });
            const payload = await res.json();
            const referrals = payload.data ?? [];

            document.getElementById('rewardsSummary').textContent =
                `${payload.summary?.completed ?? 0} rewarded · ${payload.summary?.pending ?? 0} pending`;

            list.innerHTML = referrals.length ? referrals.map(ref => {
                const name = ref.referred?.name ?? 'Someone';

                if (ref.status !== 'completed' || !ref.reward_offer) {
                    return `
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom small">
                            <span><i class="fa-regular fa-clock text-muted me-2"></i>${SF.escapeHtml(name)} signed up — waiting for their first order</span>
                            <span class="badge bg-secondary-subtle text-secondary">Pending</span>
                        </div>
                    `;
                }

                const offer = ref.reward_offer;
                const value = offer.discount_type === 'percentage'
                    ? `${parseFloat(offer.discount_value)}%${offer.max_discount_amount ? ' (max ' + SF.money(offer.max_discount_amount) + ')' : ''}`
                    : SF.money(offer.discount_value);
                const badgeClass = REWARD_STATUS_BADGE[offer.effective_status] ?? 'secondary';

                return `
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom flex-wrap gap-2">
                        <div>
                            <p class="fw-semibold mb-0 small">${SF.escapeHtml(name)} completed their first order</p>
                            <p class="text-muted mb-0" style="font-size:.78rem;">${value} off · valid until ${new Date(offer.ends_at).toLocaleDateString()}</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-${badgeClass}-subtle text-${badgeClass} text-capitalize">${offer.effective_status}</span>
                            <button type="button" class="sf-btn sf-btn-sm sf-btn-outline" data-copy-coupon="${SF.escapeHtml(offer.code)}">
                                <i class="fa-solid fa-copy"></i> ${SF.escapeHtml(offer.code)}
                            </button>
                        </div>
                    </div>
                `;
            }).join('') : `<div class="sf-empty">No referrals yet — share your link above to start earning.</div>`;

            list.querySelectorAll('[data-copy-coupon]').forEach(btn => btn.addEventListener('click', () => {
                navigator.clipboard?.writeText(btn.dataset.copyCoupon).then(() => SF.toast('Coupon code copied!'));
            }));
        } catch (err) {
            list.innerHTML = `<div class="sf-empty text-danger">Could not load your rewards.</div>`;
        }
    }

    // ─── Addresses ────────────────────────────────────────────────────────────
    async function loadAddresses() {
        const list = document.getElementById('addressList');
        list.innerHTML = `<div class="text-muted small"><i class="fa-solid fa-spinner fa-spin"></i> Loading…</div>`;

        try {
            const res = await fetch('/api/customer/addresses', { headers: SF.authHeaders() });
            const payload = await res.json();
            const addresses = payload.data ?? [];

            list.innerHTML = addresses.length ? addresses.map(addr => `
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100 position-relative">
                        ${addr.is_default ? '<span class="badge bg-success-subtle text-success position-absolute top-0 end-0 m-2" style="font-size:.65rem;">Default</span>' : ''}
                        <p class="fw-semibold mb-1">${SF.escapeHtml(addr.full_name)}</p>
                        <p class="small text-muted mb-1">${SF.escapeHtml(addr.address_line_1)}${addr.address_line_2 ? ', ' + SF.escapeHtml(addr.address_line_2) : ''}</p>
                        <p class="small text-muted mb-1">${SF.escapeHtml(addr.city)}, ${SF.escapeHtml(addr.state)} ${SF.escapeHtml(addr.postal_code)}</p>
                        <p class="small text-muted mb-2">Phone: ${SF.escapeHtml(addr.phone)}</p>
                        <button type="button" class="btn btn-sm text-danger p-0" data-delete-address="${addr.id}"><i class="fa-solid fa-trash me-1"></i>Remove</button>
                    </div>
                </div>
            `).join('') : `<div class="sf-empty">No saved addresses yet.</div>`;

            list.querySelectorAll('[data-delete-address]').forEach(btn => btn.addEventListener('click', async () => {
                if (!confirm('Remove this address?')) return;
                await fetch('/api/customer/address/' + btn.dataset.deleteAddress, { method: 'DELETE', headers: SF.authHeaders() });
                loadAddresses();
            }));
        } catch (err) {
            list.innerHTML = `<div class="sf-empty text-danger">Could not load addresses.</div>`;
        }
    }

    document.getElementById('toggleAddressForm').addEventListener('click', () => {
        document.getElementById('addressForm').classList.toggle('d-none');
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

        const body = Object.fromEntries(new FormData(e.target).entries());

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
            loadAddresses();
        } catch (err) {
            SF.toast('Something went wrong.', 'warning');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Save Address';
        }
    });

    // ─── Orders ───────────────────────────────────────────────────────────────
    const STATUS_BADGE = {
        pending: 'secondary', confirmed: 'info', processing: 'info', packed: 'info',
        shipped: 'primary', out_for_delivery: 'primary', delivered: 'success',
        cancelled: 'danger', returned: 'danger', refunded: 'danger',
    };
    const CANCELLABLE = ['pending', 'confirmed'];

    async function loadOrders() {
        const list = document.getElementById('ordersList');
        list.innerHTML = `<div class="sf-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading orders…</div>`;

        try {
            const res = await fetch('/api/orders', { headers: SF.authHeaders() });
            const payload = await res.json();
            const orders = payload.data?.data ?? [];

            list.innerHTML = orders.length ? orders.map(order => `
                <div class="sf-testimonial" style="padding:1.25rem;">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                        <div>
                            <p class="fw-bold mb-0">${SF.escapeHtml(order.order_number)}</p>
                            <p class="small text-muted mb-0">${new Date(order.created_at).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' })}</p>
                        </div>
                        <span class="badge bg-${STATUS_BADGE[order.status] ?? 'secondary'}-subtle text-${STATUS_BADGE[order.status] ?? 'secondary'} text-capitalize">${order.status.replace(/_/g, ' ')}</span>
                    </div>
                    <div class="small text-muted mb-2">
                        ${(order.items ?? []).map(i => `${SF.escapeHtml(i.product_name)} × ${i.quantity}`).join(', ')}
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>${SF.money(order.total)}</strong>
                        ${CANCELLABLE.includes(order.status) ? `<button type="button" class="btn btn-sm text-danger" data-cancel-order="${order.id}">Cancel Order</button>` : ''}
                    </div>
                </div>
            `).join('') : `<div class="sf-empty"><i class="fa-solid fa-box-open mb-2 d-block fs-3"></i>No orders yet. <a href="{{ route('home') }}#featured">Start shopping</a>.</div>`;

            list.querySelectorAll('[data-cancel-order]').forEach(btn => btn.addEventListener('click', async () => {
                if (!confirm('Cancel this order?')) return;
                btn.disabled = true;

                try {
                    const res = await fetch('/api/orders/' + btn.dataset.cancelOrder + '/cancel', {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', ...SF.authHeaders() },
                        body: JSON.stringify({}),
                    });
                    const data = await res.json();

                    if (data.success) {
                        SF.toast('Order cancelled.');
                        loadOrders();
                    } else {
                        SF.toast(data.message || 'Could not cancel order.', 'warning');
                        btn.disabled = false;
                    }
                } catch (err) {
                    SF.toast('Something went wrong.', 'warning');
                    btn.disabled = false;
                }
            }));
        } catch (err) {
            list.innerHTML = `<div class="sf-empty text-danger">Could not load orders.</div>`;
        }
    }

    loadProfile();
    loadRewards();
    loadAddresses();
    loadOrders();
})();
</script>
@endpush
