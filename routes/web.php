<?php

use App\Http\Controllers\FoodController;
use App\Livewire\Food\Index as FoodIndex;
use App\Livewire\Food\Show as FoodShow;
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

        Route::get('/', FoodIndex::class)
            ->name('foods.index');

        Route::get('/{food}', FoodShow::class)
            ->name('foods.show');

    });


    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('settings/personal-info', UserDetail::class)->name('settings.personal-info');
});

require __DIR__.'/auth.php';
