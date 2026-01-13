<?php

namespace App\Livewire\FoodLabel;

use Livewire\Component;
use App\Enum\AspectRatio;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Capture extends Component
{
    // Default to Square
    public AspectRatio $aspectRatio = AspectRatio::Square;

    public function saveImage($dataUrl)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type)) {
            $dataUrl = substr($dataUrl, strpos($dataUrl, ',') + 1);
            $type = strtolower($type[1]);

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                $this->addError('image', 'Invalid image type');
                return;
            }

            $image = base64_decode($dataUrl);

            if ($image === false) {
                $this->addError('image', 'Base64 decode failed');
                return;
            }
        } else {
            $this->addError('image', 'Invalid data URI');
            return;
        }

        $filename = 'images/food-label-test/' . Str::random(40) . '.' . $type;
        Storage::disk('public')->put($filename, $image);

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.food-label.capture');
    }
}
