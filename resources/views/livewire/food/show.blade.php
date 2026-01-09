<div class="w-full">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('home') }}" icon="home" />
        <flux:breadcrumbs.item href="{{ route('foods.index') }}">{{ __('Foods') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">{{ $food->name }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="p-6 lg:p-10 max-w-7xl mx-auto mt-4">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

            <div class="space-y-6">
                <div class="relative aspect-square overflow-hidden rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-sm bg-zinc-100 dark:bg-zinc-900 group">
                    @if($food->image)
                        <img
                            src="{{ asset('storage/' . $food->image) }}"
                            alt="{{ $food->name }}"
                            class="w-full h-full object-cover transition duration-700 group-hover:scale-105"
                        >
                    @else
                        <div class="flex items-center justify-center h-full text-zinc-400">
                            No Image
                        </div>
                    @endif
                </div>

                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight text-zinc-900 dark:text-white mb-2">
                        {{ $food->name }}
                    </h1>
                    <div class="flex items-center gap-4 text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        <span class="bg-zinc-100 dark:bg-zinc-800 px-3 py-1 rounded-full">
                            {{ $food->total_servings }}{{ $food->unit }} per serving
                        </span>
                    </div>
                </div>
            </div>

            <div class="w-full max-w-md bg-white dark:bg-zinc-900 border-2 border-zinc-900 dark:border-zinc-100 p-4 font-sans text-zinc-900 dark:text-zinc-100 shadow-[4px_4px_0px_0px_rgba(24,24,27,1)] dark:shadow-[4px_4px_0px_0px_rgba(255,255,255,1)]">

                <h2 class="text-4xl font-black border-b border-zinc-900 dark:border-zinc-100 pb-1 mb-1 tracking-tight leading-none">
                    Nutrition Facts
                </h2>
                <div class="text-sm font-bold border-b-[10px] border-zinc-900 dark:border-zinc-100 pb-2 mb-2">
                    <div>Serving Size <span class="font-normal float-right">1 Unit ({{ $food->unit }})</span></div>
                </div>

                <div class="flex justify-between items-end border-b-[4px] border-zinc-900 dark:border-zinc-100 pb-2 mb-3">
                    <div>
                        <div class="text-sm font-bold">Amount Per Serving</div>
                        <div class="text-3xl font-black">Calories</div>
                    </div>
                    <div class="text-5xl font-black leading-none">
                        {{ number_format($this->nutritionMap->get('energy')?->pivot->value ?? 0, 0) }}
                    </div>
                </div>

                {{-- Main Macros List --}}
                <div class="space-y-1 text-sm border-b-[10px] border-zinc-900 dark:border-zinc-100 pb-3 mb-3">
                    {{-- Helper function to render a row --}}
                    @php
                        $renderRow = function($key, $label, $isBold = true, $indent = false) {
                            $item = $this->nutritionMap->get($key);
                            $value = $item?->pivot->value ?? 0;
                            $unit = $item?->unit ?? 'g';
                            return '
                            <div class="flex justify-between border-b border-zinc-300 dark:border-zinc-700 py-1 last:border-0">
                                <div class="'.($indent ? 'pl-4' : '').'">
                                    <span class="'.($isBold ? 'font-black' : '').'">'.$label.'</span>
                                    '.$value.$unit.'
                                </div>
                                </div>';
                        };
                    @endphp

                    {!! $renderRow('total fat', 'Total Fat') !!}
                    {!! $renderRow('sodium', 'Sodium') !!}
                    {!! $renderRow('total carbohydrate', 'Total Carbohydrate') !!}
                    {!! $renderRow('protein', 'Protein') !!}
                </div>

                {{-- Micros (Vitamins & Minerals) --}}
                <div class="space-y-1 text-sm">
                    @foreach($this->micros as $micro)
                        <div class="flex justify-between items-center border-b border-zinc-200 dark:border-zinc-800 py-1 last:border-0">
                            <span class="font-medium">
                                {{ Str::title($micro->name) }}
                            </span>
                            <span>
                                {{ $micro->pivot->value }} <span class="text-xs text-zinc-500">{{ $micro->unit }}</span>
                            </span>
                        </div>
                    @endforeach
                </div>

                {{-- Footer Note --}}
                <div class="mt-4 pt-2 border-t border-zinc-300 dark:border-zinc-700 text-[10px] leading-tight text-zinc-500">
                    * The % Daily Value (DV) tells you how much a nutrient in a serving of food contributes to a daily diet. 2,000 calories a day is used for general nutrition advice.
                </div>
            </div>

        </div>
    </div>
</div>

