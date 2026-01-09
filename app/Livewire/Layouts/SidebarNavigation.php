<?php

namespace App\Livewire\Layouts;

use App\Models\Food;
use Livewire\Component;

class SidebarNavigation extends Component
{
    public string $search = '';

    public function render()
    {
        $foods = $this->search === ''
            ? collect()
            : Food::query()
                ->where('name', 'like', '%' . $this->search . '%')
                ->with(['nutritions'])
                ->limit(5)
                ->get();

        return view('livewire.layouts.sidebar-navigation', [
            'foods' => $foods
        ]);
    }
}
