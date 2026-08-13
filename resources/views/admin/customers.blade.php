@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')
@section('page-subtitle', 'View and manage your customer accounts')

@section('content')

<div class="admin-card">

    <div class="card-header">

        <div>
            <p class="card-title">Customers</p>
            <p class="card-subtitle">Everyone who has registered on your store</p>
        </div>

        <div class="table-toolbar">

            <div class="table-search">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                <input type="text" id="search" placeholder="Search by name or email…">
            </div>

            <select id="statusFilter" class="form-select" style="width: auto;">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

        </div>

    </div>

    <div class="table-scroll">

        <table class="admin-table" id="customerTable">

            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Email</th>
                    <th class="sa-only">Belongs To</th>
                    <th>Orders</th>
                    <th>Total Spent</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th width="80">Action</th>
                </tr>
            </thead>

            <tbody id="customerTableBody">
                <tr>
                    <td colspan="8" class="table-empty">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        Loading customers…
                    </td>
                </tr>
            </tbody>

        </table>

    </div>

    <div class="admin-pagination" id="pagination"></div>

</div>

<div class="cust-drawer-overlay" id="drawerOverlay"></div>

<aside class="cust-drawer" id="customerDrawer">
    <div class="cust-drawer-header">
        <div>
            <p class="card-title" id="drawerCustomerName">Customer</p>
            <p class="card-subtitle" id="drawerCustomerEmail"></p>
        </div>
        <button type="button" class="btn-icon" id="closeDrawerBtn" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="cust-drawer-body" id="drawerBody">
        <div class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading…</div>
    </div>
</aside>

@endsection

