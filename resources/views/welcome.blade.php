@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Your store at a glance')

@section('content')

<div class="dash-stats-grid" id="dashStatsGrid">
    <div class="sf-empty" style="grid-column:1/-1;"><i class="fa-solid fa-spinner fa-spin"></i> Loading dashboard…</div>
</div>

<div class="admin-card" id="permissionNotice" style="display:none; margin-bottom:1.5rem; border-color:#dc2626;">
    <p style="margin:0; font-size:0.9rem;">
        <i class="fa-solid fa-triangle-exclamation" style="color:#dc2626; margin-right:0.4rem;"></i>
        Could not load dashboard data.
    </p>
</div>

<div class="row-dash-grid">

    <div class="admin-card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div>
                <p class="card-title">Recent Orders</p>
                <p class="card-subtitle">The latest orders placed on your store</p>
            </div>
            <a href="{{ route('admin.orders') }}" class="btn btn-ghost">View All <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="table-scroll">
            <table class="admin-table" id="recentOrdersTable">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="recentOrdersBody">
                    <tr><td colspan="5" class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading…</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div>
                <p class="card-title">Quick Actions</p>
                <p class="card-subtitle">Jump straight to a task</p>
            </div>
        </div>
        <div class="dash-quick-actions">
            <a href="{{ route('admin.form') }}" class="dash-quick-action">
                <i class="fa-solid fa-plus"></i> Add Product
            </a>
            <a href="{{ route('admin.flash-sales') }}" class="dash-quick-action">
                <i class="fa-solid fa-bolt"></i> Create Flash Sale
            </a>
            <a href="{{ route('admin.offers') }}" class="dash-quick-action">
                <i class="fa-solid fa-tags"></i> Add Offer
            </a>
            <a href="{{ route('admin.orders') }}" class="dash-quick-action">
                <i class="fa-solid fa-cart-shopping"></i> Manage Orders
            </a>
            <a href="{{ route('admin.customers') }}" class="dash-quick-action">
                <i class="fa-solid fa-users"></i> View Customers
            </a>
            <a href="{{ route('admin.settings') }}" class="dash-quick-action">
                <i class="fa-solid fa-gear"></i> Store Settings
            </a>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
    .dash-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(13rem, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }

    .dash-stat-card {
        display: flex; align-items: center; gap: 1rem; padding: 1.25rem 1.4rem; border-radius: 1rem;
        background: color-mix(in srgb, var(--color-cream-50) 92%, transparent);
        border: 1px solid color-mix(in srgb, var(--color-forest-900) 8%, transparent);
        box-shadow: var(--shadow-panel); text-decoration: none; color: inherit; position: relative; overflow: hidden;
        transition: transform .25s cubic-bezier(.16,1,.3,1), box-shadow .25s ease, border-color .25s ease;
        opacity: 0; transform: translateY(14px);
        animation: dashCardIn .5s cubic-bezier(.16,1,.3,1) forwards;
        animation-delay: var(--delay, 0ms);
    }
    body[data-theme="dark"] .dash-stat-card {
        background: color-mix(in srgb, var(--color-forest-900) 55%, transparent);
        border-color: color-mix(in srgb, var(--color-moringa-200) 10%, transparent);
    }
    @keyframes dashCardIn { to { opacity: 1; transform: translateY(0); } }

    .dash-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -20px color-mix(in srgb, var(--color-forest-900) 35%, transparent);
        border-color: var(--color-moringa-400);
    }
    .dash-stat-card::after {
        content: ''; position: absolute; inset: 0; opacity: 0; pointer-events: none;
        background: radial-gradient(circle at 85% 15%, color-mix(in srgb, var(--accent, var(--color-moringa-500)) 18%, transparent), transparent 60%);
        transition: opacity .25s ease;
    }
    .dash-stat-card:hover::after { opacity: 1; }

    .dash-stat-icon {
        flex-shrink: 0; width: 3.1rem; height: 3.1rem; border-radius: .85rem; display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; color: #fff; background: var(--accent, var(--color-moringa-500));
        transition: transform .25s ease; position: relative; z-index: 1;
    }
    .dash-stat-card:hover .dash-stat-icon { transform: scale(1.08) rotate(-4deg); }

    .dash-stat-body { position: relative; z-index: 1; min-width: 0; }
    .dash-stat-value { font-family: var(--font-display); font-size: 1.6rem; font-weight: 700; line-height: 1; margin: 0 0 .3rem; color: var(--color-forest-950); }
    body[data-theme="dark"] .dash-stat-value { color: var(--color-cream-50); }
    .dash-stat-label { font-size: .78rem; color: color-mix(in srgb, var(--color-forest-700) 70%, transparent); margin: 0; white-space: nowrap; }
    body[data-theme="dark"] .dash-stat-label { color: color-mix(in srgb, var(--color-moringa-200) 65%, transparent); }

    .dash-stat-arrow {
        margin-left: auto; opacity: 0; transform: translateX(-6px); transition: all .25s ease;
        color: var(--accent, var(--color-moringa-500)); font-size: .85rem; position: relative; z-index: 1; flex-shrink: 0;
    }
    .dash-stat-card:hover .dash-stat-arrow { opacity: 1; transform: translateX(0); }

    .row-dash-grid { display: grid; grid-template-columns: minmax(0, 2fr) minmax(0, 1fr); gap: 1.5rem; align-items: start; }
    @media (max-width: 991.98px) { .row-dash-grid { grid-template-columns: 1fr; } }

    .dash-quick-actions { display: flex; flex-direction: column; gap: .6rem; }
    .dash-quick-action {
        display: flex; align-items: center; gap: .7rem; padding: .7rem .9rem; border-radius: .7rem;
        background: color-mix(in srgb, var(--color-moringa-100) 45%, transparent);
        color: var(--color-forest-900); font-size: .85rem; font-weight: 600; text-decoration: none;
        transition: all .2s ease;
    }
    body[data-theme="dark"] .dash-quick-action { background: color-mix(in srgb, var(--color-forest-800) 55%, transparent); color: var(--color-cream-50); }
    .dash-quick-action i { color: var(--color-moringa-600); width: 1.1rem; text-align: center; }
    body[data-theme="dark"] .dash-quick-action i { color: var(--color-moringa-300); }
    .dash-quick-action:hover { background: var(--color-moringa-500); color: #fff; transform: translateX(3px); }
    .dash-quick-action:hover i { color: #fff; }

    .order-number { font-weight: 700; color: var(--color-forest-950); }
    body[data-theme="dark"] .order-number { color: var(--color-cream-50); }
    .order-customer-name { font-weight: 600; color: var(--color-forest-950); }
    body[data-theme="dark"] .order-customer-name { color: var(--color-cream-50); }
    .order-customer-email { font-size: 0.72rem; color: color-mix(in srgb, var(--color-forest-700) 65%, transparent); }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const STATUS_LABELS = {
        pending: 'Pending', confirmed: 'Confirmed', processing: 'Processing', packed: 'Packed',
        shipped: 'Shipped', out_for_delivery: 'Out for Delivery', delivered: 'Delivered',
        cancelled: 'Cancelled', returned: 'Returned', refunded: 'Refunded',
    };
    const STATUS_BADGE = {
        pending: 'badge-neutral', confirmed: 'badge-warning', processing: 'badge-warning',
        packed: 'badge-warning', shipped: 'badge-warning', out_for_delivery: 'badge-warning',
        delivered: 'badge-success', cancelled: 'badge-danger', returned: 'badge-danger', refunded: 'badge-danger',
    };

    function authHeaders() {
        const token = localStorage.getItem('token');
        return token ? { 'Authorization': 'Bearer ' + token } : {};
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    function formatMoney(value) {
        return '₹' + parseFloat(value ?? 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function formatDate(value) {
        if (!value) return '—';
        return new Date(value).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
    }

    function statusBadge(status) {
        const cls = STATUS_BADGE[status] ?? 'badge-neutral';
        const label = STATUS_LABELS[status] ?? status;
        return `<span class="badge ${cls}">${label}</span>`;
    }

    function animateCount(el) {
        const target = parseFloat(el.dataset.count ?? '0');
        const isMoney = el.dataset.money === '1';
        const duration = 900;
        const start = performance.now();

        function tick(now) {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const value = target * eased;
            el.textContent = isMoney ? formatMoney(value) : Math.round(value).toLocaleString();
            if (progress < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    function statCard({ href, icon, accent, value, label, money, delay, alert }) {
        return `
            <a href="${href}" class="dash-stat-card ${alert ? 'is-alert' : ''}" style="--accent:${accent}; --delay:${delay}ms;">
                <div class="dash-stat-icon"><i class="fa-solid ${icon}"></i></div>
                <div class="dash-stat-body">
                    <p class="dash-stat-value" data-count="${value}" ${money ? 'data-money="1"' : ''}>0</p>
                    <p class="dash-stat-label">${label}</p>
                </div>
                <i class="fa-solid fa-arrow-right dash-stat-arrow"></i>
            </a>
        `;
    }

    async function loadDashboard() {
        try {
            const response = await fetch('/api/admin/dashboard/stats', { headers: authHeaders() });
            const payload = await response.json();

            if (!payload.status) throw new Error('failed');

            const d = payload.data;
            const grid = document.getElementById('dashStatsGrid');

            const cards = [
                statCard({ href: '{{ route('admin.orders') }}', icon: 'fa-indian-rupee-sign', accent: 'var(--color-moringa-600)', value: d.revenue, label: 'Total Revenue', money: true, delay: 0 }),
                statCard({ href: '{{ route('admin.orders') }}', icon: 'fa-cart-shopping', accent: 'var(--color-moringa-500)', value: d.orders_count, label: 'Total Orders', delay: 60 }),
                statCard({ href: '{{ route('admin.list') }}', icon: 'fa-box-open', accent: 'var(--color-forest-600)', value: d.products_count, label: 'Products', delay: 120 }),
                statCard({ href: '{{ route('admin.customers') }}', icon: 'fa-users', accent: 'var(--color-moringa-400)', value: d.customers_count, label: 'Customers', delay: 180 }),
                statCard({ href: '{{ route('admin.orders') }}?status=pending', icon: 'fa-clock', accent: 'var(--color-gold-500)', value: d.pending_orders_count, label: 'Pending Orders', delay: 240, alert: d.pending_orders_count > 0 }),
                statCard({ href: '{{ route('admin.list') }}', icon: 'fa-triangle-exclamation', accent: '#dc2626', value: d.low_stock_count, label: 'Low / Out of Stock', delay: 300, alert: d.low_stock_count > 0 }),
                statCard({ href: '{{ route('admin.offers') }}', icon: 'fa-tags', accent: 'var(--color-moringa-600)', value: d.active_offers_count, label: 'Active Offers', delay: 360 }),
                statCard({ href: '{{ route('admin.flash-sales') }}', icon: 'fa-bolt', accent: '#dc2626', value: d.active_flash_sales_count, label: 'Active Flash Sales', delay: 420 }),
            ];

            grid.innerHTML = cards.join('');
            grid.querySelectorAll('[data-count]').forEach(el => animateCount(el));

            const body = document.getElementById('recentOrdersBody');
            const orders = d.recent_orders ?? [];

            body.innerHTML = orders.length ? orders.map(order => `
                <tr>
                    <td><span class="order-number">${escapeHtml(order.order_number ?? ('#' + order.id))}</span></td>
                    <td>
                        <div class="order-customer-name">${escapeHtml(order.user?.name ?? 'Guest')}</div>
                        <div class="order-customer-email">${escapeHtml(order.user?.email ?? '')}</div>
                    </td>
                    <td>${formatDate(order.created_at)}</td>
                    <td>${formatMoney(order.total)}</td>
                    <td>${statusBadge(order.status)}</td>
                </tr>
            `).join('') : `<tr><td colspan="5" class="table-empty"><i class="fa-solid fa-inbox"></i> No orders yet.</td></tr>`;
        } catch (err) {
            document.getElementById('dashStatsGrid').innerHTML = '';
            document.getElementById('permissionNotice').style.display = 'block';
            document.getElementById('recentOrdersBody').innerHTML = `<tr><td colspan="5" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load orders.</td></tr>`;
        }
    }

    loadDashboard();
})();
</script>
@endpush
