{{--
 | Page: customer/cart.blade.php
 | Layout: layouts/app
 | Source: shopping_cart_desktop/code.html + shopping_cart_mobile/code.html
--}}
@extends('layouts.app')

@php
    $title      = 'Shopping Cart - LUXE';
    $activeTab  = 'cart';
    $activeLink = '';
@endphp

@section('content')

<div class="flex-grow max-w-container-max mx-auto w-full px-gutter py-5xl">

    {{-- Breadcrumbs --}}
    <x-ui.breadcrumb :items="[
        ['label'=>'Home','url'=>route('home')],
        ['label'=>'Shopping Cart'],
    ]" />

    <h1 class="font-h2 text-h2 text-on-surface mt-md mb-6xl">Your Cart</h1>

    @if(isset($cartItems) && $cartItems->count() > 0)

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5xl">

            {{-- ── Cart Items ───────────────────────────── --}}
            <div class="lg:col-span-8 flex flex-col gap-3xl">

                @foreach($cartItems as $item)
                    <div class="flex flex-col sm:flex-row gap-3xl pb-3xl border-b border-outline-variant/30">

                        {{-- Product Image --}}
                        <div class="w-full sm:w-32 h-40 sm:h-32 bg-surface-container rounded-lg overflow-hidden flex-shrink-0 border border-outline-variant/20 shadow-sm">
                            <img src="{{ $item->product->image_url }}"
                                 alt="{{ $item->product->name }}"
                                 class="w-full h-full object-cover"
                                 loading="lazy"/>
                        </div>

                        {{-- Item Details --}}
                        <div class="flex flex-col flex-grow justify-between py-xs">
                            <div class="flex justify-between items-start gap-md">
                                <div>
                                    <h3 class="font-h5 text-h5 text-on-surface">{{ $item->product->name }}</h3>
                                    @if($item->variant)
                                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">{{ $item->variant }}</p>
                                    @endif
                                </div>
                                <p class="font-h5 text-h5 text-on-surface">{{ money($item->total_price) }}</p>
                            </div>

                            <div class="flex justify-between items-center mt-3xl sm:mt-auto">
                                {{-- Quantity Selector --}}
                                <x-forms.quantity-selector
                                    name="quantity"
                                    :value="$item->quantity"
                                    :id="'qty-'.$item->id"
                                />

                                <div class="flex gap-lg">
                                    <form method="POST" action="{{ route('customer.wishlist.add', $item->product_id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="font-button text-button text-on-surface-variant hover:text-primary transition-colors underline-offset-4 hover:underline">
                                            Move to Wishlist
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('cart.remove', $item->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-button text-button text-error hover:text-error/80 transition-colors underline-offset-4 hover:underline">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>

            {{-- ── Order Summary ────────────────────────── --}}
            <div class="lg:col-span-4">
                <div class="bg-surface rounded-xl p-4xl border border-outline-variant/20 shadow-md sticky top-6xl">

                    <h2 class="font-h4 text-h4 text-on-surface mb-3xl">Order Summary</h2>

                    <div class="flex flex-col gap-lg font-body-default text-body-default mb-4xl">
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Subtotal</span>
                            <span class="text-on-surface">{{ money($cartSummary->subtotal ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Shipping</span>
                            <span class="text-on-surface">{{ ($cartSummary->shipping ?? 0) > 0 ? money($cartSummary->shipping) : 'Free' }}</span>
                        </div>
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Tax</span>
                            <span class="text-on-surface">{{ money($cartSummary->tax ?? 0) }}</span>
                        </div>
                        @if(isset($cartSummary->discount) && $cartSummary->discount > 0)
                            <div class="flex justify-between text-secondary">
                                <span>Discount</span>
                                <span>-{{ money($cartSummary->discount) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-between items-center border-t border-outline-variant/30 pt-3xl mb-4xl">
                        <span class="font-h5 text-h5 text-on-surface">Total</span>
                        <span class="font-h4 text-h4 text-on-surface">{{ money($cartSummary->total ?? 0) }}</span>
                    </div>

                    {{-- Coupon --}}
                    <form method="POST" action="{{ route('cart.coupon') }}" class="mb-5xl">
                        @csrf
                        <div class="flex gap-sm">
                            <input type="text"
                                   name="coupon_code"
                                   id="coupon"
                                   value="{{ session('coupon_code') }}"
                                   placeholder="Enter coupon code"
                                   class="w-full h-10 px-md bg-surface border border-outline-variant rounded-md font-body-sm text-body-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 placeholder:text-on-surface-variant/50 shadow-sm transition-all"
                                   aria-label="Coupon code"/>
                            <button type="submit"
                                    class="px-xl h-10 bg-surface-container-low border border-outline-variant text-on-surface font-button text-button rounded-md hover:bg-surface-container-high transition-colors shadow-sm whitespace-nowrap">
                                Apply
                            </button>
                        </div>
                    </form>

                    <a href="{{ route('checkout.information') }}"
                       class="w-full h-12 bg-primary text-on-primary font-button text-button rounded-md hover:bg-primary/90 transition-colors shadow-sm flex justify-center items-center mb-3xl">
                        Proceed to Checkout
                    </a>

                    <div class="flex flex-col gap-sm">
                        <div class="flex items-center justify-center gap-sm text-on-surface-variant font-body-sm text-body-sm">
                            <span class="material-symbols-outlined text-sm">lock</span>
                            <span>Secure Checkout</span>
                        </div>
                        <div class="flex items-center justify-center gap-sm text-on-surface-variant font-body-sm text-body-sm">
                            <span class="material-symbols-outlined text-sm">local_shipping</span>
                            <span>Free Shipping Returns</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- ── Recommendations ─────────────────────────── --}}
        @if(isset($recommendations) && $recommendations->count() > 0)
            <section class="mt-8xl">
                <h2 class="font-h3 text-h3 text-on-surface mb-5xl">Recommended for You</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3xl">
                    @foreach($recommendations as $product)
                        <x-cards.product-card :product="$product" />
                    @endforeach
                </div>
            </section>
        @endif

    @else

        {{-- Empty Cart State --}}
        <div class="text-center py-8xl">
            <span class="material-symbols-outlined text-[80px] text-on-surface-variant/20">shopping_cart</span>
            <h2 class="font-h3 text-h3 text-on-surface-variant mt-2xl">Your cart is empty</h2>
            <p class="font-body-default text-body-default text-on-surface-variant mt-md mb-4xl">
                Looks like you haven't added anything yet.
            </p>
            <x-ui.button href="{{ route('products.index') }}" variant="primary" size="lg" icon="arrow_forward">
                Start Shopping
            </x-ui.button>
        </div>

    @endif

</div>

@endsection
