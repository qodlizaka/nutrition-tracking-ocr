<?php

namespace App\Livewire\FoodLabel;

use App\Enum\FoodStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Enum\AspectRatio;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Actions\CompressImageForGeminiAction;
use App\Actions\ExtractNutritionFactsAction;
use App\DataTransferObjects\GeminiExtractedLabelDto;
use App\Models\Food;
use App\Models\Nutrition;
use Exception;

class Capture extends Component
{
    public AspectRatio $aspectRatio = AspectRatio::Square;

    public function extractNutritionLabel(string $dataUrl)
    {
        [$filename, $image] = $this->saveImage($dataUrl);

        // $apiResult = app(ExtractNutritionFactsAction::class)(base64_encode($image));
        $apiResult = config('gemini.schemas.sample');

        $extractedLabel = GeminiExtractedLabelDto::fromApiResult($apiResult);

        $allNutritionData = Nutrition::all()->keyBy('name');

        $food = Food::query()->create([
            'name' => __('Food label') . ' ' . Str::random(8),
            'image' => $filename,
            'total_servings' => $extractedLabel->servingSize,
            'unit' => $extractedLabel->servingUnit,
            'status' => FoodStatus::Inactive,
        ]);

        $nutritionsToAttach = $extractedLabel->nutritions
            ->mapWithKeys(function($nutri) use ($allNutritionData) {
                $nutritionModel = $allNutritionData->get($nutri['name']);

                $pivot = [];

                $pivot['value'] = array_key_exists('value', $nutri)
                    ? $nutri['value']
                    : 0;

                $pivot['percentage'] = array_key_exists('percentage', $nutri)
                    ? $nutri['percentage']
                    : 0;

                if ($nutritionModel === null)
                    return [];

                return [$nutritionModel->id => $pivot];
            });

        // dd($nutritionsToAttach);

        $food->nutritions()->attach($nutritionsToAttach);

        return $this->redirect(route('food.label.validate', $food->id));
    }

    /**
     * Main entry point for saving the image
     */
    public function saveImage(string $dataUrl): array
    {
        $rawImage = $this->decodeAndValidateImage($dataUrl);

        if (! $rawImage) return [];

        $compressedBinary = $this->compressImage($rawImage);

        if (! $compressedBinary) return [];

        return [$this->storeImage($compressedBinary), $compressedBinary];
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
    protected function compressImage(string $rawImage): ?string
    {
        try {
            $compressedBase64 = app(CompressImageForGeminiAction::class)($rawImage);
            return base64_decode(string: $compressedBase64);
        } catch (Exception $e) {
            $this->addError('image', 'Image compression failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate filename and write to disk.
     */
    protected function storeImage(string $imageBinary): string
    {
        $filename = 'images/food-labels/' . Str::random(40) . '.jpg';
        Storage::disk('public')->put($filename, $imageBinary);

        return $filename;
    }

    public function render()
    {
        return view('livewire.food-label.capture');
    }
}
