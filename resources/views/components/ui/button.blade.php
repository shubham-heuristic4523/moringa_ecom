{{--
 | Component: components/ui/button.blade.php
 | Purpose  : Reusable button with variant, size, loading state, and icon support.
 | Usage    : <x-ui.button variant="primary" size="md" :loading="false">Shop Now</x-ui.button>
 |             <x-ui.button variant="outline" href="{{ route('home') }}">Browse</x-ui.button>
--}}
@props([
    'variant'  => 'primary',
    'size'     => 'md',
    'loading'  => false,
    'disabled' => false,
    'href'     => null,
    'icon'     => null,
    'iconPosition' => 'right',
])

@php
    $variantClasses = match($variant) {
        'primary'   => 'bg-primary text-on-primary hover:bg-surface-tint shadow-sm',
        'secondary' => 'bg-surface text-on-surface border border-outline hover:bg-surface-container-low',
        'outline'   => 'bg-transparent text-primary border border-primary hover:bg-primary/5',
        'ghost'     => 'bg-transparent text-primary hover:bg-primary/5',
        'error'     => 'bg-error text-on-error hover:bg-error/90',
        'surface'   => 'bg-surface-container-low border border-outline-variant text-on-surface hover:bg-surface-container-high',
        default     => 'bg-primary text-on-primary hover:bg-surface-tint shadow-sm',
    };

    $sizeClasses = match($size) {
        'sm'  => 'px-lg py-xs text-caption rounded',
        'md'  => 'px-2xl py-md text-button rounded',
        'lg'  => 'px-4xl py-lg text-button rounded-lg',
        'xl'  => 'px-6xl py-lg text-button rounded-lg',
        'full'=> 'w-full px-2xl py-md text-button rounded',
        default => 'px-2xl py-md text-button rounded',
    };

    $baseClasses = "inline-flex items-center justify-center gap-xs font-button transition-colors duration-200 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed $variantClasses $sizeClasses";

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    @if(!$href) type="{{ $attributes->get('type', 'button') }}" @endif
    @if($disabled || $loading) disabled @endif
    {{ $attributes->merge(['class' => $baseClasses]) }}
>
    @if($loading)
        <span class="w-4 h-4 border-2 border-current/30 border-t-current rounded-full animate-spin"></span>
    @elseif($icon && $iconPosition === 'left')
        <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
    @endif

    {{ $slot }}

    @if(!$loading && $icon && $iconPosition === 'right')
        <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">{{ $icon }}</span>
    @endif
</{{ $tag }}>
