<?php

namespace App\Livewire\Food;

use App\Enum\FoodStatus;
use App\Models\Food;
use App\Models\Nutrition;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Browser extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    public Collection $nutritions;

    public array $activeNutritions = ['energy'];

    public string $source;

    public function mount(string $source = 'public'): void
    {
        $this->source = $source;
        $this->nutritions = Nutrition::all()->keyBy('name');
    }

    public function toggleNutrition(string $name): void
    {
        if (\in_array($name, $this->activeNutritions)) {
            $this->activeNutritions = array_values(array_diff($this->activeNutritions, [$name]));
        } else {
            if (\count($this->activeNutritions) < 5) {
                $this->activeNutritions[] = $name;
            }
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Food::query()->with(['nutritions']);

        match ($this->source) {
            'mine' => $query->where('user_id', Auth::id()),
            'public' => $query->ofStatus(FoodStatus::Active),
            default => $query->ofStatus(FoodStatus::Active),
        };

        $query->when($this->search, fn ($q) =>
            $q->where('name', 'like', '%'.$this->search.'%')
        );

        return view('livewire.food.browser', [
            'foods' => $query->latest()->paginate(16),
        ]);
    }
}
