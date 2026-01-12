<?php

namespace App\Livewire\Extra;

use App\Models\Food;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class NutritionFactTable extends Component
{
    public Food $food;

    public Collection $nutritions;

    #[Reactive]
    public float $multiplier;

    public function mount(Food $food, float $multiplier): void
    {
        $this->food = $food;
        $this->nutritions = $food->nutritions;
        $this->multiplier = $multiplier;
    }

    public function getNutritionMapProperty(): Collection
    {
        return $this->nutritions
            ->keyBy(fn ($n) => strtolower($n->name));
    }

    public function getMicrosProperty(): Collection
    {
        $mainSections = ['energy', 'total fat', 'total carbohydrate', 'protein', 'sodium'];

        return $this->nutritions
            ->filter(fn ($n) => ! in_array(strtolower($n->name), $mainSections))
            ->sortBy('name');
    }

    public function render()
    {
        return view('livewire.extra.nutrition-fact-table');
    }
}
