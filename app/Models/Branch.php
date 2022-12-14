<?php
namespace App\Models;


use Illuminate\Http\Request;
use \Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use Geocode, PeriodSelector, SoftDeletes, \Awobaz\Compoships\Compoships;
    public $table ='branches';
    protected $hidden = ['created_at','updated_at','deleted_at','position'];
    protected $primaryKey = 'id'; // or null
    protected $spatialFields = [
        'position'
    ];
    public $incrementing = false;

    public $branchManagerRole = [9];
    public $branchRoles = [3,5,9,11,17];
    public $branchTeamRoles = [9,17];
    public $businessManagerRole = 11;
    public $marketManagerRole = 3;
    // Add your validation rules here
    public static $rules = [
        'branchname'=>'required',
        'id'=>'required',
        'street' => 'required',
        'city' => 'required',
        'state'=>'required',
        'zip'=>'required',

    ];
    
    // Don't forget to fill this array
    public $fillable = [
        'id',
        'branchname',
        'phone',
        'region_id',
        'street',
        'address2',
        'city',
        'state',
        'zip',
        'lat',
        'lng',
        'position'
    ];

    
    public $company_ids;

    public $activityFields = [
            '4'=>'sales_appointment',
            '5'=>'stop_by',
            '7'=>'proposal',
            '10'=>'site_visit',
            '13'=>'log_a_call',
            '14'=>'in_person'

    ];
    public $leadFields = [
            'leads',
            'newbranchleads',
            'stale_leads',
            'active_leads',
            'customer'

            
        ];
    public $campaignFields =[
            'campaign_leads',
            'touched_leads',
            'activities_count',
            "new_opportunities",
            "open_opportunities",  
            "won_opportunities",
            "won_value",

        ];
    public $opportunityFields = [
                "active_opportunities",
                "lost_opportunities",
                "new_opportunities",
                "open_opportunities",
                "top25_opportunities",
                "won_opportunities",
                "active_value",
                "lost_value",
                "new_value",
                "open_value",
                "top25_value",
                "won_value"
                ];
    public $companyFields = [
                'worked_leads',
                'touched_leads',
                'activities',
                'new_opportunities',
                'won_opportunities',
                'opportunities_open',
                'won_value',
                'open_value',
                ];

    protected $guarded = [];
    public $errors;

    public function getPhoneNumberAttribute()
    {
        $cleaned = preg_replace('/[^[:digit:]]/', '', $this->phone);
        if (preg_match('/(\d{3})(\d{3})(\d{4})/', $cleaned, $matches)) {
            return "({$matches[1]}) {$matches[2]}-{$matches[3]}";
        }
        return $this->phone;
        
    }


    /**
     * [_setActivityFields Description D]
     *
     * @return [array] [activityFields]
     */
    private function _setActivityFields()
    {
         return Activity::get()->pluck('activity', 'id')->toArray();
    }
    /**
     * [locations description]
     * 
     * @return [type] [description]
     */
    public function locations()
    {
        return $this->belongsToMany(Address::class);
    }

    public function managers()
    {
        return $this->belongsToMany(Person::class)
            ->wherePivot('role_id', '=', 9);
    }
    /**
     * [region description]
     * 
     * @return [type] [description]
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    /**
     * [relatedPeople description]
     * 
     * @param [type] $role [description]
     * 
     * @return [type]       [description]
     */
    public function relatedPeople($role = null)
    {
        if ($role) {
            return $this->belongsToMany(Person::class)
                ->wherePivot('role_id', '=', $role)
                ->withPivot('role_id', 'created_at', 'updated_at')
                ->withDefault(['firstname'=>'No Person in this role at this branch']);
        } else {
            return $this->belongsToMany(Person::class)
                ->withTimestamps()
                ->withPivot('role_id', 'created_at', 'updated_at');
        }
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
     * [activityTypeCount description]
     * 
     * @return [type] [description]
     */
    public function activityTypeCount()
    {
        return $this->activities()
            ->selectRaw(
                'branch_id,activitytype_id as activity,DATE_ADD(activity_date, INTERVAL(-WEEKDAY(activity_date)) DAY) as week,count(*) as count'
            )
            ->groupBy(['branch_id','week','activitytype_id']);
    }
    /**
     * [openActivities description]
     * 
     * @return [type] [description]
     */
    public function openActivities()
    {
        
        return $this->hasMany(Activity::class)
            ->whereCompleted(0)
            ->orWhereNull('completed');
    }
    
    /**
     * [activitiesbytype description]
     * 
     * @param [type] $type [description]
     * 
     * @return [type]       [description]
     */
    public function activitiesbytype($type = null)
    {
    
        $activities = $this->hasMany(Activity::class);
        if ($type) {
             $activities->where('activitytype_id', '=', $type);
        }
        return $activities;
    }


    /**
     * [opportunities description]
     * 
     * @return [type] [description]
     */
    public function opportunities()
    {
 
        return $this->hasManyThrough(Opportunity::class, AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id');
    }
    /**
     * [oraclelocation description]
     * 
     * @return [type] [description]
     */
    public function oraclelocation()
    {
        return $this->hasMany(Oracle::class, 'location_name', 'oracle_location');
    }

    
    /**
     * [opportunities description]
     * 
     * @return [type] [description]
     */
    public function branchActivities()
    {
 
        return $this->hasManyThrough(Activity::class, AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id');
    }
    /**
     * [openOpportunities description]
     * 
     * @return [type] [description]
     */
    public function openOpportunities()
    {
 
        return $this->hasManyThrough(Opportunity::class, AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id')->where('closed', '=', 0);
    }

    public function managerEmailAddress()
    {
        return $this->manager->map(
            function ($manager) {
                return $manager->fullEmail();
            }
        )
        ->toArray();
    }

    /**
     * [opportunitiesClosingThisWeek description]
     * 
     * @return [type] [description]
     */
    public function opportunitiesClosingThisWeek()
    {
        return $this->hasManyThrough(Opportunity::class, AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id')
            ->where('closed', '=', 0)
            ->whereBetween('expected_close', [now()->subDay()->startOfDay(), now()->addWeek()->endOfDay()])
            ->with('address.activities.type');
    }
    /**
     * [pastDueOpportunities description]
     * 
     * @return [type] [description]
     */
    public function pastDueOpportunities()
    {
        return $this->hasManyThrough(Opportunity::class, AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id')->where('closed', '=', 0)
            ->where('expected_close', '<', now());
    }
    /**
     * [closedOpportunities description]
     * 
     * @return [type] [description]
     */
    public function closedOpportunities()
    {
 
        return $this->hasManyThrough(Opportunity::class, AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id')
            ->where('closed', '=', 1);
    }

    public function staleOpportunities()
    {
        return $this->hasManyThrough(Opportunity::class, AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id')
            ->where('closed', '=', 0)
            ->where('opportunities.created_at', '>', now()->subMonth(3))
            ->whereDoesntHave('currentlyActive');
              
            
    }


    /**
     * [instate description]
     * 
     * @return [type] [description]
     */
    public function instate()
    {
        return $this->belongsTo(State::class, 'state', 'statecode');
    }
    /**
     * [manager description]
     * 
     * @return [type] [description]
     */
    public function manager()
    {
        return $this->belongsToMany(Person::class)
            ->wherePivotIn('role_id', $this->branchManagerRole);
    }

    public function branchteam()
    {
        return $this->belongsToMany(Person::class)
            ->wherePivotIn('role_id', $this->branchTeamRoles)
            ->withPivot('role_id', 'created_at', 'updated_at');
    }
    /**
     * [businessmanager description]
     * 
     * @return [type] [description]
     */
    public function businessmanager()
    {
        return $this->belongsToMany(Person::class)->wherePivot('role_id', $this->businessManagerRole);
    }
    /**
     * [marketmanager description]
     * 
     * @return [type] [description]
     */
    public function marketmanager()
    {
       
        return $this->belongsToMany(Person::class)->wherePivot('role_id', $this->marketManagerRole);
    }
    /**
     * [servicelines description]
     * 
     * @return [type] [description]
     */
    public function servicelines()
    {
            return $this->belongsToMany(Serviceline::class);
    }

    public function scopeActive($query, $months = 3)
    {
        return $query->whereHas(
            'manager', function ($q) use ($months) {
                $q->whereHas(
                    'userdetails', function ($q) use ($months) {
                        $q->where('lastlogin', '>', now()->subMonths($months));
                    }
                );
            }
        );
    } 
    public function scopeInactive($query)
    {
        return $query->doesntHave('manager');
      
    } 
    public function scopeInServiceLines($query, $servicelines=null)
    {
        if (! $servicelines) {
            $servicelines = auth()->user()->serviceline;
        }
        //$servicelines = $servicelines->pluck('id')->toArray();
        return $query->whereHas(
            'servicelines', function ($q) use ($servicelines) {
                $q->whereIn('servicelines.id', $servicelines);
            
            }
        );
    }
    /**
     * [servicedBy description]
     * 
     * @return [type] [description]
     */
    public function servicedBy()
    {

        return $this->belongsToMany(Person::class)->withTimestamps()->withPivot('role_id');
    }
    /**
     * [salesTeam description]
     * 
     * @return [type] [description]
     */
    public function salesTeam()
    {


        return $this->belongsToMany(Person::class)->withTimestamps()->withPivot('role_id')->wherePivot('role_id', '=', 5);
    }
    
    /**
     * [addresses description]
     * 
     * @return [type] [description]
     */
    public function addresses()
    {
         return  $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id');
    }
    /**
     * [leads description]
     * 
     * @return [type] [description]
     */
    public function leads()
    {
        return $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id');  

    }
    /**
     * [leads description]
     * 
     * @return [type] [description]
     */
    public function offeredLeads()
    {
        return  $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id')
            ->whereDoesntHave('opportunities')
            ->whereIn('address_branch.status_id', [1]); 

    }
    /**
     * [leads description]
     * 
     * @return [type] [description]
     */
    public function workedleads()
    {
        return  $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id')
            ->whereIn('address_branch.status_id', [2]); 

    }

    /**
     * [neglectedleads description]
     * 
     * @return [type] [description]
     */
    public function neglectedleads()
    {
        return  $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id')
            ->whereDoesntHave('opportunities')
            ->where('address_branch.created_at', '<', now()->subWeek()->startOfDay())
            ->whereIn('status_id', [1]); 

    }
    /**
     * [untouchedleads description]
     * 
     * @return [type] [description]
     */
    public function untouchedleads()
    {
        return  $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id')
            ->whereDoesntHave('opportunities')
            ->whereDoesntHave('activities')
            ->whereIn('status_id', [2]); 

    }
    /**
     * [rejectedleads description]
     * 
     * @return [type] [description]
     */
    public function rejectedleads()
    {
        return  $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id')
            ->whereDoesntHave('opportunities')
            ->whereDoesntHave('activities')
            ->whereIn('status_id', [4]); 
    }

    /**
     * [scopeCampaignUntouchedLeads description]
     * 
     * @return [type] [description]
     */
    public function scopeCampaignUntouchedLeads($query)
    {   
        return $query->with(
            ['untouchedleads'=>function ($q) {
                    $q->whereIn('company_id', ['388']);
            }
            ]
        );
            
    }
    /**
     * [leads description]
     * 
     * @return [type] [description]
     */
    public function staleLeads()
    {
        return  $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id')
            ->where(
                function ($q) {
                    $q->whereDoesntHave('currentlyActive');
                }
            )
            
            ->whereIn('status_id', [2]); 

    }
    public function associatedLocations()
    {
        return $this->hasMany(AddressBranch::class);
    }
    


    /**
     * [branchLeads description]
     * 
     * @return [type] [description]
     */
    public function branchLeads()
    {
        return  $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id')
            ->whereDoesntHave('opportunities')->where('lead_source_id', 4); 
    }
    /**
     * [scopeBranchLeadsPeriod description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeBranchLeadsPeriod($query, $period)
    {
        return $query->whereHas(
            'branchLeads', function ($q) {
                $q->whereBetween('address_branch.created_at', [$period['from'], $period['to']]);
            }
        );
    }
    /**
     * [allLeads description]
     * 
     * @return [type] [description]
     */
    public function allLeads()
    {
        return  $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id');
    }
    /**
     * [leadsBySourceCount description]
     * 
     * @param [type] $leadsource [description]
     * 
     * @return [type]             [description]
     */
    public function leadsBySourceCount($leadsource)
    {

        return $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id')->where(['lead_source_id' => $leadsource->id])->count();
    }
    
    /**
     * [getManagementTeam description]
     * 
     * @return [type] [description]
     */
    public function getManagementTeam()
    {
        $team = $this->salesTeam()->get();
        $mgrs = [];
        foreach ($team as $rep) {
            $mgrs = array_unique(array_merge($mgrs, $rep->ancestorsAndSelf()->pluck('id')->toArray()));
        }
        return $mgrs;
    }
    /**
     * [getPresenterClass description]
     * 
     * @return [type] [description]
     */
    public function getPresenterClass()
    {
        return LocationPresenter::class;
    }
    /**
     * [getBranchemailAttribute description]
     * 
     * @return [type] [description]
     */
    public function getBranchemailAttribute()
    {
        return "branch-".$this->id ."@example.com";
    }

    
   
    /**
     * [getBranchIdFromid description]
     * 
     * @param [type] $branchstring [description]
     * 
     * @return [type]               [description]
     */
    public function getBranchIdFromid($branchstring)
    {
        $branchstring = str_replace(" ", "", $branchstring);
        return $this->whereIn('id', explode(',', $branchstring))
            ->pluck('id')->toArray();
    }
    /**
     * [campaign description]
     * 
     * @return [type] [description]
     */
    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }

    /**
     * [campaign description]
     * 
     * @return [type] [description]
     */
    public function currentcampaigns()
    {
        return $this->belongsToMany(Campaign::class)

            ->where('datefrom', '<=', now()->startOfDay())
            ->where('dateto', '>=', now()->endOfDay());
    }

    /**
     * [campaign description]
     * 
     * @return [type] [description]
     */
    public function currentopencampaigns()
    {
        return $this->belongsToMany(Campaign::class)
            ->where('type', 'open')
            ->where('datefrom', '<=', now()->startOfDay())
            ->where('dateto', '>=', now()->endOfDay());
    }

    /**
     * [campaign description]
     * 
     * @return [type] [description]
     */
    public function campaignLeads()
    {
        return $this->hasManyThrough(Campaign::class, AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id');
    }
    /**
     * [campaign description]
     * 
     * @return [type] [description]
     */
    public function scopeWithCampaignLeads($query, Campaign $campaign)
    {
        return  $query->with(
            [
                'locations'=>function ($q) use ($campaign) {
                    $q->whereHas(
                        'campaignLeads', function ($q1) use ($campaign) {
                            $q1->where('campaign_id', $campaign->id);
                        }
                    );
                }
            ]
        );
           
    }

    /**
     * [campaign description]
     * 
     * @return [type] [description]
     */
    public function scopeWithCountCampaignLeads($query, Campaign $campaign)
    {
        return  $query->withCount(
            'campaignLeads', function ($q1) use ($campaign) {
                $q1->where('campaign_id', $campaign->id);
            }
        );
                
           
    }

    /**
     * [campaign description]
     * 
     * @return [type] [description]
     */
    public function activeCampaigns()
    {
        return $this->belongsToMany(Campaign::class)
            ->where('status', 'launched')
            ->where('datefrom', '<=', now()->startOfDay())
            ->where('dateto', '>=', now()->endOfDay());
    }

    public function scopeCampaignLocations($query, array $campaigns)
    {
        
        return $query->with(
            ['locations'=>function ($q) use ($campaigns) { 
                $q->whereHas(
                    'campaigns', function ($q1) use ($campaigns) { 
                        $q1->whereIn('campaigns.id', $campaigns); 
                    }
                );
            }
            ]
        );

    }

    public function scopeCampaignActivities($query, array $campaigns)
    {
        
        return $query->with(
            [
                'activities'=>function ($q) use ($campaigns) { 
                    $q->whereHas(
                        'campaigns', function ($q1) use ($campaigns) { 
                            $q1->whereIn('campaigns.id', $campaigns); 
                        }
                    );
                }
            ]
        );

    }
    /**
     * [makeNearbyBranchXML   Generate Mapping xml file from branches results]
     * 
     * @param [type] $result [description]
     * 
     * @return [xml]         [description]
     */
    public function makeNearbyBranchXML($result)
    {
        /// move this to  transformer
        $dom = new \DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);
        foreach ($result as $row) {

            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("name", trim($row->branchname));
            $newnode->setAttribute(
                "address",
                trim($row->street)." ".
                trim($row->city)." ".
                trim($row->state)
            );
            $newnode->setAttribute("lat", $row->lat);
            $newnode->setAttribute("lng", $row->lng);
            if (isset($row->id)) {
                $newnode->setAttribute("locationweb", route('branches.show', $row->id));
                $newnode->setAttribute("id", $row->id);
            } else {
                $newnode->setAttribute("locationweb", route('branches.show', $row->branchid));
                $newnode->setAttribute("id", $row->branchid);
            }
            $newnode->setAttribute("type", 'branch');
            if (isset($row->serviced_by)) {
                $newnode->setAttribute("salesreps", count($row->serviced_by));
            }
            
            if (is_object($row->servicelines) && count($row->servicelines) > 0) {
                $newnode->setAttribute("brand", $row->servicelines[0]->ServiceLine);
                $newnode->setAttribute("color", $row->servicelines[0]->color);
            }
            if (is_string($row->servicelines)) {
                $newnode->setAttribute("brand", $row->servicelines);
                //$newnode->setAttribute("color", $row->color);
            }
        }

        return $dom->saveXML();
    }

    /**
     * [getBranches description]
     * 
     * @return [type] [description]
     */
    public function getBranches()
    {
        
        if (auth()->user()->hasRole('admin')) {
       
            return $this->all()->pluck('branchname', 'id')->toArray();
        } elseif (auth()->user()->hasRole('sales_operations')) {
            $manager = Person::findOrFail(auth()->user()->person->reports_to);
            
            return Person::myBranches($manager);
        } else {
      
             return  Person::myBranches();
        }
    }
    
    /**
     * [getBranchManagers description]
     * 
     * @return [type] [description]
     */
    public function getBranchManagers()
    {
        // this shouldnt be hardcoded!
        $roles=['3'];
        $accountmanagers = User::whereHas(
            'roles',
            function ($q) use ($roles) {
                $q->whereIn('role_id', $roles);
            }
        )->with('Person')->get();
        foreach ($accountmanagers as $manager) {
            $managers[$manager->person->id] = $manager->person->firstname . " ". $manager->person->lastname;
        }
        return $managers;
    }
    /**
     * [rebuildBranchXMLfile description]
     * 
     * @return [type] [description]
     */
    public function rebuildBranchXMLfile()
    {
        
        $branches = $this->with('servicelines')->get();
        $xml = response()->view('branches.xml', compact('branches'))
            ->header('Content-Type', 'text/xml');
        $file = file_put_contents(
            storage_path() . '/app/public/uploads/branches.xml', $xml
        );
        return true;
    }

    /**
     * [orders description]
     * 
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function orders($period = null)
    {
        
        return $this->hasManyThrough(
            Orders::class, 
            AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id'
        );
    }

    /**
     * [associatePeople description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function associatePeople(Request $request)
    {
        $data['roles'] = $this->removeNullsFromSelect(request('roles'));
        $associatedPeople = [];
        foreach ($data['roles'] as $key => $role) {
            foreach ($role as $person) {
                $associatedPeople[$person] = ['role_id'=>$key];
            }
        }
        $this->relatedPeople()->sync($associatedPeople);
    }
    /**
     * [allStates description]
     * 
     * @return [type] [description]
     */
    public function allStates()
    {
        $states = $this->distinct('state')->pluck('state')->toArray();
        return State::whereIn('statecode', $states)->orderBy('statecode')->get();
    }
    /**
     * [getbranchGeoCode description]
     * 
     * @param [type] $request [description]
     * 
     * @return [type]          [description]
     */
    public function getbranchGeoCode($request)
    {
            $address = request('street') . ",". request('city') . ",". request('state') . ",". request('zip');

            $geoCode = app('geocoder')->geocode($address)->get();

            $latlng = ($this->getGeoCode($geoCode));
            $request['lat']= $latlng['lat'];
            $request['lng']= $latlng['lng'];
            return $request;

    }
    /**
     * [scopeDeadLeads description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeDeadLeads($query, $period)
    {

        return $query->withCount(
            ['leads as deadleads'=>function ($query) use ($period) {
                $query
                    ->where('addresses.lead_source_id', "!=", 4)
                    ->where('address_branch.created_at', '<', $period['from'])
                    ->where(
                        function ($q) {
                            $q->whereDoesntHave('opportunities')
                                ->whereDoesntHave('activities');
                        }
                    );
            }]
        );

    }

    /**
     * [scopeDeadLeadsBySource description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeDeadLeadsBySource($query, $branches, $period)
    {
        $branch = implode("','", $branches);
 
        $query = "select branches.branchname, leadsources.source, count(address_branch.address_id) as deadleads
                from leadsources, branches, addresses, address_branch
                left join opportunities on address_branch.id = opportunities.address_branch_id
                left join activities on address_branch.address_id = activities.address_id
                where address_branch.address_id = addresses.id
                and address_branch.branch_id = branches.id
                and addresses.lead_source_id = leadsources.id
                and address_branch.created_at < '". $period['from']->format('Y-m-d') . "'
                and opportunities.id is null
                and activities.id is null
                and branches.id in ('".$branch."')
                group by branches.branchname, leadsources.source";
                return \DB::select(\DB::raw($query));
    }

    public function managementTeam()
    {
        return $this->manager->first()->getAncestorsAndSelf()->sortByDesc('depth');
     
        //return null;
        
    }
    /**
     * [scopeGetActivitiesByType description]
     * 
     * @param [type] $query        [description]
     * @param [type] $period       [description]
     * @param [type] $activitytype [description]
     * 
     * @return [type]               [description]
     */
    public function scopeGetActivitiesByType($query, array $period,$activitytype=null)
    {

        if ($activitytype) {
           
            return $query->with(
                ['activities' => function ($query) use ($activitytype,$period) {
                    $query->where('activitytype_id', '=', $activitytype)
                        ->whereBetween('activity_date', [$period['from'],$period['to']])
                        ->where('completed', '=', 1);
                }
                ], 'activities.type', 'activities.relatedAddress'
            );
        } else {
        
            return $query->with(
                ['activities'=>function ($query) use ($period) {
                    $query->whereBetween(
                        'activity_date', [$period['from'],$period['to']]
                    )
                        ->where('completed', '=', 1);
                }
                ], 'activities.type', 'activities.relatedAddress'
            );
            
        }

    }

    /**
     * [checkIfMyBranch description]
     * 
     * @param [type] $request    [description]
     * @param [type] $myBranches [description]
     * @param [type] $branch     [description]
     *  
     * @return [type]             [description]
     */
    public function checkIfMyBranch($request, $myBranches, $branch = null )
    {
        if (request()->has('branch')) {
            return  $this->findOrFail(request('branch'));
        }
        
        if (count($myBranches)==0) {
            return false;
        }

        if (isset($branch) && ! in_array($branch->id, array_keys($myBranches))) {
            return false;
        }
        $branch = array_keys($myBranches);

        return  $this->findOrFail(reset($branch));

    }
    /**
     * [branchData description]
     * 
     * @param [type] $branches [description]
     * 
     * @return [type]           [description]
     */
    public function branchData($branches)
    {
        $data = [];
        foreach ($branches as $branch) {

            $data[$branch->id]['branch'] = $branch->branchname;
            $data[$branch->id]['leads'] = $branch->leads->count();
            $data[$branch->id]['activities'] = 0;
            $data[$branch->id]['opportunities'] = 0;

            foreach ($branch->addresses as $lead) {

                $data[$branch->id]['opportunities'] += $lead->opportunities->count();
                $data[$branch->id]['activities'] += $lead->activities->count();

            }
    
        }
        return $data;
    }
    public function scopeSearch($query, $search)
    {
        return $query->where('branchname', 'like', "%{$search}%");
    }
    public function scopeSummaryOpportunities($query, array $period, array $fields=null)
    {
        $this->period = $period;
        if (! $fields) {
            $fields = $this->opportunityFields;
        }
        $this->fields = $fields;

        return $query
            ->when(
                in_array('active_opportunities', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                            'opportunities as active_opportunities'=>function ($query) {
                                $query->currentlyActive($this->period);
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('lost_opportunities', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                            'opportunities as lost_opportunities'=>function ($query) {
                                $query->lost($this->period);
                            }
                        ]
                    );
                }
            )
           
            ->when(
                in_array('new_opportunities', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                            'opportunities as new_opportunities'=>function ($query) {
                                $query->newOpportunities($this->period);
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('open_opportunities', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                            'opportunities as open_opportunities'=>function ($query) {
                                $query->open($this->period);
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('top25_opportunities', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                            'opportunities as top25_opportunities'=>function ($query) {
                                $query->top25($this->period);
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('won_opportunities', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                            'opportunities as won_opportunities'=>function ($query) {
                                $query->won($this->period);

                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('active_value', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                            'opportunities as active_value' => function ($query) {
                                $query->activeValue($this->period);
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('lost_value', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                            'opportunities as lost_value' => function ($query) {
                                $query->lostValue($this->period);
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('new_value', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                            'opportunities as new_value'=>function ($query) {
                                $query->newValue($this->period);
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('open_value', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                            'opportunities as open_value' => function ($query) {
                                $query->openValue($this->period);
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('won_value', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                            'opportunities as won_value' => function ($query) {
                                $query->wonValue($this->period);
                            }
                        ]
                    );
                }
            );

    }
    /**
     * 
     */
    public function scopePeriodOpportunities($query, array $period, array $status=[1,2]) 
    {

        return $query->with(
            [
                'opportunities'=>function ($q) use ($period, $status) {
                    $q->where('est_start', '<=', $period['to'])
                        ->where('est_end', '>=', $period['from'])
                        ->whereIn('closed', $status);
                }
            ]
        );
              
      
    }
    /**
     * [scopeBranchOpportunities description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeBranchOpenOpportunities($query, $period)
    {
        $this->period = $period;
        return $query->withCount( 
            ['opportunities as open'=>function ($query) {
                $query->whereClosed(0)        
                    ->where(
                        function ($q) {
                            $q->where('actual_close', '>', $this->period['to'])
                                ->orwhereNull('actual_close');
                        }
                    )
                ->where('opportunities.created_at', '<', $this->period['to']);
            },
            'opportunities as openvalue' => function ($query) {
                $query->select(\DB::raw("SUM(value) as openvalue"))
                    ->where(
                        function ($q) {
                            $q->where('actual_close', '>', $this->period['to'])
                                ->orwhereNull('actual_close');
                        }
                    )
                ->where('opportunities.created_at', '<', $this->period['to']);
            }
            ]
        );

    }
    /**
     * [scopeBranchOpportunitiesDetail description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeBranchOpenOpportunitiesDetail($query, $period)
    {
        $this->period = $period;

        return $query
            ->has('opportunities')
            ->with( 
                ['opportunities'=>function ($query) {
                    $query->whereClosed(0)        
                        ->where(
                            function ($q) {
                                $q->where('actual_close', '>', $this->period['to'])
                                    ->orwhereNull('actual_close');
                            }
                        )

                    ->where('opportunities.created_at', '<', $this->period['to'])
                    ->with('address.address');
                }
                ]
            );
    }


    /**
     * [scopeAgingOpportunities description]
     * 
     * @param [type] $query [description]
     * @param [type] $age   [description]
     * 
     * @return [type]        [description]
     */
    public function scopeAgingOpportunities($query, $age)
    {
        $period = now()->subDays($age);
        return $query->with(
            ['opportunities', function ($query) use ($period) {
                            $query->where('closed', 0)
                                ->where('opportunities.created_at', '<', $period);
            }
            ]
        );
        
        


    }

    /**
     * [scopeMobileStats description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeMobileStats($query)
    {
        return $query->withCount(       
            ['leads'=>function ($query) {
                $query->where(
                    function ($q) {
                        $q->whereDoesntHave('opportunities');
                    }
                );
            },
            
            'activities'=>function ($query) {
                $query->where('completed', 0);
            },

            'opportunities'=>function ($query) {
                $query->whereClosed(0)        
                    ->where(
                        function ($q) {
                            $q->where('actual_close', '>', now())
                                ->orwhereNull('actual_close');
                        }
                    )
                ->where('opportunities.created_at', '<', now());
            }
            ]
        );

    }

    /**
     * [scopeSummaryActivitiesByBranch description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeSummaryActivitiesByBranch($query, $period)
    {
        $this->period = $period;
       
        return $query->join(
            'activities', function ($join) {
                $join->on('branches.id', '=', 'activities.branch_id');
            }
        )
        ->select('branches.id')
        ->where('completed', 1)
        ->whereBetween('activity_date', [$period['from'], $period['to']])
        ->selectRaw("branchname")
        ->selectRaw('COUNT(CASE when activitytype_id = 4  then 1 else 0 end) as sales_appointment')
        ->selectRaw('COUNT(CASE when activitytype_id = 5  then 1 else 0 end) as stop_by')
        ->selectRaw('COUNT(CASE when activitytype_id = 7  then 1 else 0 end) as proposal')
        ->selectRaw('COUNT(CASE when activitytype_id = 10  then 1 else 0 end) as site_visit')
        ->selectRaw('COUNT(CASE when activitytype_id = 13  then 1 else 0 end) as log_a_call')
        ->selectRaw('COUNT(CASE when activitytype_id = 14  then 1 else 0 end) as in_person')
        ->groupBy('branches.id');
    }
    /**
     * [scopeSummaryActivities description]
     * 
     * @param [type] $query  [description]
     * @param array  $period [description]
     * @param array  $fields 
     *                       key is activity type id
     *                       value is label for activi
     *                        
     * @return [type]         [description]
     */
    public function scopeSummaryActivities($query, Array $period, Array $fields = null)
    {
       
        $this->period = $period;
        if (isset($fields)) {
            $this->activityFields = $fields;
           
            foreach ($this->activityFields as $key=>$field) {
                $label = str_replace(" ", "_", strtolower($field));
                $query->withCount(
                    [
                        'activities as '.$label => function ($query) use ($key) {
                            $query->whereBetween(
                                'activity_date', [$this->period['from'], $this->period['to']]
                            )->where('completed', 1)
                                ->where('activitytype_id', $key);
                        }
                    ]
                ); 
            }
        
        }
        $query->withCount(
            [
                'activities'=>function ($query) {
                    $query->whereBetween(
                        'activity_date', [$this->period['from'],$this->period['to']]
                    )->where('completed', 1);
                }
            ]
        );

    }
    /**
     * [scopeSummaryLeadStats description]
     * @param  [type] $query  [description]
     * @param  [type] $period [description]
     * @param  [type] $fields [description]
     * @return [type]         [description]
     */
    public function scopeSummaryLeadStats($query, Array $period, Array $fields = null)
    {
        
        $this->period = $period;
        if (! $fields) {
            $fields = $this->leadFields;
        }
        $this->fields = $fields;

        
        return $query
            ->when(
                in_array('leads', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                            'leads as leads'=>function ($query) {
                                $query->where('address_branch.created_at', '<=', $this->period['to'])
                                    ->where(
                                        function ($q) {
                                            $q->whereDoesntHave('opportunities')
                                                ->orWhereHas(
                                                    'opportunities', function ($q1) {
                                                        $q1->where(
                                                            'opportunities.created_at', '>', $this->period['to']
                                                        );
                                                    }
                                                );
                                        }
                                    )->where('address_branch.status_id', 2);
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('newbranchleads', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                            'leads as newbranchleads'=>function ($query) {
                                $query->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']])
                                    ->where('addresses.lead_source_id', 4);
                                        
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('customer', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                           'addresses as customer'=>function ($q) {
                                $q->whereNotNull('isCustomer')
                                    ->where('address_branch.created_at', '<=', $this->period['to']);
                                
                           }
                        ]
                    );
                }
            )
            ->when(
                in_array('active_customer', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                           'addresses as active_customer'=>function ($q) {
                                $q->whereNotNull('isCustomer')
                                    ->where('address_branch.created_at', '<=', $this->period['to'])
                                    ->wherehas(
                                        'activities', function ($q) {
                                            $q->where('completed', 1)
                                                ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                                        }
                                    )
                                ->where('address_branch.created_at', '<=', $this->period['to']);
                                
                           }
                        ]
                    );
                }
            )
            ->when(
                in_array('stale_leads', $this->fields), function ($q) {
                    $q->withCount( 
                        [
                            'addresses as stale_leads'=>function ($q) {
                                $q->whereDoesntHave(
                                    'activities', function ($q) {
                                        $q->where('completed', 1)
                                            ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                                    }
                                )
                                ->where('address_branch.created_at', '<=', $this->period['to']);
                            }

                        ]
                    );
                }
            )
            ->when(
                in_array('active_leads', $this->fields), function ($q) {
                    $q->withCount(
                        [
                            'addresses as active_leads'=>function ($q) {
                                $q->wherehas(
                                    'activities', function ($q) {
                                        $q->where('completed', 1)
                                            ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                                    }
                                )
                                ->where('address_branch.created_at', '<=', $this->period['to']);
                            }


                        ]
                    );

                }
            )->when(
                in_array('nearby', $this->fields), function ($q) {
                    $q->withCount(
                        [
                            'addresses as nearby'=>function ($q) {
                                $q->nearby($this->radius)
                                    ->whereDoesntHave('assignedToBranch')
                                    ->where('address.created_at', '<=', $this->period['to']);
                            }


                        ]
                    );

                }
            );

    }
    /**
     * [scopeSummaryStats description]
     * 
     * @param Eloquent $query  [description]
     * @param Array    $period Period from /to
     * @param Array    $fields fields to include in query
     * 
     * @return [type]         [description]
     */
    public function scopeSummaryStats($query,$period, $fields = null)
    {
        $this->period = $period;
        if (! $fields) {
            $fields = array_merge($this->leadFields, $this->activityFields, $this->opportunityFields);
        }
        
        $query->summaryActivities($period, $fields)
            ->summaryLeadStats($period, $fields)
            ->summaryOpportunities($period, $fields);
  
    }
    /**
     * [upcomingActivities description]
     * 
     * @return [type] [description]
     */
    public function upcomingActivities()
    {
        return $this->hasMany(Activity::class)
            ->whereBetween('activity_date', [Carbon::now()->startOfDay(),Carbon::now()->addWeek()->endOfDay()])
            ->where(
                function ($q) {
                    $q->whereNull('completed')
                        ->orWhere('completed', 0);
                }
            )->with('relatesToAddress')
            ->orderBy('activity_date', 'asc');

    }
    /**
     * [scopeUpcomingActivities description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeUpcomingActivities($query)
    {
        return $query->with(
            ['activities'=>function ($q) {
                $q->whereBetween('activity_date', [Carbon::now(),Carbon::now()->addWeek()])
                    ->where(
                        function ($q) {
                            $q->whereNull('completed')
                                ->orWhere('completed', 0);
                        }
                    )
                    ->with('relatesToAddress')
                    ->orderBy('activity_date');
            }]
        );
    }
    /**
     * [scopeSummaryCompanyStats description]
     * 
     * @param [type] $query     [description]
     * @param Array  $period    [description]
     * @param array  $companies [description]
     * @param [type] $fields    [description]
     * 
     * @return [type]            [description]
     */
    public function scopeSummaryCompanyStats($query, Array $period, array $companies, $fields = null)
    {
        $this->period = $period;
        $this->company_ids = $companies;
        if (! $fields) {
            $this->fields = $this->companyFields;
            //'worked_leads,
            //'touched_leads'
            //'activities'
            //'new_opportunities'
            //'won_opportunities'
            //'opportunities_open'
            //'won_value'
            //'open_value'
        } else {
            $this->fields = $fields;
        }
        return $query
            ->when(
                in_array('supplied_leads', $this->fields), function ($q) {
                    $q->withCount(       
                        [ 
                        'addresses as supplied_leads'=>function ($q) {
                            $q->whereIn('company_id', $this->company_ids)
                                ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                                
                        }
                        ]
                    );
                }
            )
            ->when(
                in_array('offered_leads', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'offeredLeads as offered_leads'=>function ($q) {
                                $q->whereIn('company_id', $this->company_ids)
                                    ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                                    
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('worked_leads', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'workedleads as worked_leads'=>function ($q) {
                                $q->whereIn('company_id', $this->company_ids)
                                    ->where('address_branch.created_at', '<=', $this->period['to']);
                                    
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('rejected_leads', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'rejectedleads as rejected_leads'=>function ($q) {
                                $q->whereIn('company_id', $this->company_ids)
                                    ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                                    
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('touched_leads', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'addresses as touched_leads'=>function ($q) {
                                $q->whereIn('company_id', $this->company_ids)
                                    ->where('status_id', '>', '1')
                                    ->whereHas(
                                        'activities', function ($q1) {
                                            $q1->where('completed', 1)
                                                ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                                        }
                                    )
                                    ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                                    
                            }
                        ]
                    );
                }
            )
            
            ->when(
                in_array('activityCount', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'activities as activityCount'=>function ($q) {
                
                                $q->whereBetween(
                                    'activity_date', [$this->period['from'],$this->period['to']]
                                )
                                    ->where('completed', 1)
                                    ->whereHas(
                                        'relatesToAddress', function ($q1) {
                                                $q1->whereIn('company_id', $this->company_ids);
                                        }  
                                    );
                            }
                        ]
                    );
                }
            )->when(
                in_array('new_opportunities', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'opportunities as new_opportunities'=>function ($q) {
                                $q->whereBetween(
                                    'opportunities.created_at', [$this->period['from'],$this->period['to']]
                                )->whereHas(
                                    'location', function ($q1) {
                                        $q1->whereIn('company_id', $this->company_ids);
                                    }
                                );
                            }
                        ]
                    );
                }
            )->when(
                in_array('won_opportunities', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'opportunities as won_opportunities'=>function ($q) {
                
                                $q->whereClosed(1)
                                    
                                    ->whereBetween(
                                        'actual_close', [$this->period['from'],$this->period['to']]
                                    )->whereHas(
                                        'location', function ($q1) {
                                            $q1->whereIn('company_id', $this->company_ids);
                                        }
                                    );
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('lost_opportunities', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'opportunities as lost_opportunities'=>function ($q) {
                
                                $q->whereClosed(2)
                                    ->whereBetween(
                                        'opportunities.created_at', [$this->period['from'],$this->period['to']]
                                    )
                                    ->whereBetween(
                                        'actual_close', [$this->period['from'],$this->period['to']]
                                    )->whereHas(
                                        'location', function ($q1) {
                                            $q1->whereIn('company_id', $this->company_ids);
                                        }
                                    );
                            }
                        ]
                    );
                }
            )->when(
                in_array('opportunities_open', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'opportunities as opportunities_open'=>function ($q) {
                
                                $q->where('opportunities.created_at', '<=',  $this->period['to'])
                                    ->where(
                                        function ($q) {
                                            $q->whereClosed(0)
                                                ->orWhere(
                                                    function ($q) {
                                                        $q->where('actual_close', '>', $this->period['to'])
                                                            ->orwhereNull('actual_close');
                                                    }
                                                );
                                        }
                                    )
                                    ->where('opportunities.created_at', '<=', $this->period['to'])
                                    ->whereHas(
                                        'location', function ($q1) {
                                            $q1->whereIn('company_id', $this->company_ids);
                                        }
                                    );
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('won_value', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'opportunities as won_value' => function ($q) {
                
                                $q->select(\DB::raw("SUM(value) as wonvalue"))
                                    ->where('closed', 1)
                                    ->whereBetween(
                                        'actual_close', [$this->period['from'],$this->period['to']]
                                    )
                                    ->whereHas(
                                        'location', function ($q1) {
                                            $q1->whereIn('company_id', $this->company_ids);
                                        }
                                    );
                            }
                        ]
                    );
                }
            )->when(
                in_array('open_value', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'opportunities as open_value' => function ($q) {
                                $q->where('opportunities.created_at', '<=', $this->period['to'])
                                    ->select(\DB::raw("SUM(value) as openvalue"))
                                    ->where(
                                        function ($q) {
                                            $q->whereClosed(0)
                                                ->orWhere(
                                                    function ($q) {
                                                        $q->where('actual_close', '>', $this->period['to'])
                                                            ->orwhereNull('actual_close');
                                                    }
                                                );
                                        }
                                    )
                                    ->whereHas(
                                        'location', function ($q1) {
                                            $q1->whereIn('company_id', $this->company_ids);
                                        }
                                    );
                
                            }
                        ]
                    );
                }
            );
    }
    /**
     * [scopeSummaryCampaignStats description]
     * 
     * @param [type]   $query    [description]
     * @param Campaign $campaign [description]
     * @param array    $fields   [description]
     * 
     * @return [type]         [description]
     */
    public function scopeSummaryCampaignStats($query,Campaign $campaign, $fields = null)
    {
        $this->period['from'] = $campaign->datefrom;
        $this->period['to'] = $campaign->dateto;
        if (! $fields) {
            $fields = $this->campaignFields;
        }
        $this->fields = $fields;
        $this->campaign = $campaign;
        

        return $query
            ->when(
                in_array('campaign_leads', $this->fields), function ($q) {
                    $q->withCount(
                        [ 
                            'addresses as campaign_leads'=>function ($q) {
                                $q->whereHas(
                                    'campaigns', function ($q) {
                                        $q->where('campaign_id', $this->campaign->id);
                                    }
                                )->where('address_branch.created_at', '<=', $this->period['to']);
                            }
                        ]
                    );

                }
            )
            ->when(
                in_array('touched_leads', $this->fields), function ($query) {
                    $query->withCount(       
                        [
                            'addresses as touched_leads'=>function ($q) {
                                $q->whereHas(
                                    'campaigns', function ($q) {
                                        $q->where('campaign_id', $this->campaign->id);
                                    }
                                )
                                ->wherehas(
                                    'activities', function ($q) {
                                        $q->where('completed', 1)
                                            ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                                    }
                                )->where('address_branch.created_at', '<=', $this->period['to']);

                            }
                        ]
                    );

                }
            )
            ->when(
                in_array('activities', $this->fields), function ($query) {
                    $query->withCount(       
                        [
                            'activities'=>function ($q) {
                                $q->whereBetween(
                                    'activity_date', [$this->period['from'],$this->period['to']]
                                )
                                    ->where('completed', 1)
                                    ->whereHas(
                                        'relatesToAddress', function ($q1) {
                                            $q1->whereHas(
                                                'campaigns', function ($q) {
                                                    $q->where('campaign_id', $this->campaign->id);
                                                }
                                            );
                                        }
                                    );
                            }
                        ]
                    );

                }
            )
            ->when(
                in_array('new_opportunities', $this->fields), function ($query) {
                    $query->withCount(       
                        [
                            'opportunities as new_opportunities'=>function ($q) {
                                $q->whereBetween(
                                    'opportunities.created_at', [$this->period['from'],$this->period['to']]
                                )
                                    ->whereHas(
                                        'location', function ($q) {
                                            $q->whereHas(
                                                'campaigns', function ($q) {
                                                    $q->where('campaign_id', $this->campaign->id);
                                                }
                                            );
                                                
                                        }
                                    );
                            }
                        ]
                    );

                }
            )
            ->when(
                in_array('open_opportunities', $this->fields), function ($query) {
                    $query->withCount(       
                        [
                            'opportunities as open_opportunities'=>function ($q) {
                                $q->whereClosed(0)
                                    ->whereBetween(
                                        'actual_close', [$this->period['from'],$this->period['to']]
                                    )
                                    ->whereHas(
                                        'location' , function ($q) {
                                            $q->whereHas(
                                                'campaigns', function ($q) {
                                                    $q->where('campaign_id', $this->campaign->id);
                                                }
                                            );
                                                
                                        }
                                    );
                                 }
                        ]
                    );

                }
            )
            ->when(
                in_array('won_opportunities', $this->fields), function ($query) {
                    $query->withCount(       
                        [
                            'opportunities as won_opportunities'=>function ($q) {
                
                                $q->whereClosed(1)
                                    ->whereBetween(
                                        'actual_close', [$this->period['from'],$this->period['to']]
                                    )
                                    ->whereHas(
                                        'location', function ($q) {
                                            $q->whereHas(
                                                'campaigns', function ($q) {
                                                    $q->where('campaign_id', $this->campaign->id);
                                                }
                                            );
                                                
                                        }
                                    );
                             }
                        ]
                    );

                }
            )
            ->when(
                in_array('won_value', $this->fields), function ($query) {
                    $query->withCount(       
                        [
                            'opportunities as won_value' => function ($q) {
                
                                $q->select(\DB::raw("SUM(value) as wonvalue"))
                                    ->where('closed', 1)
                                    ->whereBetween(
                                        'actual_close', [$this->period['from'],$this->period['to']]
                                    )
                                    ->whereHas(
                                        'location', function ($q) {
                                            $q->whereHas(
                                                'campaigns', function ($q) {
                                                    $q->where('campaign_id', $this->campaign->id);
                                                }
                                            );
                                                
                                        }
                                    );
                            },
                

                        ]
                    );
                }
            ); 
    }
    /**
     * [scopeSummaryOpenCampaignStats description]
     * 
     * @param [type] $query    [description]
     * @param [type] $campaign [description]
     * 
     * @return [type]         [description]
     */
    public function scopeSummaryOpenCampaignStats($query,Campaign $campaign, array $fields = null)
    {
        $this->period['from'] = $campaign->datefrom;
        $this->period['to'] = $campaign->dateto;
        
        $this->campaign = $campaign;
        if (! $fields) {
            $fields = [
                'campaign_leads',
                'touched_leads',
                'activities',
                'new_opportunities',
                'open_opportunities',
                'won_opportunities',
                'won_value'
            ];
        }
        $this->fields = $fields;

        return $query->when(
            in_array('campaign_leads', $this->fields), function ($query) {
                $query->withCount(       
                    [ 
                        'addresses as campaign_leads'=>function ($q) {
                            $q->whereHas(
                                'campaigns', function ($q) {
                                    $q->where('campaign_id', $this->campaign->id);
                                }
                            )->where('address_branch.created_at', '<=', $this->period['to']);
                        }        
                    ]    
                );

            }
        )
        ->when(
            in_array('touched_leads', $this->fields), function ($query) {
                $query->withCount(       
                    [
                        'addresses as touched_leads'=>function ($q) {
                            $q->whereHas(
                                'campaigns', function ($q) {
                                    $q->where('campaign_id', $this->campaign->id);
                                }
                            )
                            ->wherehas(
                                'activities',function ($q) {
                                    $q->where('completed', 1)
                                        ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                                }
                            )->where('address_branch.created_at', '<=', $this->period['to']);

                        }
                    ]
                );
            }
        ) 
        ->when(
            in_array('activities_count', $this->fields), function ($query) {
                $query->withCount(       
                    [
                        'activities as activities_count'=>function ($q) {
                            $q->whereBetween('activity_date', [$this->period['from'],$this->period['to']])
                                ->where('completed', 1)
                                ->whereHas(
                                    'relatesToAddress', function ($q1) {
                                        $q1->whereHas(
                                            'campaigns', function ($q) {
                                                $q->where('campaign_id', $this->campaign->id);
                                            }
                                        );
                                    }
                                );
                        }
                    ]
                );
            }
        )  
        ->when(
            in_array('new_opportunities', $this->fields), function ($query) {
                $query->withCount(       
                    ['opportunities as new_opportunities'=>function ($q) {
                        $q->whereBetween(
                            'opportunities.created_at', [$this->period['from'],$this->period['to']]
                        )->whereHas(
                            'location', function ($q) {
                                $q->whereHas(
                                    'campaigns', function ($q) {
                                        $q->where('campaign_id', $this->campaign->id);
                                    }
                                );
                                    
                            }
                        );
                    }
                    ]
                );
            }
        )  
        ->when(
            in_array('open_opportunities', $this->fields), function ($query) {
                $query->withCount(       
                    [
                        'opportunities as open_opportunities'=>function ($q) {
                        
                            $q->whereClosed(0)
                                ->whereHas(
                                    'location', function ($q) {
                                        $q->whereHas(
                                            'campaigns', function ($q) {
                                                $q->where('campaign_id', $this->campaign->id);
                                            }
                                        );
                                            
                                    }
                                );
                        }
                    ]
                );
            }
        )  
        ->when(
            in_array('won_opportunities', $this->fields), function ($query) {
                $query->withCount(       
                    [
                        'opportunities as won_opportunities'=>function ($q) {
                            $q->whereClosed(1)
                                ->whereBetween(
                                    'actual_close', [$this->period['from'],$this->period['to']]
                                )
                                ->whereHas(
                                    'location', function ($q) {
                                        $q->whereHas(
                                            'campaigns', function ($q) {
                                                $q->where('campaign_id', $this->campaign->id);
                                            }
                                        );
                                            
                                    }
                                );
                        }
                    ]
                );
            }
        )  
        ->when(
            in_array('won_value', $this->fields), function ($query) {
                $query->withCount(       
                    [
                        'opportunities as won_value' => function ($q) {
                            $q->select(\DB::raw("SUM(value) as wonvalue"))
                                ->where('closed', 1)
                                ->whereBetween(
                                    'actual_close', [$this->period['from'],$this->period['to']]
                                )
                                ->whereHas(
                                    'location', function ($q) {
                                        $q->whereHas(
                                            'campaigns', function ($q) {
                                                $q->where('campaign_id', $this->campaign->id);
                                            }
                                        );
                                            
                                    }
                                );
                        }
                    ]
                );
            }
        );
    }
    /**
     * [scopeHasCampaignleads description]
     * 
     * @param [type]   $query    [description]
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function scopeHasCampaignleads($query, Campaign $campaign)
    {
        return $query->whereHas(
            'addresses', function ($q) use ($campaign) {
                $q->whereHas(
                    'campaigns', function ($q) use ($campaign) {
                        $q->where('campaign_id', $campaign->id);
                    }
                )->where('address_branch.created_at', '<=', $campaign->dateto);
            }
        );
    }
    /**
     * [scopeCampaignDetail description]
     * 
     * @param [type]   $query    [description]
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function scopeCampaignDetail($query,Campaign $campaign, array $fields = null)
    {
        if ($campaign->type=== 'restricted') {
            return $query->restrictedCampaignDetail($campaign, $fields);
        } else {
            return $query->openCampaignDetail($campaign, $fields);
        }
    } 
    
    /**
     * [scopeCampaignRestrictedDetail description]
     * 
     * @param [type]   $query    [description]
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function scopeRestrictedCampaignDetail($query,Campaign $campaign, array $fields = null)
    {
     
        $period['from'] = $campaign->datefrom;
        $period['to'] = $campaign->dateto;
        $this->campaign = $campaign;
        $this->company_ids = $campaign->companies->pluck('id')->toArray();
        $this->period = $period;
        if (! $fields) {
            $fields = [
                'untouchedleads',
                'workedleads',
                'opportunitiesClosingThisWeek',
                'upcomingActivities' ,

            ];
        }
        $this->fields = $fields;
        return $query->when(
            in_array('untouchedleads', $this->fields), function ($query) {
                $query->with(
                    [
                        'untouchedleads'=>function ($q) {
                            $q->whereIn('company_id', $this->company_ids)
                                ->whereDoesntHave(
                                    'activities', function ($q) {
                                        $q->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                                    }
                                )
                            ->whereDoesntHave(
                                'opportunities', function ($q) {
                                    $q->whereBetween('opportunities.created_at', [$this->period['from'], $this->period['to']]);
                                }
                            ); 
                        }
                    ]
                );
               
            }
        )->when(
            in_array('workedleads', $this->fields), function ($query) {
                $query->with(
                    [ 
                        'workedleads'=>function ($q) {
                            $q->whereHas(
                                'campaigns', function ($q) {
                                    $q->where('campaign_id', $this->campaign->id);
                                }
                            )->where('address_branch.created_at', '<', $this->period['to']); 
                        }
                    ]
                );
               
            }
        )->when(
            in_array('opportunitiesClosingThisWeek', $this->fields), function ($query) {
                $query->with(
                    [ 
                        'opportunitiesClosingThisWeek'=>function ($q) {
                            $q->whereHas(
                                'address.address', function ($q1) {
                                    $q1->whereHas(
                                        'campaigns', function ($q) {
                                            $q->where('campaign_id', $this->campaign->id);
                                        }
                                    );
                                }  
                            );
                        }
                    ]
                );               
            }
        )->when(
            in_array('upcomingActivities', $this->fields), function ($query) {
                $query->with(
                    [ 
                        'upcomingActivities'=>function ($q) {
                            $q->whereHas(
                                'relatesToAddress', function ($q1) {
                                    $q1->whereIn('company_id', $this->company_ids);
                                }  
                            );
                        }
                    ]
                );
            }
        );
    }
    /**
     * [scopeOpenCampaignDetail description]
     * 
     * @param QueryBuilder $query    [description]
     * @param Campaign     $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function scopeOpenCampaignDetail($query,Campaign $campaign)
    {
     
        $period['from'] = $campaign->datefrom;
        $period['to'] = $campaign->dateto;
        $this->campaign = $campaign;
        $this->period = $period;
        
        return $query->with(       
            ['leads'=>function ($q) {
                $q->whereHas(
                    'campaigns', function ($q) {
                        $q->where('campaign_id', $this->campaign->id);
                    }
                )
                ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);    
            },
            'untouchedleads'=>function ($q) {
                $q->whereHas(
                    'campaigns', function ($q) {
                        $q->where('campaign_id', $this->campaign->id);
                    }
                ); 
            },
            'workedleads'=>function ($q) {
                $q->whereHas(
                    'campaigns', function ($q) {
                        $q->where('campaign_id', $this->campaign->id);
                    }
                )->where('address_branch.created_at', '<', $this->period['to']); 
            },
            'opportunitiesClosingThisWeek'=>function ($q) {
                $q->whereHas(
                    'address.address', function ($q1) {
                        $q1->whereHas(
                            'campaigns', function ($q) {
                                $q->where('campaign_id', $this->campaign->id);
                            }
                        );
                    }  
                );
            },
            'upcomingActivities'=>function ($q) {
                $q->whereHas(
                    'relatesToAddress', function ($q1) {
                        $q1->whereHas(
                            'campaigns', function ($q) {
                                $q->where('campaign_id', $this->campaign->id);
                            }
                        );
                    }  
                );
            },
            ]
        );
    }

    /**
     * [scopeSummaryCampaignStats description]
     * 
     * @param [type] $query    [description]
     * @param [type] $campaign [description]
     * 
     * @return [type]         [description]
     */
    public function scopeSummaryNAMStats($query, array $period, array $companies)
    {
        $this->period = $period;
        $this->company_ids = $companies;
        return $query->whereHas(
            'leads', function ($q) {
                $q->whereIn('company_id', $this->company_ids);
            }   
        )->withCount(       
            [ 
            'addresses as supplied_leads'=>function ($q) {
                $q->whereIn('company_id', $this->company_ids)
                    ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                    
            },
            'offeredLeads as offered_leads'=>function ($q) {
                $q->whereIn('company_id', $this->company_ids)
                    ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                    
            },

            'workedleads as worked_leads'=>function ($q) {
                $q->whereIn('company_id', $this->company_ids)
                    ->where('address_branch.created_at', '<=', $this->period['to']);
                    
            },
            'rejectedleads as rejected_leads'=>function ($q) {
                $q->whereIn('company_id', $this->company_ids)
                    ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                    
            },
            'addresses as touched_leads'=>function ($q) {
                $q->whereIn('company_id', $this->company_ids)
                    ->where('status_id', '>', '1')
                    ->whereHas(
                        'activities', function ($q1) {
                            $q1->where('completed', 1)
                                ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                        }
                    )
                    ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                    
            },
            
            'activities'=>function ($q) {
                
                    $q->whereBetween(
                        'activity_date', [$this->period['from'],$this->period['to']]
                    )
                        ->where('completed', 1)
                        ->whereHas(
                            'relatesToAddress', function ($q1) {
                                    $q1->whereIn('company_id', $this->company_ids);
                            }  
                        );
            },
            'opportunities as new_opportunities'=>function ($q) {
                $q->whereBetween(
                    'opportunities.created_at', [$this->period['from'],$this->period['to']]
                )->whereHas(
                    'location', function ($q1) {
                        $q1->whereIn('company_id', $this->company_ids);
                    }
                );
            },
            'opportunities as won_opportunities'=>function ($q) {
                
                $q->whereClosed(1)
                    
                    ->whereBetween(
                        'actual_close', [$this->period['from'],$this->period['to']]
                    )->whereHas(
                        'location', function ($q1) {
                            $q1->whereIn('company_id', $this->company_ids);
                        }
                    );
            },
            'opportunities as lost_opportunities'=>function ($q) {
                
                $q->whereClosed(2)
                    ->whereBetween(
                        'opportunities.created_at', [$this->period['from'],$this->period['to']]
                    )
                    ->whereBetween(
                        'actual_close', [$this->period['from'],$this->period['to']]
                    )->whereHas(
                        'location', function ($q1) {
                            $q1->whereIn('company_id', $this->company_ids);
                        }
                    );
            },
            
            'opportunities as opportunities_open'=>function ($q) {
                
                $q->where('opportunities.created_at', '<=',  $this->period['to'])
                    ->where(
                        function ($q) {
                            $q->whereClosed(0)
                                ->orWhere(
                                    function ($q) {
                                        $q->where('actual_close', '>', $this->period['to'])
                                            ->orwhereNull('actual_close');
                                    }
                                );
                        }
                    )
                    ->whereHas(
                        'location', function ($q1) {
                            $q1->whereIn('company_id', $this->company_ids);
                        }
                    );
            },
            'opportunities as won_value' => function ($q) {
                
                $q->select(\DB::raw("SUM(value) as wonvalue"))
                    ->where('closed', 1)
                    ->whereBetween(
                        'actual_close', [$this->period['from'],$this->period['to']]
                    )
                    ->whereHas(
                        'location', function ($q1) {
                            $q1->whereIn('company_id', $this->company_ids);
                        }
                    );
            },
            'opportunities as open_value' => function ($q) {
                $q->where('opportunities.created_at', '<=', $this->period['to'])
                    ->select(\DB::raw("SUM(value) as openvalue"))
                    ->where(
                        function ($q) {
                            $q->whereClosed(0)
                                ->orWhere(
                                    function ($q) {
                                        $q->where('actual_close', '>', $this->period['to'])
                                            ->orwhereNull('actual_close');
                                    }
                                );
                        }
                    )
                    ->whereHas(
                        'location', function ($q1) {
                            $q1->whereIn('company_id', $this->company_ids);
                        }
                    );
                
            }]
        );
    }
}
