<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;

class ReportController extends Controller
{
    public function projectReport()
    {
        $totalProjects = Project::count();

        $completedProjects = Project::where('status', 'completed')->count();
        $inProgressProjects = Project::where('status', 'in_progress')->count();
        $plannedProjects = Project::where('status', 'planned')->count();
        
        $completedTasks = Task::where('status', 'completed')->count();
        $pendingTasks = Task::where('status', 'pending')->count();

        return response()->json([
            'total_projects' => $totalProjects,
            'projects_by_status' => [
                'completed' => $completedProjects,
                'in_progress' => $inProgressProjects,
                'planned' => $plannedProjects,
            ],
            'tasks_by_status' => [
                'completed' => $completedTasks,
                'pending' => $pendingTasks,
            ],
        ]);
    }
}