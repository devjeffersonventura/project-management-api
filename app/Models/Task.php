<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\TaskCompleted;

class Task extends Model
{
    use HasFactory, SoftDeletes; 

    protected $fillable = [
        'title',
        'description',
        'creation_date',
        'completion_date',
        'status',
        'project_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public static function rules($isUpdate = false)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'creation_date' => 'required|date',
            'completion_date' => 'required|date|after_or_equal:creation_date',
            'status' => 'required|in:pending,in_progress,completed',
            'project_id' => 'required|exists:projects,id',
        ];

        if ($isUpdate) {
            $rules = [
                'title' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'creation_date' => 'sometimes|date',
                'completion_date' => 'sometimes|date|after_or_equal:creation_date',
                'status' => 'sometimes|in:pending,in_progress,completed',
                'project_id' => 'sometimes|exists:projects,id',
            ];
        }        

        return $rules;
    }

    protected static function booted()
    {
        static::updated(function ($task) {
            if ($task->isDirty('status') && $task->status === 'completed') {
                $task->project->user->notify(new TaskCompleted($task));
            }
        });
    }
}