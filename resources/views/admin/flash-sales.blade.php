@extends('layouts.app')

@section('title', 'Flash Sales')
@section('page-title', 'Flash Sales')
@section('page-subtitle', 'Time-boxed sales the storefront counts down to')

@section('content')

<div class="admin-card">

    <div class="card-header">

        <div>
            <p class="card-title">Flash Sales</p>
            <p class="card-subtitle">Each sale groups a set of products under a name and an end time</p>
        </div>

        <div class="table-toolbar">

            <div class="table-search">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                <input type="text" id="search" placeholder="Search by name…">
            </div>

            <select id="statusFilter" class="form-select" style="width: auto;">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

            <button type="button" id="addSaleBtn" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Add Sale
            </button>

        </div>

    </div>

    <div class="table-scroll">

        <table class="admin-table" id="saleTable">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Products</th>
                    <th>Starts</th>
                    <th>Ends</th>
                    <th>Status</th>
                    <th width="100">Action</th>
                </tr>
            </thead>

            <tbody id="saleTableBody">
                <tr>
                    <td colspan="6" class="table-empty">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        Loading sales…
                    </td>
                </tr>
            </tbody>

        </table>

    </div>

    <div class="admin-pagination" id="pagination"></div>

</div>

<div class="sale-drawer-overlay" id="drawerOverlay"></div>

<aside class="sale-drawer" id="saleDrawer">
    <div class="sale-drawer-header">
        <p class="card-title" id="drawerTitle">Add Sale</p>
        <button type="button" class="btn-icon" id="closeDrawerBtn" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="sale-drawer-body">
        <form id="saleForm">
            <input type="hidden" id="saleId">

            <div class="form-group-full" style="margin-bottom:1rem;">
                <label class="form-label" for="saleName">Sale Name<span class="form-required">*</span></label>
                <input type="text" id="saleName" class="form-input" placeholder="e.g. Independence Day Sale" required>
                <p class="form-error" data-error-for="name"></p>
            </div>

            <div class="form-grid" style="margin-bottom:1rem;">
                <div class="form-group-half">
                    <label class="form-label" for="startsAt">Starts At</label>
                    <input type="datetime-local" id="startsAt" class="form-input">
                </div>
                <div class="form-group-half">
                    <label class="form-label" for="endsAt">Ends At<span class="form-required">*</span></label>
                    <input type="datetime-local" id="endsAt" class="form-input" required>
                    <p class="form-error" data-error-for="ends_at"></p>
                </div>
            </div>
            <p class="form-help" style="margin-top:-0.5rem; margin-bottom:1rem;">Leave "Starts At" blank to go live immediately. This end time drives the storefront's countdown.</p>

            <div class="form-group-full" style="margin-bottom:1.5rem;">
                <label class="form-label" for="status">Status<span class="form-required">*</span></label>
                <select id="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="form-group-full" style="margin-bottom:0.5rem;">
                <label class="form-label">Products<span class="form-required">*</span></label>
                <p class="form-help" style="margin-top:-0.25rem;">Search your catalog and add the products included in this sale.</p>
            </div>

            <div class="sale-product-search">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                <input type="text" id="productSearch" placeholder="Search products by name or SKU…" autocomplete="off">
            </div>
            <div class="sale-product-results" id="productResults"></div>
            <p class="form-error" data-error-for="product_ids"></p>

            <div class="sale-selected-list" id="selectedProducts">
                <p class="form-help" id="selectedEmptyHint" style="margin:0.5rem 0;">No products added yet.</p>
            </div>

            <div style="display:flex; gap:0.75rem; margin-top:1.5rem;">
                <button type="submit" id="saveSaleBtn" class="btn btn-primary" style="flex:1;">
                    <span id="saveSaleBtnText">Save Sale</span>
                </button>
                <button type="button" id="deleteSaleBtn" class="btn btn-danger" style="display:none;">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </form>
    </div>
</aside>

@endsection

