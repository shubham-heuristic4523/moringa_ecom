{{--
 | Component: components/cards/category-card.blade.php
 | Purpose  : Hero category card with image overlay and hover animation.
 | Usage    : <x-cards.category-card :category="$cat" size="large" />
--}}
@props([
    'category' => null,
    'size'     => 'small', // 'large' | 'small'
])

@php
    $heading = $size === 'large' ? 'font-h3 text-h3' : 'font-h4 text-h4';
    $href = route('categories.show', $category->slug ?? '#');
@endphp

<a href="{{ $href }}"
   class="group relative rounded-xl overflow-hidden hover-lift block {{ $size === 'large' ? 'h-[300px] md:h-full' : 'h-[240px] md:h-auto' }}">

    {{-- Background Image --}}
    <div class="bg-cover bg-center w-full h-full absolute inset-0 transition-transform duration-700 group-hover:scale-105"
         style="background-image: url('{{ $category->image_url ?? '' }}')"></div>

    {{-- Gradient Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-t from-on-background/{{ $size === 'large' ? '80' : '70' }} {{ $size === 'large' ? 'via-on-background/20' : '' }} to-transparent"></div>

    {{-- Content --}}
    <div class="absolute bottom-0 left-0 p-{{ $size === 'large' ? '2xl' : 'lg' }} w-full">
        <h3 class="{{ $heading }} text-on-primary mb-xs">{{ $category->name ?? 'Category' }}</h3>

        @if($size === 'large' && isset($category->description))
            <p class="font-body-default text-body-default text-surface-container-low mb-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                {{ $category->description }}
            </p>
        @endif

        <span class="inline-flex items-center gap-xs font-button text-button text-{{ $size === 'large' ? 'on-primary border-b border-on-primary pb-xs' : 'surface-container-low' }}">
            {{ $category->cta ?? 'Shop '.$category->name ?? 'Shop Now' }}
            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
        </span>
    </div>

</a>
