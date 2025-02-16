<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'user_id',
        'cep',
        'location'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function rules($isUpdate = false)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,in_progress,completed',
            'cep' => 'nullable|string|size:8',
            'location' => 'nullable|string|max:255'
        ];
    
        if ($isUpdate) {
            $rules = [
                'name' => 'sometimes|string|max:255', 
                'description' => 'nullable|string',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after_or_equal:start_date',
                'status' => 'sometimes|in:planned,in_progress,completed',
                'cep' => 'nullable|string|size:8',
                'location' => 'nullable|string|max:255'
            ];
        }
    
        return $rules;
    }
}