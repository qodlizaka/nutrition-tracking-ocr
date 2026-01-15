<?php

namespace App\Livewire\FoodLabel;

use Livewire\Component;
use Livewire\WithFileUploads; // <--- REQUIRED TRAIT
use App\Enum\AspectRatio;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Actions\CompressImageForGeminiAction;
use App\Actions\CreateFoodFromLabelAction;
use App\DataTransferObjects\GeminiExtractedLabelDto;
use Exception;

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

            // $apiResult = app(ExtractNutritionFactsAction::class)(base64_encode($image));
            $apiResult = config('gemini.schemas.sample');
            $extractedLabel = GeminiExtractedLabelDto::fromApiResult($apiResult);

            $food = app(CreateFoodFromLabelAction::class)->execute($extractedLabel, $filename);

            return $this->redirect(route('food.label.validate', $food->id), navigate: true);

        } catch (Exception $e) {
            $this->addError('photoUpload', 'Processing failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.food-label.capture');
    }
}
