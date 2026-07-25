{{--
 | Page: customer/home.blade.php
 | Layout: layouts/app
 | Source: premium_ecommerce_home_page/code.html
--}}
@extends('layouts.app')

@php
    $title       = 'LUXE | Premium Ecommerce';
    $description = 'Discover unparalleled craftsmanship — hand-stitched leather, precision timepieces, and minimalist essentials.';
    $activeTab   = 'home';
    $activeLink  = 'shop';
@endphp

@section('content')

{{-- ─── Hero Section ─────────────────────────────────── --}}
<section class="relative w-full h-[819px] min-h-[600px] flex items-center justify-center overflow-hidden">

    {{-- Background Image --}}
    <div class="absolute inset-0 z-0">
        <div class="bg-cover bg-center w-full h-full"
             style="background-image: url('{{ asset('images/hero-leather.jpg') }}')">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-surface/80 via-transparent to-transparent mix-blend-multiply"></div>
    </div>

    {{-- Content Overlay --}}
    <div class="relative z-10 w-full max-w-container-max px-gutter mx-auto flex flex-col items-start">
        <div class="glass-panel p-3xl rounded-xl max-w-lg shadow-lg">
            <span class="font-caption text-caption uppercase tracking-wider text-primary mb-md block">New Collection</span>
            <h1 class="font-h1-mobile md:font-h1 text-h1-mobile md:text-h1 text-on-surface mb-lg">
                The Artisan Leather Series
            </h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant mb-2xl">
                Discover unparalleled craftsmanship with our latest collection of hand-stitched, full-grain leather essentials designed for the modern connoisseur.
            </p>
            <div class="flex flex-col sm:flex-row gap-lg w-full sm:w-auto">
                <x-ui.button href="{{ route('products.index') }}" variant="primary" size="md" class="text-center">
                    Shop Now
                </x-ui.button>
                <x-ui.button href="#" variant="secondary" size="md" class="text-center">
                    Explore Collection
                </x-ui.button>
            </div>
        </div>
    </div>

</section>

{{-- ─── Featured Categories ──────────────────────────── --}}
<section class="py-6xl px-gutter max-w-container-max mx-auto">
    <div class="flex justify-between items-end mb-4xl">
        <div>
            <h2 class="font-h2 text-h2 text-on-background mb-xs">Featured Categories</h2>
            <p class="font-body-default text-body-default text-on-surface-variant">Curated essentials for your everyday life.</p>
        </div>
        <a class="hidden md:flex items-center gap-xs font-button text-button text-primary hover:text-surface-tint transition-colors group"
           href="{{ route('products.index') }}">
            View All <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg h-auto md:h-[500px]">

        {{-- Large Card --}}
        @if(isset($categories[0]))
            <div class="md:col-span-2 md:row-span-2">
                <x-cards.category-card :category="$categories[0]" size="large" />
            </div>
        @else
            <a class="group relative rounded-xl overflow-hidden md:col-span-2 md:row-span-2 hover-lift h-[300px] md:h-full block" href="#">
                <div class="bg-cover bg-center w-full h-full absolute inset-0 transition-transform duration-700 group-hover:scale-105"
                     style="background-image: url('{{ asset('images/cat-timepieces.jpg') }}')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-on-background/80 via-on-background/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-2xl w-full">
                    <h3 class="font-h3 text-h3 text-on-primary mb-xs">Timepieces</h3>
                    <p class="font-body-default text-body-default text-surface-container-low mb-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">Precision engineering meets timeless design.</p>
                    <span class="inline-flex items-center gap-xs font-button text-button text-on-primary border-b border-on-primary pb-xs">Shop Watches <span class="material-symbols-outlined text-[16px]">arrow_forward</span></span>
                </div>
            </a>
        @endif

        {{-- Small Cards --}}
        @foreach($categories->skip(1)->take(2) as $category)
            <x-cards.category-card :category="$category" size="small" />
        @endforeach

        {{-- Fallback small cards --}}
        @if(!isset($categories) || $categories->count() < 2)
            <a class="group relative rounded-xl overflow-hidden hover-lift h-[240px] md:h-auto block" href="#">
                <div class="bg-cover bg-center w-full h-full absolute inset-0 transition-transform duration-700 group-hover:scale-105"
                     style="background-image: url('{{ asset('images/cat-carry.jpg') }}')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-on-background/70 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-lg w-full">
                    <h4 class="font-h4 text-h4 text-on-primary mb-xs">Everyday Carry</h4>
                    <span class="inline-flex items-center gap-xs font-button text-button text-surface-container-low">Wallets &amp; Accessories <span class="material-symbols-outlined text-[16px]">arrow_forward</span></span>
                </div>
            </a>
            <a class="group relative rounded-xl overflow-hidden hover-lift h-[240px] md:h-auto block" href="#">
                <div class="bg-cover bg-center w-full h-full absolute inset-0 transition-transform duration-700 group-hover:scale-105"
                     style="background-image: url('{{ asset('images/cat-travel.jpg') }}')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-on-background/70 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-lg w-full">
                    <h4 class="font-h4 text-h4 text-on-primary mb-xs">Travel</h4>
                    <span class="inline-flex items-center gap-xs font-button text-button text-surface-container-low">Luggage &amp; Bags <span class="material-symbols-outlined text-[16px]">arrow_forward</span></span>
                </div>
            </a>
        @endif

    </div>

    <div class="mt-xl text-center md:hidden">
        <x-ui.button href="{{ route('products.index') }}" variant="outline" size="full">
            View All Categories
        </x-ui.button>
    </div>
</section>

{{-- ─── Best Sellers ─────────────────────────────────── --}}
<section class="py-6xl px-gutter bg-surface-container-low">
    <div class="max-w-container-max mx-auto">
        <div class="text-center mb-5xl">
            <h2 class="font-h2 text-h2 text-on-background mb-xs">Best Sellers</h2>
            <p class="font-body-default text-body-default text-on-surface-variant">Our most loved pieces, chosen by you.</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-lg gap-y-3xl">
            @forelse($bestSellers ?? [] as $product)
                <x-cards.product-card :product="$product" />
            @empty
                {{-- Demo cards preserved from original design --}}
                @for($i = 0; $i < 4; $i++)
                    <div class="group flex flex-col h-full bg-surface rounded-lg p-md shadow-sm border border-outline-variant/30 hover-lift relative {{ $i === 3 ? 'hidden lg:flex' : '' }}">
                        <div class="relative w-full aspect-[4/5] bg-surface-container-lowest rounded-md overflow-hidden mb-lg">
                            <div class="w-full h-full bg-surface-container animate-pulse"></div>
                        </div>
                        <div class="h-3 bg-surface-container rounded animate-pulse mb-xs w-3/4"></div>
                        <div class="h-3 bg-surface-container rounded animate-pulse w-1/2 mt-auto"></div>
                    </div>
                @endfor
            @endforelse
        </div>
    </div>
</section>

@endsection
