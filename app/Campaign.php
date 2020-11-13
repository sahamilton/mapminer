<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model 
{
    use GeoCode;
    public $fillable = ['title', 'description', 'datefrom', 'dateto', 'created_by', 'manager_id', 'status', 'type'];
    
    public $dates =['datefrom', 'dateto'];
    // Methods for Calendar
    // 
    /**
     * [getId description]
     * 
     * @return [type] [description]
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay()
    {
        return true;
    }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart()
    {
        return $this->datefrom;
    }
    /**
     * [getEventOptions description]
     * 
     * @return [type] [description]
     */
    public function getEventOptions()
    {
        
        if ($this->status=='launched') {
            return [
                    'url' => route('campaigns.show', $this->id),
                    'color'=>'#e48535',
                ];
        } else {
            return ['url' => route('campaigns.show', $this->id),
                'color'=>'#112b46',
            ];
        }
    }
    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd()
    {
        return $this->dateto;
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
    /**
     * [setTeam description]
     */
    public function setTeam()
    {
       
        
    }
    public function getCampaignBranches()
    {
        // get managers team
        $team = $this->getCampaignBranchTeam();
       
        $branches = $team->map(
            function ($manager) {
                return $manager->branchesServiced;
            }
        )
        ->flatten()
        ->unique();
        return $branches;
    }

    public function getCampaignBranchTeam()
    {
      
        return Person::whereId($this->manager->id)->firstOrFail()
            ->descendantsAndSelf()
            ->whereHas(
                'userdetails.roles', function ($q) {
                        $q->whereIn('roles.id', ['9']);
                }
            )
            ->with(
                [
                    'branchesServiced' => function ($q) {
                        $q->whereHas(
                            'servicelines', function ($q1) {
                                $q1->whereIn('id', $this->servicelines->pluck('id')->toArray());
                            }
                        );
                    }
                ]
            )
            ->get();
    }
    /**
     * [getCompanyLocationsOfCampaign description]
     * 
     * @return [type] [description]
     */
    public function getCompanyLocationsOfCampaign()
    {
        
        $branches = $this->getCampaignBranches();
        $box = $this->getBoundingBox($branches);
        
        return Company::whereIn('id', $this->companies->pluck('id')->toArray())
            ->with(
                [
                'unassigned'=>function ($q) use ($box) {
                    $q->where('lat', '<', $box['maxLat'])
                        ->where('lat', '>', $box['minLat'])
                        ->where('lng', '<', $box['maxLng'])
                        ->where('lng', '>', $box['minLng']);
                    
                },
                'assigned'=>function ($q) use ($branches) {
                    $q->whereHas(
                        'assignedToBranch', function ($q1) use ($branches) {
                            $q1->whereIn('branch_id', $branches->pluck('id')->toArray());
                        }
                    )->with('assignedToBranch');
                }
                ]
            ) 
            ->get();


    }
    /**
     * [getAssignableLocationsofCampaign description]
     * 
     * @param [type]  $addresses [description]
     * @param boolean $count     [description]
     * 
     * @return [type]             [description]
     */
    public function getAssignableLocationsofCampaign($addresses, $count = false)
    {
        
        $branches = $this->getCampaignBranches()->pluck('id')->toArray();

        if ($count) {
            $query = "select 
            count(a.id) as assignable,
            b.id as branch ";
        } else {
            $query = "select 
            a.id,
            b.id as branch ";
        }
        

        $query.="from addresses a
            left join address_branch on a.id = address_branch.address_id
            inner join branches b
                on b.id = (
                    select b1.id
                    from branches b1,  branch_serviceline s
                    where st_distance_sphere(a.position, b1.position, 40233) * 0.00062137119 < b1.radius
                    and b1.id = s.branch_id
                    and s.serviceline_id in (5)
                    and b1.id in ('". implode("','", $branches). "')
                    order by st_distance_sphere(a.position, b1.position) 
                    limit 1
                )
            where a.id in ('". implode("','", $addresses) . "')
            and address_branch.address_id is null";
        if ($count) {
            $query.=" group by branch";
        }
        return \DB::select(\DB::raw($query));
       



    }
    /**
     * [getAssignedLocationsOfCampaign description]
     * 
     * @return [type] [description]
     */
    public function getAssignedLocationsOfCampaign()
    {
        $branches = $this->branches()->pluck('id')->toArray();
        $company_ids = $this->companies->pluck('id')->toArray();
        return Company::whereIn('id', $company_ids)
            ->with(
                [
                'assigned'=>function ($q) use ($branches) {
                    $q->whereHas(
                        'assignedToBranch', function ($q1) use ($branches) {
                            $q1->whereIn('branch_id', $branches);
                        }
                    )->with('assignedToBranch');
                }
                ]
            )->get();
    }
    public function addresses()
    {
        return $this->belongsToMany(Address::class);
    }
    /**
     * [getLocations description]
     * 
     * @return [type] [description]
     */
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
    /**
     * [scopeActive description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'launched')
            ->where('datefrom', '<=', now()->startOfDay())
            ->where('dateto', '>=', now()->endOfDay());
    }
    /**
     * [getSalesTeamFromManager description]
     * 
     * @param [type] $manager_id  [description]
     * @param [type] $serviceline [description]
     * 
     * @return [type]              [description]
     */
    public function getSalesTeamFromManager($manager=null)
    {
        if (! $manager) {
            $manager = auth()->user()->person->id;
     
        }
        $manager = Person::whereId($manager)->first();
        if ($manager) {
            return $manager->descendantsAndSelf()
     
                ->whereHas(
                    'userdetails.roles', function ($q) {
                            $q->whereIn('roles.id', ['3','7','6']);
                    }
                )
                ->with(
                    ['branchesServiced'=>function ($q) {
                        $q->whereHas(
                            'servicelines', function ($q1) {
                                $q1->whereIn('id', $this->servicelines->pluck('id')->toarray());
                            }
                        );
                    }
                    ]
                )
            ->orderBy('lastname')
            ->orderBY('firstname')
            ->get();
        } else {
            return false;
        }
    }
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
    /**
     * [manager description]
     * 
     * @return [type] [description]
     */
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
    /**
     * [vertical description]
     * 
     * @return [type] [description]
     */
    public function vertical()
    {
        return $this->belongsToMany(SearchFilter::class, 'campaign_searchfilter', 'campaign_id', 'searchfilter_id');
    }

    public function servicelines()
    {
        return $this->belongsToMany(Serviceline::class, 'campaign_serviceline', 'campaign_id', 'serviceline_id');
    }
    /**
     * [scopeCurrent description]
     * 
     * @param [type]     $query    [description]
     * @param Array|null $branches [description]
     * 
     * @return [type]               [description]
     */
    public function scopeCurrent($query, Array $branches =null)
    {
        
        $query->when(
                $branches, function ($q) use ($branches) {
                    return $q->wherehas(
                        'branches', function ($q) use ($branches) {
                            $q->whereIn('branches.id', $branches);
                        }
                    );
                }
            );
        
       
    }
    

    /**
     * [scopeCurrentOpen description]
     * 
     * @param [type]     $query    [description]
     * @param Array|null $branches [description]
     * 
     * @return [type]               [description]
     */
    public function scopeCurrentOpen($query, Array $branches =null)
    {
        
        $query = $query->active()->whereType('open')
            ->when(
                $branches, function ($q) use ($branches) {
                    return $q->wherehas(
                        'branches', function ($q) use ($branches) {
                            $q->whereIn('branches.id', $branches);
                        }
                    );
                }
            );
        
        return $query;
    }
    /**
     * [documents description]
     * 
     * @return [type] [description]
     */
    public function documents()
    {
        return $this->belongsToMany(Document::class);
    }

    
    
    /**
     * [getCampaignServiceLines description]
     * 
     * @return [type] [description]
     */
    public function getServicelines()
    {
        return $this->servicelines->pluck('id')->toArray();
    }
    /**
     * [scopeCampaignStats description]
     * 
     * @return [type] [description]
     */
    public function scopeCampaignStats($query)    
    {
        return $query->with(
            [
                'companies.locations'=>function ($q) { 
                    $q->with(
                        [
                            'assignedToBranch'=>function ($q1) {
                                $q1->wherePivot('created_at', '=>', $this->datefrom);
                            }
                        ]
                    );
                    $q->with(
                        [
                            'opportunities'=>function ($q1) {

                                $q1->where('opportunities.created_at', '=>', $this->datefrom)
                                    ->where('closed', 1);
                            }

                        ]
                    );
                }
            ]
        );

        
    }
    public function scopeLocations($query) {
        $query->with(
            ['companies.locations'=>function ($q) {
                $q->has('assignedToBranch');
            }
            ]
        );
    }
    public function getAddressesInCampaign()
    {
        return Address::whereIn('company_id', $this->companies->pluck('id')->toArray())
            ->has('assignedToBranch')->get();
    }
}
