<?php

namespace App\Livewire\FoodLabel;

use App\Models\Food;
use Livewire\Component;

class Validate extends Component
{
    public Food $food;
    public string $name;
    public float $totalServing;
    public string $unit;

    public function mount(Food $food): void
    {
        $this->food = $food;
        $this->name = $food->name;
        $this->totalServing = $food->total_servings;
        $this->unit = $food->unit;
    }

    public function saveFood(): void
    {
        $this->food->update([
            'name' => $this->name,
            'total_servings' => $this->totalServing,
            'unit' => $this->unit,
        ]);

        $this->redirect(route('foods.show', $this->food->id));
    }

    public function render()
    {
        return view('livewire.food-label.validate');
    }
}
