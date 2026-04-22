<?php

namespace App\Livewire\Intake;

use App\Enum\NutritionCategory;
use App\Enum\NutritionGroup;
use App\Models\Akg;
use App\Models\Intake;
use App\Models\Nutrientable;
use App\Models\Nutrition;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Chart extends Component
{
    public Collection $macroNutrients;
    public Collection $microNutrients;
    public string $startDateString;
    public ?Carbon $startDate;
    public string $endDateString;
    public ?Carbon $endDate;
    public string $dateString;
    public ?Carbon $date;
    public Akg $userAkg;
    public array $activeNutritions = [];
    public const MAX_DISPLAY = 5;

    public function mount()
    {
        $nutritions = Nutrition::all()->keyBy('name');

        $this->macroNutrients = $nutritions->filter(fn($nutri) =>
            $nutri->category === NutritionCategory::Macro);

        $this->microNutrients = $nutritions->filter(fn($nutri) =>
            $nutri->category === NutritionCategory::Micro);

        $this->userAkg = Auth::user()
            ->load(['detail.akg.nutritions'])
            ->detail
            ->akg;

        $this->startDate = now()->subDays(7);
        $this->endDate = $this->date = now();

        $this->startDateString = $this->startDate->format('Y-m-d');
        $this->endDateString = $this->dateString = $this->endDate->format('Y-m-d');

        foreach ($this->microNutrients->groupBy('group') as $group) {
            $groupName = $group->first()->group->name;
            $this->activeNutritions[$groupName] = $group
                ->take(self::MAX_DISPLAY)
                ->pluck('name')
                ->toArray();
        }
    }

    public function updatedStartDateString($value)
    {
        if ($value) {
            $this->startDate = Carbon::createFromFormat('Y-m-d', $value);
        }
    }

    public function updatedEndDateString($value)
    {
        if ($value) {
            $this->endDate = Carbon::createFromFormat('Y-m-d', $value);
        }
    }

    public function updatedDateString($value)
    {
        if ($value) {
            $this->date = Carbon::createFromFormat('Y-m-d', $value);
        }
    }

    public function toggleNutrition(string $groupName, string $name): void
    {
        if (\in_array($name, $this->activeNutritions[$groupName])) {
            $this->activeNutritions[$groupName] = array_values(array_diff($this->activeNutritions[$groupName], [$name]));
        } else {
            if (\count($this->activeNutritions[$groupName]) < self::MAX_DISPLAY) {
                $this->activeNutritions[$groupName][] = $name;
            }
        }
    }

    public function render()
    {
        $akgNutritions = $this->userAkg->nutritions->keyBy('id');

        return view('livewire.intake.chart', [
            'akgNutritions' => $akgNutritions,
            'chartData' => [
                'macroNutrients' => Nutrientable::query()
                    ->select(columns: 'nutrition_id')
                    ->whereIn('nutrition_id', $this->macroNutrients->pluck('id')->toArray())
                    ->selectRaw(expression: 'DATE(created_at) as created_date')
                    ->selectRaw(expression: 'SUM(value) as total_amount')
                    ->whereHasMorph('nutrientable', [Intake::class], callback: fn ($q) =>
                        $q->where( 'user_id', Auth::id())
                            ->whereBetween('created_at', [$this->startDate, $this->endDate])
                    )
                    ->groupBy('nutrition_id', 'created_date')
                    ->get()
                    ->groupBy('nutrition_id')
                    ->map(fn ($group) =>
                        $group->pluck('total_amount', 'created_date')
                    ),
                'microNutrients' => Nutrientable::query()
                    ->select('nutrition_id')
                    ->selectRaw('SUM(value) as total_amount')
                    ->whereIn('nutrition_id', $this->microNutrients->pluck('id')->toArray())
                    ->whereHasMorph('nutrientable', [Intake::class], fn ($q) =>
                        $q->where('user_id', Auth::id())
                            ->whereDate('created_at', $this->date)
                    )
                    ->groupBy('nutrition_id')
                    ->pluck('total_amount', 'nutrition_id'),
            ]
        ])->title(__('My intakes chart'));
    }
}
