@extends('layouts.app')

@section('title', 'Offers & Discounts')
@section('page-title', 'Offers & Discounts')
@section('page-subtitle', 'Manage coupon codes and automatic discounts')

@section('content')

<div class="admin-card">

    <div class="card-header">

        <div>
            <p class="card-title">Offers</p>
            <p class="card-subtitle">Coupon codes and automatic product/category discounts</p>
        </div>

        <div class="table-toolbar">

            <div class="table-search">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                <input type="text" id="search" placeholder="Search by title or code…">
            </div>

            <select id="typeFilter" class="form-select" style="width: auto;">
                <option value="">All Types</option>
                <option value="coupon">Coupon Code</option>
                <option value="auto_discount">Auto Discount</option>
            </select>

            <select id="statusFilter" class="form-select" style="width: auto;">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

            <button type="button" id="addOfferBtn" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Add Offer
            </button>

        </div>

    </div>

    <div class="table-scroll">

        <table class="admin-table" id="offerTable">

            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Code / Applies To</th>
                    <th>Discount</th>
                    <th>Validity</th>
                    <th>Status</th>
                    <th width="100">Action</th>
                </tr>
            </thead>

            <tbody id="offerTableBody">
                <tr>
                    <td colspan="7" class="table-empty">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        Loading offers…
                    </td>
                </tr>
            </tbody>

        </table>

    </div>

    <div class="admin-pagination" id="pagination"></div>

</div>

<div class="offer-drawer-overlay" id="drawerOverlay"></div>

