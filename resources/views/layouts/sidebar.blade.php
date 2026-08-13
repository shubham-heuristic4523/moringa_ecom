{{-- resources/views/layouts/sidebar.blade.php
     Include with @include('layouts.sidebar') inside a body that carries
     data-sidebar="expanded" (see layouts/app.blade.php). Requires admin.css
     + sidebar.js to be loaded via @vite for the collapse/accordion behavior. --}}
@php
    $productsActive = request()->routeIs(['admin.list', 'admin.form', 'admin.form.edit']);
@endphp
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
        <a href="{{ url('/admin') }}" class="sidebar-link {{ request()->routeIs('welcome') ? 'is-active' : '' }}">
            <span class="sidebar-icon"><i class="fa-solid fa-grid-2"></i></span>
            <span class="sidebar-label">Dashboard</span>
            <span class="sidebar-tooltip">Dashboard</span>
        </a>

        <p class="sidebar-group-title">Catalog</p>

        <div class="sidebar-group {{ $productsActive ? 'is-open' : '' }}">
            <button type="button" class="sidebar-link w-full {{ $productsActive ? 'is-active' : '' }}" data-sidebar-toggle>
                <span class="sidebar-icon"><i class="fa-solid fa-box-open"></i></span>
                <span class="sidebar-label">Products</span>
                <i class="fa-solid fa-chevron-right sidebar-chevron"></i>
                <span class="sidebar-tooltip">Products</span>
            </button>
            <div class="sidebar-submenu">
                <div>
                    <a href="{{ route('admin.list') }}" class="sidebar-link !py-2 pl-11 text-[0.82rem] {{ request()->routeIs('admin.list') ? 'is-active' : '' }}">
                        <span class="sidebar-label">All Products</span>
                    </a>
                    <a href="{{ route('admin.form') }}" class="sidebar-link !py-2 pl-11 text-[0.82rem] {{ request()->routeIs(['admin.form', 'admin.form.edit']) ? 'is-active' : '' }}">
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

        <a href="{{ route('admin.orders') }}" class="sidebar-link {{ request()->routeIs('admin.orders') ? 'is-active' : '' }}">
            <span class="sidebar-icon"><i class="fa-solid fa-cart-shopping"></i></span>
            <span class="sidebar-label">Orders</span>
            <span class="sidebar-tooltip">Orders</span>
        </a>
        <a href="{{ route('admin.customers') }}" class="sidebar-link {{ request()->routeIs('admin.customers') ? 'is-active' : '' }}">
            <span class="sidebar-icon"><i class="fa-solid fa-users"></i></span>
            <span class="sidebar-label">Customers</span>
            <span class="sidebar-tooltip">Customers</span>
        </a>
        <a href="#" class="sidebar-link">
            <span class="sidebar-icon"><i class="fa-solid fa-heart"></i></span>
            <span class="sidebar-label">Wishlists</span>
            <span class="sidebar-tooltip">Wishlists</span>
        </a>

        <p class="sidebar-group-title">Marketing</p>

        <a href="{{ route('admin.offers') }}" class="sidebar-link {{ request()->routeIs('admin.offers') ? 'is-active' : '' }}">
            <span class="sidebar-icon"><i class="fa-solid fa-tags"></i></span>
            <span class="sidebar-label">Offers &amp; Discounts</span>
            <span class="sidebar-tooltip">Offers &amp; Discounts</span>
        </a>
        <a href="{{ route('admin.flash-sales') }}" class="sidebar-link {{ request()->routeIs('admin.flash-sales') ? 'is-active' : '' }}">
            <span class="sidebar-icon"><i class="fa-solid fa-bolt"></i></span>
            <span class="sidebar-label">Flash Sales</span>
            <span class="sidebar-tooltip">Flash Sales</span>
        </a>
        <a href="{{ route('admin.referrals') }}" class="sidebar-link {{ request()->routeIs('admin.referrals') ? 'is-active' : '' }}">
            <span class="sidebar-icon"><i class="fa-solid fa-user-plus"></i></span>
            <span class="sidebar-label">Referrals</span>
            <span class="sidebar-tooltip">Referrals</span>
        </a>

        <p class="sidebar-group-title">Insights</p>

        <a href="{{ route('admin.reports') }}" class="sidebar-link {{ request()->routeIs('admin.reports') ? 'is-active' : '' }}">
            <span class="sidebar-icon"><i class="fa-solid fa-chart-line"></i></span>
            <span class="sidebar-label">Reports</span>
            <span class="sidebar-tooltip">Reports</span>
        </a>

        <p class="sidebar-group-title">System</p>

        <a href="{{ route('admin.admins') }}" class="sidebar-link {{ request()->routeIs('admin.admins') ? 'is-active' : '' }}">
            <span class="sidebar-icon"><i class="fa-solid fa-user-shield"></i></span>
            <span class="sidebar-label">All Admins</span>
            <span class="sidebar-tooltip">All Admins</span>
        </a>
        <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings') ? 'is-active' : '' }}">
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
