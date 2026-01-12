<?php

namespace Database\Seeders;

use App\Models\Alg;
use App\Models\Nutrition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AlgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/alg_data.json');
        $json = File::get($jsonPath);
        $nutritions = Nutrition::all()->keyBy('name');

        collect(json_decode($json, true))
            ->map(fn ($data) => [
                ...$data,
                'nutritions' => collect([
                    ...$data['nutritions'],
                    [
                        'name' => 'energy',
                        'value' => $data['energy'],
                    ],
                ])
                    ->keyBy(fn (array $data): int => $nutritions->get($data['name'])->id)
                    ->map(fn (array $data): array => ['value' => $data['value']]),
            ])
            ->each(function (array $data) {
                $alg = Alg::create([
                    'name' => $data['name'],
                    'energy' => $data['energy'],
                ]);

                $alg->nutritions()->attach($data['nutritions']);
            });
    }
}
