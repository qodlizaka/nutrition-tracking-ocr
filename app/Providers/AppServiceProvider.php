<?php

namespace App\Providers;

use App\Actions\ExtractNutritionFactsAction;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->bind(ExtractNutritionFactsAction::class, fn() => new class {
                public function __invoke($imageBase64, $mimeType = 'image/jpeg')
                {
                    return config('gemini.schemas.sample');
                }
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.tailwind');

        Paginator::defaultSimpleView('vendor.pagination.simple-tailwind');

        Carbon::macro('greet', function() {
            $hour = now()->hour;
                if ($hour < 12) {
                    return __('Good Morning');
                } elseif ($hour < 18) {
                    return __('Good Afternoon');
                } else {
                    return __('Good Evening');
                }
        });
    }
}
