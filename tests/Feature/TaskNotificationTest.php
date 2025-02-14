<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TaskCompleted;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_is_sent_when_task_is_completed()
    {
        Notification::fake();

        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'status' => 'in_progress'
        ]);

        $task->update(['status' => 'completed']);

        Notification::assertSentTo(
            $user,
            TaskCompleted::class,
            function ($notification) use ($task) {
                return $notification->task->id === $task->id;
            }
        );
    }
} 