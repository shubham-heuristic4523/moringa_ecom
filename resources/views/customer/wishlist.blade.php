{{--
 | Page: customer/wishlist.blade.php
 | Source: my_wishlist_grid_view/code.html
--}}
@extends('layouts.app')

@php
    $title     = 'My Wishlist - LUXE';
    $activeTab = 'account';
@endphp

@section('content')

<div class="max-w-container-max mx-auto px-gutter py-4xl">

    <x-ui.breadcrumb :items="[
        ['label'=>'Home','url'=>route('home')],
        ['label'=>'My Wishlist'],
    ]" />

    <div class="flex items-center justify-between mt-md mb-4xl">
        <h1 class="font-h2 text-h2 text-on-surface">My Wishlist</h1>
        @if(isset($wishlistItems) && $wishlistItems->count() > 0)
            <span class="font-body-sm text-body-sm text-on-surface-variant">{{ $wishlistItems->count() }} items</span>
        @endif
    </div>

    @if(isset($wishlistItems) && $wishlistItems->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-lg md:gap-xl">
            @foreach($wishlistItems as $item)
                <x-cards.product-card :product="$item->product" :showSwatches="true" />
            @endforeach
        </div>
    @else
        <div class="text-center py-8xl">
            <span class="material-symbols-outlined text-[80px] text-on-surface-variant/20">favorite</span>
            <h2 class="font-h3 text-h3 text-on-surface-variant mt-2xl">Your wishlist is empty</h2>
            <p class="font-body-default text-body-default text-on-surface-variant mt-md mb-4xl">
                Save items you love for later.
            </p>
            <x-ui.button href="{{ route('products.index') }}" variant="primary" size="lg">Explore Products</x-ui.button>
        </div>
    @endif

</div>

@endsection
