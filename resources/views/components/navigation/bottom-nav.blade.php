{{--
 | Navigation Component: components/navigation/bottom-nav.blade.php
 | Purpose : Mobile-only fixed bottom navigation bar.
 | Usage   : <x-navigation.bottom-nav :activeTab="'home'" :cartCount="2" />
--}}
@props([
    'activeTab'  => 'home',
    'cartCount'  => 0,
])

<nav class="fixed bottom-0 left-0 w-full flex justify-around items-center h-16 bg-surface dark:bg-inverse-surface border-t border-outline-variant/20 shadow-lg z-50 md:hidden pb-xs"
     aria-label="Mobile Navigation">

    {{-- Home --}}
    <a href="{{ route('home') }}"
       class="flex flex-col items-center justify-center font-caption text-caption w-full h-full active:bg-surface-container-high active:scale-110 transition-transform duration-200
              {{ $activeTab === 'home' ? 'text-primary dark:text-primary-fixed-dim font-semibold' : 'text-on-surface-variant dark:text-surface-variant' }}">
        <span class="material-symbols-outlined mb-1 {{ $activeTab === 'home' ? 'fill' : '' }}">home</span>
        Home
    </a>

    {{-- Search --}}
    <a href="{{ route('search') }}"
       class="flex flex-col items-center justify-center font-caption text-caption w-full h-full active:bg-surface-container-high active:scale-110 transition-transform duration-200
              {{ $activeTab === 'search' ? 'text-primary dark:text-primary-fixed-dim font-semibold' : 'text-on-surface-variant dark:text-surface-variant' }}">
        <span class="material-symbols-outlined mb-1">search</span>
        Search
    </a>

    {{-- Cart --}}
    <a href="{{ route('customer.cart') }}"
       class="flex flex-col items-center justify-center font-caption text-caption w-full h-full active:bg-surface-container-high active:scale-110 transition-transform duration-200 relative
              {{ $activeTab === 'cart' ? 'text-primary dark:text-primary-fixed-dim font-semibold' : 'text-on-surface-variant dark:text-surface-variant' }}">
        <span class="material-symbols-outlined mb-1">shopping_cart</span>
        Cart
        @if($cartCount > 0)
            <span class="absolute top-2 right-6 bg-primary text-on-primary text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                {{ $cartCount > 9 ? '9+' : $cartCount }}
            </span>
        @endif
    </a>

    {{-- Account --}}
    <a href="{{ auth()->check() ? route('customer.dashboard') : route('auth.login') }}"
       class="flex flex-col items-center justify-center font-caption text-caption w-full h-full active:bg-surface-container-high active:scale-110 transition-transform duration-200
              {{ $activeTab === 'account' ? 'text-primary dark:text-primary-fixed-dim font-semibold' : 'text-on-surface-variant dark:text-surface-variant' }}">
        <span class="material-symbols-outlined mb-1">person</span>
        Account
    </a>

</nav>
