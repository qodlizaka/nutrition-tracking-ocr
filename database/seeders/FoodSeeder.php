<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\Nutrition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nutritions = Nutrition::pluck('id', 'name');

        $jsonPath = database_path('data/food_data.json');
        $data = json_decode(File::get($jsonPath), true);

        $test = collect($data)
            ->take(3)
            ->map(function (array $food) use ($nutritions) {
                $mappedNutritions = collect($food['nutritions'])
                    ->mapWithKeys(function (array $n) use ($nutritions): array {
                        [$name, $value, $unit] = array_values($n);

                        $id = $nutritions->get(Str::lower($name));

                        if (!$id) return [];

                        return [$id => ['value' => $value]];
                    });

                unset($food['nutritions']);

                [$name, $url, $image, $serving_size, $unit] = array_values($food);

                $model = Food::query()
                    ->create([
                        'name' => $name,
                        'image' => $image,
                        'total_servings' => $serving_size,
                        'unit' => $unit,
                    ]);

                $model->nutritions()->attach($mappedNutritions);

                return $model;
            });
    }
}
