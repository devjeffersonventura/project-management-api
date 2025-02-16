<?php

namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="Project Report",
 *     description="Project report model"
 * )
 */
class ProjectReport
{
    /**
     * @OA\Property(example="Project Name")
     * @var string
     */
    private $project_name;

    /**
     * @OA\Property(example=10)
     * @var integer
     */
    private $total_tasks;

    /**
     * @OA\Property(example=5)
     * @var integer
     */
    private $completed_tasks;

    /**
     * @OA\Property(example=3)
     * @var integer
     */
    private $in_progress_tasks;

    /**
     * @OA\Property(example=2)
     * @var integer
     */
    private $pending_tasks;

    /**
     * @OA\Property(example="50%")
     * @var string
     */
    private $completion_rate;
} 