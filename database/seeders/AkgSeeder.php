<?php

namespace Database\Seeders;

use App\Enum\Gender;
use App\Models\Akg;
use App\Models\Nutrition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AkgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nutritions = Nutrition::pluck('id', 'name');
        $genders = array_flip(Gender::array());

        $jsonPath = database_path('data/akg_data.json');
        $data = json_decode(File::get($jsonPath), true);

        collect($data)->map(function (array $data) use ($nutritions, $genders): Akg {
            $mappedNutritions = collect($data['nutritions'])
                ->mapWithKeys(function (int $amount, string $name) use ($nutritions): array {
                    $id = $nutritions->get($name);

                    if (!$id) return [];

                    return [$id => ['value' => $amount]];
                });

            unset($data['nutritions']);

            $gender = ucfirst($data['gender']);
            $akg = Akg::query()->create([
                ...$data,
                'gender' => empty($gender) ? null : Gender::from($genders[$gender]),
            ]);
            $akg->nutritions()->attach($mappedNutritions);

            return $akg;
        });
    }
}
