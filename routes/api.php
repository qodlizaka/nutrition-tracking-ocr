<?php

use App\Actions\FindUserAkg;
use App\Enum\PhysicalActivityLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

if (app()->environment('local')) {
    Route::post('/_test/setup-user', function () {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $user->userDetails()
            ->updateOrCreate([
                'akg_id' => (new FindUserAkg())($user)->id,
                'weight' => rand(50, 100),
                'height' => rand(150, 200),
                'activity_level' => fake()->randomElement(PhysicalActivityLevel::cases())->value,
            ]);

        return response()->json([
            'email' => $user->email,
            'password' => 'password',
        ]);
    });
}
