{{-- resources/views/layouts/sidebar.blade.php
     Include with @include('layouts.sidebar') inside a body that carries
     data-sidebar="expanded" (see layouts/app.blade.php). Requires admin.css
     + sidebar.js to be loaded via @vite for the collapse/accordion behavior. --}}
<aside class="admin-sidebar font-body" id="admin-sidebar">

    <button
        type="button"
        id="sidebar-collapse-btn"
        class="sidebar-collapse-btn"
        aria-label="Toggle sidebar width"
    >
        <i class="fa-solid fa-chevron-left text-xs"></i>
    </button>

    {{-- Brand --}}
    <div class="flex items-center gap-3 px-5 py-6">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-moringa-400 to-forest-600 text-white shadow-md">
            <i class="fa-solid fa-leaf text-lg"></i>
        </div>
        <div class="sidebar-brand-text overflow-hidden">
            <p class="font-display text-lg font-semibold leading-tight text-forest-950 dark:text-cream-50">Moringa</p>
            <p class="text-[0.7rem] font-medium uppercase tracking-wider text-moringa-600 dark:text-moringa-300">Admin Panel</p>
        </div>
    </div>

    <nav class="sidebar-scroll">

        <p class="sidebar-group-title">Overview</p>
        <a href="{{ url('/admin') }}" class="sidebar-link is-active">
            <span class="sidebar-icon"><i class="fa-solid fa-grid-2"></i></span>
            <span class="sidebar-label">Dashboard</span>
            <span class="sidebar-tooltip">Dashboard</span>
        </a>

        <p class="sidebar-group-title">Catalog</p>

        <div class="sidebar-group">
            <button type="button" class="sidebar-link w-full" data-sidebar-toggle>
                <span class="sidebar-icon"><i class="fa-solid fa-box-open"></i></span>
                <span class="sidebar-label">Products</span>
                <i class="fa-solid fa-chevron-right sidebar-chevron"></i>
                <span class="sidebar-tooltip">Products</span>
            </button>
            <div class="sidebar-submenu">
                <div>
                    <a href="#" class="sidebar-link !py-2 pl-11 text-[0.82rem]">
                        <span class="sidebar-label">All Products</span>
                    </a>
                    <a href="#" class="sidebar-link !py-2 pl-11 text-[0.82rem]">
                        <span class="sidebar-label">Add New</span>
                    </a>
                    <a href="#" class="sidebar-link !py-2 pl-11 text-[0.82rem]">
                        <span class="sidebar-label">Categories</span>
                    </a>
                </div>
            </div>
        </div>

        <a href="#" class="sidebar-link">
            <span class="sidebar-icon"><i class="fa-solid fa-boxes-stacked"></i></span>
            <span class="sidebar-label">Inventory</span>
            <span class="sidebar-tooltip">Inventory</span>
        </a>

        <p class="sidebar-group-title">Sales</p>

        <a href="#" class="sidebar-link">
            <span class="sidebar-icon"><i class="fa-solid fa-cart-shopping"></i></span>
            <span class="sidebar-label">Orders</span>
            <span class="sidebar-badge">12</span>
            <span class="sidebar-tooltip">Orders</span>
        </a>
        <a href="#" class="sidebar-link">
            <span class="sidebar-icon"><i class="fa-solid fa-users"></i></span>
            <span class="sidebar-label">Customers</span>
            <span class="sidebar-tooltip">Customers</span>
        </a>
        <a href="#" class="sidebar-link">
            <span class="sidebar-icon"><i class="fa-solid fa-heart"></i></span>
            <span class="sidebar-label">Wishlists</span>
            <span class="sidebar-tooltip">Wishlists</span>
        </a>

        <p class="sidebar-group-title">Insights</p>

        <a href="#" class="sidebar-link">
            <span class="sidebar-icon"><i class="fa-solid fa-chart-line"></i></span>
            <span class="sidebar-label">Reports</span>
            <span class="sidebar-tooltip">Reports</span>
        </a>

        <p class="sidebar-group-title">System</p>

        <a href="#" class="sidebar-link">
            <span class="sidebar-icon"><i class="fa-solid fa-user-shield"></i></span>
            <span class="sidebar-label">Staff &amp; Roles</span>
            <span class="sidebar-tooltip">Staff &amp; Roles</span>
        </a>
        <a href="#" class="sidebar-link">
            <span class="sidebar-icon"><i class="fa-solid fa-gear"></i></span>
            <span class="sidebar-label">Settings</span>
            <span class="sidebar-tooltip">Settings</span>
        </a>
    </nav>

    <div class="border-t border-forest-900/10 px-4 py-4 dark:border-moringa-200/10">
        <a href="#" class="sidebar-link">
            <span class="sidebar-icon"><i class="fa-solid fa-circle-question"></i></span>
            <span class="sidebar-label sidebar-footer-text">Help &amp; Support</span>
            <span class="sidebar-tooltip">Help &amp; Support</span>
        </a>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebar-overlay"></div>
