<?php

namespace App\Http\Controllers;

use App\Actions\GetUserDetailHistoryAction;
use App\Models\Intake;
use App\Models\Nutrientable;
use App\Models\Nutrition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        $user->load(['detail']);

        if (! $user->detail) {
            return redirect()->route('settings.personal-info');
        }

        $startDate = now()->subDays(7);
        $endDate = now();

        $userDetailHistory = app(GetUserDetailHistoryAction::class)($user, $startDate, $endDate);

        $energyNutrition = Nutrition::where('name', 'energy')->first();

        $weeklyCalorieIntake = collect();

        if ($energyNutrition) {
            $weeklyCalorieIntake = Nutrientable::query()
                ->select('nutrition_id')
                ->where('nutrition_id', $energyNutrition->id)
                ->selectRaw('DATE(created_at) as created_date')
                ->selectRaw('SUM(value) as total_amount')
                ->whereHasMorph('nutrientable', [Intake::class], callback: fn ($q) =>
                    $q->where('user_id', $user->id) // Use $user->id directly, slightly cleaner than Auth::id() inside closure
                      ->whereBetween('created_at', [$startDate, $endDate])
                )
                ->groupBy('nutrition_id', 'created_date')
                ->get()
                ->groupBy('nutrition_id')
                ->map(fn ($group) =>
                    $group->pluck('total_amount', 'created_date')
                )
                ->flatten();
        }

        return view('dashboard', [
            'user' => $user,
            'intakeCountToday' => $user->intakes()->whereDate('created_at', now())->count(),
            'userDetailHistory' => collect($userDetailHistory),
            'weeklyCalorieIntake' => $weeklyCalorieIntake,
        ]);
    }
}
