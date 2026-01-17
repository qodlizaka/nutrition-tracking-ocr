@props([
    'data' => [],
    'labels' => [],
    'chartId' => null,
    'type' => 'line',
    'title' => 'Default Title',
    'limit' => null,
])

@php
    // Handle dynamic ID generation if not provided
    $chartId = $chartId ?? 'chart_' . \Illuminate\Support\Str::random(10);
@endphp

<div>
    <div class="w-full">
        <canvas id="{{ $chartId }}"></canvas>
    </div>

    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('{{ $chartId }}').getContext('2d');

            // Ensure Chart is defined. If using Vite, ensure it is imported globally.
            const myChart = new Chart(ctx, {
                type: "{{ $type }}",
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: '{{ $title }}',
                        data: @json($data),
                        // Assumes twColors and hexToRgba are globally available functions/objects
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
                    @if($limit !== null)
                        plugins: {
                            annotation: {
                                annotations: {
                                    {{ $chartId }}LimitLine: {
                                        type: 'line',
                                        mode: 'horizontal',
                                        scaleID: 'y',
                                        value: {{ $limit }},
                                        borderColor: twColors.blue[600],
                                        borderWidth: 2,
                                        borderDash: [5, 5],
                                        label: {
                                            display: true,
                                            content: '{{ $title }} limit',
                                            position: 'end',
                                            backgroundColor: twColors.blue[500],
                                            color: '#fff',
                                            font: { size: 12, weight: 'bold' },
                                            padding: 6,
                                            yAdjust: -10
                                        }
                                    }
                                }
                            }
                        }
                    @endif
                }
            });
        });
    </script>
</div>
