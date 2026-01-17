@use("Illuminate\Support\Str")

<div class="w-full">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item icon="home" href="{{ route('dashboard') }}" />
        <flux:breadcrumbs.item href="{{ route('intakes.index') }}">{{ __('Intake') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('intakes.chart') }}">{{ __('Chart') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Nutrition charts') }}</flux:heading>
            <flux:subheading>{{ __('Visualize your nutritional intake over time.') }}</flux:subheading>
        </div>
    </div>

    <flux:separator class="my-6" />

    <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading level="3" size="lg">{{ __('Macro nutrient stats') }}</flux:heading>
            <flux:subheading class="max-w-xl">
                {{ __('Track your daily intake of essential macronutrients like protein, carbs, and fats.') }}
            </flux:subheading>
        </div>

        <div class="flex items-center gap-4">
            <flux:input.group>
                <flux:input.group.prefix>{{ __('From') }}</flux:input.group.prefix>
                <flux:input
                    wire:model.live="startDateString"
                    type="date"
                    max="{{ now()->format('Y-m-d') }}"
                />
            </flux:input.group>

            <flux:input.group>
                <flux:input.group.prefix>{{ __('To') }}</flux:input.group.prefix>
                <flux:input
                    wire:model.live="endDateString"
                    type="date"
                    max="{{ now()->format('Y-m-d') }}"
                />
            </flux:input.group>
        </div>
    </div>

    {{-- Macro Grid --}}
    <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($macroNutrients as $nutrition)
            <div
                wire:key="macro-{{ $nutrition->id }}"
                class="rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900"
            >
                @php
                    $item = $chartData['macroNutrients']->get($nutrition->id);
                    $labels = $item ? $item->keys()->toArray() : [];
                    $data = $item ? $item->values()->toArray() : [];
                    $limit = $akgNutritions->get($nutrition->id)?->pivot->value ?? null;
                @endphp

                <x-extra.chart.date-range-data-chart
                    :data="$data"
                    :labels="$labels"
                    :limit="$limit"
                    :title="$nutrition->name"
                />
            </div>
        @endforeach
    </div>

    <flux:separator class="my-6" />

    <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading level="3" size="lg">{{ __('Micro nutrient stats') }}</flux:heading>
            <flux:subheading class="max-w-xl">
                {{ __('Track your daily intake of essential micronutrients.') }}
            </flux:subheading>
        </div>

        <div class="flex items-center gap-4">
            <flux:input.group>
                <flux:input.group.prefix>{{ __('Date') }}</flux:input.group.prefix>
                <flux:input
                    wire:model.live="dateString"
                    type="date"
                    max="{{ now()->format('Y-m-d') }}"
                />
            </flux:input.group>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-2 gap-6 md:grid-cols-4 lg:grid-cols-5">
        @foreach ($microNutrients as $nutrition)
            <div
                wire:key="micro-{{ $nutrition->id }}"
                class="rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900"
            >
                @php
                    $value = $chartData['microNutrients']->get($nutrition->id) ?? 0;
                    $limit = $akgNutritions->get($nutrition->id)?->pivot->value ?? null;
                @endphp

                <x-extra.chart.micronutrient-intake-chart
                    :limit="$limit"
                    :intake="$value"
                    :label="$nutrition->name"
                />

                <div class="mt-4 text-center">
                    <flux:subheading class="font-bold">
                        {{ __(ucfirst($nutrition->name)) }}
                    </flux:subheading>

                    <flux:subheading>
                        {{ round($value, 2) }}
                        @if($limit !== null)
                            / {{ $limit }} {{ $nutrition->unit }}
                            ({{ $limit ? round(($value / $limit) * 100) : 100 }})%
                        @endif
                    </flux:subheading>
                </div>
            </div>
        @endforeach
    </div>
</div>
