@extends('layouts.app')

@section('title', 'All Admins')
@section('page-title', 'All Admins')
@section('page-subtitle', 'Every admin on the platform, with their products, customers and sales')

@section('content')

<div class="admin-card" id="permissionNotice" style="display:none; margin-bottom:1.5rem; border-color:#dc2626;">
    <p style="margin:0; font-size:0.9rem;">
        <i class="fa-solid fa-triangle-exclamation" style="color:#dc2626; margin-right:0.4rem;"></i>
        Only super admins can view this page.
    </p>
</div>

<div class="admin-card" id="adminsCard">

    <div class="card-header">

        <div>
            <p class="card-title">Admins</p>
            <p class="card-subtitle">Store owners and what they've built so far</p>
        </div>

        <div class="table-toolbar">

            <div class="table-search">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                <input type="text" id="search" placeholder="Search by name or email…">
            </div>

        </div>

    </div>

    <div class="table-scroll">

        <table class="admin-table" id="adminsTable">

            <thead>
                <tr>
                    <th>Admin</th>
                    <th>Store</th>
                    <th>Products</th>
                    <th>Customers</th>
                    <th>Orders</th>
                    <th>Total Sales</th>
                    <th>Joined</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody id="adminsTableBody">
                <tr>
                    <td colspan="8" class="table-empty">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        Loading admins…
                    </td>
                </tr>
            </tbody>

        </table>

    </div>

    <div class="admin-pagination" id="pagination"></div>

</div>

@endsection

@push('styles')
<style>
    .admin-pagination { display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; margin-top: 1.25rem; }

    .adm-avatar {
        width: 2.25rem; height: 2.25rem; border-radius: 9999px; display: inline-flex;
        align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;
        color: #fff; background: linear-gradient(135deg, var(--color-moringa-400), var(--color-forest-600));
        margin-right: 0.6rem; vertical-align: middle;
    }
    .adm-name-cell { display: flex; align-items: center; }
    .adm-name { font-weight: 600; color: var(--color-forest-950); }
    body[data-theme="dark"] .adm-name { color: var(--color-cream-50); }
    .adm-email { font-size: 0.72rem; color: color-mix(in srgb, var(--color-forest-700) 65%, transparent); }
    .adm-store-link { font-size: 0.82rem; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const permissionNotice = document.getElementById('permissionNotice');
    const adminsCard = document.getElementById('adminsCard');
    const tableBody = document.getElementById('adminsTableBody');
    const pagination = document.getElementById('pagination');
    const searchInput = document.getElementById('search');

    const STATUS_BADGE = { active: 'badge-success', inactive: 'badge-danger' };

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

    async function loadAdmins(page = 1) {
        currentPage = page;

        tableBody.innerHTML = `<tr><td colspan="8" class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading admins…</td></tr>`;

        const params = new URLSearchParams({ page });
        if (searchInput.value.trim()) params.set('search', searchInput.value.trim());

        try {
            const response = await fetch('/api/admins?' + params.toString(), { headers: authHeaders() });

            if (response.status === 403) {
                adminsCard.style.display = 'none';
                permissionNotice.style.display = 'block';
                return;
            }

            const payload = await response.json();

            if (!payload.success) {
                tableBody.innerHTML = `<tr><td colspan="8" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load admins.</td></tr>`;
                return;
            }

            renderRows(payload.data?.data ?? []);
            renderPagination(payload.data);
        } catch (err) {
            tableBody.innerHTML = `<tr><td colspan="8" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load admins.</td></tr>`;
        }
    }

    function renderRows(admins) {
        if (!admins.length) {
            tableBody.innerHTML = `<tr><td colspan="8" class="table-empty"><i class="fa-solid fa-user-slash"></i> No admins found.</td></tr>`;
            return;
        }

        tableBody.innerHTML = admins.map(admin => `
            <tr>
                <td>
                    <div class="adm-name-cell">
                        <span class="adm-avatar">${initials(admin.name)}</span>
                        <div>
                            <div class="adm-name">${escapeHtml(admin.name)}</div>
                            <div class="adm-email">${escapeHtml(admin.email)}</div>
                        </div>
                    </div>
                </td>
                <td>${admin.store_slug ? `<a class="adm-store-link" href="/store/${encodeURIComponent(admin.store_slug)}" target="_blank">/store/${escapeHtml(admin.store_slug)}</a>` : '—'}</td>
                <td>${admin.products_count ?? 0}</td>
                <td>${admin.registered_customers_count ?? 0}</td>
                <td>${admin.orders_count ?? 0}</td>
                <td>${formatMoney(admin.total_sales)}</td>
                <td>${formatDate(admin.created_at)}</td>
                <td>${statusBadge(admin.status)}</td>
            </tr>
        `).join('');
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

        document.getElementById('prevPage')?.addEventListener('click', () => loadAdmins(currentPage - 1));
        document.getElementById('nextPage')?.addEventListener('click', () => loadAdmins(currentPage + 1));
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => loadAdmins(1), 400);
    });

    loadAdmins(1);
})();
</script>
@endpush
