<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchIdea extends Model
{
    protected $fillable = [
        'title',
        'description',
        'author_id',
        'author_type',
        'status',
        'tags',
        'attachment_path',
        'attachment_name',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function participants()
    {
        return $this->hasMany(IdeaParticipant::class, 'idea_id');
    }

    public function participantUsers()
    {
        return $this->belongsToMany(User::class, 'idea_participants', 'idea_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(IdeaComment::class, 'idea_id')->with('user')->latest();
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'open' => 'Ouvert',
            'in_progress' => 'En cours',
            'completed' => 'Terminé',
            default => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'open' => 'green',
            'in_progress' => 'yellow',
            'completed' => 'blue',
            default => 'gray',
        };
    }
}
