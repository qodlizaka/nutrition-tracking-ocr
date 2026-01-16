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
        $userAkg = $testUser->detail->akg->nutritions->keyBy('id');

        $intakeCount = 1500;

        Intake::factory($intakeCount)
            ->state(['user_id' => $testUser->id])
            ->create()
            ->each(function ($intake) use ($nutritions, $userAkg, $intakeCount) {
                $intake->nutritions()->attach(
                    $nutritions->shuffle()
                        ->take(rand(15, 25))
                        ->mapWithKeys(fn ($n) => [$n->id => [
                            'value' => ($userAkg->get($n->id)?->pivot->value ?? rand(100, 500)) / ($intakeCount / rand(80, 100)),
                            'created_at' => $intake->created_at,
                        ]])
                        ->toArray()
                );
            });
    }
}
