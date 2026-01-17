@props([
    'limit' => null,
    'intake' => 0,
    'label' => 'Nutrient',
    'color' => 'sky',
])

<div {{ $attributes }}
     x-data="{
        chart: null,
        initChart(limit, intake, label, colorName) {
            const ctx = this.$refs.canvas;

            let chartData = [];
            let chartColors = [];
            let chartLabels = [];

            const hasLimit = limit !== null && limit > 0;
            const isOverflow = hasLimit && (intake > limit);

            if (!hasLimit) {
                chartData = [intake];
                chartColors = [twColors[colorName][500]];
                chartLabels = ['{{ __('Today') }}'];
            } else {
                chartLabels = ['{{ __('Intake') }}', '{{ __('Remaining') }}'];

                if (isOverflow) {
                    chartData = [limit, 0];
                    chartColors = [twColors.red[500], twColors.gray[200]];
                } else {
                    chartData = [intake, limit - intake];
                    chartColors = [twColors[colorName][500], twColors[colorName][100]];
                }
            }

            if (this.chart) this.chart.destroy();

            this.chart = new Chart(ctx, {
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
        }
     }"
     x-init='initChart(
        @json($limit, JSON_HEX_APOS),
        @json((float) $intake, JSON_HEX_APOS),
        @json($label, JSON_HEX_APOS),
        @json($color, JSON_HEX_APOS)
     )'
>
    <canvas x-ref="canvas"></canvas>
</div>
