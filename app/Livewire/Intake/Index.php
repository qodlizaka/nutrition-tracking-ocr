<?php

namespace App\Livewire\Intake;

use App\Models\Intake;
use App\Models\Nutrition;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

    public Collection $nutritions;

    #[Url]
    public string $date;

    public array $activeNutritions = [];

    public $sortBy = 'created_at';

    public $sortDirection = 'desc';

    public function mount(): void
    {
        $this->nutritions = Nutrition::all();

        $this->date = today()->format('Y-m-d');

        $this->activeNutritions = $this->nutritions->take(4)->pluck('id')->toArray();
    }

    public function updatedDate()
    {
        $this->resetPage();
    }

    public function previousDate()
    {
        $this->date = Carbon::parse($this->date)->subDay()->format('Y-m-d');
        $this->resetPage();
    }

    public function nextDate()
    {
        if (Carbon::parse($this->date)->isToday()) {
            return;
        }

        $this->date = Carbon::parse($this->date)->addDay()->format('Y-m-d');
        $this->resetPage();
    }

    public function toggleNutrition($id)
    {
        if (\in_array($id, $this->activeNutritions)) {
            if (\count($this->activeNutritions) > 1) {
                $this->activeNutritions = \array_values(\array_diff($this->activeNutritions, [$id]));
            }
        } else {
            if (\count($this->activeNutritions) < 5) {
                $this->activeNutritions[] = $id;
            }
        }
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = Auth::user()
            ->intakes()
            ->with(['nutritions'])
            ->whereDate('created_at', $this->date);

        if (str_starts_with($this->sortBy, 'nutrition_')) {
            $nutritionId = str_replace('nutrition_', '', $this->sortBy);

            $query->orderBy(
                DB::table('nutrientables')
                    ->select('value')
                    ->whereColumn('nutrientables.nutrientable_id', 'intakes.id')
                    ->where('nutrientables.nutrientable_type', Intake::class)
                    ->where('nutrientables.nutrition_id', $nutritionId)
                    ->limit(1),
                $this->sortDirection
            );
        } else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        return view('livewire.intake.index', [
            'intakes' => $query->paginate(10),
        ])->title(__('My intakes'));
    }
}
