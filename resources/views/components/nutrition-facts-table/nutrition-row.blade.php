@props([
    'item' => null,
    'label',
    'multiplier' => 1,
    'isBold' => true,
    'indent' => false,
])

@php
    $value = ($item?->pivot?->value ?? 0) * $multiplier;
    $unit = $item?->unit ?? 'g';
@endphp

<div class="flex justify-between border-b border-zinc-200 dark:border-zinc-700 py-1.5 last:border-0">
    <div class="{{ $indent ? 'pl-4' : '' }}">
        <span class="{{ $isBold ? 'font-black' : '' }}">
            {{ $label }}
        </span>
        {{ $value }}{{ $unit }}
    </div>

    <div class="font-bold">
        {{ $value > 0 ? '' : '-' }}
    </div>
</div>
