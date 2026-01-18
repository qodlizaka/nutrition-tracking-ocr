<?php

namespace App\Actions;

use App\DataTransferObjects\UserDetailStatsDto;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GetUserDetailHistoryAction
{
    public function __invoke(User $user, Carbon $startDate, Carbon $endDate)
    {
        $period = CarbonPeriod::create($startDate, '1 day', $endDate);

        $latestBeforeRange = $user->userDetails()
            ->where('created_at', '<', $startDate)
            ->latest('created_at')
            ->first();

        $changesInRange = $user->userDetails()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(fn ($item) => $item->created_at->format('Y-m-d'));

        $chartData = [];

        $currentDetail = $latestBeforeRange;
        $bmr = app(CalculateBmrAction::class)($user, $currentDetail);
        $tdee = app(CalculateTdeeAction::class)($currentDetail, $bmr);

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');

            if ($changesInRange->has($dateString)) {
                $currentDetail = $changesInRange->get($dateString)->last();
                $bmr = app(CalculateBmrAction::class)($user, $currentDetail);
                $tdee = app(CalculateTdeeAction::class)($currentDetail, $bmr);
            }

            $chartData[] = new UserDetailStatsDto($dateString, $bmr, $tdee);
        }

        return $chartData;
    }
}
