<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement([
                TaskStatus::PENDING->value,
                TaskStatus::IN_PROGRESS->value,
                TaskStatus::COMPLETED->value
            ]),
            'project_id' => Project::factory(),
            'creation_date' => now()
        ];
    }
} 