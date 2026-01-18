<?php

namespace App\Livewire\Settings;

use App\Enum\PhysicalActivityLevel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class UserDetail extends Component
{
    #[Validate('required|numeric|min:1|max:500')]
    public ?float $weight = null;

    #[Validate('required|numeric|min:50|max:300')]
    public ?float $height = null;

    #[Validate('required')]
    public ?PhysicalActivityLevel $activityLevel = null;

    public function mount(): void
    {
        $latest = Auth::user()->userDetails()->latest()->first();

        if ($latest) {
            $this->weight = $latest->weight;
            $this->height = $latest->height;
            $this->activityLevel = $latest->activity_level;
        }
    }

    public function updatePersonalInfo(): void
    {
        $this->validate();

        $user = Auth::user();
        $latest = $user->userDetails()
            ->latest()
            ->first();

        if ($latest?->isIdenticalTo($this->weight, $this->height, $this->activityLevel)) {
            $this->dispatch('personal-info-updated');

            return;
        }

        $user->userDetails()
            ->updateOrCreate([
                'weight' => $this->weight,
                'height' => $this->height,
                'activity_level' => $this->activityLevel,
            ]);

        $this->dispatch('personal-info-updated');
    }

    public function render()
    {
        return view('livewire.settings.user-detail');
    }
}
