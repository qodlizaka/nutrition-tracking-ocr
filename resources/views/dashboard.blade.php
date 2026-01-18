@use("Carbon\Carbon")

<x-layouts.app :title="__('Dashboard')">

    <div class="w-full min-h-full">

        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}" icon="home" />
        </flux:breadcrumbs>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mt-4">
            <div>
                <flux:heading size="xl">{{ Carbon::greet() }}, {{ auth()->user()->name }}!</flux:heading>
                <flux:subheading>{{ __("Here's quick overview of your daily nutrition intake.") }}</flux:subheading>
            </div>
        </div>

        <flux:separator class="my-6" />

        @php
            $lastItem = $userDetailHistory->last();
            $prevItem = $userDetailHistory->count() > 1 ? $userDetailHistory->get($userDetailHistory->count() - 2) : null;

            $bmrDiff = $prevItem ? $lastItem->bmr - $prevItem->bmr : 0;
            $tdeeDiff = $prevItem ? $lastItem->tdee - $prevItem->tdee : 0;
        @endphp

        <div class="mt-4 grid grid-cols-2 gap-6 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">

            <x-dashboard.metric
                :label="__('Intake count today')"
                :value="$intakeCountToday"
                :unit="__('kcal')"
            />

            <x-dashboard.metric
                :label="__('Current weight')"
                :value="$user->detail->weight"
                :unit="__('kg')"
            />

            <x-dashboard.metric
                :label="__('Current height')"
                :value="$user->detail->height"
                :unit="__('cm')"
            />

            <x-dashboard.metric
                :label="__('Today') . ' BMR'"
                :value="round($lastItem->bmr, 0)"
                :unit="__('kcal')"
                :tooltip="__('Basal Metabolic Rate')"
                :trend="$bmrDiff"
            />

            <x-dashboard.metric
                :label="__('Today') . ' TDEE'"
                :value="round($lastItem->tdee, 0)"
                :unit="__('kcal')"
                :tooltip="__('Total Daily Energy Expenditure')"
                :trend="$tdeeDiff"
            />

            <x-dashboard.metric
                :label="__('Today') . ' ' . __('calorie intake')"
                :value="round($weeklyCalorieIntake->last(), 0)"
                :unit="__('kcal')"
            />

        </div>

        <flux:separator class="my-6" />

        <div class="mt-4 grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                @php
                    $title = "BMR (Basal Metabolic Rate)";
                    $datasets = [
                        [
                            'label' => $title,
                            'data' => $userDetailHistory->pluck('bmr'),
                        ]
                    ];
                    $labels = $userDetailHistory->pluck('date');
                @endphp

                <x-extra.chart.date-range-data-chart
                    :datasets="$datasets"
                    :labels="$labels"
                    :title="$title"
                    :min="['y' => 0]"
                />
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                @php
                    $title = "TDEE (Total Daily Energy Expenditure) vs " . __('Daily Calorie');
                    $datasets = [
                        [
                            'label' => 'TDEE',
                            'data' => $userDetailHistory->pluck('tdee')->toArray(),
                        ],
                        [
                            'label' => __('Calorie'),
                            'data' => $weeklyCalorieIntake->toArray(),
                        ]
                    ];
                    $labels = $userDetailHistory->pluck('date');
                @endphp

                <x-extra.chart.date-range-data-chart
                    :datasets="$datasets"
                    :labels="$labels"
                    :title="$title"
                    :min="['y' => 0]"
                />
            </div>
        </div>

    </div>

</x-layouts.app>
