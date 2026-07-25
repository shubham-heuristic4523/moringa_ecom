{{--
 | Page: customer/products/index.blade.php
 | Layout: layouts/app
 | Source: product_listing_page_desktop/code.html + product_listing_page_mobile/code.html
--}}
@extends('layouts.app')

@php
    $title       = ($category->name ?? 'Products') . ' - LUXE Ecommerce';
    $description = $category->description ?? 'Discover our curated collection of premium products.';
    $activeTab   = 'search';
    $activeLink  = 'shop';
@endphp

@push('styles')
<style type="text/tailwindcss">
    @layer utilities {
        .glass-panel { @apply bg-surface/80 backdrop-blur-md border border-outline-variant/30 shadow-sm; }
        .nav-link { @apply relative overflow-hidden transition-colors duration-200; }
        .nav-link::after { content:''; @apply absolute bottom-0 left-0 w-full h-0.5 bg-primary scale-x-0 transition-transform duration-300 origin-right; }
        .nav-link:hover::after, .nav-link.active::after { @apply scale-x-100 origin-left; }
        .product-card { @apply group relative rounded-lg border border-outline-variant/30 bg-surface overflow-hidden transition-all duration-300 hover:shadow-md hover:-translate-y-1; }
        .product-image-wrapper { @apply aspect-[4/5] relative overflow-hidden bg-surface-container-low; }
        .product-image { @apply object-cover w-full h-full transition-transform duration-700 group-hover:scale-105; }
        .wishlist-btn { @apply absolute top-sm right-sm w-8 h-8 rounded-full bg-surface/90 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-on-surface-variant hover:text-error hover:bg-error-container; }
        .quick-add-btn { @apply absolute bottom-sm left-sm right-sm bg-primary/90 hover:bg-primary text-on-primary font-button text-button py-sm rounded-md opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 backdrop-blur-sm flex items-center justify-center gap-xs; }
        .badge { @apply absolute top-sm left-sm px-xs py-[2px] text-[10px] font-bold uppercase tracking-wider rounded-sm; }
        .filter-section-header { @apply flex items-center justify-between py-sm cursor-pointer hover:text-primary transition-colors border-t border-outline-variant/20 mt-sm pt-sm; }
        .checkbox-label { @apply flex items-center gap-sm cursor-pointer group; }
        .checkbox-custom { @apply w-4 h-4 border border-outline rounded-sm flex items-center justify-center text-transparent transition-colors group-hover:border-primary; }
        input[type="checkbox"]:checked + .checkbox-custom { @apply bg-primary border-primary text-on-primary; }
    }
    .filter-scroll::-webkit-scrollbar { width: 4px; }
    .filter-scroll::-webkit-scrollbar-track { background: transparent; }
    .filter-scroll::-webkit-scrollbar-thumb { @apply bg-outline-variant/50 rounded-full; }
</style>
@endpush

@section('content')

