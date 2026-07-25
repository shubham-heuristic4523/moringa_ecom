{{--
 | Page: customer/orders/index.blade.php
 | Source: order_history_list_view/code.html
--}}
@extends('layouts.customer')

@php
    $title              = 'My Orders - LUXE';
    $activeTab          = 'account';
    $customerActiveItem = 'orders';
@endphp

@section('customer-content')

<div class="flex items-center justify-between mb-4xl">
    <h1 class="font-h3 text-h3 text-on-surface">My Orders</h1>
</div>

{{-- Orders List --}}
@forelse($orders ?? [] as $order)
    <div class="bg-surface border border-outline-variant/30 rounded-xl p-2xl mb-lg shadow-sm hover:shadow-md transition-shadow">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md mb-lg">
            <div>
                <p class="font-h6 text-h6 text-on-surface">#{{ $order->order_number }}</p>
                <p class="font-caption text-caption text-on-surface-variant">Placed on {{ $order->created_at->format('M d, Y') }}</p>
            </div>
            <x-ui.badge :variant="$order->status">{{ ucfirst($order->status) }}</x-ui.badge>
        </div>

        {{-- Order Items --}}
        @foreach($order->items->take(2) as $item)
            <div class="flex gap-md mb-md">
                <div class="w-16 h-16 bg-surface-container rounded-lg overflow-hidden shrink-0">
                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover" loading="lazy"/>
                </div>
                <div class="flex-1">
                    <p class="font-body-sm text-body-sm font-medium text-on-surface">{{ $item->product->name }}</p>
                    <p class="font-caption text-caption text-on-surface-variant">Qty: {{ $item->quantity }}</p>
                </div>
                <p class="font-body-sm text-body-sm font-medium text-on-surface">{{ money($item->price * $item->quantity) }}</p>
            </div>
        @endforeach

        @if($order->items->count() > 2)
            <p class="font-caption text-caption text-on-surface-variant mb-md">+{{ $order->items->count() - 2 }} more items</p>
        @endif

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md pt-md border-t border-outline-variant/20">
            <p class="font-body-sm text-body-sm text-on-surface-variant">
                Total: <span class="font-semibold text-on-surface">{{ money($order->total) }}</span>
            </p>
            <div class="flex gap-sm">
                <x-ui.button href="{{ route('customer.orders.show', $order->id) }}" variant="surface" size="sm">
                    View Details
                </x-ui.button>
                @if($order->status === 'delivered')
                    <x-ui.button href="{{ route('customer.orders.reorder', $order->id) }}" variant="outline" size="sm">
                        Reorder
                    </x-ui.button>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-8xl">
        <span class="material-symbols-outlined text-[64px] text-on-surface-variant/20">shopping_bag</span>
        <h2 class="font-h4 text-h4 text-on-surface-variant mt-2xl">No orders yet</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-md mb-4xl">Your order history will appear here.</p>
        <x-ui.button href="{{ route('products.index') }}" variant="primary" size="md">Start Shopping</x-ui.button>
    </div>
@endforelse

@if(isset($orders) && $orders->hasPages())
    <x-ui.pagination :paginator="$orders" />
@endif

@endsection
