<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'role' => UserRole::class
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Password encryption.
     *  
     * @var string  $value
     * @return void
     */
    public function setPasswordAttribute($value) {
        $this->attributes['password'] = bcrypt($value);
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role === UserRole::USER;
    }    

    /**
     * Rules for validation.
     *
     * @return array<string, mixed>
     */
    public static function rules($isUpdate = false): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ];

        if ($isUpdate) {
            $rules['email'] = 'sometimes|string|email|max:255|unique:users';
            $rules['password'] = 'sometimes|string|min:8';
        }

        return $rules;
    }
}