<div class="flex-grow w-full max-w-container-max mx-auto px-4 md:px-gutter py-2xl md:py-4xl flex flex-col gap-3xl">

    {{-- Breadcrumbs & Category Header --}}
    <section class="flex flex-col gap-md">
        <x-ui.breadcrumb :items="$breadcrumbs ?? [['label'=>'Home','url'=>route('home')],['label'=>$category->name ?? 'Products']]" />

        <div class="flex flex-col md:flex-row gap-xl items-start md:items-end justify-between">
            <div class="max-w-2xl">
                <h1 class="font-h1-mobile md:font-h1 text-h1-mobile md:text-h1 text-on-surface mb-sm">
                    {{ $category->name ?? 'Accessories &amp; Essentials' }}
                </h1>
                <p class="font-body-default text-body-default text-on-surface-variant">
                    {{ $category->description ?? 'Elevate your everyday with our curated collection of premium accessories.' }}
                </p>
            </div>

            {{-- Sub-category tabs --}}
            @if(isset($subCategories) && $subCategories->count() > 0)
                <div class="flex items-center gap-sm shrink-0 mt-4 md:mt-0 overflow-x-auto pb-2 md:pb-0 w-full md:w-auto no-scrollbar">
                    <a href="{{ route('products.index') }}"
                       class="px-md py-xs rounded-full border font-caption text-caption whitespace-nowrap
                              {{ !request('sub') ? 'border-primary bg-primary text-on-primary' : 'border-outline-variant text-on-surface hover:border-primary hover:text-primary transition-colors' }}">
                        All
                    </a>
                    @foreach($subCategories as $sub)
                        <a href="{{ route('products.index', ['category' => $category->slug, 'sub' => $sub->slug]) }}"
                           class="px-md py-xs rounded-full border font-caption text-caption whitespace-nowrap
                                  {{ request('sub') === $sub->slug ? 'border-primary bg-primary text-on-primary' : 'border-outline-variant text-on-surface hover:border-primary hover:text-primary transition-colors' }}">
                            {{ $sub->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <hr class="border-outline-variant/30"/>

    {{-- Toolbox --}}
    <section class="flex flex-wrap items-center justify-between gap-md">
        <span class="font-body-sm text-body-sm text-on-surface-variant">
            <strong class="text-on-surface font-semibold">{{ $products->total() ?? 0 }}</strong> items
        </span>

        <div class="flex items-center gap-lg ml-auto">

            {{-- Mobile Filter Toggle --}}
            <button class="lg:hidden flex items-center gap-xs font-button text-button text-on-surface hover:text-primary transition-colors p-xs border border-outline-variant rounded-md px-sm"
                    aria-label="Open filters">
                <span class="material-symbols-outlined text-[18px]">tune</span>
                Filters
            </button>

            {{-- Sort --}}
            <div class="relative group">
                <button class="flex items-center gap-xs font-button text-button text-on-surface hover:text-primary transition-colors p-xs border-b border-transparent hover:border-primary">
                    Sort by: {{ request('sort', 'Recommended') }}
                    <span class="material-symbols-outlined text-[18px] group-hover:rotate-180 transition-transform duration-300">expand_more</span>
                </button>
                <div class="absolute top-full right-0 mt-xs w-48 bg-surface rounded-lg shadow-md border border-outline-variant/30 py-sm opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-20">
                    @foreach(['recommended'=>'Recommended','price_asc'=>'Price: Low to High','price_desc'=>'Price: High to Low','newest'=>'Newest Arrivals'] as $key => $label)
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $key]) }}"
                           class="block px-md py-xs text-body-sm {{ request('sort', 'recommended') === $key ? 'font-medium text-primary bg-primary-container/10' : 'text-on-surface hover:bg-surface-container-low transition-colors' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Grid/List Toggle --}}
            <div class="hidden sm:flex items-center bg-surface-container-low rounded-md p-xs border border-outline-variant/30">
                <button aria-label="Grid View" class="p-xs rounded text-primary bg-surface shadow-sm">
                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">grid_view</span>
                </button>
                <button aria-label="List View" class="p-xs rounded text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[20px]">view_list</span>
                </button>
            </div>

        </div>
    </section>

    {{-- Layout: Sidebar + Grid --}}
    <div class="flex gap-3xl items-start">

        {{-- Sticky Sidebar Filters --}}
        <aside class="hidden lg:block w-64 shrink-0 sticky top-24 self-start max-h-[calc(100vh-8rem)] overflow-y-auto filter-scroll pr-md pb-md">
            <div class="flex items-center justify-between mb-lg">
                <h2 class="font-h5 text-h5 text-on-surface">Filters</h2>
                <a href="{{ request()->url() }}" class="font-caption text-caption text-on-surface-variant hover:text-primary underline">Clear All</a>
            </div>

            {{-- Availability --}}
            <div class="mb-lg">
                <div class="filter-section-header group border-t-0 pt-0 mt-0">
                    <span class="font-h6 text-h6 text-on-surface group-hover:text-primary">Availability</span>
                    <span class="material-symbols-outlined text-[20px] text-on-surface-variant group-hover:text-primary transition-transform">remove</span>
                </div>
                <div class="flex flex-col gap-sm mt-xs">
                    <label class="checkbox-label">
                        <input type="checkbox" name="availability[]" value="in_stock"
                               {{ in_array('in_stock', request('availability', [])) ? 'checked' : '' }} class="sr-only"/>
                        <span class="checkbox-custom"><span class="material-symbols-outlined text-[12px] font-bold">check</span></span>
                        <span class="font-body-sm text-body-sm text-on-surface flex-grow">In Stock</span>
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="availability[]" value="preorder"
                               {{ in_array('preorder', request('availability', [])) ? 'checked' : '' }} class="sr-only"/>
                        <span class="checkbox-custom"><span class="material-symbols-outlined text-[12px] font-bold">check</span></span>
                        <span class="font-body-sm text-body-sm text-on-surface flex-grow">Pre-order</span>
                    </label>
                </div>
            </div>

            {{-- Price --}}
            <div class="mb-lg">
                <div class="filter-section-header group">
                    <span class="font-h6 text-h6 text-on-surface group-hover:text-primary">Price</span>
                    <span class="material-symbols-outlined text-[20px] text-on-surface-variant group-hover:text-primary transition-transform">remove</span>
                </div>
                <div class="mt-sm space-y-md">
                    <div class="relative h-1 bg-outline-variant/50 rounded-full mt-md mb-xl mx-2">
                        <div class="absolute left-1/4 right-1/4 h-full bg-primary rounded-full"></div>
                        <div class="absolute left-1/4 top-1/2 -translate-y-1/2 w-4 h-4 bg-surface border-2 border-primary rounded-full shadow-sm cursor-pointer"></div>
                        <div class="absolute right-1/4 top-1/2 -translate-y-1/2 w-4 h-4 bg-surface border-2 border-primary rounded-full shadow-sm cursor-pointer"></div>
                    </div>
                    <div class="flex items-center gap-sm">
                        <div class="flex-1 bg-surface border border-outline-variant/50 rounded-md px-sm py-xs flex items-center">
                            <span class="text-on-surface-variant text-caption mr-xs">$</span>
                            <input type="number" name="price_min" value="{{ request('price_min', 0) }}"
                                   class="w-full bg-transparent border-none p-0 text-body-sm focus:ring-0"/>
                        </div>
                        <span class="text-on-surface-variant">-</span>
                        <div class="flex-1 bg-surface border border-outline-variant/50 rounded-md px-sm py-xs flex items-center">
                            <span class="text-on-surface-variant text-caption mr-xs">$</span>
                            <input type="number" name="price_max" value="{{ request('price_max', 9999) }}"
                                   class="w-full bg-transparent border-none p-0 text-body-sm focus:ring-0"/>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Brand --}}
            @if(isset($brands) && $brands->count() > 0)
            <div class="mb-lg">
                <div class="filter-section-header group">
                    <span class="font-h6 text-h6 text-on-surface group-hover:text-primary">Brand</span>
                    <span class="material-symbols-outlined text-[20px] text-on-surface-variant">remove</span>
                </div>
                <div class="flex flex-col gap-sm mt-xs max-h-48 overflow-y-auto filter-scroll pr-xs">
                    @foreach($brands as $brand)
                        <label class="checkbox-label">
                            <input type="checkbox" name="brands[]" value="{{ $brand->slug }}"
                                   {{ in_array($brand->slug, request('brands', [])) ? 'checked' : '' }} class="sr-only"/>
                            <span class="checkbox-custom"><span class="material-symbols-outlined text-[12px] font-bold">check</span></span>
                            <span class="font-body-sm text-body-sm text-on-surface flex-grow">{{ $brand->name }}</span>
                            <span class="text-caption text-on-surface-variant">({{ $brand->products_count }})</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

        </aside>

        {{-- Product Grid --}}
        <div class="flex-grow">

            {{-- Active Filters --}}
            @if(request()->hasAny(['brands','availability','price_min','price_max']))
                <div class="flex flex-wrap gap-sm mb-lg">
                    @foreach(request('brands', []) as $brand)
                        <span class="inline-flex items-center gap-xs px-sm py-1 bg-surface-container-high rounded-full border border-outline-variant/50 text-caption font-medium text-on-surface">
                            Brand: {{ $brand }}
                            <a href="{{ request()->fullUrlWithQuery(['brands' => array_diff(request('brands', []), [$brand])]) }}"
                               class="hover:text-error transition-colors p-[1px]">
                                <span class="material-symbols-outlined text-[14px]">close</span>
                            </a>
                        </span>
                    @endforeach
                    <a href="{{ request()->url() }}" class="text-caption font-medium text-on-surface-variant hover:text-primary underline px-sm py-1">
                        Clear All
                    </a>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-lg md:gap-xl">
                @forelse($products as $product)
                    <x-cards.product-card :product="$product" :showSwatches="true" />
                @empty
                    <div class="col-span-full text-center py-8xl">
                        <span class="material-symbols-outlined text-[64px] text-on-surface-variant/30">search_off</span>
                        <h3 class="font-h4 text-h4 text-on-surface-variant mt-lg">No products found</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-sm">Try adjusting your filters.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if(isset($products) && $products->hasPages())
                <x-ui.pagination :paginator="$products" />
            @endif

        </div>

    </div>

</div>

@endsection
