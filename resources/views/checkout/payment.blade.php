{{--
 | Page: checkout/payment.blade.php
 | Layout: layouts/checkout
 | Source: checkout_payment_selection/code.html
--}}
@extends('layouts.checkout')

@php
    $title = 'Checkout - Payment - LUXE';
@endphp

@section('content')

{{-- Checkout Progress Stepper --}}
<div class="mb-5xl">
    <nav aria-label="Checkout Progress">
        <ol class="flex items-center" role="list">

            {{-- Step 1: Information --}}
            <li class="relative pr-8 sm:pr-20">
                <div aria-hidden="true" class="absolute inset-0 flex items-center">
                    <div class="h-0.5 w-full bg-primary"></div>
                </div>
                <a class="relative flex h-8 w-8 items-center justify-center rounded-full bg-primary hover:bg-primary/90"
                   href="{{ route('checkout.information') }}">
                    <span class="material-symbols-outlined text-white text-[16px]">check</span>
                    <span class="sr-only">Information</span>
                </a>
                <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 font-caption text-caption text-on-surface-variant whitespace-nowrap">Information</span>
            </li>

            {{-- Step 2: Shipping --}}
            <li class="relative pr-8 sm:pr-20">
                <div aria-hidden="true" class="absolute inset-0 flex items-center">
                    <div class="h-0.5 w-full bg-primary"></div>
                </div>
                <a class="relative flex h-8 w-8 items-center justify-center rounded-full bg-primary hover:bg-primary/90"
                   href="{{ route('checkout.shipping') }}">
                    <span class="material-symbols-outlined text-white text-[16px]">check</span>
                    <span class="sr-only">Shipping</span>
                </a>
                <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 font-caption text-caption text-on-surface-variant whitespace-nowrap">Shipping</span>
            </li>

            {{-- Step 3: Payment (Current) --}}
            <li class="relative">
                <a class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-primary bg-surface" href="#">
                    <span class="font-button text-button text-primary">3</span>
                    <span class="sr-only">Payment</span>
                </a>
                <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 font-caption text-caption text-primary font-bold whitespace-nowrap">Payment</span>
            </li>

        </ol>
    </nav>
</div>

{{-- Page Header --}}
<div class="mb-6xl">
    <h1 class="font-h2 text-h2 text-on-surface mb-xs">Payment Method</h1>
    <p class="font-body-default text-body-default text-on-surface-variant">All transactions are secure and encrypted.</p>
</div>