@push('styles')
<style>
    .admin-pagination { display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; margin-top: 1.25rem; }

    .sale-drawer-overlay {
        position: fixed; inset: 0; background: rgba(15, 23, 20, 0.45);
        opacity: 0; pointer-events: none; transition: opacity 0.25s ease; z-index: 40;
    }
    .sale-drawer-overlay.is-open { opacity: 1; pointer-events: auto; }

    .sale-drawer {
        position: fixed; top: 0; right: 0; height: 100vh; width: min(28rem, 100vw);
        background: var(--color-cream-50);
        box-shadow: var(--shadow-panel);
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 41; display: flex; flex-direction: column; overflow-y: auto;
    }
    body[data-theme="dark"] .sale-drawer { background: var(--color-forest-900); }
    .sale-drawer.is-open { transform: translateX(0); }

    .sale-drawer-header {
        display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem;
        padding: 1.25rem 1.5rem; border-bottom: 1px solid color-mix(in srgb, var(--color-forest-900) 8%, transparent);
    }
    body[data-theme="dark"] .sale-drawer-header { border-bottom-color: color-mix(in srgb, var(--color-moringa-200) 10%, transparent); }
    .sale-drawer-body { padding: 1.5rem; }

    .sale-count-badge {
        display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.78rem; font-weight: 600;
        color: color-mix(in srgb, var(--color-forest-700) 75%, transparent);
    }
    body[data-theme="dark"] .sale-count-badge { color: color-mix(in srgb, var(--color-moringa-200) 75%, transparent); }
    .sale-window { font-size: 0.82rem; color: color-mix(in srgb, var(--color-forest-700) 70%, transparent); }
    body[data-theme="dark"] .sale-window { color: color-mix(in srgb, var(--color-moringa-200) 70%, transparent); }

    .sale-product-search {
        display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0.9rem; border-radius: 0.6rem;
        border: 1px solid color-mix(in srgb, var(--color-forest-900) 12%, transparent); position: relative;
    }
    body[data-theme="dark"] .sale-product-search { border-color: color-mix(in srgb, var(--color-moringa-200) 18%, transparent); }
    .sale-product-search input { flex: 1; border: none; background: transparent; outline: none; font-size: 0.88rem; color: inherit; }

    .sale-product-results {
        max-height: 12rem; overflow-y: auto; margin-top: 0.4rem; border-radius: 0.6rem;
        border: 1px solid color-mix(in srgb, var(--color-forest-900) 8%, transparent);
    }
    body[data-theme="dark"] .sale-product-results { border-color: color-mix(in srgb, var(--color-moringa-200) 12%, transparent); }
    .sale-product-results:empty { display: none; }
    .sale-result-row {
        display: flex; align-items: center; gap: 0.6rem; padding: 0.55rem 0.75rem; cursor: pointer;
        border-bottom: 1px solid color-mix(in srgb, var(--color-forest-900) 6%, transparent); transition: background 0.15s ease;
    }
    body[data-theme="dark"] .sale-result-row { border-bottom-color: color-mix(in srgb, var(--color-moringa-200) 8%, transparent); }
    .sale-result-row:last-child { border-bottom: none; }
    .sale-result-row:hover { background: color-mix(in srgb, var(--color-moringa-200) 25%, transparent); }
    .sale-result-row.is-added { opacity: 0.45; cursor: default; }
    .sale-result-thumb { width: 2rem; height: 2rem; border-radius: 0.4rem; object-fit: cover; background: var(--color-moringa-100); flex-shrink: 0; }
    .sale-result-name { font-size: 0.85rem; font-weight: 600; flex: 1; }
    .sale-result-price { font-size: 0.78rem; color: color-mix(in srgb, var(--color-forest-700) 65%, transparent); }

    .sale-selected-list { margin-top: 0.75rem; display: flex; flex-direction: column; gap: 0.5rem; }
    .sale-selected-row {
        display: flex; align-items: center; gap: 0.6rem; padding: 0.5rem 0.65rem; border-radius: 0.6rem;
        background: color-mix(in srgb, var(--color-moringa-200) 30%, transparent);
    }
    .sale-selected-thumb { width: 2.25rem; height: 2.25rem; border-radius: 0.45rem; object-fit: cover; background: #fff; flex-shrink: 0; }
    .sale-selected-name { font-size: 0.85rem; font-weight: 600; flex: 1; }
    .sale-selected-remove {
        border: none; background: transparent; color: #dc2626; cursor: pointer; padding: 0.25rem;
        display: flex; align-items: center; justify-content: center;
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const tableBody = document.getElementById('saleTableBody');
    const pagination = document.getElementById('pagination');
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('statusFilter');

    const drawer = document.getElementById('saleDrawer');
    const drawerOverlay = document.getElementById('drawerOverlay');
    const drawerTitle = document.getElementById('drawerTitle');
    const closeDrawerBtn = document.getElementById('closeDrawerBtn');
    const form = document.getElementById('saleForm');
    const saveBtn = document.getElementById('saveSaleBtn');
    const saveBtnText = document.getElementById('saveSaleBtnText');
    const deleteBtn = document.getElementById('deleteSaleBtn');

    const productSearch = document.getElementById('productSearch');
    const productResults = document.getElementById('productResults');
    const selectedProducts = document.getElementById('selectedProducts');
    const selectedEmptyHint = document.getElementById('selectedEmptyHint');

    const STATUS_BADGE = { active: 'badge-success', inactive: 'badge-neutral' };

    let currentPage = 1;
    let searchDebounce = null;
    let productSearchDebounce = null;
    let selected = new Map(); // id -> { id, name, thumbnail_url, regular_price }

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
        return new Date(value).toLocaleString(undefined, { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function toDatetimeLocal(value) {
        if (!value) return '';
        const d = new Date(value);
        const pad = n => String(n).padStart(2, '0');
        return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    }

    function statusBadge(status) {
        const cls = STATUS_BADGE[status] ?? 'badge-neutral';
        return `<span class="badge ${cls}">${status === 'active' ? 'Active' : 'Inactive'}</span>`;
    }

    // ─── List ────────────────────────────────────────────────────────────
    async function loadSales(page = 1) {
        currentPage = page;

        tableBody.innerHTML = `<tr><td colspan="6" class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading sales…</td></tr>`;

        const params = new URLSearchParams({ page });
        if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
        if (statusFilter.value) params.set('status', statusFilter.value);

        try {
            const response = await fetch('/api/admin/flash-sales?' + params.toString(), { headers: authHeaders() });
            const payload = await response.json();

            if (!payload.status) {
                tableBody.innerHTML = `<tr><td colspan="6" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load sales.</td></tr>`;
                return;
            }

            renderRows(payload.data?.data ?? []);
            renderPagination(payload.data);
        } catch (err) {
            tableBody.innerHTML = `<tr><td colspan="6" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load sales.</td></tr>`;
        }
    }

    function renderRows(sales) {
        if (!sales.length) {
            tableBody.innerHTML = `<tr><td colspan="6" class="table-empty"><i class="fa-solid fa-bolt"></i> No flash sales yet.</td></tr>`;
            return;
        }

        tableBody.innerHTML = sales.map(sale => `
            <tr data-row-id="${sale.id}">
                <td><strong>${escapeHtml(sale.name)}</strong></td>
                <td><span class="sale-count-badge"><i class="fa-solid fa-box"></i> ${sale.products_count ?? 0}</span></td>
                <td><span class="sale-window">${formatDate(sale.starts_at)}</span></td>
                <td><span class="sale-window">${formatDate(sale.ends_at)}</span></td>
                <td>${statusBadge(sale.effective_status === 'ended' ? 'inactive' : sale.status)}${sale.effective_status === 'ended' ? ' <span class="form-help" style="margin:0;">Ended</span>' : ''}</td>
                <td>
                    <div class="table-actions">
                        <button type="button" class="btn-icon" title="Edit" data-edit-id="${sale.id}">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button type="button" class="btn-icon btn-icon-danger" title="Delete" data-delete-id="${sale.id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        tableBody.querySelectorAll('[data-edit-id]').forEach(btn => {
            btn.addEventListener('click', () => openDrawer(btn.dataset.editId));
        });
        tableBody.querySelectorAll('[data-delete-id]').forEach(btn => {
            btn.addEventListener('click', () => deleteSale(btn.dataset.deleteId, btn));
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

        document.getElementById('prevPage')?.addEventListener('click', () => loadSales(currentPage - 1));
        document.getElementById('nextPage')?.addEventListener('click', () => loadSales(currentPage + 1));
    }

    // ─── Product picker ─────────────────────────────────────────────────
    function renderSelected() {
        if (!selected.size) {
            selectedProducts.innerHTML = '<p class="form-help" id="selectedEmptyHint" style="margin:0.5rem 0;">No products added yet.</p>';
            return;
        }

        selectedProducts.innerHTML = Array.from(selected.values()).map(p => `
            <div class="sale-selected-row" data-selected-id="${p.id}">
                ${p.thumbnail_url ? `<img src="${p.thumbnail_url}" class="sale-selected-thumb" alt="">` : `<div class="sale-selected-thumb"></div>`}
                <span class="sale-selected-name">${escapeHtml(p.name)}</span>
                <span class="form-help" style="margin:0;">${formatMoney(p.sale_price ?? p.regular_price)}</span>
                <button type="button" class="sale-selected-remove" data-remove-selected="${p.id}" title="Remove">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        `).join('');

        selectedProducts.querySelectorAll('[data-remove-selected]').forEach(btn => {
            btn.addEventListener('click', () => {
                selected.delete(parseInt(btn.dataset.removeSelected));
                renderSelected();
                renderProductResults(lastResults);
            });
        });
    }

    let lastResults = [];

    function renderProductResults(products) {
        lastResults = products;

        if (!products.length) {
            productResults.innerHTML = productSearch.value.trim()
                ? `<div class="sale-result-row" style="cursor:default;"><span class="form-help" style="margin:0;">No products match.</span></div>`
                : '';
            return;
        }

        productResults.innerHTML = products.map(p => {
            const added = selected.has(p.id);
            return `
                <div class="sale-result-row ${added ? 'is-added' : ''}" data-product-id="${p.id}">
                    ${p.thumbnail_url ? `<img src="${p.thumbnail_url}" class="sale-result-thumb" alt="">` : `<div class="sale-result-thumb"></div>`}
                    <span class="sale-result-name">${escapeHtml(p.name)}</span>
                    <span class="sale-result-price">${formatMoney(p.sale_price ?? p.regular_price)}</span>
                    ${added ? '<i class="fa-solid fa-check" style="color:var(--color-moringa-600);"></i>' : '<i class="fa-solid fa-plus"></i>'}
                </div>
            `;
        }).join('');

        productResults.querySelectorAll('[data-product-id]').forEach(row => {
            row.addEventListener('click', () => {
                const id = parseInt(row.dataset.productId);
                if (selected.has(id)) return;

                const product = products.find(p => p.id === id);
                if (!product) return;

                selected.set(id, {
                    id: product.id, name: product.name, thumbnail_url: product.thumbnail_url,
                    regular_price: product.regular_price, sale_price: product.sale_price,
                });
                renderSelected();
                renderProductResults(products);
            });
        });
    }

    async function searchProducts(query) {
        try {
            const params = new URLSearchParams({ per_page: 8 });
            if (query) params.set('search', query);

            const response = await fetch('/api/products?' + params.toString(), { headers: authHeaders() });
            const payload = await response.json();
            renderProductResults(payload.data?.data ?? []);
        } catch (err) {
            productResults.innerHTML = '';
        }
    }

    productSearch.addEventListener('input', () => {
        clearTimeout(productSearchDebounce);
        productSearchDebounce = setTimeout(() => searchProducts(productSearch.value.trim()), 350);
    });

    // ─── Drawer ──────────────────────────────────────────────────────────
    function openDrawer(id = null) {
        form.reset();
        document.getElementById('saleId').value = id ?? '';
        document.querySelectorAll('.form-error').forEach(el => el.textContent = '');
        selected = new Map();
        productSearch.value = '';
        productResults.innerHTML = '';
        renderSelected();
        deleteBtn.style.display = id ? 'inline-flex' : 'none';
        document.getElementById('status').value = 'active';

        drawerTitle.textContent = id ? 'Edit Sale' : 'Add Sale';
        drawer.classList.add('is-open');
        drawerOverlay.classList.add('is-open');

        if (id) fetchSale(id);
    }

    function closeDrawer() {
        drawer.classList.remove('is-open');
        drawerOverlay.classList.remove('is-open');
    }

    closeDrawerBtn.addEventListener('click', closeDrawer);
    drawerOverlay.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && drawer.classList.contains('is-open')) closeDrawer(); });
    document.getElementById('addSaleBtn').addEventListener('click', () => openDrawer());

    async function fetchSale(id) {
        try {
            const response = await fetch('/api/admin/flash-sales/' + id, { headers: authHeaders() });
            const payload = await response.json();
            if (!payload.status) return;

            const sale = payload.data;
            document.getElementById('saleName').value = sale.name;
            document.getElementById('startsAt').value = toDatetimeLocal(sale.starts_at);
            document.getElementById('endsAt').value = toDatetimeLocal(sale.ends_at);
            document.getElementById('status').value = sale.status;

            (sale.products ?? []).forEach(p => {
                selected.set(p.id, {
                    id: p.id, name: p.name, thumbnail_url: p.thumbnail_url,
                    regular_price: p.regular_price, sale_price: p.sale_price,
                });
            });
            renderSelected();
        } catch (err) {
            // Leave the drawer as-is — the form just stays empty.
        }
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (saveBtn.disabled) return;

        document.querySelectorAll('.form-error').forEach(el => el.textContent = '');

        const id = document.getElementById('saleId').value;

        if (!selected.size) {
            document.querySelector('[data-error-for="product_ids"]').textContent = 'Add at least one product.';
            return;
        }

        saveBtn.disabled = true;
        saveBtnText.textContent = 'Saving…';

        const body = {
            name: document.getElementById('saleName').value,
            starts_at: document.getElementById('startsAt').value || null,
            ends_at: document.getElementById('endsAt').value,
            status: document.getElementById('status').value,
            product_ids: Array.from(selected.keys()),
        };

        try {
            const response = await fetch(id ? '/api/admin/flash-sales/' + id : '/api/admin/flash-sales', {
                method: id ? 'PUT' : 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', ...authHeaders() },
                body: JSON.stringify(body),
            });
            const data = await response.json();

            if (data.status) {
                closeDrawer();
                loadSales(currentPage);
            } else if (data.errors) {
                Object.entries(data.errors).forEach(([field, messages]) => {
                    const el = document.querySelector(`[data-error-for="${field}"]`) ?? document.querySelector(`[data-error-for="${field.split('.')[0]}"]`);
                    if (el) el.textContent = messages[0];
                });
            } else {
                alert(data.message || 'Could not save sale.');
            }
        } catch (err) {
            alert('Something went wrong. Please try again.');
        } finally {
            saveBtn.disabled = false;
            saveBtnText.textContent = 'Save Sale';
        }
    });

    deleteBtn.addEventListener('click', () => {
        const id = document.getElementById('saleId').value;
        if (id) deleteSale(id, deleteBtn);
    });

    async function deleteSale(id, btn) {
        if (btn.disabled) return;
        if (!confirm('Delete this sale? This cannot be undone.')) return;

        btn.disabled = true;

        try {
            const response = await fetch('/api/admin/flash-sales/' + id, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', ...authHeaders() },
            });
            const data = await response.json();

            if (data.status) {
                closeDrawer();
                document.querySelector(`tr[data-row-id="${id}"]`)?.remove();
                if (!tableBody.children.length) loadSales(currentPage);
            } else {
                alert(data.message || 'Could not delete sale.');
                btn.disabled = false;
            }
        } catch (err) {
            alert('Something went wrong. Please try again.');
            btn.disabled = false;
        }
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => loadSales(1), 400);
    });
    statusFilter.addEventListener('change', () => loadSales(1));

    loadSales(1);
})();
</script>
@endpush
