<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectData extends Model
{
    protected $table = 'project_data';

    protected $fillable = [
        'project_id',
        'name',
        'type',
        'content',
        'sort_order',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function files()
    {
        return $this->hasMany(ProjectDataFile::class, 'project_data_id');
    }
}
