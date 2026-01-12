<?php

namespace Database\Seeders;

use App\Enum\NutritionCategory;
use App\Enum\NutritionGroup;
use App\Models\Nutrition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class NutritionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/nutritions_data.json');
        $json = File::get($jsonPath);

        $groups = array_flip(NutritionGroup::array());
        $categories = array_flip(NutritionCategory::array());

        collect(json_decode($json, true))
            ->map(fn ($n) => [
                ...$n,
                'group' => NutritionGroup::from($groups[$n['group']]),
                'category' => NutritionCategory::from($categories[ucfirst($n['category'])]),
            ])
            ->each(fn (array $n) => Nutrition::create($n));
    }
}
