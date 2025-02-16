<?php

namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="Project",
 *     description="Project model",
 *     @OA\Xml(name="Project")
 * )
 */
class Project
{
    /**
     * @OA\Property(format="int64")
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(example="Project Name")
     * @var string
     */
    private $name;

    /**
     * @OA\Property(example="Project Description")
     * @var string
     */
    private $description;

    /**
     * @OA\Property(format="date", example="2024-02-15")
     * @var string
     */
    private $start_date;

    /**
     * @OA\Property(format="date", example="2024-03-15")
     * @var string
     */
    private $end_date;

    /**
     * @OA\Property(enum={"planned", "in_progress", "completed"})
     * @var string
     */
    private $status;

    /**
     * @OA\Property(example="12345678")
     * @var string
     */
    private $cep;

    /**
     * @OA\Property(example="Rua Example, 123")
     * @var string
     */
    private $location;
} 