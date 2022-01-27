<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Oracle extends Model
{
    use HasFactory, SoftDeletes;
    public $table = 'oracle';
    protected $primaryKey = 'person_number';
    public $fillable = [
        'person_number',
        'first_name',
        'last_name',
        'name',
        'primary_email',
        'business_title',
        'job_code',
        'job_profile',
        'management_level',
        'current_hire_date',
        'home_zip_code',
        'location_name',
        'country',
        'cost_center',
        'service_line',
        'company',
        'manager_name',
        'manager_email_address',
        'source_id',
    ];

    public $requiredfields = [
        'person_number',
        'first_name',
        'last_name',
        'primary_email',
        'business_title',
        'home_zip_code',
        'manager_name',
        'manager_email_address',
    ];

    public $dates = ['current_hire_date'];

    public function source()
    {
        return $this->belongsTo(OracleSource::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(Oracle::class, 'manager_email_address', 'primary_email');
    }

    public function oracleManager()
    {
        return $this->hasOne(User::class,  'email', 'manager_email_address')->with('person');
    }

    public function mapminerUser()
    {
        return $this->hasOne(User::class,  'employee_id', 'person_number')->with('person');
    }

    public function scopeSearch($query, $search)
    { 
        
        return  $query->where('primary_email', 'like', "%{$search}%")
            ->orWhere('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->orWhere('manager_name', 'like', "%{$search}%");
     

    }


}
