<?php

namespace Database\Seeders;

use App\Models\Intake;
use App\Models\Nutrition;
use App\Models\User;
use Illuminate\Database\Seeder;

class IntakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nutritions = Nutrition::all();
        $testUser = User::findOrFail(3);

        Intake::factory(1500)
            ->state(['user_id' => $testUser->id])
            ->create()
            ->each(function ($intake) use ($nutritions) {
                $intake->nutritions()->attach(
                    $nutritions->shuffle()
                        ->take(rand(15, 25))
                        ->mapWithKeys(fn ($n) => [$n->id => [
                            'value' => fake()->randomFloat(2, 10, 900),
                            'created_at' => $intake->created_at,
                        ]])
                        ->toArray()
                );
            });
    }
}
