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

        <div class="mt-4 grid grid-cols-2 gap-6 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-7">
            <div class="rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900 flex flex-col items-center justify-center aspect-square gap-3 text-center">
                <flux:subheading>{{ __('Intake count today') }}</flux:subheading>
                <div class="flex flex-col">
                    <flux:heading level="2" class="text-4xl! mb-0!">{{ $intakeCountToday }}</flux:heading>
                    <flux:subheading>{{ __('kcal') }}</flux:subheading>
                </div>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900 flex flex-col items-center justify-center aspect-square gap-3 text-center">
                <flux:subheading>{{ __('Current weight') }}</flux:subheading>
                <div class="flex flex-col">
                    <flux:heading level="2" class="text-4xl! mb-0!">{{ $user->detail->weight }}</flux:heading>
                    <flux:subheading>{{ __('kg') }}</flux:subheading>
                </div>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900 flex flex-col items-center justify-center aspect-square gap-3 text-center">
                <flux:subheading>{{ __('Current height') }}</flux:subheading>
                <div class="flex flex-col">
                    <flux:heading level="2" class="text-4xl! mb-0!">{{ $user->detail->height }}</flux:heading>
                    <flux:subheading>{{ __('cm') }}</flux:subheading>
                </div>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900 flex flex-col items-center justify-center aspect-square gap-3 text-center">
                <flux:subheading class="flex items-center gap-0.5">
                    {{ __('Today') }} BMR

                    <flux:tooltip>
                        <flux:button icon="information-circle" size="xs" variant="ghost" />

                        <flux:tooltip.content class="max-w-[20rem] space-y-2">
                            <p>{{ __('Basal Metabolic Rate') }}</p>
                        </flux:tooltip.content>
                    </flux:tooltip>
                </flux:subheading>
                <div class="flex flex-col">
                    <flux:heading level="2" class="text-4xl! mb-0!">{{ round($userDetailHistory->last()->bmr, 0) }}</flux:heading>
                    <flux:subheading>{{ __('kcal') }}</flux:subheading>

                    @php
                        $bmrDiff = $userDetailHistory->count() > 1 ? $userDetailHistory->last()->bmr - $userDetailHistory->get($userDetailHistory->count() - 2)->bmr : 0;
                        $bmrTrendIcon = $bmrDiff > 0 ? 'trending-up' : ($bmrDiff < 0 ? 'trending-down' : 'minus');
                        $bmrTrendColor = $bmrDiff > 0 ? 'text-green-500' : ($bmrDiff < 0 ? 'text-red-500' : 'text-zinc-500');
                    @endphp
                    <div class="flex items-center gap-1 {{ $bmrTrendColor }}">
                        <flux:icon name="{{ $bmrTrendIcon }}" size="sm" />
                        <flux:subheading class="!text-sm">
                            {{ abs(round($bmrDiff, 0)) }} {{ __('kcal') }}
                        </flux:subheading>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900 flex flex-col items-center justify-center aspect-square gap-3 text-center">
                <flux:subheading class="flex items-center gap-0.5">
                    {{ __('Today') }} TDEE

                    <flux:tooltip>
                        <flux:button icon="information-circle" size="xs" variant="ghost" />

                        <flux:tooltip.content class="max-w-[20rem] space-y-2">
                            <p>{{ __('Total Daily Energy Expenditure') }}</p>
                        </flux:tooltip.content>
                    </flux:tooltip>
                </flux:subheading>
                <div class="flex flex-col">
                    <flux:heading level="2" class="text-4xl! mb-0!">{{ round($userDetailHistory->last()->tdee, 0) }}</flux:heading>
                    <flux:subheading>{{ __('kcal') }}</flux:subheading>

                    @php
                        $tdeeDiff = $userDetailHistory->count() > 1 ? $userDetailHistory->last()->tdee - $userDetailHistory->get($userDetailHistory->count() - 2)->tdee : 0;
                        $tdeeTrendIcon = $tdeeDiff > 0 ? 'trending-up' : ($tdeeDiff < 0 ? 'trending-down' : 'minus');
                        $tdeeTrendColor = $tdeeDiff > 0 ? 'text-green-500' : ($tdeeDiff < 0 ? 'text-red-500' : 'text-zinc-500');
                    @endphp
                    <div class="flex items-center gap-1 {{ $tdeeTrendColor }}">
                        <flux:icon name="{{ $tdeeTrendIcon }}" size="sm" />
                        <flux:subheading class="!text-sm">
                            {{ abs(round($tdeeDiff, 0)) }} {{ __('kcal') }}
                        </flux:subheading>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900 flex flex-col items-center justify-center aspect-square gap-3 text-center">
                <flux:subheading>{{ __('Today') }} {{ __('calorie intake') }}</flux:subheading>
                <div class="flex flex-col">
                    <flux:heading level="2" class="text-4xl! mb-0!">{{ round($weeklyCalorieIntake->last(), 0) }}</flux:heading>
                    <flux:subheading>{{ __('kcal') }}</flux:subheading>
                </div>
            </div>
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
