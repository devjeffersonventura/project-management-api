<?php

namespace App\Services;

class ProjectHoursCalculator
{
    public function calculateTotalHours(array $tasks): int
    {
        return array_reduce($tasks, function($total, $task) {
            return $total + $task['estimated_hours'];
        }, 0);
    }

    public function calculateRemainingHours(array $tasks): int
    {
        $incompleteTasks = array_filter($tasks, function($task) {
            return $task['status'] !== 'completed';
        });

        return $this->calculateTotalHours($incompleteTasks);
    }
} 