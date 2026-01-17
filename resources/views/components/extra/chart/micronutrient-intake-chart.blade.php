@props([
    'limit' => null,
    'intake' => 0,
    'label' => 'Nutrient',
    'color' => 'sky',
])

@php
    $chartId = 'chart_' . md5($label . $intake . microtime());
@endphp

<div {{ $attributes }}>
    <canvas id="{{ $chartId }}"></canvas>

    <script>
        document.addEventListener('livewire:initialized', function () {
            const ctx = document.getElementById("{{ $chartId }}");

            const limit = @json($limit);
            const intake = @json((float) $intake);
            const colorName = @json($color);
            const label = @json($label);

            let chartData = [];
            let chartColors = [];
            let chartLabels = [];

            const hasLimit = limit !== null && limit > 0;
            const isOverflow = hasLimit && (intake > limit);

            if (!hasLimit) {
                chartData = [intake];

                chartColors = [
                    twColors[colorName][500],
                ];
                chartLabels = ['{{ __('Today') }}'];

            } else {
                chartLabels = ['{{ __('Intake') }}', '{{ __('Remaining') }}'];

                if (isOverflow) {
                    chartData = [limit, 0];
                    chartColors = [
                        twColors.red[500],
                        twColors.gray[200],
                    ];
                } else {
                    chartData = [intake, limit - intake];
                    chartColors = [
                        twColors[colorName][500],
                        twColors[colorName][100],
                    ];
                }
            }

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        data: chartData,
                        backgroundColor: chartColors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    cutout: '65%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if (hasLimit && isOverflow && context.dataIndex === 0) {
                                        return `${label}: ${intake} / ${limit} ({{ __('Over limit!') }})`;
                                    }

                                    return `${context.label}: ${intake}`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>
