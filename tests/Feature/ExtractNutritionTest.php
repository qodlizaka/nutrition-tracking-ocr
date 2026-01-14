<?php

use App\Actions\CompressImageForGeminiAction;
use App\Actions\ExtractNutritionFactsAction;
use Illuminate\Support\Facades\Storage;

test('extraction_success', function () {
    $image = Storage::disk('public')->get('images/food-label-test/test.png');

    $compressedImage = app(CompressImageForGeminiAction::class)($image);

    $nutritionJson = app(ExtractNutritionFactsAction::class)($compressedImage);

    dd($nutritionJson);

    $sampleOutput = config('gemini.schemas.sample');

    dd($sampleOutput);
});
