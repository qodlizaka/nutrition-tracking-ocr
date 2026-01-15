<?php

use App\Livewire\Food\Index as FoodIndex;
use App\Livewire\Food\Show as FoodShow;
use App\Livewire\FoodLabel\Capture as FoodLabelCapture;
use App\Livewire\FoodLabel\Validate as ValidateFoodLabel;
use App\Livewire\Intake\Index as IntakeIndex;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\UserDetail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::group(['prefix' => 'foods'], function () {
        Route::view('/', 'foods.index')
            ->name('foods.index');

        Route::get('/{food}', FoodShow::class)
            ->name('foods.show');
    });

    Route::group(['prefix' => 'intakes'], function () {
        Route::get('/', IntakeIndex::class)
            ->name('intakes.index');
    });

    Route::group(['prefix' => 'food-label'], function () {
        Route::get('/', FoodLabelCapture::class)
            ->name('food.label.capture');

        Route::get('/validate/{food}', ValidateFoodLabel::class)
            ->name('food.label.validate');

        Route::view('/history', 'food-label.history')
            ->name('food.label.history');
    });

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('settings/personal-info', UserDetail::class)->name('settings.personal-info');
});

require __DIR__.'/auth.php';
