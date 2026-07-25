{{--
 | Navigation Component: components/navigation/admin-sidebar.blade.php
 | Purpose : Admin panel left sidebar navigation.
 | Usage   : <x-navigation.admin-sidebar :activeItem="'dashboard'" />
--}}
@props([
    'activeItem' => 'dashboard',
])

<nav class="bg-surface-container-low dark:bg-inverse-surface border-r border-outline-variant/30 h-full w-64 hidden lg:flex flex-col p-lg gap-md z-40 relative"
     aria-label="Admin Navigation">

    {{-- Brand / Admin Panel Header --}}
    <div class="flex items-center gap-md px-md py-sm mb-2xl">
        <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container font-h6 font-bold">
            A
        </div>
        <div>
            <h2 class="font-h6 text-h6 text-on-surface dark:text-inverse-on-surface">Admin Panel</h2>
            <p class="font-caption text-caption text-on-surface-variant">Premium Management</p>
        </div>
    </div>

    {{-- New Product CTA --}}
    <a href="{{ route('admin.products.create') }}"
       class="w-full bg-primary text-on-primary font-button text-button py-md px-lg rounded-lg mb-2xl hover:opacity-90 transition-opacity text-center block">
        + New Product
    </a>

    {{-- Navigation Links --}}
    <div class="flex flex-col gap-sm">

        @php
            $navItems = [
                ['key' => 'dashboard',    'icon' => 'dashboard',      'label' => 'Dashboard',   'route' => 'admin.dashboard'],
                ['key' => 'products',     'icon' => 'inventory_2',    'label' => 'Products',    'route' => 'admin.products.index'],
                ['key' => 'categories',   'icon' => 'category',       'label' => 'Categories',  'route' => 'admin.categories.index'],
                ['key' => 'orders',       'icon' => 'shopping_cart',  'label' => 'Orders',      'route' => 'admin.orders.index'],
                ['key' => 'customers',    'icon' => 'group',          'label' => 'Customers',   'route' => 'admin.customers.index'],
                ['key' => 'analytics',    'icon' => 'analytics',      'label' => 'Analytics',   'route' => 'admin.analytics'],
                ['key' => 'marketing',    'icon' => 'campaign',       'label' => 'Marketing',   'route' => 'admin.marketing.index'],
                ['key' => 'cms',          'icon' => 'article',        'label' => 'CMS',         'route' => 'admin.cms.index'],
                ['key' => 'support',      'icon' => 'support_agent',  'label' => 'Support',     'route' => 'admin.support.index'],
                ['key' => 'settings',     'icon' => 'settings',       'label' => 'Settings',    'route' => 'admin.settings.index'],
            ];
        @endphp

        @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-md rounded-lg px-md py-sm transition-all active:translate-x-1
                      {{ $activeItem === $item['key']
                          ? 'bg-primary-container/20 text-primary font-semibold'
                          : 'text-on-surface-variant hover:bg-surface-container-high hover:bg-surface-container-highest' }}">
                <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                <span class="font-body-sm text-body-sm">{{ $item['label'] }}</span>
            </a>
        @endforeach

    </div>

</nav>
