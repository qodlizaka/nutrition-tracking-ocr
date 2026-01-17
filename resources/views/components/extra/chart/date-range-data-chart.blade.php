@props([
    'data' => [],
    'labels' => [],
    'type' => 'line',
    'title' => 'Default Title',
    'limit' => null,
])

<div class="w-full"
     x-data="{
        chart: null,
        initChart(data, labels, limit, title) {
            if (typeof Chart === 'undefined' || typeof twColors === 'undefined') {
                console.error('Chart.js or twColors is missing');
                return;
            }

            const ctx = this.$refs.canvas.getContext('2d');

            if (this.chart) {
                this.chart.destroy();
            }

            this.chart = new Chart(ctx, {
                type: '{{ $type }}',
                data: {
                    labels: labels,
                    datasets: [{
                        label: title,
                        data: data,
                        backgroundColor: hexToRgba(twColors.blue[600], 0.2),
                        borderColor: twColors.blue[600],
                        borderWidth: 2
                    }]
                },
                options: {
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { display: false } }
                    },
                    plugins: {
                        annotation: {
                            annotations: {
                                ...(limit !== null ? {
                                    limitLine: {
                                        type: 'line',
                                        mode: 'horizontal',
                                        scaleID: 'y',
                                        value: limit,
                                        borderColor: twColors.blue[600],
                                        borderWidth: 2,
                                        borderDash: [5, 5],
                                        label: {
                                            display: true,
                                            content: title + ' limit',
                                            position: 'end',
                                            backgroundColor: twColors.blue[500],
                                            color: '#fff',
                                            font: { size: 12, weight: 'bold' },
                                            padding: 6,
                                            yAdjust: -10
                                        }
                                    }
                                } : {})
                            }
                        }
                    }
                }
            });
        }
     }"
     x-init='initChart(
        @json($data, JSON_HEX_APOS),
        @json($labels, JSON_HEX_APOS),
        @json($limit, JSON_HEX_APOS),
        @json($title, JSON_HEX_APOS)
     )'
>
    <canvas x-ref="canvas"></canvas>
</div>
