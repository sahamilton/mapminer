<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public $fillable = ['type','test','route','message','created_by','expiration'];
    public $dates =['expiration'];
    /**
     * [participants description]
     * 
     * @return [type] [description]
     */
    public function participants()
    {
        return $this->belongsToMany(Person::class)->withPivot('activity');
    }
    /**
     * [respondents description]
     * 
     * @return [type] [description]
     */
    public function respondents()
    {
        return $this->belongsToMany(Person::class)->withPivot('activity')->wherePivot('activity', '!=', 'null');
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

    public function documents()
    {
        return $this->hasMany(CampaignDocuments::class);
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

    public function scopeActive($query)
    {
        return $query->where('status', 'launched')
            ->where('datefrom', '<=', now())
            ->where('dateto', '>=', now());
    }

    public function getSalesTeamFromManager($manager_id, $serviceline)
    {
        return Person::whereId([$manager_id])->firstOrFail()->descendantsAndSelf()
            ->whereHas(
                'userdetails.roles', function ($q) {
                        $q->whereIn('roles.id', ['3','6','7']);
                }
            )
            ->with(
                ['branchesServiced'=>function ($q) use ($serviceline) {
                    $q->whereHas(
                        'servicelines', function ($q1) use ($serviceline) {
                            $q1->whereIn('id', $serviceline);
                        }
                    );
                }
                ]
            )
            ->orderBy('lastname')
            ->orderBY('firstname')
            ->get();
    }
    /**
     * [getCampaignServiceLines description]
     * 
     * @return [type] [description]
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
