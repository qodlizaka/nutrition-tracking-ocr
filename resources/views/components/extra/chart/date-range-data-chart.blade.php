@props([
    'datasets' => [],
    'labels' => [],
    'type' => 'line',
    'title' => null,
    'limit' => null,
])

@php
    $chartHash = md5(json_encode([$datasets, $labels, $limit, $title, $type]));
@endphp

<div class="w-full h-full"
     wire:key="{{ $chartHash }}"
     wire:ignore
     x-data="{
        chart: null,
        initChart(datasets, labels, limit, title) {
            if (typeof Chart === 'undefined' || typeof twColors === 'undefined') {
                console.error('Chart.js or twColors is missing');
                return;
            }

            const ctx = this.$refs.canvas.getContext('2d');

            const palette = [
                twColors.blue[600],
                twColors.red[600],
                twColors.green[600],
                twColors.yellow[500],
                twColors.purple[600],
                twColors.pink[600],
                twColors.indigo[600],
                twColors.orange[500],
            ];

            const formattedDatasets = datasets.map((ds, index) => {
                const color = ds.borderColor || palette[index % palette.length];
                const bgColor = ds.backgroundColor || (typeof hexToRgba === 'function'
                    ? hexToRgba(color, 0.2)
                    : color); // Fallback if helper missing

                return {
                    label: ds.label || `Dataset ${index + 1}`,
                    data: ds.data,
                    backgroundColor: bgColor,
                    borderColor: color,
                    borderWidth: ds.borderWidth || 2,
                    // Merge any other chart.js specific options passed in the array
                    ...ds
                };
            });

            this.chart = new Chart(ctx, {
                type: '{{ $type }}',
                data: {
                    labels: labels,
                    datasets: formattedDatasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { display: false } }
                    },
                    plugins: {
                        // Enable Legend to differentiate datasets
                        legend: {
                            display: true,
                            position: 'bottom'
                        },
                        title: {
                            display: !!title,
                            text: title,
                            font: { size: 16 }
                        },
                        annotation: {
                            annotations: {
                                ...(limit !== null ? {
                                    limitLine: {
                                        type: 'line',
                                        mode: 'horizontal',
                                        scaleID: 'y',
                                        value: limit,
                                        borderColor: twColors.gray[500], // Neutral color for limit
                                        borderWidth: 2,
                                        borderDash: [5, 5],
                                        label: {
                                            display: true,
                                            content: 'Limit: ' + limit,
                                            position: 'end',
                                            backgroundColor: twColors.gray[600],
                                            color: '#fff',
                                            font: { size: 10, weight: 'bold' },
                                            padding: 4,
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
        @json($datasets, JSON_HEX_APOS),
        @json($labels, JSON_HEX_APOS),
        @json($limit, JSON_HEX_APOS),
        @json($title, JSON_HEX_APOS)
     )'
>
    <canvas x-ref="canvas" class="w-full h-full"></canvas>
</div>
