<?php

namespace App\Livewire\Intake;

use App\Enum\NutritionCategory;
use App\Models\Akg;
use App\Models\Intake;
use App\Models\Nutrientable;
use App\Models\Nutrition;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Chart extends Component
{
    public Collection $macroNutrients;
    public Collection $microNutrients;
    public string $startDate;
    public string $endDate;
    public string $date;
    public Akg $userAkg;

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

        $this->userAkg->setRelation(
            'nutritions',
            $this->userAkg->nutritions->keyBy('id')
        );
    }

    public function render()
    {
        return view('livewire.intake.chart', [
            'chartData' => Nutrientable::query()
                ->select('nutrition_id')
                ->selectRaw('DATE(created_at) as created_date')
                ->selectRaw('SUM(value) as total_amount')
                ->whereHasMorph('nutrientable', [Intake::class], fn ($q) =>
                    $q->where('user_id', 3)
                        ->whereBetween('created_at', [now()->subDays(7), now()])
                )
                ->groupBy('nutrition_id', 'created_date')
                ->get()
                ->groupBy('nutrition_id')
                ->map(fn ($group) =>
                    $group->pluck('total_amount', 'created_date')
                )
        ]);
    }
}
