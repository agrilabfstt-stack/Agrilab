<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdeaParticipant extends Model
{
    protected $fillable = [
        'idea_id',
        'user_id',
    ];

    public function idea()
    {
        return $this->belongsTo(ResearchIdea::class, 'idea_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
