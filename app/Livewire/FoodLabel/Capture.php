<?php

namespace App\Livewire\FoodLabel;

use Livewire\Component;
use App\Enum\AspectRatio;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Actions\CompressImageForGeminiAction;

class Capture extends Component
{
    public AspectRatio $aspectRatio = AspectRatio::Square;

    public function saveImage(CompressImageForGeminiAction $compressor, $dataUrl)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type)) {
            $dataUrl = substr($dataUrl, strpos($dataUrl, ',') + 1);
            $inputType = strtolower($type[1]);

            if (! \in_array($inputType, ['jpg', 'jpeg', 'gif', 'png'])) {
                $this->addError('image', 'Invalid image type');
                return;
            }

            $rawImage = base64_decode($dataUrl);

            if ($rawImage === false) {
                $this->addError('image', 'Base64 decode failed');
                return;
            }
        } else {
            $this->addError('image', 'Invalid data URI');
            return;
        }

        try {
            $compressedBase64 = $compressor($rawImage);
        } catch (\Exception $e) {
            $this->addError('image', 'Image compression failed: ' . $e->getMessage());
            return;
        }

        $finalImageBinary = base64_decode($compressedBase64);

        $filename = 'images/food-label-test/' . Str::random(40) . '.jpg';

        Storage::disk('public')->put($filename, $finalImageBinary);

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.food-label.capture');
    }
}
