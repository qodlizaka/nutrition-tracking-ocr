<div>
    <div class="w-full">
        <canvas id="{{ $chartId }}"></canvas>
    </div>

    <script type="module">
        // $flux.appearance // get/set the user appearance
        document.addEventListener('livewire:initialized', function () {
            const ctx = document.getElementById('{{ $chartId }}').getContext('2d');
            const myChart = new Chart(ctx, {
                type: "{{ $type }}",
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: '{{ $title }}',
                        data: @json($data),
                        backgroundColor: hexToRgba(twColors.blue[600], 0.2),
                        borderColor: twColors.blue[600],
                        borderWidth: 2
                    }]
                },
                options: {
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
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
                                            font: {
                                                size: 12,
                                                weight: 'bold'
                                            },
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

