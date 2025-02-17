<?php

namespace Tests\Unit;

use Tests\TestCase;
use Carbon\Carbon;
use App\Services\ProjectDurationCalculator;
use PHPUnit\Framework\Attributes\Test;

class ProjectDurationCalculatorTest extends TestCase
{
    #[Test]
    public function it_calculates_estimated_days_of_project()
    {
        // Simula um projeto com datas
        $project = [
            'data' => [
                'id' => 2,
                'name' => 'PROJETO TESTE',
                'description' => 'Descrição do projeto teste',
                'start_date' => '2023-10-01',
                'end_date' => '2023-10-10',
                'status' => 'in_progress'
            ]
        ];

        $calculator = new ProjectDurationCalculator();
        
        // Calcula dias estimados
        $estimatedDays = $calculator->calculateEstimatedDays($project['data']);
        
        // Deve retornar 9 dias (10 - 1 = 9 dias)
        $this->assertEquals(9, $estimatedDays);
    }

    #[Test]
    public function it_handles_same_start_and_end_date()
    {
        $project = [
            'data' => [
                'start_date' => '2023-10-01',
                'end_date' => '2023-10-01'
            ]
        ];

        $calculator = new ProjectDurationCalculator();
        
        $estimatedDays = $calculator->calculateEstimatedDays($project['data']);
        
        // Deve retornar 0 dias quando início e fim são no mesmo dia
        $this->assertEquals(0, $estimatedDays);
    }
} 