@push('styles')
<style>
    .admin-pagination { display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; margin-top: 1.25rem; }

    .cust-avatar {
        width: 2.25rem; height: 2.25rem; border-radius: 9999px; display: inline-flex;
        align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;
        color: #fff; background: linear-gradient(135deg, var(--color-moringa-400), var(--color-forest-600));
        margin-right: 0.6rem; vertical-align: middle;
    }
    .cust-name-cell { display: flex; align-items: center; }
    .cust-name { font-weight: 600; color: var(--color-forest-950); }
    body[data-theme="dark"] .cust-name { color: var(--color-cream-50); }

    .cust-drawer-overlay {
        position: fixed; inset: 0; background: rgba(15, 23, 20, 0.45);
        opacity: 0; pointer-events: none; transition: opacity 0.25s ease; z-index: 40;
    }
    .cust-drawer-overlay.is-open { opacity: 1; pointer-events: auto; }

    .cust-drawer {
        position: fixed; top: 0; right: 0; height: 100vh; width: min(28rem, 100vw);
        background: var(--color-cream-50);
        box-shadow: var(--shadow-panel);
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 41; display: flex; flex-direction: column; overflow-y: auto;
    }
    body[data-theme="dark"] .cust-drawer { background: var(--color-forest-900); }
    .cust-drawer.is-open { transform: translateX(0); }

    .cust-drawer-header {
        display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem;
        padding: 1.25rem 1.5rem; border-bottom: 1px solid color-mix(in srgb, var(--color-forest-900) 8%, transparent);
    }
    body[data-theme="dark"] .cust-drawer-header { border-bottom-color: color-mix(in srgb, var(--color-moringa-200) 10%, transparent); }
    .cust-drawer-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.5rem; }

    .cust-section-title {
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;
        color: color-mix(in srgb, var(--color-forest-700) 70%, transparent); margin-bottom: 0.6rem;
    }
    body[data-theme="dark"] .cust-section-title { color: color-mix(in srgb, var(--color-moringa-200) 70%, transparent); }

    .cust-stat-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
    .cust-stat-box {
        border-radius: 0.65rem; padding: 0.75rem;
        background: color-mix(in srgb, var(--color-moringa-200) 30%, transparent);
    }
    body[data-theme="dark"] .cust-stat-box { background: color-mix(in srgb, var(--color-moringa-700) 25%, transparent); }
    .cust-stat-value { font-size: 1.1rem; font-weight: 700; color: var(--color-forest-950); }
    body[data-theme="dark"] .cust-stat-value { color: var(--color-cream-50); }
    .cust-stat-label { font-size: 0.72rem; color: color-mix(in srgb, var(--color-forest-700) 65%, transparent); }

    .cust-address-card {
        border: 1px solid color-mix(in srgb, var(--color-forest-900) 8%, transparent);
        border-radius: 0.65rem; padding: 0.75rem; font-size: 0.83rem; line-height: 1.5;
        margin-bottom: 0.6rem;
    }
    body[data-theme="dark"] .cust-address-card { border-color: color-mix(in srgb, var(--color-moringa-200) 10%, transparent); }
    .cust-address-card:last-child { margin-bottom: 0; }

    .cust-order-row {
        display: flex; justify-content: space-between; align-items: center; gap: 0.75rem;
        padding: 0.5rem 0; border-top: 1px solid color-mix(in srgb, var(--color-forest-900) 6%, transparent);
        font-size: 0.83rem;
    }
    .cust-order-row:first-child { border-top: none; }
    body[data-theme="dark"] .cust-order-row { border-top-color: color-mix(in srgb, var(--color-moringa-200) 8%, transparent); }

    .cust-plain-text { font-size: 0.85rem; line-height: 1.5; color: var(--color-forest-900); }
    body[data-theme="dark"] .cust-plain-text { color: var(--color-moringa-100); }

    .sa-only { display: none; }
    #customerTable.is-super-admin .sa-only { display: table-cell; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const tableBody = document.getElementById('customerTableBody');
    const pagination = document.getElementById('pagination');
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('statusFilter');

    const drawer = document.getElementById('customerDrawer');
    const drawerOverlay = document.getElementById('drawerOverlay');
    const drawerBody = document.getElementById('drawerBody');
    const drawerCustomerName = document.getElementById('drawerCustomerName');
    const drawerCustomerEmail = document.getElementById('drawerCustomerEmail');
    const closeDrawerBtn = document.getElementById('closeDrawerBtn');

    const STATUS_BADGE = { active: 'badge-success', inactive: 'badge-danger' };

    let isSuperAdmin = false;

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

    function initials(name) {
        return (name ?? '?').trim().charAt(0).toUpperCase() || '?';
    }

    function statusBadge(status) {
        const cls = STATUS_BADGE[status] ?? 'badge-neutral';
        const label = status === 'active' ? 'Active' : 'Inactive';
        return `<span class="badge ${cls}">${label}</span>`;
    }

    // ─── List ────────────────────────────────────────────────────────────────
    async function loadCustomers(page = 1) {
        currentPage = page;

        tableBody.innerHTML = `<tr><td colspan="8" class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading customers…</td></tr>`;

        const params = new URLSearchParams({ page });
        if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
        if (statusFilter.value) params.set('status', statusFilter.value);

        try {
            const response = await fetch('/api/admin/customers?' + params.toString(), { headers: authHeaders() });
            const payload = await response.json();

            if (!payload.success) {
                tableBody.innerHTML = `<tr><td colspan="8" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load customers.</td></tr>`;
                return;
            }

            renderRows(payload.data?.data ?? []);
            renderPagination(payload.data);
        } catch (err) {
            tableBody.innerHTML = `<tr><td colspan="8" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load customers.</td></tr>`;
        }
    }

    function renderRows(customers) {
        if (!customers.length) {
            tableBody.innerHTML = `<tr><td colspan="8" class="table-empty"><i class="fa-solid fa-user-slash"></i> No customers found.</td></tr>`;
            return;
        }

        tableBody.innerHTML = customers.map(customer => `
            <tr data-row-id="${customer.id}">
                <td>
                    <div class="cust-name-cell">
                        <span class="cust-avatar">${initials(customer.name)}</span>
                        <span class="cust-name">${escapeHtml(customer.name)}</span>
                    </div>
                </td>
                <td>${escapeHtml(customer.email)}</td>
                <td class="sa-only">${customer.registered_via_admin?.name ? escapeHtml(customer.registered_via_admin.name) : '—'}</td>
                <td>${customer.orders_count ?? 0}</td>
                <td>${formatMoney(customer.total_spent)}</td>
                <td>${formatDate(customer.created_at)}</td>
                <td>${statusBadge(customer.status)}</td>
                <td>
                    <div class="table-actions">
                        <button type="button" class="btn-icon" title="Manage" data-manage-id="${customer.id}">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        tableBody.querySelectorAll('[data-manage-id]').forEach(btn => {
            btn.addEventListener('click', () => openDrawer(btn.dataset.manageId));
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

        document.getElementById('prevPage')?.addEventListener('click', () => loadCustomers(currentPage - 1));
        document.getElementById('nextPage')?.addEventListener('click', () => loadCustomers(currentPage + 1));
    }

    // ─── Drawer (view + manage a single customer) ───────────────────────────
    function openDrawer(id) {
        drawer.dataset.customerId = id;
        drawer.classList.add('is-open');
        drawerOverlay.classList.add('is-open');
        drawerCustomerName.textContent = 'Customer';
        drawerCustomerEmail.textContent = '';
        drawerBody.innerHTML = `<div class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading…</div>`;

        fetchCustomer(id);
    }

    function closeDrawer() {
        drawer.classList.remove('is-open');
        drawerOverlay.classList.remove('is-open');
        delete drawer.dataset.customerId;
    }

    closeDrawerBtn.addEventListener('click', closeDrawer);
    drawerOverlay.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

    async function fetchCustomer(id) {
        try {
            const response = await fetch('/api/admin/customers/' + id, { headers: authHeaders() });
            const payload = await response.json();

            if (!payload.success) {
                drawerBody.innerHTML = `<div class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load customer.</div>`;
                return;
            }

            renderDrawer(payload.data);
        } catch (err) {
            drawerBody.innerHTML = `<div class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load customer.</div>`;
        }
    }

    function renderDrawer(customer) {
        drawerCustomerName.textContent = customer.name;
        drawerCustomerEmail.textContent = customer.email;

        const profile = customer.customer_profile;
        const addresses = customer.customer_addresses ?? [];
        const orders = customer.orders ?? [];

        const profileHtml = profile ? `
            <div class="cust-address-card">
                ${profile.gender ? `<div><strong>Gender:</strong> ${escapeHtml(profile.gender)}</div>` : ''}
                ${profile.date_of_birth ? `<div><strong>DOB:</strong> ${formatDate(profile.date_of_birth)}</div>` : ''}
                ${profile.company_name ? `<div><strong>Company:</strong> ${escapeHtml(profile.company_name)}</div>` : ''}
                ${profile.gst_number ? `<div><strong>GST:</strong> ${escapeHtml(profile.gst_number)}</div>` : ''}
                ${profile.bio ? `<div>${escapeHtml(profile.bio)}</div>` : ''}
                ${!profile.gender && !profile.date_of_birth && !profile.company_name && !profile.gst_number && !profile.bio ? '<div class="form-help" style="margin:0;">No profile details added.</div>' : ''}
            </div>
        ` : '';

        const addressesHtml = addresses.length ? addresses.map(address => `
            <div class="cust-address-card">
                <strong>${escapeHtml(address.full_name)}</strong> ${address.is_default ? '<span class="badge badge-neutral">Default</span>' : ''}<br>
                ${escapeHtml(address.address_line_1)}${address.address_line_2 ? ', ' + escapeHtml(address.address_line_2) : ''}<br>
                ${escapeHtml(address.city)}, ${escapeHtml(address.state)} ${escapeHtml(address.postal_code)}<br>
                Phone: ${escapeHtml(address.phone)}
            </div>
        `).join('') : '<p class="form-help">No saved addresses.</p>';

        const ordersHtml = orders.length ? orders.map(order => `
            <div class="cust-order-row">
                <div>
                    <div style="font-weight:600;">${escapeHtml(order.order_number ?? ('#' + order.id))}</div>
                    <div class="form-help" style="margin:0;">${formatDate(order.created_at)}</div>
                    ${isSuperAdmin ? `<div class="form-help" style="margin:0;"><i class="fa-solid fa-store"></i> ${order.admin?.name ? escapeHtml(order.admin.name) : 'Main site'}</div>` : ''}
                </div>
                <div style="text-align:right;">
                    <div>${formatMoney(order.total)}</div>
                    <div>${statusBadge(order.status === 'delivered' ? 'active' : order.status)}</div>
                </div>
            </div>
        `).join('') : '<p class="form-help">No orders yet.</p>';

        drawerBody.innerHTML = `
            <div class="cust-stat-grid">
                <div class="cust-stat-box">
                    <div class="cust-stat-value">${customer.orders_count ?? 0}</div>
                    <div class="cust-stat-label">Total Orders</div>
                </div>
                <div class="cust-stat-box">
                    <div class="cust-stat-value">${formatMoney(customer.total_spent)}</div>
                    <div class="cust-stat-label">Total Spent</div>
                </div>
            </div>

            <div>
                <p class="cust-section-title">Joined</p>
                <p class="cust-plain-text">${formatDate(customer.created_at)}</p>
            </div>

            ${isSuperAdmin ? `
            <div>
                <p class="cust-section-title">Registered Via</p>
                <p class="cust-plain-text"><i class="fa-solid fa-store"></i> ${customer.registered_via_admin?.name ? escapeHtml(customer.registered_via_admin.name) + "'s store" : 'Main site (direct)'}</p>
            </div>` : ''}

            ${profile ? `<div><p class="cust-section-title">Profile</p>${profileHtml}</div>` : ''}

            <div>
                <p class="cust-section-title">Addresses</p>
                ${addressesHtml}
            </div>

            <div>
                <p class="cust-section-title">${isSuperAdmin ? 'Order History' : 'Recent Orders'}</p>
                ${ordersHtml}
            </div>

            <div>
                <p class="cust-section-title">Account Status</p>
                <form id="manageCustomerForm">
                    <div class="form-group-full" style="margin-bottom:1rem;">
                        <select id="customerStatus" name="status" class="form-select">
                            <option value="active" ${customer.status === 'active' ? 'selected' : ''}>Active</option>
                            <option value="inactive" ${customer.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" id="saveCustomerBtn" class="btn btn-primary" style="width:100%;">
                        <span id="saveCustomerBtnText">Save Changes</span>
                    </button>
                </form>
            </div>
        `;

        const form = document.getElementById('manageCustomerForm');
        const saveBtn = document.getElementById('saveCustomerBtn');
        const saveBtnText = document.getElementById('saveCustomerBtnText');

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (saveBtn.disabled) return;

            saveBtn.disabled = true;
            saveBtnText.textContent = 'Saving…';

            try {
                const response = await fetch('/api/admin/customers/' + customer.id, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        ...authHeaders(),
                    },
                    body: JSON.stringify({ status: document.getElementById('customerStatus').value }),
                });

                const data = await response.json();

                if (data.success) {
                    renderDrawer({ ...customer, status: data.data.status });
                    loadCustomers(currentPage);
                } else {
                    alert(data.message || 'Could not update customer.');
                }
            } catch (err) {
                alert('Something went wrong. Please try again.');
            } finally {
                saveBtn.disabled = false;
                saveBtnText.textContent = 'Save Changes';
            }
        });
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => loadCustomers(1), 400);
    });
    statusFilter.addEventListener('change', () => loadCustomers(1));

    async function markSuperAdmin() {
        try {
            const response = await fetch('/api/profile', { headers: authHeaders() });
            const payload = await response.json();
            if (payload.user?.role === 'super_admin') {
                isSuperAdmin = true;
                document.getElementById('customerTable').classList.add('is-super-admin');
            }
        } catch (err) {
            // Non-critical — the "Belongs To" column just stays hidden.
        }
    }

    markSuperAdmin();
    loadCustomers(1);
})();
</script>
@endpush
