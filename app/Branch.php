<?php
namespace App;

use App\Presenters\LocationPresenter;
use McCool\LaravelAutoPresenter\HasPresenter;
use Illuminate\Http\Request;
use \Carbon\Carbon;

class Branch extends Model implements HasPresenter
{
    use GeoCode, PeriodSelector;
    public $table ='branches';
    protected $hidden = ['created_at','updated_at','position'];
    protected $primaryKey = 'id'; // or null
    protected $spatialFields = [
        'position'
    ];
    public $incrementing = false;

    public $branchManagerRole = 9;
    public $branchRoles = [3,5,9,11];
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
            '4'=>'Sales Appointment',
            '5'=>'Stop By',
            '7'=>'Proposal',
            '10'=>'Site Visit',
            '13'=>'Log a call',
            '14'=>'In Person'

    ];
    public $leadFields = [
            'open_leads',
            'active_leads',
            "supplied_leads",
            "offered_leads",
            "worked_leads",
            "rejected_leads",
            "touched_leads",
            
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

    protected $guarded = [];
    public $errors;
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
                ->wherePivot('role_id', '=', $role);
        } else {
            return $this->belongsToMany(Person::class)
                ->withTimestamps()
                ->withPivot('role_id');
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
     * [openOpportunities description]
     * 
     * @return [type] [description]
     */
    public function openOpportunities()
    {
 
        return $this->hasManyThrough(Opportunity::class, AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id')->where('closed', '=', 0);
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
 
        return $this->hasManyThrough(Opportunity::class, AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id')->where('closed', '=', 1);
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
        return $this->relatedPeople($this->branchManagerRole);
    }
    /**
     * [businessmanager description]
     * 
     * @return [type] [description]
     */
    public function businessmanager()
    {
        return $this->relatedPeople($this->businessManagerRole);
    }
    /**
     * [marketmanager description]
     * 
     * @return [type] [description]
     */
    public function marketmanager()
    {
       
        return $this->relatedPeople($this->marketManagerRole);
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
        return $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id')
            ->whereDoesntHave('opportunities')
            ->whereIn('status_id', [2]);  

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
    public function workedLeads()
    {
        return  $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id')
            ->whereIn('address_branch.status_id', [2]); 

    }

    /**
     * [neglectedLeads description]
     * 
     * @return [type] [description]
     */
    public function neglectedLeads()
    {
        return  $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id')
            ->whereDoesntHave('opportunities')
            ->where('address_branch.created_at', '<', now()->subWeek()->startOfDay())
            ->whereIn('status_id', [1]); 

    }
    /**
     * [untouchedLeads description]
     * 
     * @return [type] [description]
     */
    public function untouchedLeads()
    {
        return  $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id')
            ->whereDoesntHave('opportunities')
            ->whereDoesntHave('activities')
            
            ->whereIn('status_id', [2]); 

    }
    /**
     * [rejectedLeads description]
     * 
     * @return [type] [description]
     */
    public function rejectedLeads()
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
            ['untouchedLeads'=>function ($q) {
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
     * [branchemail description]
     * 
     * @return [type] [description]
     */
    public function branchemail()
    {
        return $this->id ."br@peopleready.com";
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
     * [makeNearbyBranchXML   Generate Mapping xml file from branches results]
     * 
     * @param [type] $result [description]
     * 
     * @return [xml]         [description]
     */
    public function makeNearbyBranchXML($result)
    {
        
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
    public function scopeGetActivitiesByType($query,$period,$activitytype=null)
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

    public function scopeSummaryOpportunities($query, array $period, array $fields=null)
    {
        $this->period = $period;
        if (! $fields) {
            $fields = $this->opportunityFields;
        }
        /*
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

         */
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
        return $query->with(       
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
            'activities.address',
            'opportunities'=>function ($query) {
                $query->whereClosed(0)        
                    ->where(
                        function ($q) {
                            $q->where('actual_close', '>', now())
                                ->orwhereNull('actual_close');
                        }
                    )
                ->where('opportunities.created_at', '<', now());
            },
            'opportunities.address'

            ]
        );

    }
    /**
     * [scopeSummaryStats description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeSummaryStats($query,$period)
    {
        $this->period = $period;
     
        return $query->withCount(       
            ['leads'=>function ($query) {
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
                    );
            },
            'offeredLeads'=>function ($query) {
                $query->whereHas(
                    'assignedToBranch', function ($q) {

                        $q->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                    }
                );
            },
            'neglectedLeads',
            'leads as newbranchleads'=>function ($query) {
                $query->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']])
                    ->where(
                        function ($q) {
                            $q->where('lead_source_id', 4);
                        }
                    );
            },
            'activities'=>function ($query) {
                $query->whereBetween(
                    'activity_date', [$this->period['from'],$this->period['to']]
                )->where('completed', 1);
            },
            'activities as openactivities'=>function ($query) {
                $query->where('completed', 0)->orWhereNull('completed');
                    
            },
            'activities as salesappts'=>function ($query) {
                $query->whereBetween(
                    'activity_date', [$this->period['from'],$this->period['to']]
                )->where('completed', 1)
                    ->where('activitytype_id', 4);
            },
            'activities as sitevisits'=>function ($query) {
                $query->whereBetween(
                    'activity_date', [$this->period['from'],$this->period['to']]
                )->where('completed', 1)
                    ->where('activitytype_id', 10);
            },
            'activities as logacall'=>function ($query) {
                $query->whereBetween(
                    'activity_date', [$this->period['from'],$this->period['to']]
                )
                    ->where('completed', 1)
                    ->where('activitytype_id', 13);
            },
            'activities as proposals'=>function ($query) {
                $query->whereBetween(
                    'activity_date', [$this->period['from'],$this->period['to']]
                )
                    ->where('completed', 1)
                    ->where('activitytype_id', 7);
            },
            'activities as sitevists'=>function ($query) {
                $query->whereBetween(
                    'activity_date', [$this->period['from'],$this->period['to']]
                )
                    ->where('completed', 1)
                    ->where('activitytype_id', 13);
            },
            'activities as salesapptsscheduled'=>function ($query) {
                $query->whereBetween(
                    'activity_date', [$this->period['from'],$this->period['to']]
                )->where('activitytype_id', 4);
            },
            'opportunities as opened'=>function ($query) {
                $query->whereBetween(
                    'opportunities.created_at', [$this->period['from'],$this->period['to']]
                );
            },
            'opportunities as won'=>function ($query) {
                $query->whereClosed(1)
                    ->whereBetween(
                        'actual_close', [$this->period['from'],$this->period['to']]
                    );
            },
            'opportunities as lost'=>function ($query) {
                $query->whereClosed(2)
                    ->whereBetween(
                        'actual_close', [$this->period['from'],$this->period['to']]
                    );
            },
            'opportunities as Top25'=>function ($query) {
                $query->where('opportunities.Top25',  1)
                    ->where(
                        function ($q) {
                            $q->where('actual_close', '>', $this->period['to'])
                                ->orwhereNull('actual_close');
                        }
                    )
                ->where('opportunities.created_at', '<', $this->period['to']);
            },
            'opportunities as Top25value'=>function ($query) {
                $query->select(\DB::raw("SUM(value) as Top25value"))
                    ->where('opportunities.Top25',  1)
                    ->where(
                        function ($q) {
                            $q->where('actual_close', '>', $this->period['to'])
                                ->orwhereNull('actual_close');
                        }
                    )
                ->where('opportunities.created_at', '<', $this->period['to']);
            },
            'opportunities as open'=>function ($query) {
                $query->whereClosed(0)        
                    ->OrWhere(
                        function ($q) {
                            $q->where('actual_close', '>', $this->period['to'])
                                ->orwhereNull('actual_close');
                        }
                    )
                ->where('opportunities.created_at', '<', $this->period['to']);
            },
            'opportunities as wonvalue' => function ($query) {
                $query->select(\DB::raw("SUM(value) as wonvalue"))
                    ->where('closed', 1)
                    ->whereBetween(
                        'actual_close', [$this->period['from'],$this->period['to']]
                    );
            },
            'opportunities as openvalue' => function ($query) {
                $query->select(\DB::raw("SUM(value) as wonvalue"))
                    ->whereClosed(0)        
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
     * [scopeSummaryStats description]
     * 
     * @param [type] $query    [description]
     * @param [type] $campaign [description]
     * 
     * @return [type]         [description]
     */
    public function scopeSummaryCampaignStats($query,Campaign $campaign, array $companies=null)
    {
        $this->period['from'] = $campaign->datefrom;
        $this->period['to'] = $campaign->dateto;
        if (! $companies) {
            $this->company_ids = $campaign->companies->pluck('id')->toarray();
        } else {
            $this->company_ids = $companies;
        }
        
        

        return $query->withCount(       
            [ 
            'addresses as supplied_leads'=>function ($q) {
                $q->whereIn('company_id', $this->company_ids)
                    ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                    
            },
            'offeredLeads as offered_leads'=>function ($q) {
                $q->whereIn('company_id', $this->company_ids)
                    ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                    
            },

            'workedLeads as worked_leads'=>function ($q) {
                $q->whereIn('company_id', $this->company_ids)
                    ->where('address_branch.created_at', '<=', $this->period['to']);
                    
            },
            'rejectedLeads as rejected_leads'=>function ($q) {
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
                    ->where('opportunities.created_at', '<=', $this->period['to'])
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
    
    /**
     * [scopeSummaryCompanyStats description]
     * 
     * @param [query] $query     [description]
     * @param [array] $period    [description]
     * @param [array] $companies [description]
     * 
     * @return [type]         [description]
     */
    
    public function scopeCompanyLeadSummary($query, array $period, array $company_ids, array $fields = null)
    {

        if (! $fields) {
            $fields = $this->leadFields;
        }
        $this->fields = $fields;
        $this->period = $period;
        $this->company_ids = $company_ids;
        
        return $query->when(
            in_array('top_25leads', $this->fields), function ($q) {

                $q->withCount(       
                    [
                        'locations as top_25leads'=>function ($query) {

                            $query->whereHas(
                                'location', function ($q1) {
                                    $q1->whereIn('company_id', $this->company_ids);
                                }
                            )
                            ->where('address_branch.top50',  1);
                               
                        }
                    ]
                );
            }
        )

        ->when(
            in_array('new_leads', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'locations as new_leads'=>function ($query) {

                            $query->whereHas(
                                'location', function ($q1) {
                                    $q1->whereIn('company_id', $this->company_ids);
                                }
                            )
                            ->whereBetween('address_branch.created_at',  [$this->period['to'], $this->period['from']]);
                        }
                    ]
                );
            }
        )
        ->when(
            in_array('supplied_leads', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'locations as supplied_leads'=>function ($query) {
                            $query->whereHas(
                                'location', function ($q1) {
                                    $q1->whereIn('company_id', $this->company_ids);
                                }
                            )
                            ->where('address_branch.created_at', '<=', $this->period['to']);
                                
                        }
                    ]
                );
            }
        )
        ->when(
            in_array('open_leads', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'locations as open_leads'=>function ($query) {
                            $query->whereIn('company_id', $this->company_ids)
                                ->where('address_branch.status_id', 2);
                                    
                        }   
                    ]
                );
            }
        )
        ->when(
            in_array('offered_leads', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'locations as offered_leads'=>function ($query) {
                            $query->whereHas(
                                'location', function ($q1) {
                                    $q1->whereIn('company_id', $this->company_ids);
                                }
                            )
                            ->where('address_branch.status_id', 1)
                                ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                                
                        }
                    ]
                );
            }
        )->when(
            in_array('worked_leads', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'locations as worked_leads'=>function ($query) {
                            $query->whereHas(
                                'location', function ($q1) {
                                    $q1->whereIn('company_id', $this->company_ids);
                                }
                            )
                            ->where('address_branch.status_id', 2)
                                 ->where('address_branch.created_at', '<=', $this->period['to']);
                                
                        }
                    ]
                );
            }
        )

        ->when(
            // note that this and touched leads should return the same
            // consider merging.
            in_array('active_leads', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'locations as active_leads'=>function ($query) {
                            $query->whereIn('company_id', $this->company_ids)
                                ->where('address_branch.status_id', 2)
                                ->whereHas(
                                    'activities', function ($q1) {
                                        $q1->whereBetween('activity_date', [$this->period['from'], $this->period['to']])
                                            ->where('completed', 1);
                                    }
                                );
                                    
                        
                           
                        }
                    ]
                );
            }
        )
        ->when(
            in_array('rejected_leads', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'locations as rejected_leads'=>function ($query) {
                            $query->whereHas(
                                'location', function ($q1) {
                                    $q1->whereIn('company_id', $this->company_ids);
                                }
                            )
                            ->where('address_branch.status_id', 4)
                            ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']])
                            ->whereDoesntHave('opportunities')
                            ->whereDoesntHave('activities');
                                
                            
                        }
                    ]
                );
            }
        );

        
    }

    public function scopeCompanyOpportunitySummary($query, $period, $fields = null)
    {
        if (! $fields) {
            $fields = $this->opportunityFields;
        }
        $this->fields = $fields;
        return $query->when(
            in_array('new_opportunities', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as new_opportunities'=>function ($q1) {
                
                            $q1->whereHas(
                                'location', function ($q1) {

                                    $q1->whereIn('company_id', $this->company_ids);

                                }
                            )
                            ->whereBetween(
                                'opportunities.created_at', [$this->period['from'],$this->period['to']]
                            );
                                
                        }
                    ]
                );
            }
        )->when(
            in_array('won_opportunities', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as won_opportunities'=>function ($query) {
                            $query->whereHas(
                                'location', function ($q1) {

                                    $q1->whereIn('company_id', $this->company_ids);

                                }
                            )
                            ->whereClosed(1)
                                ->whereBetween(
                                    'actual_close', [$this->period['from'],$this->period['to']]
                                );
                        }
                    ]
                );
            }
        )->when(
            in_array('lost_opportunities', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as lost_opportunities'=>function ($query) {
                            $query->whereHas(
                                'location', function ($q1) {

                                    $q1->whereIn('company_id', $this->company_ids);

                                }
                            )
                            ->whereClosed(2)        
                                
                                ->whereBetween(
                                    'opportunities.actual_close', [$this->period['from'], $this->period['to']]
                                );
                        }
                    ]
                );
            }
        )->when(
            in_array('open_opportunities', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as open_opportunities'=>function ($query) {
                            $query->whereHas(
                                'location', function ($q1) {

                                    $q1->whereIn('company_id', $this->company_ids);

                                }
                            )
                            ->whereClosed(0)        
                                ->OrWhere(
                                    function ($q) {
                                        $q->where('actual_close', '>', $this->period['to'])
                                            ->orwhereNull('actual_close');
                                    }
                                )
                                ->where('opportunities.created_at', '<=', $this->period['to']);
                        }
                    ]
                );
            }
        )->when(
            in_array('active_opportunities', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as active_opportunities'=>function ($query) {
                            $query->whereHas(
                                'location', function ($q1) {

                                    $q1->whereIn('company_id', $this->company_ids);

                                }
                            )
                            ->whereClosed(0)        
                                ->OrWhere(
                                    function ($q) {
                                        $q->where('actual_close', '>', $this->period['to'])
                                            ->orwhereNull('actual_close');
                                    }
                                )->has('currentlyActive')
                                
                                ->where('opportunities.created_at', '<=', $this->period['to']);
                        }
                    ]
                );
            }
        )
        ->when(
            in_array('active_value', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as active_value'=>function ($query) {
                            $query->whereHas(
                                'location', function ($q1) {

                                    $q1->whereIn('company_id', $this->company_ids);

                                }
                            )->activeValue($this->period);
                        }
                    ]
                );
            }
        )->when(
            in_array('top_25opportunities', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as top_25opportunities'=>function ($query) {
                            $query->whereHas(
                                'location', function ($q1) {

                                    $q1->whereIn('company_id', $this->company_ids);

                                }
                            )->top25($this->period);
                           
                        }
                    ]
                );
            }
        )->when(
            in_array('Top25value', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as Top25value'=>function ($query) {
                            $query->whereHas(
                                'location', function ($q1) {

                                    $q1->whereIn('company_id', $this->company_ids);

                                }
                            )->top25Value($this->period);
                        }
                    ]
                );
            }
        )->when(
            in_array('won_value', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as won_value'=>function ($query) {
                
                            $query->whereHas(
                                'location', function ($q1) {

                                    $q1->whereIn('company_id', $this->company_ids);

                                }
                            )->wonValue($this->period);
                                
                           
                        }
                    ]
                );
            }
        )->when(
            in_array('lost_value', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as won_value'=>function ($query) {
                
                            $query->whereHas(
                                'location', function ($q1) {

                                    $q1->whereIn('company_id', $this->company_ids);

                                }
                            )->lostValue($this->period);
                                
                           
                        }
                    ]
                );
            }
        )->when(
            in_array('open_value', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as open_value' => function ($q) {
                            $q->whereHas(
                                'location', function ($q1) {

                                    $q1->whereIn('company_id', $this->company_ids);

                                }
                            )->openValue($this->period);
                        }
                    ]
                );
            }
        );

    }

    public function scopeActivitySummary($query, array $period, array $fields=null)
    {
        if (! $fields) {
            $fields = $this->activityFields;
        }

        $this->period = $period;
        $this->fields = $fields;
        
        return $query->with(
            [
                'activities'=>function ($q) {
                    $q->whereHas(
                        'type', function ($q1) {
                            $q1->whereIn('activity', $this->fields);
                        }
                    )->completed()
                        ->periodActivities($this->period)
                        ->typeCount();
                }
            ]
        );
    }
    


    public function scopeCompanyActivitySummary($query, array $period, array $fields=null)
    {
        if (! $fields) {
            $fields = $this->_setActivityFields();
        }
        $this->period = $period;
        $this->fields = $fields;
        return $query->when(
            in_array('Sales_Appointment', $this->fields), function ($q) {
                $q->withCount(
                    [
                        'activities as Sales_Appointment'=>function ($q1) {

                            $q1->whereHas(
                                'relatesToAddress', function ($q1) {
                                    $q1->whereIn('company_id', $this->company_ids);
                                }
                            )
                            ->where('completed', 1)
                                ->where('activitytype_id', 4)
                                ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                        }
                    ]
                );
            }
        )->when(
            in_array('Stop_By', $this->fields), function ($q) {
                $q->withCount(
                    [
                        'activities as Stop_By'=>function ($q1) {
                            $q1->whereHas(
                                'relatesToAddress', function ($q1) {
                                    $q1->whereIn('company_id', $this->company_ids);
                                }
                            )
                            ->where('completed', 1)
                                ->where('activitytype_id', 5)
                                ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                        }
                        
                    ]
                );
            }
        )->when(
            in_array('Proposal', $this->fields), function ($q) {
                $q->withCount(
                    [
                        'activities as Proposal'=>function ($q1) {
                            $q1->whereHas(
                                'relatesToAddress', function ($q1) {
                                    $q1->whereIn('company_id', $this->company_ids);
                                }
                            )
                            ->where('completed', 1)
                                ->where('activitytype_id', 7)
                                ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                        }
                    ]
                );
            }
        )->when(
            in_array('Site_Visit', $this->fields), function ($q) {
                $q->withCount(
                    [
                        'activities as Site_Visit'=>function ($q1) {
                            $q1->whereHas(
                                'relatesToAddress', function ($q1) {
                                    $q1->whereIn('company_id', $this->company_ids);
                                }
                            )
                            ->where('completed', 1)
                                ->where('activitytype_id', 10)
                                ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                        }
                    ]
                );
            }
        )->when(
            in_array('Log_a_call', $this->fields), function ($q) {
                $q->withCount(
                    [
                        'activities as Log_a_call'=>function ($q1) {
                            $q1->whereHas(
                                'relatesToAddress', function ($q1) {
                                    $q1->whereIn('company_id', $this->company_ids);
                                }
                            )
                            ->where('completed', 1)
                                ->where('activitytype_id', 13)
                                ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                        }
                    ]
                );
            }
        )->when(
            in_array('In_Person', $this->fields), function ($q) {
                $q->withCount(
                    [
                        'activities as In_Person'=>function ($q1) {
                            $q1->whereHas(
                                'relatesToAddress', function ($q1) {
                                    $q1->whereIn('company_id', $this->company_ids);
                                }
                            )
                            ->where('completed', 1)
                                ->where('activitytype_id', 14)
                                ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                        }
                    ]
                );
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
    public function scopeCampaignDetail($query,Campaign $campaign, array $companies=null)
    {
     
        $period['from'] = $campaign->datefrom;
        $period['to'] = $campaign->dateto;
        if(! $companies) {
            $this->company_ids = $campaign->companies->pluck('id')->toarray();
        }else {
            $this->company_ids = $companies;
        }
        
        
        $this->location_ids = $campaign->getLocations();

        $this->setPeriod($period);;
        
        return $query->with(       
            ['offeredLeads'=>function ($q) {
                $q->whereIn('company_id', $this->company_ids);
                    
            },
            'untouchedLeads'=>function ($q) {
                 $q->whereIn('company_id', $this->company_ids);
            },
            'workedLeads'=>function ($q) {
                 $q->whereIn('company_id', $this->company_ids);
            },
            'opportunitiesClosingThisWeek'=>function ($q) {
                $q->whereHas(
                    'address.address', function ($q1) {
                            $q1->whereIn('company_id', $this->company_ids);
                    }  
                );
            },
            'upcomingActivities'=>function ($q) {
                $q->whereHas(
                    'relatesToAddress', function ($q1) {
                            $q1->whereIn('company_id', $this->company_ids);
                    }  
                );
            },
            ]
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
    public function scopeCompanyDetail($query,Company $company, array $period=null)
    {
     
        if (! $period) {
            $period = $this->getPeriod();
        }
        $this->company_ids = [$company->id];
        return $query->with(       
            ['offeredLeads'=>function ($q) {
                $q->whereIn('company_id', $this->company_ids);
                    
            },
            'untouchedLeads'=>function ($q) {
                 $q->whereIn('company_id', $this->company_ids);
            },
            'workedLeads'=>function ($q) {
                 $q->whereIn('company_id', $this->company_ids);
            },
            'opportunitiesClosingThisWeek'=>function ($q) {
                $q->whereHas(
                    'address.address', function ($q1) {
                            $q1->whereIn('company_id', $this->company_ids);
                    }  
                );
            },
            'upcomingActivities'=>function ($q) {
                $q->whereHas(
                    'relatesToAddress', function ($q1) {
                            $q1->whereIn('company_id', $this->company_ids);
                    }  
                );
            },
            ]
        );
    }
}
