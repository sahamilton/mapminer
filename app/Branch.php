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
    
    public function locations()
    {
        return $this->belongsToMany(Address::class);
    }
    
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function relatedPeople($role = null)
    {
        if ($role) {
            return $this->belongsToMany(Person::class)

            ->wherePivot('role_id', '=', $role);
        } else {
            return $this->belongsToMany(Person::class)->withTimestamps()->withPivot('role_id');
        }
    }

    public function activities()
    {
        return $this->hasManyThrough(Activity::class, AddressBranch::class, 'branch_id', 'address_id', 'id', 'address_id');
    }

    public function activitiesbytype($type = null)
    {

            
            $activities = $this->hasManyThrough(Activity::class, AddressBranch::class, 'branch_id', 'address_id', 'id', 'address_id');
            if($type){
                 $activities->where('activitytype_id', '=', $type);
             }
             return $activities;
    }


    /*public function opportunities(){
		return $this->hasMany(Opportunity::class);
	}*/
    public function opportunities()
    {
 
        return $this->hasManyThrough(Opportunity::class, AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id');
    }
    public function openOpportunities()
    {
 
        return $this->hasManyThrough(Opportunity::class, AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id')->where('closed','=',0);
    }
    public function closedOpportunities()
    {
 
        return $this->hasManyThrough(Opportunity::class, AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id')->where('closed','=',1);
    }
    public function instate()
    {
        return $this->belongsTo(State::class, 'state', 'statecode');
    }
    
    public function manager()
    {
        return $this->relatedPeople($this->branchManagerRole);
    }

    public function businessmanager()
    {
        return $this->relatedPeople($this->businessManagerRole);
    }

    public function marketmanager()
    {
        return $this->relatedPeople($this->marketManagerRole);
    }
    
    public function servicelines()
    {
            return $this->belongsToMany(Serviceline::class);
    }
    
    public function servicedBy()
    {

        return $this->belongsToMany(Person::class)->withTimestamps()->withPivot('role_id');
    }
    public function salesTeam()
    {

        return $this->belongsToMany(Person::class)->withTimestamps()->withPivot('role_id')->wherePivot('role_id', '=', 5);
    }
    public function leads()
    {
        return  $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id')->whereDoesntHave('opportunities');
    }

    public function leadsBySourceCount($leadsource)
    {

        return $this->belongsToMany(Address::class, 'address_branch', 'branch_id', 'address_id')->where(['lead_source_id' => $leadsource->id])->count();
    }
    
    
    public function getManagementTeam()
    {
        $team = $this->salesTeam()->get();
        $mgrs = [];
        foreach ($team as $rep) {
            $mgrs = array_unique(array_merge($mgrs, $rep->ancestorsAndSelf()->pluck('id')->toArray()));
        }
        return $mgrs;
    }

    public function getPresenterClass()
    {
        return LocationPresenter::class;
    }

    public function branchemail()
    {
        return $this->id ."br@peopleready.com";
    }

    
   
    /*
		Calculate bounding box coordinates

	*/
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
    
    public function getBranchIdFromid($branchstring)
    {
        $branchstring = str_replace(" ", "", $branchstring);
        return $this->whereIn('id', explode(',', $branchstring))
        ->pluck('id')->toArray();
    }
        
    /*
		Generate Mapping xml file from branches results
		       
		Passed to function:                                                   
		@ results                             
	
		@return xml
	
	*/
    
    public function makeNearbyBranchXML($result)
    {
        
        $dom = new \DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);
        foreach ($result as $row) {
          // ADD TO XML DOCUMENT NODE
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

    public function getBranchTeam()
    {
        return User::whereHas(
            'roles',
            function ($q) {
                $q->whereIn('role_id', $this->branchRoles);
            }
        )->with('Person')->get();
    }
    

    public function getNearByBranches($servicelines, $location, $distance = 100, $limit = 5)
    {
        return $this->wherehas('servicelines', function ($q) use ($servicelines) {
            $q->whereIn('servicelines.id', $servicelines);
        })
        ->nearby($location, $distance)
        ->limit($limit)
        ->get();
    }

    public function orders($period = null)
    {
        
        return $this->hasManyThrough(Orders::class, AddressBranch::class, 'branch_id', 'address_branch_id', 'id', 'id');
    }

    public function allStates()
    {
        $states = $this->distinct('state')->pluck('state')->toArray();
        return State::whereIn('statecode', $states)->orderBy('statecode')->get();
    }

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

    public function getbranchGeoCode($request)
    {
        $address = request('street') . ",". request('city') . ",". request('state') . ",". request('zip');

        $geoCode = app('geocoder')->geocode($address)->get();

        $latlng = ($this->getGeoCode($geoCode));
        $request['lat']= $latlng['lat'];
        $request['lng']= $latlng['lng'];
        return $request;
    }

	public function  scopeGetActivitiesByType($query,$activitytype=null){
	
        if($activitytype){
           
            return $query->with(['activities'=> function($query) use ($activitytype) { 
                $query->where('activitytype_id','=',$activitytype)
                ->whereBetween('activity_date',[Carbon::now()->subMonth(),Carbon::now()]);
            }],'activities.type','activities.relatedAddress');
        }else{
            
             return $query->with(['activities'=>function($query){
                $query->whereBetween('activity_date',[Carbon::now()->subMonth(),Carbon::now()]);
            }],'activities.type','activities.relatedAddress');
            
        }
    }

    public function checkIfMyBranch($request, $branch = null, $myBranches)
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
}
