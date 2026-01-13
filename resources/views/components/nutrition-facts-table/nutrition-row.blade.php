@props([
    'item',
    'label',
    'multiplier' => 1,
    'isBold' => true,
    'indent' => false,
    'editable' => false,
    'formKey' => null
])

@php
    $displayValue = isset($item) ? ($item->pivot?->value * $multiplier) : 0;
    $unit = $item->unit ?? 'g';
@endphp

<div class="flex justify-between items-center border-b border-zinc-200 dark:border-zinc-700 py-1.5 last:border-0">
    <div class="{{ $indent ? 'pl-4' : '' }}">
        <span class="{{ $isBold ? 'font-black' : '' }}">
            {{ $label }}
        </span>

        @if(!$editable)
            {{ round($displayValue, 1) }}{{ $unit }}
        @endif
    </div>

    <div class="font-bold">
        @if($editable && $formKey)
            <div class="flex items-center">
                <flux:input
                    wire:model="form.{{ $formKey }}"
                    type="number"
                    size="sm"
                    step="any"
                    class="!w-24 !h-8 !text-right !text-xs"
                />
                <span class="text-xs font-normal text-zinc-500">{{ $unit }}</span>
            </div>
        @else
             {{ $displayValue > 0 ? '' : '-' }}
        @endif
    </div>
</div>
