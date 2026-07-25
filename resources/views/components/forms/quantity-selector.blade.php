{{--
 | Component: components/forms/quantity-selector.blade.php
 | Purpose  : Cart/PDP quantity stepper.
 | Usage    : <x-forms.quantity-selector name="qty" :value="1" :min="1" :max="99" />
--}}
@props([
    'name'  => 'quantity',
    'value' => 1,
    'min'   => 1,
    'max'   => 99,
    'id'    => 'qty',
])

<div class="flex items-center border border-outline-variant rounded-md overflow-hidden bg-surface h-10 shadow-sm"
     role="group"
     aria-label="Quantity selector">

    <button type="button"
            aria-label="Decrease quantity"
            onclick="
                const inp = document.getElementById('{{ $id }}');
                if(inp.value > {{ $min }}) { inp.value = parseInt(inp.value) - 1; inp.dispatchEvent(new Event('change')); }
            "
            class="px-md hover:bg-surface-container-high transition-colors h-full flex items-center justify-center text-on-surface-variant">
        <span class="material-symbols-outlined text-sm">remove</span>
    </button>

    <input id="{{ $id }}"
           type="number"
           name="{{ $name }}"
           value="{{ $value }}"
           min="{{ $min }}"
           max="{{ $max }}"
           class="font-body-default text-body-default px-md text-center min-w-[2.5rem] border-none focus:ring-0 bg-transparent"
           aria-label="Quantity"/>

    <button type="button"
            aria-label="Increase quantity"
            onclick="
                const inp = document.getElementById('{{ $id }}');
                if(inp.value < {{ $max }}) { inp.value = parseInt(inp.value) + 1; inp.dispatchEvent(new Event('change')); }
            "
            class="px-md hover:bg-surface-container-high transition-colors h-full flex items-center justify-center text-on-surface-variant">
        <span class="material-symbols-outlined text-sm">add</span>
    </button>

</div>
