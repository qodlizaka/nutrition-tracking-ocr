<?php

namespace App\Livewire\Food;

use App\Models\Food;
use App\Models\Nutrition;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    public Collection $nutritions;

    public array $activeNutritions = ['energy'];

    public function mount(): void
    {
        $this->nutritions = Nutrition::all()->keyBy('name');
    }

    public function toggleNutrition(string $name): void
    {
        if (in_array($name, $this->activeNutritions)) {
            $this->activeNutritions = array_values(array_diff($this->activeNutritions, [$name]));
        } else {
            if (count($this->activeNutritions) < 4) {
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
        $foods = Food::query()
            ->with(['nutritions'])
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->latest()
            ->paginate(16);

        return view('livewire.food.index', [
            'foods' => $foods,
        ]);
    }
}
