<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public static function validate(array $data, $isUpdate = false)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,in_progress,completed',
        ];

        if ($isUpdate) {
            $rules = [
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after_or_equal:start_date',
                'status' => 'sometimes|in:planned,in_progress,completed',
            ];
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function save(array $options = [])
    {
        $this->validate($this->attributes, $this->exists);
        parent::save($options);
    }
}