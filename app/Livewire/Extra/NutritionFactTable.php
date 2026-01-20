<?php

namespace App\Livewire\Extra;

use App\Models\Akg;
use App\Models\Food;
use App\Models\Nutrition;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
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

    public Akg $userAkg;

    public function mount(Food $food, float $multiplier = 1.0, bool $editable = false): void
    {
        $this->food = $food;
        $this->editable = $editable;

        $this->multiplier = $this->editable ? 1.0 : $multiplier;

        $this->nutritions = $food->nutritions;

        $this->hydrateForm();

        $this->userAkg = Auth::user()
            ->load(['detail.akg.nutritions'])
            ->detail
            ->akg;

        $this->userAkg->setRelation(
            'nutritions',
            $this->userAkg->nutritions->keyBy('id')
        );
    }

    public function hydrateForm(): void
    {
        foreach ($this->food->nutritions as $nutrition) {
            $this->form[$nutrition->id] = !is_null($nutrition->pivot->percentage)
                ? ['percentage' => $nutrition->pivot->percentage]
                : ['value' => $nutrition->pivot->value];
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
        if (! \is_object($itemOrValue)) {
            return 0;
        }

        if ($this->editable) {
            return array_key_first($this->form[$itemOrValue->id]) === 'percentage'
                ? (float) $this->form[$itemOrValue->id]['percentage']
                : (float) $this->form[$itemOrValue->id]['value'];
        }

        $value = $itemOrValue->pivot?->percentage !== null
            ? ($itemOrValue->pivot->percentage ?? 0)
            : ($itemOrValue->pivot->value ?? 0);

        return $itemOrValue->pivot?->percentage !== null
            ? round(($value / 100 * ($this->userAkg->nutritions->get($itemOrValue->id)?->pivot->value ?? 0)) * $this->multiplier, 4)
            : round($value * $this->multiplier, 4);
    }

    public function save(): void
    {
        $syncData = [];
        foreach ($this->form as $id => $data) {
            $pivot = ['value' => null, 'percentage' => null];

            $syncData[$id] = array_key_first($data) === 'percentage'
                ? [...$pivot, 'percentage' => $data['percentage']]
                : [...$pivot, 'value' => $data['value']];
        }

        $this->food->nutritions()->sync($syncData);
        $this->food->load('nutritions');

        $this->hydrateForm();

        $this->dispatch('nutritions-saved');
    }

    public function render()
    {
        return view('livewire.extra.nutrition-fact-table');
    }
}
