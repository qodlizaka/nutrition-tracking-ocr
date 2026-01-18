<?php

namespace App\Http\Controllers;

use App\Actions\GetUserDetailHistoryAction;
use App\Models\Intake;
use App\Models\Nutrientable;
use App\Models\Nutrition;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        $startDate = now()->subDays(7);
        $endDate = now();

        $userDetailHistory = (new GetUserDetailHistoryAction())($user, $startDate, $endDate);

        return view('dashboard', [
            'user' => $user->load(['detail']),
            'intakeCountToday' => $user->intakes()->whereDate('created_at', now())->count(),
            'userDetailHistory' => collect($userDetailHistory),
            'weeklyCalorieIntake' => Nutrientable::query()
                ->select(columns: 'nutrition_id')
                ->where('nutrition_id', Nutrition::where('name', 'energy')->first()->id)
                ->selectRaw(expression: 'DATE(created_at) as created_date')
                ->selectRaw(expression: 'SUM(value) as total_amount')
                ->whereHasMorph('nutrientable', [Intake::class], callback: fn ($q) =>
                    $q->where( 'user_id', Auth::id())
                        ->whereBetween('created_at', [$startDate, $endDate])
                )
                ->groupBy('nutrition_id', 'created_date')
                ->get()
                ->groupBy('nutrition_id')
                ->map(fn ($group) =>
                    $group->pluck('total_amount', 'created_date')
                )
                ->flatten(),
        ]);
    }
}