<aside class="offer-drawer" id="offerDrawer">
    <div class="offer-drawer-header">
        <p class="card-title" id="drawerTitle">Add Offer</p>
        <button type="button" class="btn-icon" id="closeDrawerBtn" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="offer-drawer-body">
        <form id="offerForm">
            <input type="hidden" id="offerId">

            <div class="form-group-full" style="margin-bottom:1rem;">
                <label class="form-label" for="title">Title<span class="form-required">*</span></label>
                <input type="text" id="title" class="form-input" placeholder="e.g. Summer Sale 20%" required>
                <p class="form-error" data-error-for="title"></p>
            </div>

            <div class="form-group-full" style="margin-bottom:1rem;">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" class="form-textarea" rows="2" placeholder="Optional, shown to customers"></textarea>
            </div>

            <div class="form-group-full" style="margin-bottom:1rem;">
                <label class="form-label" for="offerType">Offer Type<span class="form-required">*</span></label>
                <select id="offerType" class="form-select">
                    <option value="coupon">Coupon Code</option>
                    <option value="auto_discount">Automatic Discount</option>
                </select>
                <p class="form-help">Coupon codes are entered by the customer. Automatic discounts apply on their own.</p>
            </div>

            <div class="form-group-full" style="margin-bottom:1rem;" id="codeField">
                <label class="form-label" for="code">Coupon Code<span class="form-required">*</span></label>
                <input type="text" id="code" class="form-input" placeholder="e.g. SAVE20" style="text-transform:uppercase;">
                <p class="form-error" data-error-for="code"></p>
            </div>

            <div class="form-grid" style="margin-bottom:1rem;">
                <div class="form-group-half">
                    <label class="form-label" for="discountType">Discount Type<span class="form-required">*</span></label>
                    <select id="discountType" class="form-select">
                        <option value="percentage">Percentage</option>
                        <option value="flat">Flat Amount</option>
                    </select>
                </div>
                <div class="form-group-half">
                    <label class="form-label" for="discountValue">Discount Value<span class="form-required">*</span></label>
                    <input type="number" step="0.01" min="0" id="discountValue" class="form-input">
                    <p class="form-error" data-error-for="discount_value"></p>
                </div>
            </div>

            <div class="form-group-full" style="margin-bottom:1rem;" id="maxDiscountField">
                <label class="form-label" for="maxDiscountAmount">Max Discount Amount</label>
                <input type="number" step="0.01" min="0" id="maxDiscountAmount" class="form-input" placeholder="Optional cap, e.g. 200">
                <p class="form-help">Caps how much a percentage discount can take off in rupees.</p>
            </div>

            <div class="form-group-full" style="margin-bottom:1rem;">
                <label class="form-label" for="minOrderAmount">Minimum Order Amount</label>
                <input type="number" step="0.01" min="0" id="minOrderAmount" class="form-input" placeholder="Optional, e.g. 500">
            </div>

            <div class="form-group-full" style="margin-bottom:1rem;">
                <label class="form-label" for="offerScope">Applies To<span class="form-required">*</span></label>
                <select id="offerScope" class="form-select">
                    <option value="all">All Products</option>
                    <option value="category">Specific Category</option>
                    <option value="product">Specific Product</option>
                </select>
            </div>

            <div class="form-group-full" style="margin-bottom:1rem;" id="categoryField">
                <label class="form-label" for="categoryId">Category<span class="form-required">*</span></label>
                <select id="categoryId" class="form-select">
                    <option value="">Select category</option>
                </select>
                <p class="form-error" data-error-for="category_id"></p>
            </div>

            <div class="form-group-full" style="margin-bottom:1rem;" id="productField">
                <label class="form-label" for="productId">Product<span class="form-required">*</span></label>
                <select id="productId" class="form-select">
                    <option value="">Select product</option>
                </select>
                <p class="form-error" data-error-for="product_id"></p>
            </div>

            <div class="form-grid" style="margin-bottom:1rem;">
                <div class="form-group-half">
                    <label class="form-label" for="startsAt">Starts At</label>
                    <input type="datetime-local" id="startsAt" class="form-input">
                </div>
                <div class="form-group-half">
                    <label class="form-label" for="endsAt">Ends At</label>
                    <input type="datetime-local" id="endsAt" class="form-input">
                    <p class="form-error" data-error-for="ends_at"></p>
                </div>
            </div>
            <p class="form-help" style="margin-top:-0.5rem; margin-bottom:1rem;">Leave both blank for an offer with no expiry.</p>

            <div class="form-group-full" style="margin-bottom:1.5rem;">
                <label class="form-label" for="status">Status<span class="form-required">*</span></label>
                <select id="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div style="display:flex; gap:0.75rem;">
                <button type="submit" id="saveOfferBtn" class="btn btn-primary" style="flex:1;">
                    <span id="saveOfferBtnText">Save Offer</span>
                </button>
                <button type="button" id="deleteOfferBtn" class="btn btn-danger" style="display:none;">
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

    .offer-code-chip {
        display: inline-block; padding: 0.15rem 0.55rem; border-radius: 0.4rem; font-size: 0.78rem; font-weight: 700;
        letter-spacing: 0.03em; background: color-mix(in srgb, var(--color-moringa-200) 45%, transparent); color: var(--color-forest-900);
    }
    body[data-theme="dark"] .offer-code-chip { background: color-mix(in srgb, var(--color-moringa-700) 35%, transparent); color: var(--color-cream-50); }
    .offer-scope-text { font-size: 0.82rem; color: color-mix(in srgb, var(--color-forest-700) 75%, transparent); }
    body[data-theme="dark"] .offer-scope-text { color: color-mix(in srgb, var(--color-moringa-200) 75%, transparent); }
    .offer-validity { font-size: 0.78rem; color: color-mix(in srgb, var(--color-forest-700) 65%, transparent); }

    .offer-drawer-overlay {
        position: fixed; inset: 0; background: rgba(15, 23, 20, 0.45);
        opacity: 0; pointer-events: none; transition: opacity 0.25s ease; z-index: 40;
    }
    .offer-drawer-overlay.is-open { opacity: 1; pointer-events: auto; }

    .offer-drawer {
        position: fixed; top: 0; right: 0; height: 100vh; width: min(28rem, 100vw);
        background: var(--color-cream-50);
        box-shadow: var(--shadow-panel);
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 41; display: flex; flex-direction: column; overflow-y: auto;
    }
    body[data-theme="dark"] .offer-drawer { background: var(--color-forest-900); }
    .offer-drawer.is-open { transform: translateX(0); }

    .offer-drawer-header {
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        padding: 1.25rem 1.5rem; border-bottom: 1px solid color-mix(in srgb, var(--color-forest-900) 8%, transparent);
    }
    body[data-theme="dark"] .offer-drawer-header { border-bottom-color: color-mix(in srgb, var(--color-moringa-200) 10%, transparent); }
    .offer-drawer-body { padding: 1.5rem; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const tableBody = document.getElementById('offerTableBody');
    const pagination = document.getElementById('pagination');
    const searchInput = document.getElementById('search');
    const typeFilter = document.getElementById('typeFilter');
    const statusFilter = document.getElementById('statusFilter');

    const drawer = document.getElementById('offerDrawer');
    const drawerOverlay = document.getElementById('drawerOverlay');
    const drawerTitle = document.getElementById('drawerTitle');
    const closeDrawerBtn = document.getElementById('closeDrawerBtn');
    const addOfferBtn = document.getElementById('addOfferBtn');

    const form = document.getElementById('offerForm');
    const offerIdInput = document.getElementById('offerId');
    const saveBtn = document.getElementById('saveOfferBtn');
    const saveBtnText = document.getElementById('saveOfferBtnText');
    const deleteBtn = document.getElementById('deleteOfferBtn');

    const offerTypeSelect = document.getElementById('offerType');
    const discountTypeSelect = document.getElementById('discountType');
    const offerScopeSelect = document.getElementById('offerScope');
    const codeField = document.getElementById('codeField');
    const maxDiscountField = document.getElementById('maxDiscountField');
    const categoryField = document.getElementById('categoryField');
    const productField = document.getElementById('productField');
    const categorySelect = document.getElementById('categoryId');
    const productSelect = document.getElementById('productId');

    const TYPE_LABELS = { coupon: 'Coupon Code', auto_discount: 'Auto Discount' };
    const STATUS_LABELS = { active: 'Active', scheduled: 'Scheduled', expired: 'Expired', inactive: 'Inactive' };
    const STATUS_BADGE = { active: 'badge-success', scheduled: 'badge-warning', expired: 'badge-danger', inactive: 'badge-neutral' };

    let currentPage = 1;
    let searchDebounce = null;
    let optionsLoaded = false;

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

    function toDatetimeLocal(value) {
        if (!value) return '';
        const date = new Date(value);
        const pad = n => String(n).padStart(2, '0');
        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    }

    function statusBadge(status) {
        return `<span class="badge ${STATUS_BADGE[status] ?? 'badge-neutral'}">${STATUS_LABELS[status] ?? status}</span>`;
    }

    function discountLabel(offer) {
        if (offer.discount_type === 'percentage') {
            const cap = offer.max_discount_amount ? ` (max ${formatMoney(offer.max_discount_amount)})` : '';
            return `${parseFloat(offer.discount_value)}%${cap}`;
        }
        return formatMoney(offer.discount_value);
    }

    function scopeLabel(offer) {
        if (offer.type === 'coupon') {
            return `<span class="offer-code-chip">${escapeHtml(offer.code)}</span>`;
        }
        if (offer.scope === 'category') return `<span class="offer-scope-text">Category: ${escapeHtml(offer.category?.name ?? '—')}</span>`;
        if (offer.scope === 'product') return `<span class="offer-scope-text">Product: ${escapeHtml(offer.product?.name ?? '—')}</span>`;
        return `<span class="offer-scope-text">All Products</span>`;
    }

    function validityLabel(offer) {
        if (!offer.starts_at && !offer.ends_at) return '<span class="offer-validity">No expiry</span>';
        const start = offer.starts_at ? formatDate(offer.starts_at) : 'Now';
        const end = offer.ends_at ? formatDate(offer.ends_at) : 'No end';
        return `<span class="offer-validity">${start} → ${end}</span>`;
    }

    // ─── Category / Product selects ─────────────────────────────────────────
    async function loadOptions(url, select) {
        try {
            const response = await fetch(url, { headers: authHeaders() });
            const payload = await response.json();

            (payload.data?.data ?? []).forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                select.appendChild(option);
            });
        } catch (err) {
            // Non-critical — dropdown just stays empty
        }
    }

    async function ensureOptionsLoaded() {
        if (optionsLoaded) return;
        optionsLoaded = true;
        await Promise.all([
            loadOptions('/api/categories', categorySelect),
            loadOptions('/api/products', productSelect),
        ]);
    }

    // ─── List ────────────────────────────────────────────────────────────────
    async function loadOffers(page = 1) {
        currentPage = page;

        tableBody.innerHTML = `<tr><td colspan="7" class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading offers…</td></tr>`;

        const params = new URLSearchParams({ page });
        if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
        if (typeFilter.value) params.set('type', typeFilter.value);
        if (statusFilter.value) params.set('status', statusFilter.value);

        try {
            const response = await fetch('/api/admin/offers?' + params.toString(), { headers: authHeaders() });
            const payload = await response.json();

            if (!payload.status) {
                tableBody.innerHTML = `<tr><td colspan="7" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load offers.</td></tr>`;
                return;
            }

            renderRows(payload.data?.data ?? []);
            renderPagination(payload.data);
        } catch (err) {
            tableBody.innerHTML = `<tr><td colspan="7" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load offers.</td></tr>`;
        }
    }

    function renderRows(offers) {
        if (!offers.length) {
            tableBody.innerHTML = `<tr><td colspan="7" class="table-empty"><i class="fa-solid fa-tags"></i> No offers yet.</td></tr>`;
            return;
        }

        tableBody.innerHTML = offers.map(offer => `
            <tr data-row-id="${offer.id}">
                <td style="font-weight:600;">${escapeHtml(offer.title)}</td>
                <td>${TYPE_LABELS[offer.type] ?? offer.type}</td>
                <td>${scopeLabel(offer)}</td>
                <td>${discountLabel(offer)}</td>
                <td>${validityLabel(offer)}</td>
                <td>${statusBadge(offer.effective_status)}</td>
                <td>
                    <div class="table-actions">
                        <button type="button" class="btn-icon" title="Edit" data-edit-id="${offer.id}">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button type="button" class="btn-icon btn-icon-danger" title="Delete" data-delete-id="${offer.id}">
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
            btn.addEventListener('click', () => deleteOffer(btn.dataset.deleteId, btn));
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

        document.getElementById('prevPage')?.addEventListener('click', () => loadOffers(currentPage - 1));
        document.getElementById('nextPage')?.addEventListener('click', () => loadOffers(currentPage + 1));
    }

    async function deleteOffer(id, btn) {
        if (btn.disabled) return;
        if (!confirm('Delete this offer? This cannot be undone.')) return;

        btn.disabled = true;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

        try {
            const response = await fetch('/api/admin/offers/' + id, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', ...authHeaders() },
            });
            const data = await response.json();

            if (data.status) {
                document.querySelector(`tr[data-row-id="${id}"]`)?.remove();
                if (offerIdInput.value === String(id)) closeDrawer();
                if (!tableBody.children.length) loadOffers(currentPage);
            } else {
                alert(data.message || 'Could not delete offer.');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        } catch (err) {
            alert('Something went wrong. Please try again.');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }

    // ─── Drawer field visibility ─────────────────────────────────────────────
    function syncFieldVisibility() {
        codeField.style.display = offerTypeSelect.value === 'coupon' ? '' : 'none';
        maxDiscountField.style.display = discountTypeSelect.value === 'percentage' ? '' : 'none';
        categoryField.style.display = offerScopeSelect.value === 'category' ? '' : 'none';
        productField.style.display = offerScopeSelect.value === 'product' ? '' : 'none';
    }

    offerTypeSelect.addEventListener('change', syncFieldVisibility);
    discountTypeSelect.addEventListener('change', syncFieldVisibility);
    offerScopeSelect.addEventListener('change', syncFieldVisibility);

    // ─── Drawer open/close ───────────────────────────────────────────────────
    function clearErrors() {
        form.querySelectorAll('.form-error').forEach(el => el.textContent = '');
    }

    function showErrors(errors) {
        let unmatchedShown = false;
        Object.entries(errors).forEach(([field, messages]) => {
            const el = form.querySelector(`[data-error-for="${field}"]`);
            if (el) {
                el.textContent = messages[0];
            } else if (!unmatchedShown) {
                alert(messages[0]);
                unmatchedShown = true;
            }
        });
    }

    function resetForm() {
        form.reset();
        offerIdInput.value = '';
        clearErrors();
        syncFieldVisibility();
    }

    async function openDrawer(id = null) {
        await ensureOptionsLoaded();
        resetForm();

        drawer.classList.add('is-open');
        drawerOverlay.classList.add('is-open');

        if (id) {
            drawerTitle.textContent = 'Edit Offer';
            deleteBtn.style.display = '';
            offerIdInput.value = id;
            await fetchOffer(id);
        } else {
            drawerTitle.textContent = 'Add Offer';
            deleteBtn.style.display = 'none';
        }
    }

    function closeDrawer() {
        drawer.classList.remove('is-open');
        drawerOverlay.classList.remove('is-open');
    }

    addOfferBtn.addEventListener('click', () => openDrawer());
    closeDrawerBtn.addEventListener('click', closeDrawer);
    drawerOverlay.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

    async function fetchOffer(id) {
        try {
            const response = await fetch('/api/admin/offers/' + id, { headers: authHeaders() });
            const payload = await response.json();

            if (!payload.status) {
                alert(payload.message || 'Offer not found.');
                closeDrawer();
                return;
            }

            const offer = payload.data;

            document.getElementById('title').value = offer.title ?? '';
            document.getElementById('description').value = offer.description ?? '';
            offerTypeSelect.value = offer.type;
            document.getElementById('code').value = offer.code ?? '';
            discountTypeSelect.value = offer.discount_type;
            document.getElementById('discountValue').value = offer.discount_value ?? '';
            document.getElementById('maxDiscountAmount').value = offer.max_discount_amount ?? '';
            document.getElementById('minOrderAmount').value = offer.min_order_amount ?? '';
            offerScopeSelect.value = offer.scope;
            categorySelect.value = offer.category_id ?? '';
            productSelect.value = offer.product_id ?? '';
            document.getElementById('startsAt').value = toDatetimeLocal(offer.starts_at);
            document.getElementById('endsAt').value = toDatetimeLocal(offer.ends_at);
            document.getElementById('status').value = offer.status;

            syncFieldVisibility();
        } catch (err) {
            alert('Could not load offer.');
            closeDrawer();
        }
    }

    // ─── Submit ──────────────────────────────────────────────────────────────
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (saveBtn.disabled) return;

        clearErrors();
        saveBtn.disabled = true;
        saveBtnText.textContent = 'Saving…';

        const id = offerIdInput.value;
        const payload = {
            title: document.getElementById('title').value,
            description: document.getElementById('description').value || null,
            type: offerTypeSelect.value,
            code: document.getElementById('code').value || null,
            discount_type: discountTypeSelect.value,
            discount_value: document.getElementById('discountValue').value,
            max_discount_amount: document.getElementById('maxDiscountAmount').value || null,
            min_order_amount: document.getElementById('minOrderAmount').value || null,
            scope: offerScopeSelect.value,
            category_id: categorySelect.value || null,
            product_id: productSelect.value || null,
            starts_at: document.getElementById('startsAt').value || null,
            ends_at: document.getElementById('endsAt').value || null,
            status: document.getElementById('status').value,
        };

        try {
            const url = id ? '/api/admin/offers/' + id : '/api/admin/offers';
            const response = await fetch(url, {
                method: id ? 'PUT' : 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    ...authHeaders(),
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json();

            if (response.status === 422) {
                showErrors(data.errors || {});
                return;
            }

            if (!data.status) {
                alert(data.message || 'Could not save offer.');
                return;
            }

            closeDrawer();
            loadOffers(currentPage);
        } catch (err) {
            alert('Something went wrong. Please try again.');
        } finally {
            saveBtn.disabled = false;
            saveBtnText.textContent = 'Save Offer';
        }
    });

    deleteBtn.addEventListener('click', function () {
        if (offerIdInput.value) deleteOffer(offerIdInput.value, this);
    });

    searchInput.addEventListener('input', () => {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => loadOffers(1), 400);
    });
    typeFilter.addEventListener('change', () => loadOffers(1));
    statusFilter.addEventListener('change', () => loadOffers(1));

    syncFieldVisibility();
    loadOffers(1);
})();
</script>
@endpush
