{{--
 | Customer Account Sidebar Partial
 | Path: customer/partials/account-sidebar.blade.php
--}}
<nav class="bg-surface border border-outline-variant/30 rounded-xl p-md" aria-label="Account Navigation">
    <div class="flex items-center gap-md px-md py-sm mb-lg border-b border-outline-variant/20 pb-lg">
        <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-h6 font-bold">
            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
        </div>
        <div>
            <p class="font-body-default font-medium text-on-surface">{{ auth()->user()->name ?? 'Customer' }}</p>
            <p class="font-caption text-caption text-on-surface-variant">{{ auth()->user()->email ?? '' }}</p>
        </div>
    </div>

    @php
        $navItems = [
            ['route' => 'customer.dashboard',      'icon' => 'dashboard',       'label' => 'Overview'],
            ['route' => 'customer.orders',          'icon' => 'shopping_bag',    'label' => 'My Orders'],
            ['route' => 'customer.wishlist',        'icon' => 'favorite',        'label' => 'Wishlist'],
            ['route' => 'customer.addresses',       'icon' => 'location_on',     'label' => 'Addresses'],
            ['route' => 'customer.profile',         'icon' => 'person',          'label' => 'Profile'],
            ['route' => 'customer.security',        'icon' => 'lock',            'label' => 'Security'],
            ['route' => 'customer.notifications',   'icon' => 'notifications',   'label' => 'Notifications'],
            ['route' => 'customer.support',         'icon' => 'support_agent',   'label' => 'Support'],
        ];
        $current = Route::currentRouteName();
    @endphp

    <div class="flex flex-col gap-xs">
        @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-md px-md py-sm rounded-lg font-body-sm text-body-sm transition-all
                      {{ $current === $item['route']
                          ? 'bg-primary-container/15 text-primary font-semibold'
                          : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[20px]">{{ $item['icon'] }}</span>
                {{ $item['label'] }}
            </a>
        @endforeach

        <div class="border-t border-outline-variant/20 mt-sm pt-sm">
            <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center gap-md px-md py-sm rounded-lg font-body-sm text-body-sm text-error hover:bg-error-container/20 transition-all w-full">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</nav>
