@use("Illuminate\Support\Str")

<div class="w-full">

    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}" icon="home" />
        <flux:breadcrumbs.item href="{{ route('intakes.index') }}">{{ __('Intake') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('intakes.chart') }}">{{ __('Chart') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mt-4">
        <div>
            <flux:heading size="xl">{{ __('Nutrition charts') }}</flux:heading>
            <flux:subheading>{{ __('Visualize your nutritional intake over time.') }}</flux:subheading>
        </div>
    </div>

    <flux:separator class="my-6" />

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mt-4">
        <div>
            <flux:heading level="3" size="lg">{{ __('Macro nutrient stats') }}</flux:heading>
            <flux:subheading class="max-w-xl">{{ __('Track your daily intake of essential macronutrients like protein, carbs, and fats to maintain a balanced diet and achieve your health goals.') }}</flux:subheading>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
        @foreach ($macroNutrients as $nutrition)
            <div class="rounded-xl border border-zinc-200 bg-white transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900 p-4">
                @php
                    $item = $chartData['macroNutrients']->get($nutrition->id);
                    $labels = $item->keys()->toArray();
                    $data = $item->values()->toArray();
                @endphp

                <livewire:extra.chart.date-range-data-chart
                    chartId="{{ Str::snake($nutrition->name) }}_chart"
                    :data="$data"
                    :labels="$labels"
                    title="{{ $nutrition->name }}"
                    :limit="$this->userAkg->nutritions->find($nutrition->id)?->pivot->value ?? null" />
            </div>
        @endforeach
    </div>

    <flux:separator class="my-6" />

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mt-4">
        <div>
            <flux:heading level="3" size="lg">{{ __('Micro nutrient stats') }}</flux:heading>
            <flux:subheading class="max-w-xl">{{ __('Track your daily intake of essential micronutrients like vitamins and minerals to maintain a balanced diet and achieve your health goals.') }}</flux:subheading>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 mt-4">
        @foreach ($microNutrients as $nutrition)
            <div class="rounded-xl border border-zinc-200 bg-white transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900 p-4">
                @php
                    $value = $chartData['microNutrients']->get($nutrition->id) ?? 0;
                    $limit = $userAkg->nutritions->get($nutrition->id)?->pivot->value ?? null;
                @endphp

                <x-extra.chart.micronutrient-intake-chart
                    :limit="$limit"
                    :intake="$value"
                    label="{{ $nutrition->name }}"
                    />

                <div class="text-center mt-4">
                    <flux:subheading class="font-bold">{{ __(ucfirst($nutrition->name)) }}</flux:heading>
                    <flux:subheading>
                        {{ round($value, 2) }}
                        @if($limit !== null)
                            / {{ $nutrition->unit }} ({{ $limit ? round(($value / $limit) * 100) : 100 }})%
                        @endif
                    </flux:subheading>
                </div>
            </div>
        @endforeach
        </div>
    </div>
</div>
