<?php

namespace App\Livewire\Food;

use App\Measurements\Base as BaseMeasurement;
use App\Measurements\Quantity;
use App\Measurements\Volume;
use App\Measurements\Weight;
use App\Models\Food;
use App\Models\Intake;
use App\Models\Nutrition;
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

    public string $consumedAt;

    public function mount(Food $food): void
    {
        $this->food = $food->load(['nutritions']);

        $measurements = [
            new Quantity($this->food),
        ];

        if ($food->unit === 'g') {
            $measurements[] = new Weight($this->food);
        } else {
            $measurements = [
                ...$measurements,
                (new Volume($this->food))
                    ->setName(__('Teaspoon'))
                    ->setIcon('spoon')
                    ->setBaseMultiplier(5),
                (new Volume($this->food))
                    ->setName(__('Tablespoon'))
                    ->setIcon('spoon')
                    ->setBaseMultiplier(15),
                (new Volume($this->food))
                    ->setName(__('Cup'))
                    ->setIcon('cup-soda')
                    ->setBaseMultiplier(250),
                (new Volume($this->food))
                    ->setName(__('Litre'))
                    ->setIcon('flask-round')
                    ->setBaseMultiplier(1000),
            ];
        }

        $this->measurements = collect($measurements)
            ->keyBy('name');

        $this->measure = $this->measurements->first();
        $this->amount = $this->measure->value;

        $this->consumedAt = now()->format('Y-m-d\TH:i');
    }

    public function setMeasure(string $name): void
    {
        $this->measure = $this->measurements
            ->get($name);

        $this->measure->setValue($this->measure->value);
    }

    public function consumeFood()
    {
        $allNutritionData = Nutrition::all()->keyBy('name');

        $userAkg = Auth::user()
            ->load(['detail.akg.nutritions'])
            ->detail
            ->akg;

        $userAkg->setRelation(
            'nutritions',
            $userAkg->nutritions->keyBy('id')
        );

        $nutritions = $this->food
            ->nutritions
            ->mapWithKeys(function($nutri) use ($allNutritionData, $userAkg) {
                $nutritionModel = $allNutritionData->get($nutri['name']);

                $pivot = [];

                $pivot['value'] = $nutri->pivot->value !== null
                    ? $nutri->pivot->value * $this->measure->getMultiplier()
                    : ($nutri->pivot->percentage / 100) * $userAkg->nutritions->get($nutritionModel->id)?->pivot->value * $this->measure->getMultiplier();

                if ($nutritionModel === null)
                    return [];

                return [$nutritionModel->id => $pivot];
            })
            ->toArray();

        $intake = Intake::create([
            'user_id' => Auth::id(),
            'notes' => empty($this->notes) ? '-' : $this->notes,
            'consumed_at' => $this->consumedAt,
        ]);

        $intake->nutritions()->attach($nutritions);

        return $this->redirect(route('intakes.index'), navigate: true);
    }

    public function render()
    {
        $this->measure->setValue($this->amount ?? 0);

        return view('livewire.food.show')
            ->title($this->food->name);
    }
}
