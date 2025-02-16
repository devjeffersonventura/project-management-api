<?php

namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model"
 * )
 */
class User
{
    /**
     * @OA\Property(format="int64")
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(example="John Doe")
     * @var string
     */
    private $name;

    /**
     * @OA\Property(format="email", example="user@example.com")
     * @var string
     */
    private $email;

    /**
     * @OA\Property(enum={"admin", "user"})
     * @var string
     */
    private $role;
} 