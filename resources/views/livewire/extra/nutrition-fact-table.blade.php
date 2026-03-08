@use("Illuminate\Support\Str")

<form wire:submit.prevent="save" class="w-full bg-white dark:bg-zinc-900 border-2 border-zinc-900 dark:border-zinc-100 p-6 font-sans text-zinc-900 dark:text-zinc-100 shadow-[4px_4px_0px_0px_rgba(24,24,27,1)] dark:shadow-[4px_4px_0px_0px_rgba(255,255,255,1)]" data-testid="nutrition-table-form">

    <div class="flex justify-between items-start border-b-[1px] border-zinc-900 dark:border-zinc-100 pb-2 mb-2">
        <h2 class="text-4xl font-black tracking-tight leading-none" data-testid="nutrition-table-header">
            Nutrition Facts
        </h2>
        <div class="flex flex-nowrap gap-1 items-center">
            @if($editable)
                <flux:button type="submit" size="sm" variant="primary" data-testid="nutrition-table-save-button">Save</flux:button>
                <x-action-message on="nutritions-saved" data-testid="nutrition-table-save-message">
                    {{ __('Saved.') }}
                </x-action-message>
            @endif
        </div>
    </div>

    {{-- Serving Size Display (Static) --}}
    <div class="text-base font-bold border-b-[8px] border-zinc-900 dark:border-zinc-100 pb-2 mb-4" data-testid="nutrition-table-serving-size-container">
        <div class="flex justify-between items-baseline">
            <span>Serving Size</span>
            <span class="font-bold" data-testid="nutrition-table-serving-size-value">
                {{ $food->serving_size }}{{ $food->serving_unit }}
            </span>
        </div>
    </div>

    {{-- Calories Section --}}
    <div class="flex justify-between items-end border-b-[4px] border-zinc-900 dark:border-zinc-100 pb-4 mb-4" data-testid="nutrition-table-calories-container">
        <div>
            <div class="text-sm font-bold">{{ __('Amount Per Serving') }}</div>
            <div class="text-3xl font-black">{{ __('Calories') }}</div>
        </div>
        <div class="text-5xl font-black leading-none tracking-tight">
            @if($editable && $id = $this->getIdFor('energy'))
                <flux:input
                    wire:model="form.{{ $id }}.value"
                    type="number"
                    step="0.1"
                    class="!text-4xl !font-black !w-32 text-right !border-0 !p-0 !shadow-none focus:!ring-0"
                    data-testid="nutrition-table-calories-input"
                />
            @else
                <span data-testid="nutrition-table-calories-value">
                    {{ number_format($this->calculate($this->nutritionMap->get('energy')), 0) }}
                </span>
            @endif
        </div>
    </div>

    {{-- Main Macros List --}}
    <div class="space-y-1 text-sm border-b-[8px] border-zinc-900 dark:border-zinc-100 pb-4 mb-4" data-testid="nutrition-table-macros-container">

        @foreach (['Total fat', 'Sodium', 'Total carbohydrate', 'protein'] as $macro)
            @php
                $lower = Str::lower($macro);
                $id = $this->getIdFor($lower);
            @endphp

            @if($id && isset($this->form[$id]))
                <x-nutrition-facts-table.nutrition-row
                    label="{{ __($macro) }}"
                    :item="$this->nutritionMap->get($lower)"
                    multiplier="{{ $multiplier }}"
                    editable="{{ $editable }}"
                    formKey="{{ $id }}"
                    pivotType="{{ array_key_first($this->form[$id]) }}"
                />
            @endif
        @endforeach

    </div>

    {{-- Micros (Dynamic Loop) --}}
    <div class="space-y-1 text-sm" data-testid="nutrition-table-micros-container">
        @foreach($this->micros as $micro)
            <div wire:key="micro-row-{{ $micro->id }}" class="flex justify-between items-center border-b border-zinc-200 dark:border-zinc-800 py-1.5 last:border-0" data-testid="nutrition-table-micro-row-{{ $micro->id }}">
                <span class="font-medium" data-testid="nutrition-table-micro-label-{{ $micro->id }}">
                    {{ Str::title($micro->name) }}
                </span>
                <span class="flex items-center gap-1">
                    @if($editable)
                        @if(array_key_first($this->form[$micro->id]) === 'percentage')
                            <flux:input
                                wire:model="form.{{ $micro->id }}.percentage"
                                type="number"
                                step="any"
                                size="sm"
                                class="!w-24 !h-8 !text-right !text-xs"
                                data-testid="nutrition-table-micro-percentage-input-{{ $micro->id }}"
                            />
                        @else
                            <flux:input
                                wire:model="form.{{ $micro->id }}.value"
                                type="number"
                                step="any"
                                size="sm"
                                class="!w-24 !h-8 !text-right !text-xs"
                                data-testid="nutrition-table-micro-value-input-{{ $micro->id }}"
                            />
                        @endif
                        <span class="text-xs text-zinc-500">{{ $micro->pivot?->percentage !== null ? '%' : $micro->unit }}</span>
                    @else
                        <span data-testid="nutrition-table-micro-static-value-{{ $micro->id }}">
                            {{ $this->calculate($micro) }}
                        </span>
                        <span class="text-xs text-zinc-500">{{ $micro->unit }}</span>
                    @endif
                </span>
            </div>
        @endforeach
    </div>

    <div class="text-red-500 text-xs mt-2" data-testid="nutrition-table-error-container">
        @error('form.*') <span class="error" data-testid="nutrition-table-error-message">{{ $message }}</span> @enderror
    </div>

</form>
