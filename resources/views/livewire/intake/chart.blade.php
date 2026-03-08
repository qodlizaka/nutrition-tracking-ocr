@use("Illuminate\Support\Str")

<div class="w-full" data-testid="intake-chart-main-container">
    <flux:breadcrumbs data-testid="intake-chart-breadcrumbs">
        <flux:breadcrumbs.item icon="home" href="{{ route('dashboard') }}" />
        <flux:breadcrumbs.item href="{{ route('intakes.index') }}">{{ __('Intake') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('intakes.chart') }}">{{ __('Chart') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" data-testid="intake-chart-heading">{{ __('Nutrition charts') }}</flux:heading>
            <flux:subheading>{{ __('Visualize your nutritional intake over time.') }}</flux:subheading>
        </div>
    </div>

    <flux:separator class="my-6" />

    <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between" data-testid="chart-macro-header-section">
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
                    data-testid="chart-macro-start-date-input"
                />
            </flux:input.group>

            <flux:input.group>
                <flux:input.group.prefix>{{ __('To') }}</flux:input.group.prefix>
                <flux:input
                    wire:model.live="endDateString"
                    type="date"
                    max="{{ now()->format('Y-m-d') }}"
                    data-testid="chart-macro-end-date-input"
                />
            </flux:input.group>
        </div>
    </div>

    {{-- Macro Grid --}}
    <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3" data-testid="chart-macro-grid">
        @foreach ($macroNutrients as $nutrition)
            <div
                wire:key="macro-{{ $nutrition->id }}"
                class="rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900"
                data-testid="chart-macro-card-{{ $nutrition->id }}"
            >
                @php
                    $item = $chartData['macroNutrients']->get($nutrition->id);

                    $labels = $item ? $item->keys()->toArray() : [];
                    $rawValues = $item ? $item->values()->toArray() : [];

                    $limit = $akgNutritions->get($nutrition->id)?->pivot->value ?? null;

                    $datasets = [
                        [
                            'label' => $nutrition->name,
                            'data' => $rawValues,
                        ]
                    ];
                @endphp

                <x-extra.chart.date-range-data-chart
                    :datasets="$datasets"
                    :labels="$labels"
                    :limit="$limit"
                    :title="__(ucfirst($nutrition->name))"
                />
            </div>
        @endforeach
    </div>

    <flux:separator class="my-6" />

    <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between" data-testid="chart-micro-header-section">
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
                    data-testid="chart-micro-date-input"
                />
            </flux:input.group>
        </div>
    </div>

    <flux:separator class="my-6" />

    <div class="mt-4 grid grid-cols-2 gap-6 md:grid-cols-4 lg:grid-cols-5" data-testid="chart-micro-grid">
        @foreach ($microNutrients->groupBy('group') as $group)
            @php
                $groupEnum = $group->first()->group;
            @endphp

            <div wire:key="micro-group-header-{{ $groupEnum->name }}" class="col-span-full flex justify-between items-end flex-wrap gap-4" data-testid="chart-micro-group-{{ Str::slug($groupEnum->name) }}">
                <flux:heading level="3" size="lg">{{ __($groupEnum->name) }}</flux:heading>

                <flux:dropdown data-testid="chart-micro-dropdown-{{ Str::slug($groupEnum->name) }}">
                    <flux:button icon-trailing="chevron-down" data-testid="chart-micro-dropdown-trigger-{{ Str::slug($groupEnum->name) }}">
                        {{ __($groupEnum->name) }}
                        <span class="ml-1 text-zinc-400 text-xs">({{ count($activeNutritions[$groupEnum->name]) }})</span>
                    </flux:button>

                    <flux:menu keep-open data-testid="chart-micro-menu-{{ Str::slug($groupEnum->name) }}">
                        @foreach ($group as $nutri)
                            @php
                                $isSelected = in_array($nutri->name, $activeNutritions[$groupEnum->name]);
                                $isLimitReached = count($activeNutritions[$groupEnum->name]) >= self::MAX_DISPLAY;
                                $shouldDisable = $isLimitReached && !$isSelected;
                            @endphp

                            <flux:menu.checkbox
                                wire:key="micro-checkbox-{{ $nutri->id }}"
                                wire:click="toggleNutrition('{{ $groupEnum->name }}', '{{ $nutri->name }}')"
                                :checked="$isSelected"
                                :disabled="$shouldDisable"
                                data-testid="chart-micro-checkbox-{{ Str::slug($nutri->name) }}"
                            >
                                {{ __(ucfirst($nutri->name)) }}

                                @if($shouldDisable)
                                    <span class="ml-auto text-xs text-zinc-400 font-normal">{{ __('Max') }} {{ self::MAX_DISPLAY }}</span>
                                @endif
                            </flux:menu.checkbox>
                        @endforeach
                    </flux:menu>
                </flux:dropdown>
            </div>

            @foreach ($group as $nutrition)
                @if(\in_array($nutrition->name, $activeNutritions[$groupEnum->name]))
                    <div
                        wire:key="micro-chart-{{ $nutrition->id }}"
                        class="rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900"
                        data-testid="chart-micro-card-{{ $nutrition->id }}"
                    >
                        @php
                            $value = $chartData['microNutrients']->get($nutrition->id) ?? 0;
                            $limit = $akgNutritions->get($nutrition->id)?->pivot->value ?? null;
                        @endphp

                        <x-extra.chart.micronutrient-intake-chart
                            :limit="$limit"
                            :intake="$value"
                            :label="__(Str::title($nutrition->name))"
                        />

                        <div class="mt-4 text-center">
                            <flux:subheading class="font-bold" data-testid="chart-micro-card-name-{{ $nutrition->id }}">
                                {{ __(ucfirst($nutrition->name)) }}
                            </flux:subheading>

                            <flux:subheading data-testid="chart-micro-card-values-{{ $nutrition->id }}">
                                {{ round($value, 2) }}{{ $nutrition->unit }}
                                @if($limit !== null)
                                    / {{ $limit }}{{ $nutrition->unit }}
                                    ({{ $limit ? round(($value / $limit) * 100) : 100 }})%
                                @endif
                            </flux:subheading>
                        </div>
                    </div>
                @endif
            @endforeach
        @endforeach
    </div>
</div>
