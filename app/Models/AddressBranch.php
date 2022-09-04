<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AddressBranch extends Model
{
    public $table = 'address_branch';
    public $fillable = ['branch_id','address_id', 'last_activity'];
    public $dates = ['last_activity'];
    /**
     * [orders description]
     * 
     * @return [type] [description]
     */
    public function orders()
    {
        return $this->hasMany(Orders::class, 'id', 'address_branch_id');
    }
    /**
     * [branch description]
     * 
     * @return [type] [description]
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    /**
     * [address description]
     * 
     * @return [type] [description]
     */
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }
    /**
     * [activities description]
     * 
     * @return [type] [description]
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }


    /**
     * [activities description]
     * 
     * @return [type] [description]
     */
    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }
    /**
     * [activities description]
     * 
     * @return [type] [description]
     */
    public function lastactivity()
    {
        return $this->hasMany(Activity::class, 'address_id', 'address_id')->latest();
    }
    /**
     * [opportunities description]
     * 
     * @return [type] [description]
     */
    public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'address_branch_id', 'id');
    }
    /**
     * [scopeActivityChart description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeActivityChart($query)
    {
        return $query->selectRaw(
            'address_branch.branch_id as branch_id,
            YEARWEEK(activities.expected_close,3) as yearweek,
            sum(activities.value) as funnel'
        )
            ->groupBy(['branch_id','yearweek'])
            ->orderBy('yearweek', 'asc');
    }

    public function OpportunitiesOpen()
    {
        return $this->hasMany(Opportunity::class, 'address_branch_id', 'id')->where('closed', 0);
    }
    /**
     * [scopeOpenOpportunities description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeOpenOpportunities($query)
    {
        $this->opportunities()->where('closed', 0);
    }
    /** 
     * [scopeWonOpportunities description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeWonOpportunities($query)
    {
        $this->opportunities()->where('closed', 1);
    }
    /**
     * [scopeLostOpportunities description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeLostOpportunities($query)
    {
        $this->opportunities()->where('closed', 2);
    }

    public function leadsource()
    {
        return $this->hasManyThrough(LeadSource::class, Address::class, 'id', 'id', 'address_id', 'lead_source_id');
    }

    /**
     * [scopeStaleLeads description]
     * 
     * @param [type] $query      [description]
     * @param [type] $leadsource [description]
     * @param [type] $branches   [description]
     * @param [type] $before     [description]
     * 
     * @return [type]             [description]
     */
    public function scopeStaleLeads(
        $query, 
        array $leadsource, 
        array $branches, 
        Carbon $before
    ) {
        return $query
            ->whereHas(
                'leadsource', function ($q) use ($leadsource) {
                    $q->whereIn('leadsources.id', $leadsource);
                }
            )
            ->whereIn('branch_id', $branches)
            ->where('created_at', '<=', $before)
            ->doesntHave('activities')
            ->doesntHave('opportunities');
    }
    /**
     * [scopeStaleBranchLeads description]
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeStaleBranchLeads($query) {
        return $query
            
            ->where('created_at', '<=', now()->subMonths(3))
            ->where(
                function ($q) {
                    $q->doesntHave('activities')
                        ->orWhereHas(
                            'lastactivity', function ($q1) {
                                $q1->where('created_at', '<=', now()->subMonths(3));
                            }
                        );
                }
            )->doesntHave('opportunities');
    }
    public function scopeOrderByColumn($query, $field, $dir) 
    {
        
         return $query->orderBy($field, $dir);

    }

    public function scopeSearch($query, $search)
    {
        return $query->where('businessname', 'like', "%{$search}%")
            ->orWhere('fullname', 'like', "%{$search}%")
            ->orWhere('firstname', 'like', "%{$search}%")
            ->orWhere('lastname', 'like', "%{$search}%");
    }
}
