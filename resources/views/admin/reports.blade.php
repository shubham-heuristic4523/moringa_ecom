@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports')
@section('page-subtitle', 'Best-selling products, ranked by units sold')

@section('content')

<div class="admin-card">

    <div class="card-header">
        <div>
            <p class="card-title">Best Sellers</p>
            <p class="card-subtitle" id="cardSubtitle">Most-ordered products, highest first</p>
        </div>
    </div>

    <div class="table-scroll">

        <table class="admin-table" id="bestSellersTable">

            <thead>
                <tr>
                    <th width="60">#</th>
                    <th width="60">Image</th>
                    <th>Product</th>
                    <th class="sa-only">Admin</th>
                    <th>Category</th>
                    <th>Units Sold</th>
                    <th>Revenue</th>
                </tr>
            </thead>

            <tbody id="bestSellersTableBody">
                <tr>
                    <td colspan="7" class="table-empty">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        Loading report…
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
    .product-thumb {
        width: 2.75rem; height: 2.75rem; border-radius: 0.5rem; object-fit: cover;
        background: color-mix(in srgb, var(--color-moringa-200) 40%, transparent);
    }
    .product-thumb-placeholder {
        width: 2.75rem; height: 2.75rem; border-radius: 0.5rem; display: flex;
        align-items: center; justify-content: center;
        background: color-mix(in srgb, var(--color-moringa-200) 40%, transparent);
        color: var(--color-moringa-600);
    }
    .product-name { font-weight: 600; color: var(--color-forest-950); }
    body[data-theme="dark"] .product-name { color: var(--color-cream-50); }
    .rank-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 1.75rem; height: 1.75rem; border-radius: 9999px; font-weight: 700; font-size: 0.78rem;
        background: color-mix(in srgb, var(--color-moringa-200) 45%, transparent); color: var(--color-forest-950);
    }
    body[data-theme="dark"] .rank-badge { color: var(--color-cream-50); }

    .sa-only { display: none; }
    #bestSellersTable.is-super-admin .sa-only { display: table-cell; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const tableBody = document.getElementById('bestSellersTableBody');
    const pagination = document.getElementById('pagination');
    const cardSubtitle = document.getElementById('cardSubtitle');

    let currentPage = 1;

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

    async function loadReport(page = 1) {
        currentPage = page;

        tableBody.innerHTML = `<tr><td colspan="7" class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading report…</td></tr>`;

        try {
            const response = await fetch('/api/admin/reports/best-sellers?page=' + page, { headers: authHeaders() });
            const payload = await response.json();

            if (!payload.success) {
                tableBody.innerHTML = `<tr><td colspan="7" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load report.</td></tr>`;
                return;
            }

            renderRows(payload.data?.data ?? [], (payload.data?.per_page ?? 20) * (page - 1));
            renderPagination(payload.data);
        } catch (err) {
            tableBody.innerHTML = `<tr><td colspan="7" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load report.</td></tr>`;
        }
    }

    function renderRows(products, rankOffset) {
        if (!products.length) {
            tableBody.innerHTML = `<tr><td colspan="7" class="table-empty"><i class="fa-solid fa-box-open"></i> No sales yet.</td></tr>`;
            return;
        }

        tableBody.innerHTML = products.map((product, index) => `
            <tr>
                <td><span class="rank-badge">${rankOffset + index + 1}</span></td>
                <td>
                    ${product.thumbnail_url
                        ? `<img src="${product.thumbnail_url}" alt="" class="product-thumb">`
                        : `<span class="product-thumb-placeholder"><i class="fa-solid fa-image"></i></span>`}
                </td>
                <td>
                    <div class="product-name">${escapeHtml(product.name)}</div>
                    ${product.sku ? `<div class="product-sku">SKU: ${escapeHtml(product.sku)}</div>` : ''}
                </td>
                <td class="sa-only">${product.owner?.name ? escapeHtml(product.owner.name) : '—'}</td>
                <td>${product.category ? escapeHtml(product.category.name) : '—'}</td>
                <td>${product.units_sold ?? 0}</td>
                <td>${formatMoney(product.revenue)}</td>
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

        document.getElementById('prevPage')?.addEventListener('click', () => loadReport(currentPage - 1));
        document.getElementById('nextPage')?.addEventListener('click', () => loadReport(currentPage + 1));
    }

    async function markSuperAdmin() {
        try {
            const response = await fetch('/api/profile', { headers: authHeaders() });
            const payload = await response.json();
            if (payload.user?.role === 'super_admin') {
                document.getElementById('bestSellersTable').classList.add('is-super-admin');
                cardSubtitle.textContent = 'Most-ordered products across every admin, highest first';
            }
        } catch (err) {
            // Non-critical — the Admin column just stays hidden.
        }
    }

    markSuperAdmin();
    loadReport(1);
})();
</script>
@endpush
