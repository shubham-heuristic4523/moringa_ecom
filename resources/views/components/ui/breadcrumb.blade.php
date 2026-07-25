{{--
 | Component: components/ui/breadcrumb.blade.php
 | Purpose  : Accessible breadcrumb navigation trail.
 | Usage    : <x-ui.breadcrumb :items="[['label'=>'Home','url'=>route('home')], ['label'=>'Products']]" />
--}}
@props([
    'items' => [],
])

<nav aria-label="Breadcrumb" class="flex items-center gap-xs text-caption font-caption text-on-surface-variant">
    @foreach($items as $index => $item)
        @if($index > 0)
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        @endif

        @if($index < count($items) - 1)
            <a href="{{ $item['url'] ?? '#' }}"
               class="hover:text-primary transition-colors">{{ $item['label'] }}</a>
        @else
            <span aria-current="page" class="text-on-surface font-semibold">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
