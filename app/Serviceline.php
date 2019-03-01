<?php
namespace App;

class Serviceline extends Model
{

    // Add your validation rules here
    public static $rules = [
        'ServiceLine' => 'required'
    ];

    // Don't forget to fill this array
    protected $fillable = ['ServiceLine','color'];


    public function providedBy()
    {
            return $this->belongsToMany(Branch::class);
    }
    public function companiesServed()
    {
            return $this->belongsToMany(Company::class);
    }
    
    public function usersServing()
    {
        return $this->belongsToMany(User::class);
    }

    public function companyCount()
    {
        return $this->belongsToMany(Company::class)
        ->selectRaw('serviceline_id, count(*) as aggregate')
        ->groupBy('serviceline_id');
    }

    public function branchCount()
    {
        return $this->belongsToMany(Branch::class)
        ->selectRaw('serviceline_id, count(*) as aggregate')
        ->groupBy('serviceline_id');
    }

    public function userCount()
    {
        return $this->belongsToMany(User::class)
        ->selectRaw('serviceline_id, count(*) as aggregate')
        ->groupBy('serviceline_id');
    }

    public function relatedNews()
    {
        return $this->belongsToMany(News::class);
    }
}
