@extends('layouts.app')

@section('title', 'Referrals')
@section('page-title', 'Referrals')
@section('page-subtitle', 'See who referred whom, and track reward payouts')

@section('content')

<div class="ref-stat-row">
    <div class="admin-card ref-stat-card">
        <p class="ref-stat-value" id="statTotal">—</p>
        <p class="ref-stat-label">Total Referrals</p>
    </div>
    <div class="admin-card ref-stat-card">
        <p class="ref-stat-value" id="statPending">—</p>
        <p class="ref-stat-label">Pending</p>
    </div>
    <div class="admin-card ref-stat-card">
        <p class="ref-stat-value" id="statCompleted">—</p>
        <p class="ref-stat-label">Rewarded</p>
    </div>
</div>

<div class="admin-card" style="margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
    <div>
        <p class="card-subtitle" style="margin-bottom:0.2rem;">Current reward rule</p>
        <p class="card-title" style="margin-bottom:0; font-size:1rem;" id="currentRewardRule">Loading…</p>
    </div>
    <a href="{{ route('admin.settings') }}" class="btn btn-ghost"><i class="fa-solid fa-pen"></i> Change</a>
</div>

<div class="admin-card">

    <div class="card-header">

        <div>
            <p class="card-title">Referrals</p>
            <p class="card-subtitle">A referral is logged when someone signs up with a customer's referral code</p>
        </div>

        <div class="table-toolbar">

            <div class="table-search">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                <input type="text" id="search" placeholder="Search by name, email or code…">
            </div>

            <select id="statusFilter" class="form-select" style="width: auto;">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="completed">Rewarded</option>
            </select>

            <a href="{{ route('admin.settings') }}" class="btn btn-ghost">
                <i class="fa-solid fa-gear"></i> Configure Reward Amount
            </a>

        </div>

    </div>

    <div class="table-scroll">

        <table class="admin-table" id="referralTable">

            <thead>
                <tr>
                    <th>Referrer</th>
                    <th>Referred Customer</th>
                    <th>Code Used</th>
                    <th>Status</th>
                    <th>Reward Coupon</th>
                    <th>Date</th>
                    <th width="70">Action</th>
                </tr>
            </thead>

            <tbody id="referralTableBody">
                <tr>
                    <td colspan="7" class="table-empty">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        Loading referrals…
                    </td>
                </tr>
            </tbody>

        </table>

    </div>

    <div class="admin-pagination" id="pagination"></div>

</div>

<div class="ref-drawer-overlay" id="drawerOverlay"></div>

<aside class="ref-drawer" id="referralDrawer">
    <div class="ref-drawer-header">
        <p class="card-title">Referral Details</p>
        <button type="button" class="btn-icon" id="closeDrawerBtn" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="ref-drawer-body" id="drawerBody">
        <div class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading…</div>
    </div>
</aside>

@endsection

