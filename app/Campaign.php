<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public $fillable = ['title', 'description', 'datefrom', 'dateto', 'created_by', 'manager_id', 'status'];
    public $dates =['datefrom', 'dateto'];
    
    /**
     * [author description]
     * 
     * @return [type] [description]
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    /**
     * [companies description]
     * 
     * @return [type] [description]
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }
    public function manager()
    {
        return $this->belongsTo(Person::class, 'manager_id', 'id');
    }
    /**
     * [branches description]
     * 
     * @return [type] [description]
     */
    public function branches()
    {
        return $this->belongsToMany(Branch::class);
    }

    public function vertical()
    {
        return $this->belongsToMany(SearchFilter::class, 'campaign_searchfilter', 'campaign_id', 'searchfilter_id');
    }

    public function servicelines()
    {
        return $this->belongsToMany(Serviceline::class, 'campaign_serviceline', 'campaign_id', 'serviceline_id');
    }

    public function scopeCurrent($query, Array $branches =null)
    {
        
        $query = $query
            ->where('datefrom', '<=', Carbon::now()->startOfDay())
            ->where('dateto', '>=', Carbon::now()->endOfDay());
        if ($branches) {
            $query = $query->wherehas(
                'branches', function ($q) use ($branches) {
                    $q->whereIn('branches.id', $branches);
                }
            );
        }
        return $query;
    }
    /**
     * [team description]
     * 
     * @return [type] [description]
     */
    public function team()
    {
        return $this->belongsToMany(Person::class);
    }

    public function setTeam()
    {
       
        
    }

    public function getLocations()
    {
        
        return Address::wherehas(
            'assignedToBranch', function ($q) {
                $q->whereIn('branches.id', $this->branches()->pluck('id')->toArray());
            }
        )
        ->whereIn('company_id', $this->companies()->pluck('id')->toArray())
        ->pluck('id')->toArray();
       
    }




}
