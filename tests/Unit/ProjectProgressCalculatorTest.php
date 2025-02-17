<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Enums\TaskStatus;
use App\Enums\ProjectStatus;
use App\Services\ProjectProgressCalculator;
use PHPUnit\Framework\Attributes\Test;

class ProjectProgressCalculatorTest extends TestCase
{
    #[Test]
    public function it_calculates_correct_project_progress_percentage()
    {
        // Cria um usuário
        $user = User::factory()->create();

        // Cria um projeto associado ao usuário
        $project = Project::factory()->create([
            'name' => 'Projeto Teste',
            'description' => 'Descrição do projeto teste',
            'user_id' => $user->id,
            'status' => ProjectStatus::IN_PROGRESS->value
        ]);

        // Duas tarefas completadas
        Task::factory()->count(2)->create([
            'project_id' => $project->id,
            'status' => TaskStatus::COMPLETED->value,
            'creation_date' => now()
        ]);

        // Uma tarefa em progresso
        Task::factory()->create([
            'project_id' => $project->id,
            'status' => TaskStatus::IN_PROGRESS->value,
            'creation_date' => now()
        ]);

        // Uma tarefa pendente
        Task::factory()->create([
            'project_id' => $project->id,
            'status' => TaskStatus::PENDING->value,
            'creation_date' => now()
        ]);

        // Busca as tarefas do projeto
        $tasks = $project->tasks()->get()->toArray();

        $calculator = new ProjectProgressCalculator();
        
        // Calcula o progresso
        $progress = $calculator->calculateProgress($tasks);
        
        // Deve retornar 50% pois 2 de 4 tarefas estão completas
        $this->assertEquals(50, $progress);
    }

    #[Test]
    public function it_returns_zero_progress_when_project_has_no_tasks()
    {
        // Cria um usuário
        $user = User::factory()->create();

        // Cria um projeto sem tarefas
        $project = Project::factory()->create([
            'name' => 'Projeto Sem Tarefas',
            'description' => 'Projeto para teste sem tarefas',
            'user_id' => $user->id,
            'status' => ProjectStatus::IN_PROGRESS->value
        ]);

        $tasks = $project->tasks()->get()->toArray();

        $calculator = new ProjectProgressCalculator();
        
        $progress = $calculator->calculateProgress($tasks);
        
        $this->assertEquals(0, $progress);
    }
} 