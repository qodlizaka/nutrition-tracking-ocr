<div class="w-full bg-white dark:bg-zinc-900 border-2 border-zinc-900 dark:border-zinc-100 p-6 font-sans text-zinc-900 dark:text-zinc-100 shadow-[4px_4px_0px_0px_rgba(24,24,27,1)] dark:shadow-[4px_4px_0px_0px_rgba(255,255,255,1)]">
    <h2 class="text-4xl font-black border-b-[1px] border-zinc-900 dark:border-zinc-100 pb-2 mb-2 tracking-tight leading-none">
        Nutrition Facts
    </h2>

    <div class="text-base font-bold border-b-[8px] border-zinc-900 dark:border-zinc-100 pb-2 mb-4">
        <div class="flex justify-between items-baseline">
            <span>Serving Size</span>
            <span class="font-bold">
                {{ $food->serving_size }}{{ $food->serving_unit }}
            </span>
        </div>
    </div>

    <div class="flex justify-between items-end border-b-[4px] border-zinc-900 dark:border-zinc-100 pb-4 mb-4">
        <div>
            <div class="text-sm font-bold">Amount Per Serving</div>
            <div class="text-3xl font-black">Calories</div>
        </div>
        <div class="text-5xl font-black leading-none tracking-tight">
            {{ ($this->nutritionMap->get('energy')?->pivot->value ?? 0) * $multiplier }}
        </div>
    </div>

    {{-- Main Macros List --}}
    <div class="space-y-1 text-sm border-b-[8px] border-zinc-900 dark:border-zinc-100 pb-4 mb-4">
        @php
            $renderRow = function($key, $label, $isBold = true, $indent = false) {
                $item = $this->nutritionMap->get($key);
                $value = ($item?->pivot->value ?? 0) * $this->multiplier;
                $unit = $item?->unit ?? 'g';
                return '
                <div class="flex justify-between border-b border-zinc-200 dark:border-zinc-700 py-1.5 last:border-0">
                    <div class="'.($indent ? 'pl-4' : '').'">
                        <span class="'.($isBold ? 'font-black' : '').'">'.$label.'</span>
                        '.$value.$unit.'
                    </div>
                    <div class="font-bold">
                        '. ($value > 0 ? '' : '-') .'
                    </div>
                </div>';
            };
        @endphp

        {!! $renderRow('total fat', 'Total Fat') !!}
        {!! $renderRow('sodium', 'Sodium') !!}
        {!! $renderRow('total carbohydrate', 'Total Carbohydrate') !!}
        {!! $renderRow('protein', 'Protein') !!}
    </div>

    {{-- Micros (Dynamic) --}}
    <div class="space-y-1 text-sm">
        @foreach($this->micros as $micro)
            <div class="flex justify-between items-center border-b border-zinc-200 dark:border-zinc-800 py-1.5 last:border-0">
                <span class="font-medium">
                    {{ Str::title($micro->name) }}
                </span>
                <span>
                    {{ ($micro?->pivot?->value) * $multiplier }} <span class="text-xs text-zinc-500">{{ $micro->unit }}</span>
                </span>
            </div>
        @endforeach
    </div>

    <div class="mt-6 pt-4 border-t border-zinc-300 dark:border-zinc-700 text-[10px] leading-relaxed text-zinc-500">
        * The % Daily Value (DV) tells you how much a nutrient in a serving of food contributes to a daily diet. 2,150 calories a day is used for general nutrition advice.
    </div>
</div>
