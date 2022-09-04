<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OracleJobs extends Model
{
    use HasFactory;
    public $table="oracle_jobs";

    public function mapminerRole()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function oracleJob()
    {
        return $this->belongsTo(Oracle::class, 'job_code', 'job_code');
    }
    
    public function scopeSearch($query, $search)
    {
        return $query->where('job_profile', 'like', "%{$search}%");
    }
}
