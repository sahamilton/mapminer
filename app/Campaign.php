<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model implements \MaddHatter\LaravelFullcalendar\IdentifiableEvent
{
    public $fillable = ['title', 'description', 'datefrom', 'dateto', 'created_by', 'manager_id', 'status'];
    
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
                    'color'=>'#8800cc',
                ];
        } else {
            return ['url' => route('campaigns.show', $this->id),
                'color'=>'#cc0088',
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
     * 
     * 
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'launched')
            ->where('datefrom', '<=', now())
            ->where('dateto', '>=', now());
    }
    /**
     * [getSalesTeamFromManager description]
     * 
     * @param [type] $manager_id  [description]
     * @param [type] $serviceline [description]
     * 
     * @return [type]              [description]
     */
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
     * [documents description]
     * 
     * @return [type] [description]
     */
    public function documents()
    {
        return $this->hasMany(CampaignDocuments::class);
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
    
        

}
