<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Adicione esta linha

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
}