{{-- resources/views/layouts/navbar.blade.php
     Include with @include('layouts.navbar') inside the main column, next to
     @include('layouts.sidebar'). Requires admin.css + navbar.js via @vite. --}}
<header class="admin-navbar font-body">

    {{-- Mobile sidebar trigger --}}
    <button type="button" id="sidebar-mobile-btn" class="navbar-icon-btn lg:hidden" aria-label="Open sidebar">
        <i class="fa-solid fa-bars"></i>
    </button>

    {{-- Page title / breadcrumb slot --}}
    <div class="min-w-0 flex-1">
        <h1 class="truncate font-display text-lg font-semibold text-forest-950 dark:text-cream-50">
            @yield('page-title', 'Dashboard')
        </h1>
        <p class="hidden text-xs text-forest-700/70 dark:text-moringa-200/70 sm:block">
            @yield('page-subtitle', 'Welcome back — here’s what’s happening today.')
        </p>
    </div>

    {{-- Search --}}
    <div class="navbar-search hidden w-full max-w-xs md:flex">
        <i class="fa-solid fa-magnifying-glass text-sm text-forest-700/60 dark:text-moringa-200/60"></i>
        <input type="text" placeholder="Search orders, products…" aria-label="Search">
    </div>

    <div class="flex items-center gap-2">

        {{-- Theme toggle --}}
        <button type="button" id="theme-toggle-btn" class="theme-toggle" aria-label="Toggle dark mode">
            <span class="theme-toggle-knob">
                <i class="fa-solid fa-sun" id="theme-icon-light"></i>
                <i class="fa-solid fa-moon hidden" id="theme-icon-dark"></i>
            </span>
        </button>

        {{-- Notifications --}}
        <div class="relative" data-dropdown-root>
            <button type="button" class="navbar-icon-btn" data-dropdown-toggle aria-label="Notifications">
                <i class="fa-solid fa-bell"></i>
                <span class="navbar-dot has-pulse hidden" id="notifDot"></span>
            </button>
            <div class="navbar-dropdown" data-dropdown-panel>
                <div class="flex items-center justify-between gap-2 px-2 py-1.5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-forest-700/60 dark:text-moringa-200/60">
                        Notifications
                    </p>
                    <button type="button" id="notifMarkAllBtn" class="text-xs font-medium text-moringa-600 hover:underline dark:text-moringa-300">
                        Mark all read
                    </button>
                </div>
                <div id="notifList">
                    <p class="px-2 py-3 text-xs text-forest-700/60 dark:text-moringa-200/60">Loading…</p>
                </div>
            </div>
        </div>

        {{-- Profile --}}
        <div class="relative" data-dropdown-root>
            <button type="button" class="flex items-center gap-2 rounded-full py-1 pl-1 pr-2 transition-colors hover:bg-moringa-200/50 dark:hover:bg-moringa-700/40" data-dropdown-toggle>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-moringa-400 to-forest-600 text-sm font-semibold text-white">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                </span>
                <span class="hidden text-left sm:block">
                    <span class="block text-sm font-semibold leading-tight text-forest-950 dark:text-cream-50">
                        {{ auth()->user()?->name ?? 'Admin' }}
                    </span>
                    <span class="block text-[0.7rem] leading-tight text-forest-700/60 dark:text-moringa-200/60">Administrator</span>
                </span>
                <i class="fa-solid fa-chevron-down navbar-chevron text-xs text-forest-700/60 dark:text-moringa-200/60" data-dropdown-chevron></i>
            </button>
            <div class="navbar-dropdown" data-dropdown-panel>
                <a href="#" class="dropdown-item"><i class="fa-solid fa-user"></i><span>My Profile</span></a>
                <a href="#" class="dropdown-item"><i class="fa-solid fa-gear"></i><span>Account Settings</span></a>
                <hr class="my-1 border-forest-900/10 dark:border-moringa-200/10">
                <button type="button" id="logoutBtn" class="dropdown-item w-full text-left text-red-600">
                    <i class="fa-solid fa-right-from-bracket"></i><span>Log Out</span>
                </button>
            </div>
        </div>
    </div>
</header>
<script>
    document.getElementById('logoutBtn').addEventListener('click', async function () {

    if (this.disabled) return;
    this.disabled = true;

    const token = localStorage.getItem('token');

    // Clear locally first so the user is logged out client-side no matter what the API does
    localStorage.removeItem('token');

    try {

        await fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });

    } catch (error) {
        // Ignore — token is already cleared locally, still proceed to login
    }

    window.location.href = '/login';

});
</script>
<script>
(function () {
    const notifDot = document.getElementById('notifDot');
    const notifList = document.getElementById('notifList');
    const notifMarkAllBtn = document.getElementById('notifMarkAllBtn');

    function authHeaders() {
        const token = localStorage.getItem('token');
        return token ? { 'Authorization': 'Bearer ' + token } : {};
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    function timeAgo(value) {
        const seconds = Math.floor((Date.now() - new Date(value).getTime()) / 1000);
        if (seconds < 60) return 'just now';
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) return minutes + 'm ago';
        const hours = Math.floor(minutes / 60);
        if (hours < 24) return hours + 'h ago';
        return Math.floor(hours / 24) + 'd ago';
    }

    async function goToOrder(notifId, orderId) {
        try {
            await fetch('/api/notifications/' + notifId + '/read', { method: 'POST', headers: authHeaders() });
        } catch (err) {
            // Ignore — still navigate to the order regardless.
        }
        window.location.href = '/admin/orders?order=' + orderId;
    }

    async function loadNotifications() {
        try {
            const response = await fetch('/api/notifications', { headers: authHeaders() });
            const payload = await response.json();

            if (!payload.success) return;

            notifDot.classList.toggle('hidden', !payload.unread_count);

            const items = payload.data ?? [];

            if (!items.length) {
                notifList.innerHTML = `<p class="px-2 py-3 text-xs text-forest-700/60 dark:text-moringa-200/60">No notifications yet.</p>`;
                return;
            }

            notifList.innerHTML = items.map(n => `
                <a href="#" class="dropdown-item" data-notif-id="${n.id}" data-order-id="${n.data.order_id}">
                    <i class="fa-solid fa-box text-moringa-600 dark:text-moringa-300"></i>
                    <span>
                        <span style="display:block; ${n.read_at ? '' : 'font-weight:600;'}">${escapeHtml(n.data.message)}</span>
                        <span style="display:block; font-weight:400; font-size:0.7rem; opacity:0.65;">${timeAgo(n.created_at)}</span>
                    </span>
                </a>
            `).join('');

            notifList.querySelectorAll('[data-notif-id]').forEach(el => {
                el.addEventListener('click', (e) => {
                    e.preventDefault();
                    goToOrder(el.dataset.notifId, el.dataset.orderId);
                });
            });
        } catch (err) {
            // Non-critical — dropdown just stays on its current state.
        }
    }

    notifMarkAllBtn?.addEventListener('click', async (e) => {
        e.stopPropagation();
        try {
            await fetch('/api/notifications/read-all', { method: 'POST', headers: authHeaders() });
        } catch (err) {
            // Ignore — next load will just show unread items again.
        }
        loadNotifications();
    });

    loadNotifications();
    setInterval(loadNotifications, 30000);
})();
</script>
