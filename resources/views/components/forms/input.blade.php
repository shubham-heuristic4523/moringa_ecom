{{--
 | Component: components/forms/input.blade.php
 | Purpose  : Text input with floating label, error state, and optional icon.
 | Usage    : <x-forms.input id="email" name="email" type="email" label="Email address" :required="true" />
--}}
@props([
    'id'          => null,
    'name'        => null,
    'type'        => 'text',
    'label'       => null,
    'placeholder' => ' ',
    'value'       => null,
    'required'    => false,
    'disabled'    => false,
    'readonly'    => false,
    'trailingIcon'=> null,
    'floatingLabel'=> true,
    'helpText'    => null,
])

@php
    $errorKey   = $name ?? $id;
    $hasError   = $errors->has($errorKey);
    $inputClass = $hasError ? 'border-error focus:border-error focus:ring-error/10' : 'border-outline focus:border-primary focus:ring-primary/10';
    $old        = old($name ?? $id, $value);
@endphp

<div class="{{ $floatingLabel ? 'input-group relative' : 'relative' }}">

    @if(!$floatingLabel && $label)
        <label for="{{ $id }}" class="block font-body-sm text-body-sm font-medium text-on-surface mb-xs {{ $required ? 'after:content-[\"*\"] after:text-error after:ml-xs' : '' }}">
            {{ $label }}
        </label>
    @endif

    <input
        id="{{ $id }}"
        name="{{ $name ?? $id }}"
        type="{{ $type }}"
        value="{{ $old }}"
        placeholder="{{ $floatingLabel ? ' ' : ($placeholder ?? '') }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        {{ $attributes->merge(['class' =>
            "peer w-full " .
            ($floatingLabel
                ? "h-11 bg-transparent border-0 border-b $inputClass focus:ring-0 px-0 transition-all font-body-default"
                : "h-11 px-md bg-surface rounded $inputClass border focus:ring-1 transition-all font-body-default " .
                  ($trailingIcon ? 'pr-4xl' : '')
            )
        ]) }}
        aria-describedby="{{ $hasError ? $errorKey.'-error' : '' }}"
    />

    @if($floatingLabel && $label)
        <label for="{{ $id }}"
               class="absolute left-0 top-3 text-on-surface-variant/60 font-body-default">
            {{ $label }}{{ $required ? ' *' : '' }}
        </label>
    @endif

    @if($trailingIcon)
        <span class="absolute right-md top-1/2 -translate-y-1/2 text-on-surface-variant material-symbols-outlined text-[20px]">
            {{ $trailingIcon }}
        </span>
    @endif

    @if($hasError)
        <p id="{{ $errorKey }}-error" class="mt-xs font-caption text-caption text-error">
            {{ $errors->first($errorKey) }}
        </p>
    @endif

    @if(!$hasError && $helpText)
        <p class="mt-xs font-caption text-caption text-on-surface-variant">{{ $helpText }}</p>
    @endif

</div>
