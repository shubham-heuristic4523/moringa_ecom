@extends('layouts.app')

@section('title', 'Orders')
@section('page-title', 'Orders')
@section('page-subtitle', 'Track and manage customer orders')

@section('content')

<div class="admin-card">

    <div class="card-header">

        <div>
            <p class="card-title">Orders</p>
            <p class="card-subtitle">All customer orders</p>
        </div>

        <div class="table-toolbar">

            <div class="table-search">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                <input type="text" id="search" placeholder="Search by order #, customer name or email…">
            </div>

            <select id="statusFilter" class="form-select" style="width: auto;">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="processing">Processing</option>
                <option value="packed">Packed</option>
                <option value="shipped">Shipped</option>
                <option value="out_for_delivery">Out for Delivery</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
                <option value="returned">Returned</option>
                <option value="refunded">Refunded</option>
            </select>

        </div>

    </div>

    <div class="table-scroll">

        <table class="admin-table" id="orderTable">

            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th class="sa-only">Admin</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th width="110">Action</th>
                </tr>
            </thead>

            <tbody id="orderTableBody">
                <tr>
                    <td colspan="8" class="table-empty">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        Loading orders…
                    </td>
                </tr>
            </tbody>

        </table>

    </div>

    <div class="admin-pagination" id="pagination"></div>

</div>

<div class="order-drawer-overlay" id="drawerOverlay"></div>

<aside class="order-drawer" id="orderDrawer">
    <div class="order-drawer-header">
        <div>
            <p class="card-title" id="drawerOrderNumber">Order</p>
            <p class="card-subtitle" id="drawerOrderDate"></p>
        </div>
        <button type="button" class="btn-icon" id="closeDrawerBtn" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="order-drawer-body" id="drawerBody">
        <div class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading…</div>
    </div>
</aside>

@endsection

