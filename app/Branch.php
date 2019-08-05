<?php
namespace App;

use App\Presenters\LocationPresenter;
use McCool\LaravelAutoPresenter\HasPresenter;
use Illuminate\Http\Request;
use \Carbon\Carbon;

class Branch extends Model implements HasPresenter
{
    use Geocode;
    public $table ='branches';
    protected $hidden = ['created_at','updated_at','position'];
    protected $primaryKey = 'id'; // or null

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
    protected $guarded = [];
    public $errors;
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
        return $this->hasManyThrough(Opportunity::class, AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id')->where('closed', '=', 0)
            ->whereBetween('expected_close', [now(), now()->addWeek()]);
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
        return  $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id')
            ->whereDoesntHave('opportunities'); 

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

    
   
    /*
    Calculate bounding box coordinates

    
    private function getPositionCoordinates($lat, $lng, $distance)
    {
        

        $coordinates['lat']= $lat;
        $coordinates['lon'] = $lng;
        $coordinates['dist'] = $distance;
        $location = Geolocation::fromDegrees($lat, $lng);
        $box = $location->boundingCoordinates($distance, 'mi');

        $coordinates['rlon1'] = $box['min']->degLon;
        $coordinates['rlon2'] = $box['max']->degLon;
        $coordinates['rlat1'] = $box['min']->degLat;
        $coordinates['rlat2'] = $box['max']->degLat;
    
        return $coordinates;
    }
    */
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
                        }]
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
            'activities'=>function ($query) {
                $query->whereBetween(
                    'activity_date', [$this->period['from'],$this->period['to']]
                )
                    ->where('completed', 1);
            },
            'activities as openactivities'=>function ($query) {
                $query->where('completed', 0)->orWhereNull('completed');
                    
            },
            'activities as salesappts'=>function ($query) {
                $query->whereBetween(
                    'activity_date', [$this->period['from'],$this->period['to']]
                )
                    ->where('completed', 1)
                    ->where('activitytype_id', 4);
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
            'opportunities as top50'=>function ($query) {
                $query->where('opportunities.top50',  1)
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
                    ->where(
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
}
