@props([
    'item',
    'label',
    'multiplier' => 1,
    'isBold' => true,
    'indent' => false,
    'editable' => false,
    'formKey' => null,
    'pivotType' => null,
])

@php
    $displayValue = isset($item) ? ($item->pivot?->value * $multiplier) : 0;
    $unit = $item->unit ?? 'g';
@endphp

<div class="flex justify-between items-center border-b border-zinc-200 dark:border-zinc-700 py-1.5 last:border-0" data-testid="nutrition-row-container-{{ $formKey }}">
    <div class="{{ $indent ? 'pl-4' : '' }}">
        <span class="{{ $isBold ? 'font-black' : '' }}" data-testid="nutrition-row-label-{{ $formKey }}">
            {{ $label }}
        </span>

        @if(!$editable)
            <span data-testid="nutrition-row-static-value-{{ $formKey }}">
                {{ round($displayValue, 4) }}{{ $unit }}
            </span>
        @endif
    </div>

    <div class="font-bold">
        @if($editable && $formKey)
            <div class="flex items-center gap-1">
                @if($pivotType === 'percentage')
                    <flux:input
                        wire:model="form.{{ $formKey }}.percentage"
                        type="number"
                        size="sm"
                        step="any"
                        class="!w-24 !h-8 !text-right !text-xs"
                        data-testid="nutrition-row-percentage-input-{{ $formKey }}"
                    />
                @else
                    <flux:input
                        wire:model="form.{{ $formKey }}.value"
                        type="number"
                        size="sm"
                        step="any"
                        class="!w-24 !h-8 !text-right !text-xs"
                        data-testid="nutrition-row-value-input-{{ $formKey }}"
                    />
                @endif
                <span class="text-xs font-normal text-zinc-500">{{ $pivotType === 'percentage' ? '%' : $unit }}</span>
            </div>
        @endif
    </div>
</div>
