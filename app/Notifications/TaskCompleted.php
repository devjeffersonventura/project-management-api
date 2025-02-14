<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Tarefa Concluída: ' . $this->task->title)
            ->line('A tarefa "' . $this->task->title . '" foi marcada como concluída.')
            ->line('Descrição: ' . $this->task->description)
            ->line('Projeto: ' . $this->task->project->name)
            ->action('Ver Tarefa', url('/tasks/' . $this->task->id))
            ->line('Obrigado por usar nossa aplicação!');
    }
} 