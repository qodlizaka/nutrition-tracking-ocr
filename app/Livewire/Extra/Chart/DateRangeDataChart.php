<?php

namespace App\Livewire\Extra\Chart;

use Illuminate\Support\Str;
use Livewire\Component;

class DateRangeDataChart extends Component
{
    public array $data = [];
    public array $labels = [];
    public string $chartId = '';
    public string $type = 'line';
    public string $title = 'Default Title';
    public ?int $limit = null;

    public function mount()
    {
        if (empty($this->chartId))
            $this->chartId = Str::random(10);
    }

    public function render()
    {
        return view('livewire.extra.chart.date-range-data-chart');
    }
}
