<?php

namespace App\Livewire\Extra;

use App\Models\Food;
use App\Models\Nutrition;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class NutritionFactTable extends Component
{
    public Food $food;
    public Collection $nutritions;

    public array $form = [];

    #[Reactive]
    public float $multiplier = 1.0;

    public bool $editable = false;

    public function mount(Food $food, float $multiplier = 1.0, bool $editable = false): void
    {
        $this->food = $food;
        $this->editable = $editable;

        $this->multiplier = $this->editable ? 1.0 : $multiplier;

        $this->nutritions = $food->nutritions;
        // dd($this->nutritionMap->pluck('pivot')->toArray());

        if ($this->editable) {
            $this->hydrateForm();
        }
    }

    public function hydrateForm(): void
    {
        foreach ($this->food->nutritions as $nutrition) {
            $this->form[$nutrition->id] = $nutrition->pivot->value;
        }
    }

    public function getNutritionMapProperty(): Collection
    {
        return $this->nutritions->keyBy(fn ($n) => strtolower($n->name));
    }

    public function getIdFor(string $name): ?int
    {
        return $this->nutritionMap->get(strtolower($name))?->id;
    }

    public function getMicrosProperty(): Collection
    {
        $mainSections = ['energy', 'total fat', 'total carbohydrate', 'protein', 'sodium'];

        return $this->nutritions
            ->filter(fn ($n) => ! \in_array(strtolower($n->name), $mainSections))
            ->sortBy('name');
    }

    public function calculate(mixed $itemOrValue): float
    {
        if ($this->editable && \is_object($itemOrValue)) {
            return (float) ($this->form[$itemOrValue->id] ?? 0);
        }

        $value = \is_object($itemOrValue)
            ? ($itemOrValue->pivot->value ?? 0)
            : ($itemOrValue ?? 0);

        return round($value * $this->multiplier, 1);
    }

    public function save(): void
    {
        $syncData = [];
        foreach ($this->form as $id => $value) {
            $syncData[$id] = ['value' => $value];
        }

        $this->food->nutritions()->sync($syncData);

        $this->dispatch('nutritions-saved');
    }

    public function render()
    {
        return view('livewire.extra.nutrition-fact-table');
    }
}
