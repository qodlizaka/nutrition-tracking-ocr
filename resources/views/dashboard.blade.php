@use("Carbon\Carbon")

<x-layouts.app :title="__('Dashboard')">

    <div class="w-full min-h-full" data-testid="dashboard-main-container">

        <flux:breadcrumbs data-testid="dashboard-breadcrumbs">
            <flux:breadcrumbs.item href="{{ route('dashboard') }}" icon="home" />
        </flux:breadcrumbs>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mt-4">
            <div>
                <flux:heading size="xl" data-testid="dashboard-welcome-heading">{{ Carbon::greet() }}, {{ auth()->user()->name }}!</flux:heading>
                <flux:subheading>{{ __("Here's quick overview of your daily nutrition intake.") }}</flux:subheading>
            </div>
        </div>

        <flux:separator class="my-6" />

        @if(auth()->user()->hasVerifiedEmail())
            @php
                $lastItem = $userDetailHistory->last();
                $prevItem = $userDetailHistory->count() > 1 ? $userDetailHistory->get($userDetailHistory->count() - 2) : null;

                $bmrDiff = $prevItem && $lastItem ? $lastItem->bmr - $prevItem->bmr : 0;
                $tdeeDiff = $prevItem && $lastItem ? $lastItem->tdee - $prevItem->tdee : 0;

                $lastCalorie = $weeklyCalorieIntake->last();
            @endphp

            <div class="mt-4 grid grid-cols-2 gap-6 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6" data-testid="dashboard-metrics-grid">

                <x-dashboard.metric
                    :label="__('Intake count today')"
                    :value="$intakeCountToday"
                    unit=""
                    data-testid="dashboard-metric-intake-count"
                />

                <x-dashboard.metric
                    :label="__('Current weight')"
                    :value="$user->detail->weight"
                    :unit="__('kg')"
                    data-testid="dashboard-metric-weight"
                />

                <x-dashboard.metric
                    :label="__('Current height')"
                    :value="$user->detail->height"
                    :unit="__('cm')"
                    data-testid="dashboard-metric-height"
                />

                <x-dashboard.metric
                    :label="__('Today BMR')"
                    :value="round($lastItem?->bmr ?? 0, 0)"
                    :unit="__('kcal')"
                    :tooltip="__('Basal Metabolic Rate')"
                    :trend="$bmrDiff"
                    data-testid="dashboard-metric-bmr"
                />

                <x-dashboard.metric
                    :label="__('Today TDEE')"
                    :value="round($lastItem?->tdee ?? 0, 0)"
                    :unit="__('kcal')"
                    :tooltip="__('Total Daily Energy Expenditure')"
                    :trend="$tdeeDiff"
                    data-testid="dashboard-metric-tdee"
                />

                <x-dashboard.metric
                    :label="__('Today Calorie Intake')"
                    :value="round($lastCalorie['total'] ?? 0, 0)"
                    :unit="__('kcal')"
                    data-testid="dashboard-metric-calorie-intake"
                />

            </div>

            <flux:separator class="my-6" />

            <div class="mt-4 grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-3" data-testid="dashboard-charts-grid">
                <div class="rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900" data-testid="dashboard-chart-bmr-container">
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
                <div class="rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900" data-testid="dashboard-chart-tdee-container">
                    @php
                        $title = "TDEE (Total Daily Energy Expenditure) vs " . __('Daily Calorie');
                        $datasets = [
                            [
                                'label' => 'TDEE',
                                'data' => $userDetailHistory->pluck('tdee')->toArray(),
                            ],
                            [
                                'label' => __('Calorie'),
                                'data' => $weeklyCalorieIntake->pluck('total')->toArray(),
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
        @else
            <div class="flex flex-col items-center justify-center h-96 bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700" data-testid="dashboard-verify-email-container">
                <flux:icon name="mail-open" class="w-16 h-16 text-zinc-400 dark:text-zinc-600" />
                <flux:heading size="lg" class="mt-4" data-testid="dashboard-verify-email-heading">{{ __('Verify your email address') }}</flux:heading>
                <flux:subheading class="mt-2 text-center">{{ __('Please verify your email address to unlock all features.') }}</flux:subheading>
                <form method="POST" action="{{ route('verification.send') }}" class="mt-4" data-testid="dashboard-verify-email-form">
                    @csrf
                    <flux:button type="submit" variant="primary" data-testid="dashboard-verify-email-button">{{ __('Resend Verification Email') }}</flux:button>
                </form>
                @if (session('status') == 'verification-link-sent')
                    <p class="text-sm text-green-600 dark:text-green-400 mt-2" data-testid="dashboard-verify-email-success-message">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </p>
                @endif
            </div>
        @endif

    </div>

</x-layouts.app>