@push('styles')
<style>
    .admin-pagination { display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; margin-top: 1.25rem; }

    .ref-stat-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
    @media (max-width: 640px) { .ref-stat-row { grid-template-columns: 1fr; } }
    .ref-stat-card { padding: 1.1rem 1.25rem; }
    .ref-stat-value { font-family: var(--font-display); font-size: 1.6rem; font-weight: 700; color: var(--color-forest-950); }
    body[data-theme="dark"] .ref-stat-value { color: var(--color-cream-50); }
    .ref-stat-label { font-size: 0.78rem; color: color-mix(in srgb, var(--color-forest-700) 65%, transparent); margin-top: 0.15rem; }

    .ref-person-name { font-weight: 600; color: var(--color-forest-950); }
    body[data-theme="dark"] .ref-person-name { color: var(--color-cream-50); }
    .ref-person-email { font-size: 0.72rem; color: color-mix(in srgb, var(--color-forest-700) 65%, transparent); }

    .ref-code-chip {
        display: inline-block; padding: 0.15rem 0.55rem; border-radius: 0.4rem; font-size: 0.78rem; font-weight: 700;
        letter-spacing: 0.03em; background: color-mix(in srgb, var(--color-moringa-200) 45%, transparent); color: var(--color-forest-900);
    }
    body[data-theme="dark"] .ref-code-chip { background: color-mix(in srgb, var(--color-moringa-700) 35%, transparent); color: var(--color-cream-50); }

    .ref-drawer-overlay {
        position: fixed; inset: 0; background: rgba(15, 23, 20, 0.45);
        opacity: 0; pointer-events: none; transition: opacity 0.25s ease; z-index: 40;
    }
    .ref-drawer-overlay.is-open { opacity: 1; pointer-events: auto; }

    .ref-drawer {
        position: fixed; top: 0; right: 0; height: 100vh; width: min(26rem, 100vw);
        background: var(--color-cream-50);
        box-shadow: var(--shadow-panel);
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 41; display: flex; flex-direction: column; overflow-y: auto;
    }
    body[data-theme="dark"] .ref-drawer { background: var(--color-forest-900); }
    .ref-drawer.is-open { transform: translateX(0); }

    .ref-drawer-header {
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        padding: 1.25rem 1.5rem; border-bottom: 1px solid color-mix(in srgb, var(--color-forest-900) 8%, transparent);
    }
    body[data-theme="dark"] .ref-drawer-header { border-bottom-color: color-mix(in srgb, var(--color-moringa-200) 10%, transparent); }
    .ref-drawer-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.5rem; }

    .ref-section-title {
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;
        color: color-mix(in srgb, var(--color-forest-700) 70%, transparent); margin-bottom: 0.6rem;
    }
    body[data-theme="dark"] .ref-section-title { color: color-mix(in srgb, var(--color-moringa-200) 70%, transparent); }

    .ref-info-card {
        border: 1px solid color-mix(in srgb, var(--color-forest-900) 8%, transparent);
        border-radius: 0.65rem; padding: 0.85rem; font-size: 0.85rem; line-height: 1.5;
    }
    body[data-theme="dark"] .ref-info-card { border-color: color-mix(in srgb, var(--color-moringa-200) 10%, transparent); }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const tableBody = document.getElementById('referralTableBody');
    const pagination = document.getElementById('pagination');
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('statusFilter');

    const drawer = document.getElementById('referralDrawer');
    const drawerOverlay = document.getElementById('drawerOverlay');
    const drawerBody = document.getElementById('drawerBody');
    const closeDrawerBtn = document.getElementById('closeDrawerBtn');

    const STATUS_LABELS = { pending: 'Pending', completed: 'Rewarded' };
    const STATUS_BADGE = { pending: 'badge-warning', completed: 'badge-success' };

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

    function formatDate(value) {
        if (!value) return '—';
        return new Date(value).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
    }

    function statusBadge(status) {
        return `<span class="badge ${STATUS_BADGE[status] ?? 'badge-neutral'}">${STATUS_LABELS[status] ?? status}</span>`;
    }

    function personCell(user) {
        if (!user) return '<span class="form-help" style="margin:0;">—</span>';
        return `
            <div class="ref-person-name">${escapeHtml(user.name)}</div>
            <div class="ref-person-email">${escapeHtml(user.email)}</div>
        `;
    }

    // ─── List ────────────────────────────────────────────────────────────────
    async function loadReferrals(page = 1) {
        currentPage = page;

        tableBody.innerHTML = `<tr><td colspan="7" class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading referrals…</td></tr>`;

        const params = new URLSearchParams({ page });
        if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
        if (statusFilter.value) params.set('status', statusFilter.value);

        try {
            const response = await fetch('/api/admin/referrals?' + params.toString(), { headers: authHeaders() });
            const payload = await response.json();

            if (!payload.success) {
                tableBody.innerHTML = `<tr><td colspan="7" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load referrals.</td></tr>`;
                return;
            }

            document.getElementById('statTotal').textContent = payload.summary?.total ?? 0;
            document.getElementById('statPending').textContent = payload.summary?.pending ?? 0;
            document.getElementById('statCompleted').textContent = payload.summary?.completed ?? 0;

            renderRows(payload.data?.data ?? []);
            renderPagination(payload.data);
        } catch (err) {
            tableBody.innerHTML = `<tr><td colspan="7" class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load referrals.</td></tr>`;
        }
    }

    function renderRows(referrals) {
        if (!referrals.length) {
            tableBody.innerHTML = `<tr><td colspan="7" class="table-empty"><i class="fa-solid fa-user-plus"></i> No referrals yet.</td></tr>`;
            return;
        }

        tableBody.innerHTML = referrals.map(referral => `
            <tr data-row-id="${referral.id}">
                <td>${personCell(referral.referrer)}</td>
                <td>${personCell(referral.referred)}</td>
                <td><span class="ref-code-chip">${escapeHtml(referral.referral_code)}</span></td>
                <td>${statusBadge(referral.status)}</td>
                <td>${referral.reward_offer ? `<span class="ref-code-chip">${escapeHtml(referral.reward_offer.code)}</span>` : '<span class="form-help" style="margin:0;">—</span>'}</td>
                <td>${formatDate(referral.created_at)}</td>
                <td>
                    <div class="table-actions">
                        <button type="button" class="btn-icon" title="View" data-view-id="${referral.id}">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        tableBody.querySelectorAll('[data-view-id]').forEach(btn => {
            btn.addEventListener('click', () => openDrawer(btn.dataset.viewId));
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

        document.getElementById('prevPage')?.addEventListener('click', () => loadReferrals(currentPage - 1));
        document.getElementById('nextPage')?.addEventListener('click', () => loadReferrals(currentPage + 1));
    }

    // ─── Drawer ──────────────────────────────────────────────────────────────
    function openDrawer(id) {
        drawer.classList.add('is-open');
        drawerOverlay.classList.add('is-open');
        drawerBody.innerHTML = `<div class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading…</div>`;

        fetchReferral(id);
    }

    function closeDrawer() {
        drawer.classList.remove('is-open');
        drawerOverlay.classList.remove('is-open');
    }

    closeDrawerBtn.addEventListener('click', closeDrawer);
    drawerOverlay.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

    async function fetchReferral(id) {
        try {
            const response = await fetch('/api/admin/referrals/' + id, { headers: authHeaders() });
            const payload = await response.json();

            if (!payload.success) {
                drawerBody.innerHTML = `<div class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load referral.</div>`;
                return;
            }

            renderDrawer(payload.data);
        } catch (err) {
            drawerBody.innerHTML = `<div class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load referral.</div>`;
        }
    }

    function renderDrawer(referral) {
        const reward = referral.reward_offer;

        const rewardHtml = referral.status === 'completed' && reward ? `
            <div class="ref-info-card">
                <div><strong>Code:</strong> <span class="ref-code-chip">${escapeHtml(reward.code)}</span></div>
                <div><strong>Discount:</strong> ${reward.discount_type === 'percentage' ? parseFloat(reward.discount_value) + '%' : '₹' + parseFloat(reward.discount_value).toFixed(2)}</div>
                <div><strong>Valid Until:</strong> ${formatDate(reward.ends_at)}</div>
            </div>
        ` : `
            <p class="form-help">Reward will be issued automatically once this customer completes their first delivered order.</p>
        `;

        drawerBody.innerHTML = `
            <div>
                <p class="ref-section-title">Status</p>
                ${statusBadge(referral.status)}
            </div>

            <div>
                <p class="ref-section-title">Referrer</p>
                <div class="ref-info-card">
                    ${personCell(referral.referrer)}
                    <div style="margin-top:0.4rem;">Referral code: <span class="ref-code-chip">${escapeHtml(referral.referrer?.referral_code)}</span></div>
                </div>
            </div>

            <div>
                <p class="ref-section-title">Referred Customer</p>
                <div class="ref-info-card">
                    ${personCell(referral.referred)}
                    <div style="margin-top:0.4rem;">Joined: ${formatDate(referral.referred?.created_at)}</div>
                </div>
            </div>

            <div>
                <p class="ref-section-title">Reward</p>
                ${rewardHtml}
            </div>

            <div>
                <p class="ref-section-title">Timeline</p>
                <p class="form-help" style="margin:0;">Referred on ${formatDate(referral.created_at)}</p>
                ${referral.completed_at ? `<p class="form-help" style="margin:0;">Rewarded on ${formatDate(referral.completed_at)}</p>` : ''}
            </div>
        `;
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => loadReferrals(1), 400);
    });
    statusFilter.addEventListener('change', () => loadReferrals(1));

    async function loadCurrentRewardRule() {
        const el = document.getElementById('currentRewardRule');

        try {
            const response = await fetch('/api/admin/settings', { headers: authHeaders() });
            const payload = await response.json();
            const s = payload.data;

            if (!payload.status || !s) {
                el.textContent = 'Could not load — open Settings to view.';
                return;
            }

            const value = s.referral_discount_type === 'percentage'
                ? `${parseFloat(s.referral_discount_value)}%${s.referral_max_discount_amount ? ' (max ₹' + parseFloat(s.referral_max_discount_amount).toFixed(2) + ')' : ''}`
                : `₹${parseFloat(s.referral_discount_value).toFixed(2)}`;

            el.textContent = `${value} off, valid ${s.referral_validity_days} days after being earned`;
        } catch (err) {
            el.textContent = 'Could not load — open Settings to view.';
        }
    }

    loadReferrals(1);
    loadCurrentRewardRule();
})();
</script>
@endpush
