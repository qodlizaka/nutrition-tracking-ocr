<?php

namespace App\Actions;

use App\DataTransferObjects\UserDetailStatsDto;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GetUserDetailHistoryAction
{
    /**
     * Create a new class instance.
     */
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
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            });

        $chartData = [];

        $bmr = (new CalculateBmrAction())($user, $latestBeforeRange) ?? 0;
        $tdee = (new CalculateTdeeAction())($latestBeforeRange, $bmr) ?? 0;

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');

            if ($changesInRange->has($dateString)) {
                $dailyRecord = $changesInRange->get($dateString)->last();
                $bmr = (new CalculateBmrAction())($user, $dailyRecord);
                $tdee = (new CalculateTdeeAction())($dailyRecord, $bmr);
            }

            $chartData[] = new UserDetailStatsDto($dateString, $bmr, $tdee);
        }

        return $chartData;
    }
}
