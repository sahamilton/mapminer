<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oracle extends Model
{
    use HasFactory;
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
    ];

    public $requiredfields = [
        'person_number',
        'first_name',
        'last_name',
        'primary_email',
        'business_title',
        'current_hire_date',
        'home_zip_code',
        'manager_name',
        'manager_e-mail_address',
    ];

    public $dates = ['current_hire_date'];
}
