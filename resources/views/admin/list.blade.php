@extends('layouts.app')

@section('title', 'Products')
@section('page-title', 'Products')
@section('page-subtitle', 'Manage your product catalog')

@section('content')

<div class="admin-card">

    <div class="card-header">

        <div>
            <p class="card-title">Products</p>
            <p class="card-subtitle">All products in your catalog</p>
        </div>

        <div class="table-toolbar">

            <div class="table-search">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                <input type="text" id="search" placeholder="Search by name or SKU…">
            </div>

            <select id="categoryFilter" class="form-select" style="width: auto;">
                <option value="">All Categories</option>
            </select>

            <select id="statusFilter" class="form-select" style="width: auto;">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

            <a href="{{ route('admin.form') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Add Product
            </a>

        </div>

    </div>

    <div class="table-scroll">

        <table class="admin-table" id="productTable">

            <thead>
                <tr>
                    <th width="60">Image</th>
                    <th>Product</th>
                    <th class="sa-only">Admin</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th width="140">Action</th>
                </tr>
            </thead>

            <tbody id="productTableBody">
                <tr>
                    <td colspan="9" class="table-empty">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        Loading products…
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
    .admin-pagination {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.5rem;
        margin-top: 1.25rem;
    }
    .product-thumb {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 0.5rem;
        object-fit: cover;
        background: color-mix(in srgb, var(--color-moringa-200) 40%, transparent);
    }
    .product-thumb-placeholder {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: color-mix(in srgb, var(--color-moringa-200) 40%, transparent);
        color: var(--color-moringa-600);
    }
    .product-name { font-weight: 600; color: var(--color-forest-950); }
    body[data-theme="dark"] .product-name { color: var(--color-cream-50); }
    .product-sku { font-size: 0.72rem; color: color-mix(in srgb, var(--color-forest-700) 65%, transparent); }
    .price-sale { font-weight: 700; color: var(--color-forest-950); }
    body[data-theme="dark"] .price-sale { color: var(--color-cream-50); }
    .price-regular { text-decoration: line-through; font-size: 0.75rem; color: color-mix(in srgb, var(--color-forest-700) 55%, transparent); margin-left: 0.35rem; }

    .sa-only { display: none; }
    #productTable.is-super-admin .sa-only { display: table-cell; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const tableBody = document.getElementById('productTableBody');
    const pagination = document.getElementById('pagination');
    const searchInput = document.getElementById('search');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');

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

    function formatPrice(product) {
        const regular = parseFloat(product.regular_price ?? 0);
        const sale = product.sale_price !== null && product.sale_price !== undefined ? parseFloat(product.sale_price) : null;

        if (sale !== null && sale < regular) {
            return `<span class="price-sale">₹${sale.toFixed(2)}</span><span class="price-regular">₹${regular.toFixed(2)}</span>`;
        }

        return `<span class="price-sale">₹${regular.toFixed(2)}</span>`;
    }

    function statusBadge(status) {
        return status === 'active'
            ? '<span class="badge badge-success">Active</span>'
            : '<span class="badge badge-neutral">Inactive</span>';
    }

    function stockCell(product) {
        const variants = product.variants ?? [];

        if (!variants.length) {
            return `<span class="form-help" style="margin:0;">Not tracked</span>`;
        }

        const total = variants.reduce((sum, v) => sum + (v.stock ?? 0), 0);
        const cls = total === 0 ? 'badge-danger' : total <= 10 ? 'badge-warning' : 'badge-success';
        const label = total === 0 ? 'Out of stock' : `${total} in stock`;
        const unitCount = variants.length > 1 ? ` <span class="form-help" style="margin:0;">(${variants.length} options)</span>` : '';

        return `<span class="badge ${cls}">${label}</span>${unitCount}`;
    }

    async function loadCategories() {
        try {
            const response = await fetch('/api/categories', { headers: authHeaders() });
            const data = await response.json();
            (data.data?.data ?? []).forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categoryFilter.appendChild(option);
            });
        } catch (err) {
            // Non-critical — filter just stays empty
        }
    }

    async function loadProducts(page = 1) {
        currentPage = page;

        tableBody.innerHTML = `<tr><td colspan="9" class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading products…</td></tr>`;

        const params = new URLSearchParams({ page });
        if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
        if (categoryFilter.value) params.set('category_id', categoryFilter.value);
        if (statusFilter.value) params.set('status', statusFilter.value);

        try {
            const response = await fetch('/api/products?' + params.toString(), { headers: authHeaders() });
            const payload = await response.json();
            const products = payload.data?.data ?? [];

            renderRows(products);
            renderPagination(payload.data);
        } catch (err) {
            tableBody.innerHTML = `<tr><td colspan="9" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load products.</td></tr>`;
        }
    }

    function renderRows(products) {
        if (!products.length) {
            tableBody.innerHTML = `<tr><td colspan="9" class="table-empty"><i class="fa-solid fa-box-open"></i> No products yet.</td></tr>`;
            return;
        }

        tableBody.innerHTML = products.map(product => `
            <tr data-row-id="${product.id}">
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
                <td>${product.brand ? escapeHtml(product.brand.name) : '—'}</td>
                <td>${formatPrice(product)}</td>
                <td>${stockCell(product)}</td>
                <td>${statusBadge(product.status)}</td>
                <td>
                    <div class="table-actions">
                        <a href="/admin/form/${product.id}" class="btn-icon" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button type="button" class="btn-icon btn-icon-danger" title="Delete" data-delete-id="${product.id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        tableBody.querySelectorAll('[data-delete-id]').forEach(btn => {
            btn.addEventListener('click', () => deleteProduct(btn));
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

        document.getElementById('prevPage')?.addEventListener('click', () => loadProducts(currentPage - 1));
        document.getElementById('nextPage')?.addEventListener('click', () => loadProducts(currentPage + 1));
    }

    async function deleteProduct(btn) {
        const id = btn.dataset.deleteId;

        if (btn.disabled) return;
        if (!confirm('Delete this product? This cannot be undone.')) return;

        btn.disabled = true;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

        try {
            const response = await fetch('/api/products/' + id, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', ...authHeaders() },
            });

            const data = await response.json();

            if (data.status) {
                document.querySelector(`tr[data-row-id="${id}"]`)?.remove();
                if (!tableBody.children.length) loadProducts(currentPage);
            } else {
                alert(data.message || 'Could not delete product.');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        } catch (err) {
            alert('Something went wrong. Please try again.');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => loadProducts(1), 400);
    });
    categoryFilter.addEventListener('change', () => loadProducts(1));
    statusFilter.addEventListener('change', () => loadProducts(1));

    async function markSuperAdmin() {
        try {
            const response = await fetch('/api/profile', { headers: authHeaders() });
            const payload = await response.json();
            if (payload.user?.role === 'super_admin') {
                document.getElementById('productTable').classList.add('is-super-admin');
            }
        } catch (err) {
            // Non-critical — the Admin column just stays hidden.
        }
    }

    markSuperAdmin();
    loadCategories();
    loadProducts(1);
})();
</script>
@endpush
