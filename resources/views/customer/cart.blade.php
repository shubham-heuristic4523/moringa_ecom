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

<div class="flex-grow max-w-container-max mx-auto w-full px-gutter py-5xl" id="cart-page-root">

    {{-- Breadcrumbs --}}
    <x-ui.breadcrumb :items="[
        ['label'=>'Home','url'=>route('home')],
        ['label'=>'Shopping Cart'],
    ]" />

    <h1 class="font-h2 text-h2 text-on-surface mt-md mb-6xl">Your Cart</h1>

    {{-- Cart grid driven by cart.js (falls back to SSR if JS disabled) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5xl">

        {{-- ── Cart Items (populated by cart.js) ──────── --}}
        <div class="lg:col-span-8 flex flex-col gap-3xl" id="cart-items-container">
            {{-- Skeleton loader shown until JS loads --}}
            @foreach(range(1,2) as $sk)
            <div class="flex flex-col sm:flex-row gap-3xl pb-3xl border-b border-outline-variant/30 animate-pulse">
                <div class="w-full sm:w-32 h-40 sm:h-32 bg-surface-container rounded-lg flex-shrink-0"></div>
                <div class="flex flex-col flex-grow gap-md py-xs">
                    <div class="h-4 bg-surface-container rounded w-3/4"></div>
                    <div class="h-3 bg-surface-container rounded w-1/2"></div>
                    <div class="h-10 bg-surface-container rounded w-32 mt-auto"></div>
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
                            <span class="text-on-surface" id="summary-subtotal">—</span>
                        </div>
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Shipping</span>
                            <span class="text-on-surface" id="summary-shipping">—</span>
                        </div>
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Tax</span>
                            <span class="text-on-surface" id="summary-tax">—</span>
                        </div>
                        <div class="flex justify-between text-secondary">
                            <span>Discount</span>
                            <span id="summary-discount">$0.00</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center border-t border-outline-variant/30 pt-3xl mb-4xl">
                        <span class="font-h5 text-h5 text-on-surface">Total</span>
                        <span class="font-h4 text-h4 text-on-surface" id="summary-total">—</span>
                    </div>

                    {{-- Coupon --}}
                    <form id="coupon-form" class="mb-5xl">
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

    </div>{{-- /cart grid --}}

</div>

@endsection
