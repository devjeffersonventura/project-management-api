<?php

namespace App\Services;

class ProjectProgressCalculator
{
    public function calculateProgress(array $tasks): int
    {
        if (empty($tasks)) {
            return 0;
        }

        $completedTasks = array_filter($tasks, function($task) {
            return $task['status'] === 'completed';
        });

        return (int) (count($completedTasks) / count($tasks) * 100);
    }
} 