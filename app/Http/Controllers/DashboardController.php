<?php

namespace App\Http\Controllers;

use App\Actions\GetUserDetailHistoryAction;
use App\Models\Intake;
use App\Models\Nutrientable;
use App\Models\Nutrition;
use Carbon\CarbonPeriod;
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

        $startDate = now()->subDays(7)->startOfDay();
        $endDate = now()->endOfDay();

        $userDetailHistory = app(GetUserDetailHistoryAction::class)($user, $startDate, $endDate);

        $energyNutrition = Nutrition::where('name', 'energy')->first();
        $weeklyCalorieIntake = [];

        if ($energyNutrition) {
            $period = CarbonPeriod::create($startDate, '1 day', $endDate);

            $intakeChanges = Nutrientable::query()
                ->where('nutrition_id', $energyNutrition->id)
                ->whereHasMorph('nutrientable', [Intake::class], fn ($q) =>
                    $q->where('user_id', $user->id)
                      ->whereBetween('created_at', [$startDate, $endDate])
                )
                ->selectRaw('DATE(created_at) as date')
                ->selectRaw('SUM(value) as total')
                ->groupBy('date')
                ->pluck('total', 'date');

            foreach ($period as $date) {
                $dateString = $date->format('Y-m-d');

                $dailyTotal = $intakeChanges->get($dateString, 0);

                $weeklyCalorieIntake[] = [
                    'date' => $dateString,
                    'total' => (int) $dailyTotal,
                ];
            }
        }

        return view('dashboard', [
            'user' => $user,
            'intakeCountToday' => $user->intakes()->whereDate('created_at', now())->count(),
            'userDetailHistory' => collect($userDetailHistory),
            'weeklyCalorieIntake' => collect($weeklyCalorieIntake),
        ]);
    }
}
