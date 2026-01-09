<?php

namespace App\Livewire\Food;

use App\Models\Food;
use Livewire\Component;
use Illuminate\Support\Collection;

class Show extends Component
{
    public Food $food;

    public function mount(Food $food): void
    {
        $this->food = $food->load(['nutritions']);
    }

    /**
     * Creates a keyed collection of nutritions for easy access by name.
     * e.g. $this->nutritionMap->get('energy')
     */
    public function getNutritionMapProperty(): Collection
    {
        return $this->food->nutritions->keyBy(fn($n) => strtolower($n->name));
    }

    /**
     * Get vitamins and minerals only (excluding the main macros displayed at the top).
     */
    public function getMicrosProperty(): Collection
    {
        // Define names already shown in the main section to exclude them
        $mainSections = ['energy', 'total fat', 'total carbohydrate', 'protein', 'sodium'];

        return $this->food->nutritions
            ->filter(fn($n) => !in_array(strtolower($n->name), $mainSections))
            ->sortBy('name'); // Or sort by Group enum if preferred
    }

    public function render()
    {
        return view('livewire.food.show');
    }
}
