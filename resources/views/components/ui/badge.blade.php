{{--
 | Component: components/ui/badge.blade.php
 | Purpose  : Status badge chip for products, orders, and labels.
 | Usage    : <x-ui.badge variant="new">New</x-ui.badge>
 |             <x-ui.badge variant="sale">Sale -20%</x-ui.badge>
 |             <x-ui.badge variant="sold-out">Sold Out</x-ui.badge>
 |             <x-ui.badge variant="pending">Pending</x-ui.badge>
--}}
@props([
    'variant' => 'default',
])

@php
    $variantClasses = match($variant) {
        'new'       => 'bg-secondary-container text-on-secondary-container',
        'sale'      => 'bg-error-container text-on-error-container',
        'sold-out'  => 'bg-error text-on-error',
        'almost-gone'=> 'bg-surface text-on-surface border border-outline-variant/30',
        'pending'   => 'bg-surface-container-high text-on-surface-variant',
        'delivered' => 'bg-secondary-container text-on-secondary-container',
        'shipped'   => 'bg-tertiary-container/30 text-on-tertiary-container',
        'processing'=> 'bg-primary-container/20 text-on-primary-container',
        'cancelled' => 'bg-error-container text-on-error-container',
        default     => 'bg-surface-container text-on-surface',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-md py-xs font-caption text-caption font-bold uppercase tracking-wider rounded-sm $variantClasses"]) }}>
    {{ $slot }}
</span>
