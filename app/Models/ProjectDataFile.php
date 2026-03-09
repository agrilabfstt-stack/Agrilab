<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDataFile extends Model
{
    protected $table = 'project_data_files';

    protected $fillable = ['project_data_id', 'path', 'original_name'];

    public function projectData()
    {
        return $this->belongsTo(ProjectData::class, 'project_data_id');
    }
}
