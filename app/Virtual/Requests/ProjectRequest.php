<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *     title="Project Request",
 *     description="Project request body data",
 *     type="object",
 *     required={"name", "start_date", "end_date", "status"}
 * )
 */
class ProjectRequest
{
    /**
     * @OA\Property(example="Project Name")
     * @var string
     */
    public $name;

    /**
     * @OA\Property(example="Project Description")
     * @var string
     */
    public $description;

    /**
     * @OA\Property(format="date", example="2024-02-15")
     * @var string
     */
    public $start_date;

    /**
     * @OA\Property(format="date", example="2024-03-15")
     * @var string
     */
    public $end_date;

    /**
     * @OA\Property(enum={"planned", "in_progress", "completed"})
     * @var string
     */
    public $status;

    /**
     * @OA\Property(example="12345678")
     * @var string
     */
    public $cep;

    /**
     * @OA\Property(example="Rua Example, 123")
     * @var string
     */
    public $location;
} 