@push('styles')
<style>
    .admin-pagination { display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; margin-top: 1.25rem; }

    .order-number { font-weight: 700; color: var(--color-forest-950); }
    body[data-theme="dark"] .order-number { color: var(--color-cream-50); }
    .order-customer-name { font-weight: 600; color: var(--color-forest-950); }
    body[data-theme="dark"] .order-customer-name { color: var(--color-cream-50); }
    .order-customer-email { font-size: 0.72rem; color: color-mix(in srgb, var(--color-forest-700) 65%, transparent); }

    .order-drawer-overlay {
        position: fixed; inset: 0; background: rgba(15, 23, 20, 0.45);
        opacity: 0; pointer-events: none; transition: opacity 0.25s ease; z-index: 40;
    }
    .order-drawer-overlay.is-open { opacity: 1; pointer-events: auto; }

    .order-drawer {
        position: fixed; top: 0; right: 0; height: 100vh; width: min(28rem, 100vw);
        background: var(--color-cream-50);
        box-shadow: var(--shadow-panel);
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 41; display: flex; flex-direction: column; overflow-y: auto;
    }
    body[data-theme="dark"] .order-drawer { background: var(--color-forest-900); }
    .order-drawer.is-open { transform: translateX(0); }

    .order-drawer-header {
        display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem;
        padding: 1.25rem 1.5rem; border-bottom: 1px solid color-mix(in srgb, var(--color-forest-900) 8%, transparent);
    }
    body[data-theme="dark"] .order-drawer-header { border-bottom-color: color-mix(in srgb, var(--color-moringa-200) 10%, transparent); }
    .order-drawer-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.5rem; }

    .order-section-title {
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;
        color: color-mix(in srgb, var(--color-forest-700) 70%, transparent); margin-bottom: 0.6rem;
    }
    body[data-theme="dark"] .order-section-title { color: color-mix(in srgb, var(--color-moringa-200) 70%, transparent); }

    .order-item-row {
        display: flex; justify-content: space-between; gap: 0.75rem; padding: 0.5rem 0;
        border-top: 1px solid color-mix(in srgb, var(--color-forest-900) 6%, transparent); font-size: 0.83rem;
    }
    .order-item-row:first-child { border-top: none; }
    body[data-theme="dark"] .order-item-row { border-top-color: color-mix(in srgb, var(--color-moringa-200) 8%, transparent); }
    .order-item-name { font-weight: 600; color: var(--color-forest-950); }
    body[data-theme="dark"] .order-item-name { color: var(--color-cream-50); }
    .order-item-meta { font-size: 0.75rem; color: color-mix(in srgb, var(--color-forest-700) 65%, transparent); }

    .order-totals-row { display: flex; justify-content: space-between; font-size: 0.85rem; padding: 0.2rem 0; }
    .order-totals-row.is-grand {
        font-weight: 700; font-size: 0.95rem; padding-top: 0.6rem; margin-top: 0.4rem;
        border-top: 1px solid color-mix(in srgb, var(--color-forest-900) 10%, transparent);
    }
    body[data-theme="dark"] .order-totals-row.is-grand { border-top-color: color-mix(in srgb, var(--color-moringa-200) 10%, transparent); }
    .order-totals-row.is-discount { color: var(--color-moringa-700); }
    body[data-theme="dark"] .order-totals-row.is-discount { color: var(--color-moringa-300); }

    .order-address-text { font-size: 0.85rem; line-height: 1.5; color: var(--color-forest-900); }
    body[data-theme="dark"] .order-address-text { color: var(--color-moringa-100); }

    .sa-only { display: none; }
    #orderTable.is-super-admin .sa-only { display: table-cell; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const tableBody = document.getElementById('orderTableBody');
    const pagination = document.getElementById('pagination');
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('statusFilter');

    const drawer = document.getElementById('orderDrawer');
    const drawerOverlay = document.getElementById('drawerOverlay');
    const drawerBody = document.getElementById('drawerBody');
    const drawerOrderNumber = document.getElementById('drawerOrderNumber');
    const drawerOrderDate = document.getElementById('drawerOrderDate');
    const closeDrawerBtn = document.getElementById('closeDrawerBtn');

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

    let currentPage = 1;
    let searchDebounce = null;

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
        return '₹' + parseFloat(value ?? 0).toFixed(2);
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

    // ─── List ────────────────────────────────────────────────────────────────
    async function loadOrders(page = 1) {
        currentPage = page;

        tableBody.innerHTML = `<tr><td colspan="8" class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading orders…</td></tr>`;

        const params = new URLSearchParams({ page });
        if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
        if (statusFilter.value) params.set('status', statusFilter.value);

        try {
            const response = await fetch('/api/admin/orders?' + params.toString(), { headers: authHeaders() });
            const payload = await response.json();

            if (!payload.success) {
                tableBody.innerHTML = `<tr><td colspan="8" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load orders.</td></tr>`;
                return;
            }

            renderRows(payload.data?.data ?? []);
            renderPagination(payload.data);
        } catch (err) {
            tableBody.innerHTML = `<tr><td colspan="8" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load orders.</td></tr>`;
        }
    }

    function renderRows(orders) {
        if (!orders.length) {
            tableBody.innerHTML = `<tr><td colspan="8" class="table-empty"><i class="fa-solid fa-inbox"></i> No orders found.</td></tr>`;
            return;
        }

        tableBody.innerHTML = orders.map(order => `
            <tr data-row-id="${order.id}">
                <td><span class="order-number">${escapeHtml(order.order_number ?? ('#' + order.id))}</span></td>
                <td>
                    <div class="order-customer-name">${escapeHtml(order.user?.name ?? 'Guest')}</div>
                    <div class="order-customer-email">${escapeHtml(order.user?.email ?? '')}</div>
                </td>
                <td class="sa-only">${order.admin?.name ? escapeHtml(order.admin.name) : '—'}</td>
                <td>${formatDate(order.created_at)}</td>
                <td>${order.items?.length ?? 0}</td>
                <td>${formatMoney(order.total)}</td>
                <td>${statusBadge(order.status)}</td>
                <td>
                    <div class="table-actions">
                        <button type="button" class="btn-icon" title="Manage" data-manage-id="${order.id}">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button type="button" class="btn-icon btn-icon-danger" title="Delete" data-delete-id="${order.id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        tableBody.querySelectorAll('[data-manage-id]').forEach(btn => {
            btn.addEventListener('click', () => openDrawer(btn.dataset.manageId));
        });
        tableBody.querySelectorAll('[data-delete-id]').forEach(btn => {
            btn.addEventListener('click', () => deleteOrder(btn.dataset.deleteId, btn));
        });
    }

    function renderPagination(page) {
        if (!page || page.last_page <= 1) {
            pagination.innerHTML = '';
            return;
        }

        pagination.innerHTML = `
            <button type="button" class="btn btn-ghost" id="prevPage" ${page.current_page <= 1 ? 'disabled' : ''}>
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <span class="form-help" style="margin:0;">Page ${page.current_page} of ${page.last_page}</span>
            <button type="button" class="btn btn-ghost" id="nextPage" ${page.current_page >= page.last_page ? 'disabled' : ''}>
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        `;

        document.getElementById('prevPage')?.addEventListener('click', () => loadOrders(currentPage - 1));
        document.getElementById('nextPage')?.addEventListener('click', () => loadOrders(currentPage + 1));
    }

    async function deleteOrder(id, btn) {
        if (btn.disabled) return;
        if (!confirm('Delete this order permanently? This cannot be undone.')) return;

        btn.disabled = true;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

        try {
            const response = await fetch('/api/admin/orders/' + id, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', ...authHeaders() },
            });
            const data = await response.json();

            if (data.success) {
                document.querySelector(`tr[data-row-id="${id}"]`)?.remove();
                if (drawer.dataset.orderId === String(id)) closeDrawer();
                if (!tableBody.children.length) loadOrders(currentPage);
            } else {
                alert(data.message || 'Could not delete order.');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        } catch (err) {
            alert('Something went wrong. Please try again.');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }

    // ─── Drawer (view + manage a single order) ──────────────────────────────
    function openDrawer(id) {
        drawer.dataset.orderId = id;
        drawer.classList.add('is-open');
        drawerOverlay.classList.add('is-open');
        drawerOrderNumber.textContent = 'Order';
        drawerOrderDate.textContent = '';
        drawerBody.innerHTML = `<div class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading…</div>`;

        fetchOrder(id);
    }

    function closeDrawer() {
        drawer.classList.remove('is-open');
        drawerOverlay.classList.remove('is-open');
        delete drawer.dataset.orderId;
    }

    closeDrawerBtn.addEventListener('click', closeDrawer);
    drawerOverlay.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

    async function fetchOrder(id) {
        try {
            const response = await fetch('/api/admin/orders/' + id, { headers: authHeaders() });
            const payload = await response.json();

            if (!payload.success) {
                drawerBody.innerHTML = `<div class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load order.</div>`;
                return;
            }

            renderDrawer(payload.data);
        } catch (err) {
            drawerBody.innerHTML = `<div class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load order.</div>`;
        }
    }

    function renderDrawer(order) {
        drawerOrderNumber.textContent = order.order_number ?? ('#' + order.id);
        drawerOrderDate.textContent = formatDate(order.created_at);

        const address = order.address;
        const addressHtml = address ? `
            <div class="order-address-text">
                <strong>${escapeHtml(address.full_name)}</strong><br>
                ${escapeHtml(address.address_line_1)}${address.address_line_2 ? ', ' + escapeHtml(address.address_line_2) : ''}<br>
                ${address.landmark ? escapeHtml(address.landmark) + '<br>' : ''}
                ${escapeHtml(address.city)}, ${escapeHtml(address.state)} ${escapeHtml(address.postal_code)}<br>
                ${escapeHtml(address.country)}<br>
                Phone: ${escapeHtml(address.phone)}
            </div>
        ` : `<p class="order-address-text">No address on file.</p>`;

        const itemsHtml = (order.items ?? []).map(item => `
            <div class="order-item-row">
                <div>
                    <div class="order-item-name">${escapeHtml(item.product_name)}</div>
                    <div class="order-item-meta">${item.variant ? escapeHtml(item.variant) + ' · ' : ''}Qty ${item.quantity} × ${formatMoney(item.price)}</div>
                </div>
                <div>${formatMoney(item.line_total)}</div>
            </div>
        `).join('') || '<p class="form-help">No items.</p>';

        drawerBody.innerHTML = `
            <div>
                <p class="order-section-title">Status</p>
                ${statusBadge(order.status)}
            </div>

            <div>
                <p class="order-section-title">Customer</p>
                <div class="order-customer-name">${escapeHtml(order.user?.name ?? 'Guest')}</div>
                <div class="order-customer-email">${escapeHtml(order.user?.email ?? '')}</div>
            </div>

            ${document.getElementById('orderTable').classList.contains('is-super-admin') ? `
            <div>
                <p class="order-section-title">Admin</p>
                <div class="order-customer-name">${order.admin?.name ? escapeHtml(order.admin.name) : '—'}</div>
            </div>` : ''}

            <div>
                <p class="order-section-title">Shipping Address</p>
                ${addressHtml}
            </div>

            <div>
                <p class="order-section-title">Items</p>
                ${itemsHtml}
            </div>

            <div>
                <p class="order-section-title">Totals</p>
                <div class="order-totals-row"><span>Subtotal</span><span>${formatMoney(order.subtotal)}</span></div>
                ${parseFloat(order.discount) > 0 ? `
                <div class="order-totals-row is-discount">
                    <span>Discount${order.coupon_code ? ' (' + escapeHtml(order.coupon_code) + ')' : ''}</span>
                    <span>-${formatMoney(order.discount)}</span>
                </div>` : ''}
                <div class="order-totals-row"><span>Shipping</span><span>${formatMoney(order.shipping)}</span></div>
                <div class="order-totals-row"><span>Tax</span><span>${formatMoney(order.tax)}</span></div>
                <div class="order-totals-row is-grand"><span>Total</span><span>${formatMoney(order.total)}</span></div>
            </div>

            ${order.notes ? `
            <div>
                <p class="order-section-title">Customer Notes</p>
                <p class="order-address-text">${escapeHtml(order.notes)}</p>
            </div>` : ''}

            <div>
                <p class="order-section-title">Manage Order</p>
                <form id="manageOrderForm">
                    <div class="form-group-full" style="margin-bottom:1rem;">
                        <label class="form-label" for="orderStatus">Status</label>
                        <select id="orderStatus" name="status" class="form-select">
                            ${Object.entries(STATUS_LABELS).map(([value, label]) =>
                                `<option value="${value}" ${order.status === value ? 'selected' : ''}>${label}</option>`
                            ).join('')}
                        </select>
                    </div>
                    <div class="form-group-full" style="margin-bottom:1rem;">
                        <label class="form-label" for="adminNotes">Admin Notes</label>
                        <textarea id="adminNotes" name="admin_notes" class="form-textarea" rows="3">${escapeHtml(order.admin_notes)}</textarea>
                    </div>
                    <div style="display:flex; gap:0.75rem;">
                        <button type="submit" id="saveOrderBtn" class="btn btn-primary" style="flex:1;">
                            <span id="saveOrderBtnText">Save Changes</span>
                        </button>
                        <button type="button" id="drawerDeleteBtn" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </form>
            </div>
        `;

        const form = document.getElementById('manageOrderForm');
        const saveBtn = document.getElementById('saveOrderBtn');
        const saveBtnText = document.getElementById('saveOrderBtnText');

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (saveBtn.disabled) return;

            saveBtn.disabled = true;
            saveBtnText.textContent = 'Saving…';

            try {
                const response = await fetch('/api/admin/orders/' + order.id, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        ...authHeaders(),
                    },
                    body: JSON.stringify({
                        status: document.getElementById('orderStatus').value,
                        admin_notes: document.getElementById('adminNotes').value,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    renderDrawer(data.data);
                    loadOrders(currentPage);
                } else {
                    alert(data.message || 'Could not update order.');
                }
            } catch (err) {
                alert('Something went wrong. Please try again.');
            } finally {
                saveBtn.disabled = false;
                saveBtnText.textContent = 'Save Changes';
            }
        });

        document.getElementById('drawerDeleteBtn').addEventListener('click', function () {
            const row = document.querySelector(`tr[data-row-id="${order.id}"] [data-delete-id]`);
            if (row) { row.click(); } else {
                deleteOrder(order.id, this);
            }
        });
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => loadOrders(1), 400);
    });
    statusFilter.addEventListener('change', () => loadOrders(1));

    async function markSuperAdmin() {
        try {
            const response = await fetch('/api/profile', { headers: authHeaders() });
            const payload = await response.json();
            if (payload.user?.role === 'super_admin') {
                document.getElementById('orderTable').classList.add('is-super-admin');
            }
        } catch (err) {
            // Non-critical — the Admin column just stays hidden.
        }
    }

    const urlParams = new URLSearchParams(window.location.search);
    const initialStatus = urlParams.get('status');
    if (initialStatus) statusFilter.value = initialStatus;

    markSuperAdmin();
    loadOrders(1).then(() => {
        const orderId = urlParams.get('order');
        if (orderId) openDrawer(orderId);
    });
})();
</script>
@endpush
