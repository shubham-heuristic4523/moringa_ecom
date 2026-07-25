{{--
 | Component: components/ui/pagination.blade.php
 | Purpose  : Laravel paginator styled to match the design system.
 | Usage    : <x-ui.pagination :paginator="$products" />
--}}
@props(['paginator'])

@if($paginator->hasPages())
<nav aria-label="Pagination" class="flex justify-center items-center gap-sm mt-5xl pt-xl border-t border-outline-variant/30">

    {{-- Previous --}}
    @if($paginator->onFirstPage())
        <button disabled class="w-10 h-10 flex items-center justify-center rounded-md text-on-surface-variant opacity-50 cursor-not-allowed">
            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
        </button>
    @else
        <a href="{{ $paginator->previousPageUrl() }}"
           class="w-10 h-10 flex items-center justify-center rounded-md text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
        </a>
    @endif

    {{-- Page Numbers --}}
    <div class="hidden sm:flex items-center gap-xs">
        @foreach($paginator->links()->elements as $element)
            @if(is_string($element))
                <span class="w-10 h-10 flex items-center justify-center text-on-surface-variant">{{ $element }}</span>
            @endif
            @if(is_array($element))
                @foreach($element as $page => $url)
                    @if($page == $paginator->currentPage())
                        <button class="w-10 h-10 flex items-center justify-center rounded-md font-button text-button bg-primary text-on-primary shadow-sm">
                            {{ $page }}
                        </button>
                    @else
                        <a href="{{ $url }}"
                           class="w-10 h-10 flex items-center justify-center rounded-md font-button text-button text-on-surface hover:bg-surface-container-high transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach
    </div>

    {{-- Mobile page indicator --}}
    <div class="sm:hidden font-body-sm text-body-sm text-on-surface-variant px-md">
        Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
    </div>

    {{-- Next --}}
    @if($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
           class="w-10 h-10 flex items-center justify-center rounded-md text-on-surface hover:text-primary hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
        </a>
    @else
        <button disabled class="w-10 h-10 flex items-center justify-center rounded-md text-on-surface-variant opacity-50 cursor-not-allowed">
            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
        </button>
    @endif

</nav>
@endif
