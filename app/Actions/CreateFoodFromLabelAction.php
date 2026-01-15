<?php

namespace App\Actions;

use App\Models\Food;
use App\Models\Nutrition;
use App\Enum\FoodStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DataTransferObjects\GeminiExtractedLabelDto;

class CreateFoodFromLabelAction
{
    public function execute(GeminiExtractedLabelDto $extractedLabel, string $imagePath): Food
    {
        return DB::transaction(function () use ($extractedLabel, $imagePath) {
            // Create the food
            $food = Food::create([
                'user_id' => Auth::id(),
                'name' => __('Food label') . ' - ' . now()->format('M d, H:i'),
                'image' => $imagePath,
                'total_servings' => $extractedLabel->servingSize,
                'unit' => $extractedLabel->servingUnit,
                'status' => FoodStatus::Inactive,
            ]);

            // Prepare pivot data
            // Optimization: Cache nutrition IDs if this runs frequently, or keep fetching all if table is small (<100 rows)
            $allNutritionData = Nutrition::all()->keyBy('name');

            $nutritionsToAttach = $extractedLabel->nutritions
                ->mapWithKeys(function ($nutri) use ($allNutritionData) {
                    $nutritionModel = $allNutritionData->get($nutri['name']);

                    if (! $nutritionModel) return [];

                    return [
                        $nutritionModel->id => [
                            'value' => $nutri['value'] ?? null,
                            'percentage' => $nutri['percentage'] ?? null,
                        ]
                    ];
                });

            if ($nutritionsToAttach->isNotEmpty()) {
                $food->nutritions()->attach($nutritionsToAttach);
            }

            return $food;
        });
    }
}