{{-- Payment Options Form --}}
<form method="POST" action="{{ route('checkout.process') }}" class="space-y-3xl" id="payment-form">
    @csrf

    <div class="bg-surface rounded-lg shadow-sm border border-outline-variant/50 overflow-hidden">

        {{-- Credit/Debit Card --}}
        <div class="p-lg border-b border-outline-variant/30 bg-surface-container-lowest">
            <label class="flex items-center gap-md cursor-pointer w-full">
                <input checked name="payment_method" type="radio" value="card"
                       class="w-4 h-4 text-primary border-outline focus:ring-primary" id="method-card"/>
                <span class="font-h6 text-h6 text-on-surface flex-1">Credit or Debit Card</span>
                <div class="flex gap-2">
                    <span class="material-symbols-outlined text-on-surface-variant">credit_card</span>
                </div>
            </label>

            {{-- Card Details --}}
            <div class="mt-4xl pl-xl pr-md pb-md space-y-4xl" id="card-details">

                <div class="relative">
                    <label class="block font-body-sm text-body-sm font-medium text-on-surface mb-xs" for="cardNumber">Card Number</label>
                    <div class="relative">
                        <input class="block w-full h-11 px-md rounded bg-surface border border-outline-variant text-on-surface placeholder:text-on-surface-variant/50 focus:border-primary focus:ring-2 focus:ring-primary/10 transition-shadow"
                               id="cardNumber" name="card_number" placeholder="0000 0000 0000 0000" type="text"
                               inputmode="numeric" autocomplete="cc-number"/>
                        <span class="material-symbols-outlined absolute right-md top-1/2 -translate-y-1/2 text-on-surface-variant">lock</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-md">
                    <div>
                        <label class="block font-body-sm text-body-sm font-medium text-on-surface mb-xs" for="expiry">Expiration (MM/YY)</label>
                        <input class="block w-full h-11 px-md rounded bg-surface border border-outline-variant text-on-surface placeholder:text-on-surface-variant/50 focus:border-primary focus:ring-2 focus:ring-primary/10 transition-shadow"
                               id="expiry" name="expiry" placeholder="MM / YY" type="text" autocomplete="cc-exp"/>
                    </div>
                    <div>
                        <label class="block font-body-sm text-body-sm font-medium text-on-surface mb-xs flex items-center gap-xs" for="cvv">
                            Security Code
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant cursor-help" title="3 digit code on back of card">help</span>
                        </label>
                        <input class="block w-full h-11 px-md rounded bg-surface border border-outline-variant text-on-surface placeholder:text-on-surface-variant/50 focus:border-primary focus:ring-2 focus:ring-primary/10 transition-shadow"
                               id="cvv" name="cvv" placeholder="123" type="text" inputmode="numeric" autocomplete="cc-csc"/>
                    </div>
                </div>

                <div>
                    <label class="block font-body-sm text-body-sm font-medium text-on-surface mb-xs" for="nameOnCard">Name on Card</label>
                    <input class="block w-full h-11 px-md rounded bg-surface border border-outline-variant text-on-surface placeholder:text-on-surface-variant/50 focus:border-primary focus:ring-2 focus:ring-primary/10 transition-shadow"
                           id="nameOnCard" name="name_on_card" placeholder="First Last" type="text" autocomplete="cc-name"/>
                </div>

                <div class="flex items-center gap-sm mt-xl">
                    <input class="w-4 h-4 rounded border-outline text-primary focus:ring-primary" id="saveCard" name="save_card" type="checkbox" value="1"/>
                    <label class="font-body-sm text-body-sm text-on-surface-variant cursor-pointer" for="saveCard">Save this card for future purchases securely</label>
                </div>

            </div>
        </div>

        {{-- UPI --}}
        <div class="p-lg border-b border-outline-variant/30 hover:bg-surface-container-lowest transition-colors cursor-pointer opacity-70 hover:opacity-100">
            <label class="flex items-center gap-md cursor-pointer w-full">
                <input name="payment_method" type="radio" value="upi"
                       class="w-4 h-4 border-outline text-primary focus:ring-primary"/>
                <span class="font-h6 text-h6 text-on-surface flex-1">UPI</span>
                <span class="material-symbols-outlined text-on-surface-variant">qr_code_scanner</span>
            </label>
        </div>

        {{-- Net Banking --}}
        <div class="p-lg border-b border-outline-variant/30 hover:bg-surface-container-lowest transition-colors cursor-pointer opacity-70 hover:opacity-100">
            <label class="flex items-center gap-md cursor-pointer w-full">
                <input name="payment_method" type="radio" value="netbanking"
                       class="w-4 h-4 border-outline text-primary focus:ring-primary"/>
                <span class="font-h6 text-h6 text-on-surface flex-1">Net Banking</span>
                <span class="material-symbols-outlined text-on-surface-variant">account_balance</span>
            </label>
        </div>

        {{-- PayPal --}}
        <div class="p-lg hover:bg-surface-container-lowest transition-colors cursor-pointer opacity-70 hover:opacity-100">
            <label class="flex items-center gap-md cursor-pointer w-full">
                <input name="payment_method" type="radio" value="paypal"
                       class="w-4 h-4 border-outline text-primary focus:ring-primary"/>
                <span class="font-h6 text-h6 text-on-surface flex-1">PayPal</span>
                <span class="material-symbols-outlined text-on-surface-variant">payments</span>
            </label>
        </div>

    </div>

    {{-- Total + CTA --}}
    <div class="mt-8xl pt-3xl border-t border-outline-variant/30 flex flex-col sm:flex-row items-center justify-between gap-4xl">
        <div>
            <p class="font-body-sm text-body-sm text-on-surface-variant mb-xs">Total to pay</p>
            <p class="font-h3 text-h3 text-on-surface">{{ money($orderTotal ?? 0) }}</p>
        </div>
        <button type="submit"
                class="w-full sm:w-auto px-6xl py-md bg-primary hover:bg-surface-tint text-on-primary font-button text-button rounded transition-colors duration-200 flex items-center justify-center gap-sm shadow-sm hover:shadow-md">
            <span class="material-symbols-outlined text-[20px]">lock</span>
            Pay Now
        </button>
    </div>

    <p class="text-center mt-xl font-caption text-caption text-on-surface-variant flex items-center justify-center gap-xs">
        <span class="material-symbols-outlined text-[14px]">shield</span>
        Guaranteed safe &amp; secure checkout
    </p>

</form>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const radios = document.querySelectorAll('input[name="payment_method"]');
        const cardDetails = document.getElementById('card-details');
        radios.forEach(radio => {
            radio.addEventListener('change', e => {
                cardDetails.style.display = e.target.value === 'card' ? 'block' : 'none';
            });
        });
    });
</script>
@endpush
