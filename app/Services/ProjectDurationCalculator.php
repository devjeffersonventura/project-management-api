<?php

namespace App\Services;

use Carbon\Carbon;

class ProjectDurationCalculator
{
    public function calculateEstimatedDays(array $project): int
    {
        $startDate = Carbon::parse($project['start_date']);
        $endDate = Carbon::parse($project['end_date']);

        return $startDate->diffInDays($endDate);
    }
} 