<?php

namespace App\Livewire\Food;

use App\Measurements\Base as BaseMeasurement;
use App\Measurements\Quantity;
use App\Measurements\Weight;
use App\Models\Food;
use Livewire\Component;
use Illuminate\Support\Collection;

class Show extends Component
{
    public Food $food;
    public Collection $measurements;
    public BaseMeasurement $measure;
    public float $amount;

    public function mount(Food $food): void
    {
        $this->food = $food->load(['nutritions']);

        $this->measurements = collect([
            new Weight($this->food),
            new Quantity($this->food),
        ])->keyBy('name');

        $this->measure = $this->measurements->first();
        $this->amount = $this->measure->value;
    }

    public function setMeasure(string $name): void
    {
        $this->measure = $this->measurements
            ->get($name)
            ->setValue($this->amount);
    }

    public function render()
    {
        $this->measure->setValue($this->amount ?? 0);
        return view('livewire.food.show');
    }
}
