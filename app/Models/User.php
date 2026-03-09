<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'professor_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isProfessor(): bool
    {
        return $this->role === 'professor';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function students()
    {
        return $this->hasMany(User::class, 'professor_id');
    }

    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
