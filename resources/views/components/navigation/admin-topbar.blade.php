{{--
 | Navigation Component: components/navigation/admin-topbar.blade.php
 | Purpose : Admin panel top bar with breadcrumbs, search, notifications, and user avatar.
 | Usage   : <x-navigation.admin-topbar :breadcrumbs="[['label'=>'Dashboard','route'=>null]]" />
--}}
@props([
    'breadcrumbs' => [],
])

<header class="h-16 border-b border-outline-variant/30 bg-surface/80 backdrop-blur-md flex items-center justify-between px-gutter shrink-0 z-30 relative"
        aria-label="Admin Top Bar">

    {{-- Breadcrumbs --}}
    <nav aria-label="Breadcrumb" class="flex items-center gap-md text-on-surface-variant font-body-sm text-body-sm">
        @foreach($breadcrumbs as $index => $crumb)
            @if($index > 0)
                <span class="material-symbols-outlined text-sm">chevron_right</span>
            @endif
            @if(isset($crumb['route']) && $crumb['route'])
                <a href="{{ route($crumb['route']) }}" class="hover:text-primary transition-colors">{{ $crumb['label'] }}</a>
            @else
                <span class="text-primary font-medium">{{ $crumb['label'] }}</span>
            @endif
        @endforeach
    </nav>

    {{-- Actions --}}
    <div class="flex items-center gap-2xl">

        {{-- Search --}}
        <div class="relative hidden md:block">
            <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input type="text"
                   placeholder="Search..."
                   class="h-10 pl-4xl pr-lg rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-1 focus:ring-primary/10 font-body-sm text-body-sm w-64 transition-all"
                   aria-label="Search admin panel"/>
        </div>

        <div class="flex items-center gap-md">
            {{-- Notifications --}}
            <button class="text-on-surface-variant hover:text-primary transition-colors relative" aria-label="Notifications">
                <span class="material-symbols-outlined">notifications</span>
            </button>

            {{-- Admin Avatar --}}
            <div class="w-8 h-8 rounded-full bg-surface-container-highest overflow-hidden border border-outline-variant cursor-pointer">
                @if(auth()->user()?->avatar)
                    <img src="{{ asset('storage/'.auth()->user()->avatar) }}"
                         alt="{{ auth()->user()->name }}"
                         class="w-full h-full object-cover"/>
                @else
                    <div class="w-full h-full flex items-center justify-center bg-primary text-on-primary font-button text-caption font-bold">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                    </div>
                @endif
            </div>
        </div>

    </div>

</header>
