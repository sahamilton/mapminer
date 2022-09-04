<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectSource extends Model
{
    public $table = 'projectsource';
    public $dates = ['datefrom', 'dateto'];
    public $fillable = ['source', 'description', 'reference', 'datefrom', 'dateto', 'status'];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
