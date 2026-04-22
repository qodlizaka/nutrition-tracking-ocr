<?php

namespace App\Livewire\FoodLabel;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Enum\AspectRatio;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Actions\CompressImageForGeminiAction;
use App\Actions\CreateFoodFromLabelAction;
use App\Actions\ExtractNutritionFactsAction;
use App\DataTransferObjects\GeminiExtractedLabelDto;
use Exception;
use Illuminate\Support\Facades\Log;

class Capture extends Component
{
    use WithFileUploads;

    public AspectRatio $aspectRatio = AspectRatio::Square;

    public $photoUpload;

    /**
     * This method no longer needs arguments.
     * It processes the file already uploaded to $this->photoUpload
     */
    public function extractNutritionLabel()
    {
        $this->validate([
            'photoUpload' => 'required|image|max:10000',
        ]);

        try {
            $rawImage = $this->photoUpload->get();

            try {
                $compressedBase64 = app(CompressImageForGeminiAction::class)($rawImage);
                $finalImageBinary = base64_decode($compressedBase64);
            } catch (Exception $e) {
                $finalImageBinary = $rawImage;
            }

            $filename = 'images/food-labels/' . Str::random(40) . '.jpg';
            Storage::disk('public')->put($filename, $finalImageBinary);

            $apiResult = app(ExtractNutritionFactsAction::class)(base64_encode($finalImageBinary));
            // $apiResult = config('gemini.schemas.sample');

            if (isset($apiResult['is_readable']) && $apiResult['is_readable'] === false) {
                Storage::disk('public')->delete($filename);
                $this->addError('photoUpload', __('The image is too blurry or text is unreadable. Please retake the photo.'));
                return;
            }

            if (isset($apiResult['is_nutrition_label']) && $apiResult['is_nutrition_label'] === false) {
                Storage::disk('public')->delete($filename);
                $this->addError('photoUpload', __('We could not detect a valid Nutrition Facts table. Please ensure the photo captures the "Informasi Nilai Gizi" section.'));
                return;
            }

            if (!isset($apiResult['nutritions']) || !isset($apiResult['serving_size'])) {
                 Storage::disk('public')->delete($filename);
                 $this->addError('photoUpload', __('Could not extract complete data. Please try a clearer angle.'));
                 return;
            }

            $extractedLabel = GeminiExtractedLabelDto::fromApiResult($apiResult);

            $food = app(CreateFoodFromLabelAction::class)->execute($extractedLabel, $filename);

            return $this->redirect(route('food.label.validate', $food->id), navigate: true);

        } catch (Exception $e) {
            Log::error('Food Label Error: ' . $e->getMessage());

            $this->addError('photoUpload', 'Processing failed. Please try again or enter manually.');
        }
    }

    public function render()
    {
        return view('livewire.food-label.capture')->title(__('Capture nutrition label'));
    }
}
