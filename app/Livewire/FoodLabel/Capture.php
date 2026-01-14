<?php

namespace App\Livewire\FoodLabel;

use Livewire\Component;
use App\Enum\AspectRatio;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Actions\CompressImageForGeminiAction;
use Exception;

class Capture extends Component
{
    public AspectRatio $aspectRatio = AspectRatio::Square;

    /**
     * Main entry point for saving the image
     */
    public function saveImage(CompressImageForGeminiAction $compressor, $dataUrl)
    {
        $rawImage = $this->decodeAndValidateImage($dataUrl);

        if (! $rawImage) return;

        $compressedBinary = $this->compressImage($compressor, $rawImage);

        if (! $compressedBinary) return;

        $this->storeImage($compressedBinary);

        $this->redirect(route('dashboard'), navigate: true);
    }

    /**
     * Extract inputs, validate file type, and decode Base64.
     */
    protected function decodeAndValidateImage(string $dataUrl): ?string
    {
        if (! preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type)) {
            $this->addError('image', 'Invalid data URI');
            return null;
        }

        $dataUrl = substr($dataUrl, strpos($dataUrl, ',') + 1);
        $inputType = strtolower($type[1]);

        if (! \in_array($inputType, ['jpg', 'jpeg', 'gif', 'png'])) {
            $this->addError('image', 'Invalid image type');
            return null;
        }

        $rawImage = base64_decode($dataUrl);

        if ($rawImage === false) {
            $this->addError('image', 'Base64 decode failed');
            return null;
        }

        return $rawImage;
    }

    /**
     * Handle the external action to compress the image for Gemini.
     */
    protected function compressImage(CompressImageForGeminiAction $compressor, string $rawImage): ?string
    {
        try {
            $compressedBase64 = $compressor($rawImage);
            return base64_decode($compressedBase64);
        } catch (Exception $e) {
            $this->addError('image', 'Image compression failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate filename and write to disk.
     */
    protected function storeImage(string $imageBinary): void
    {
        $filename = 'images/food-label-test/' . Str::random(40) . '.jpg';
        Storage::disk('public')->put($filename, $imageBinary);
    }

    public function render()
    {
        return view('livewire.food-label.capture');
    }
}
