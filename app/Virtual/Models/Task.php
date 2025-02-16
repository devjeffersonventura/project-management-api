<?php

namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="Task",
 *     description="Task model"
 * )
 */
class Task
{
    /**
     * @OA\Property(format="int64")
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(example="Task Title")
     * @var string
     */
    private $title;

    /**
     * @OA\Property(example="Task Description")
     * @var string
     */
    private $description;

    /**
     * @OA\Property(format="date", example="2024-02-15")
     * @var string
     */
    private $creation_date;

    /**
     * @OA\Property(format="date", example="2024-03-15")
     * @var string
     */
    private $completion_date;

    /**
     * @OA\Property(enum={"pending", "in_progress", "completed"})
     * @var string
     */
    private $status;

    /**
     * @OA\Property(format="int64")
     * @var integer
     */
    private $project_id;
} 