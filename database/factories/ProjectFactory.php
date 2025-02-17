<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $endDate = $this->faker->dateTimeBetween($startDate, '+2 months');
        
        return [
            'name' => $this->faker->company,
            'description' => $this->faker->paragraph,
            'status' => ProjectStatus::IN_PROGRESS->value,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'user_id' => User::factory(),
            'cep' => str_replace('-', '', $this->faker->numerify('#####-###')),
            'location' => $this->faker->city . ' - ' . $this->faker->stateAbbr
        ];
    }
} 