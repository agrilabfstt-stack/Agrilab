<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'created_by',
        'category_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function data()
    {
        return $this->hasMany(ProjectData::class)->orderBy('sort_order');
    }

    public function attachments()
    {
        return $this->hasMany(ProjectAttachment::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->with('user')->latest();
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'active' => 'Actif',
            'blocked' => 'Bloqué',
            'completed' => 'Terminé',
            default => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'active' => 'green',
            'blocked' => 'red',
            'completed' => 'blue',
            default => 'gray',
        };
    }
}
