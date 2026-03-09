<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectAttachment extends Model
{
    protected $fillable = ['project_id', 'path', 'original_name', 'mime_type', 'file_type'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function isImage(): bool
    {
        return $this->file_type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->file_type === 'video';
    }
}
