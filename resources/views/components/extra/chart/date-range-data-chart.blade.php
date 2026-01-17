@props([
    'data' => [],
    'labels' => [],
    'type' => 'line',
    'title' => 'Default Title',
    'limit' => null,
])

@php
    $chartHash = md5(json_encode([$data, $labels, $limit, $title, $type]));
@endphp

<div class="w-full h-full"
     wire:key="{{ $chartHash }}"
     wire:ignore
     x-data="{
        chart: null,
        initChart(data, labels, limit, title) {
            if (typeof Chart === 'undefined' || typeof twColors === 'undefined') {
                console.error('Chart.js or twColors is missing');
                return;
            }

            const ctx = this.$refs.canvas.getContext('2d');

            // No need to destroy old chart, because wire:key ensures
            // we are always in a fresh DOM element if data changes.

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
                    responsive: true,
                    maintainAspectRatio: false,
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
    <canvas x-ref="canvas" class="w-full h-full"></canvas>
</div>
