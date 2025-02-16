<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectReportController extends Controller
{
    /**
     * @OA\Get(
     *     path="/reports/projects",
     *     summary="Get projects report",
     *     description="Get report of all projects with task statistics",
     *     tags={"Project Reports"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Projects report",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="project_name", type="string", example="Project Name"),
     *                 @OA\Property(property="total_tasks", type="integer", example=10),
     *                 @OA\Property(property="completed_tasks", type="integer", example=5),
     *                 @OA\Property(property="in_progress_tasks", type="integer", example=3),
     *                 @OA\Property(property="pending_tasks", type="integer", example=2),
     *                 @OA\Property(property="completion_rate", type="string", example="50%")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized - Only admins can access this report"
     *     )
     * )
     */
    public function index()
    {
        // ... seu código existente
    }
} 