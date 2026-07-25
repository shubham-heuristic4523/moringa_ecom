{{--
 | Component: components/cards/product-card.blade.php
 | Purpose  : Universal product card — shared between Home, PLP, Wishlist, and Recommendations.
 | Usage    : <x-cards.product-card :product="$product" />
--}}
@props([
    'product' => null,
    'showBadge'   => true,
    'showWishlist' => true,
    'showQuickAdd' => true,
    'showSwatches' => false,
])

@php
    $badge = $product->badge ?? null; // 'new', 'sale', 'sold-out', 'almost-gone'
    $isSoldOut = $product->stock_qty === 0 ?? false;
    $salePrice = $product->sale_price ?? null;
    $regularPrice = $product->price ?? 0;
    $rating = $product->rating ?? null;
    $reviewCount = $product->review_count ?? 0;
    $productUrl = route('products.show', $product->slug ?? '#');
@endphp

<article class="group flex flex-col h-full bg-surface rounded-lg p-md shadow-sm border border-outline-variant/30 hover-lift relative overflow-hidden">

    {{-- Wishlist Button --}}
    @if($showWishlist)
        <form method="POST" action="{{ route('customer.wishlist.toggle', $product->id ?? 0) }}" class="absolute top-xl right-xl z-10">
            @csrf
            <button type="submit"
                    aria-label="{{ $product->in_wishlist ?? false ? 'Remove from Wishlist' : 'Add to Wishlist' }}"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-surface/80 backdrop-blur text-on-surface-variant hover:text-primary transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px] {{ $product->in_wishlist ?? false ? 'fill text-primary' : '' }}">favorite</span>
            </button>
        </form>
    @endif

    {{-- Badge --}}
    @if($showBadge && $badge)
        @if($badge === 'sold-out')
            <div class="absolute top-lg left-lg z-10 px-sm py-xs bg-error text-on-error font-caption text-caption font-bold rounded-sm uppercase tracking-wider">
                Sold Out
            </div>
        @elseif($badge === 'new')
            <div class="absolute top-sm left-sm z-10 px-xs py-[2px] text-[10px] font-bold uppercase tracking-wider rounded-sm bg-secondary-container text-on-secondary-container">
                New
            </div>
        @elseif($badge === 'sale')
            <div class="absolute top-sm left-sm z-10 px-xs py-[2px] text-[10px] font-bold uppercase tracking-wider rounded-sm bg-error-container text-on-error-container">
                Sale {{ $product->sale_percentage ? '-'.$product->sale_percentage.'%' : '' }}
            </div>
        @elseif($badge === 'almost-gone')
            <div class="absolute top-sm left-sm z-10 px-xs py-[2px] text-[10px] font-bold uppercase tracking-wider rounded-sm bg-surface text-on-surface border border-outline-variant/30">
                Almost Gone
            </div>
        @endif
    @endif

    {{-- Product Image --}}
    <a href="{{ $productUrl }}" class="block relative w-full aspect-[4/5] bg-surface-container-lowest rounded-md overflow-hidden mb-lg {{ $isSoldOut ? 'opacity-70' : '' }}">
        <img src="{{ $product->image_url ?? '' }}"
             alt="{{ $product->name ?? 'Product Image' }}"
             loading="lazy"
             class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105"/>
        <div class="absolute inset-0 bg-on-background/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

        {{-- Quick Add --}}
        @if($showQuickAdd && !$isSoldOut)
            <form method="POST" action="{{ route('cart.add') }}"
                  class="quick-add-btn z-10">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id ?? '' }}">
                <button type="submit" class="flex items-center gap-xs w-full justify-center">
                    <span class="material-symbols-outlined text-[18px]">add_shopping_cart</span>
                    Quick Add
                </button>
            </form>
        @endif
    </a>

    {{-- Product Info --}}
    <div class="flex flex-col flex-grow">

        {{-- Rating --}}
        @if($rating)
            <div class="flex items-center gap-xs mb-xs">
                <span class="material-symbols-outlined text-[14px] text-primary fill">star</span>
                <span class="font-caption text-caption text-on-surface-variant">{{ number_format($rating, 1) }} ({{ $reviewCount }})</span>
            </div>
        @endif

        {{-- Name and Brand --}}
        @if(isset($product->brand))
            <span class="font-caption text-caption text-on-surface-variant uppercase tracking-wider mb-xs">{{ $product->brand }}</span>
        @endif
        <a href="{{ $productUrl }}">
            <h3 class="font-body-default text-body-default text-on-background font-medium mb-xs line-clamp-1 hover:text-primary transition-colors">
                {{ $product->name ?? 'Product Name' }}
            </h3>
        </a>
        @if(isset($product->variant))
            <p class="font-body-sm text-body-sm text-on-surface-variant mb-md line-clamp-1">{{ $product->variant }}</p>
        @endif

        {{-- Color Swatches --}}
        @if($showSwatches && isset($product->colors) && count($product->colors) > 0)
            <div class="flex items-center gap-xs mb-sm">
                @foreach($product->colors as $color)
                    <button aria-label="{{ $color['name'] }}"
                            class="w-4 h-4 rounded-full border border-surface shadow-[0_0_0_1px_rgba(0,0,0,0.1)] hover:ring-1 hover:ring-outline-variant focus:outline-none"
                            style="background-color: {{ $color['hex'] }}"></button>
                @endforeach
            </div>
        @endif

        {{-- Price + Add to Cart --}}
        <div class="mt-auto flex items-center justify-between">
            <div class="flex items-center gap-sm">
                @if($salePrice)
                    <span class="font-h6 text-h6 text-error">{{ money($salePrice) }}</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant line-through">{{ money($regularPrice) }}</span>
                @else
                    <span class="font-h6 text-h6 text-on-background">{{ money($regularPrice) }}</span>
                @endif
            </div>

            @if(!$isSoldOut)
                <form method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id ?? '' }}">
                    <button type="submit"
                            aria-label="Add {{ $product->name ?? '' }} to Cart"
                            class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface hover:bg-primary hover:text-on-primary transition-colors">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                    </button>
                </form>
            @else
                <button disabled aria-disabled="true"
                        class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface opacity-50 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                </button>
            @endif
        </div>

    </div>

</article>
