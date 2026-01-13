<?php

namespace App\Livewire\Food;

use App\Measurements\Base as BaseMeasurement;
use App\Measurements\Quantity;
use App\Measurements\Weight;
use App\Models\Food;
use App\Models\Intake;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public Food $food;

    public Collection $measurements;

    public BaseMeasurement $measure;

    public float $amount;

    public string $notes = '';

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
            ->get($name);

        $this->measure->setValue($this->measure->value);
    }

    public function consumeFood()
    {
        $nutritions = $this->food
            ->nutritions
            ->mapWithKeys(fn ($n) => [$n->id => ['value' => $n->pivot->value * $this->measure->getMultiplier()]])
            ->toArray();

        $intake = Intake::create([
            'user_id' => Auth::id(),
            'notes' => empty($this->notes) ? '-' : $this->notes,
        ]);

        $intake->nutritions()->attach($nutritions);

        return $this->redirect(route('intakes.index'), navigate: true);
    }

    public function render()
    {
        $this->measure->setValue($this->amount ?? 0);

        return view('livewire.food.show');
    }
}
