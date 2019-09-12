<?php
namespace App;

use App\Presenters\LocationPresenter;
use Illuminate\Database\Eloquent\SoftDeletes;
use McCool\LaravelAutoPresenter\HasPresenter;

class Person extends NodeModel implements HasPresenter
{
    use Geocode, Filters, SoftDeletes, FullTextSearch;
    public $salesroles = ['5','9'];
    // Add your validation rules here
    public static $rules = [
        'email'=>'required',
        'mgrtype' => 'required',
    ];
    
    protected $table ='persons';
    protected $hidden = ['created_at','updated_at','deleted_at','position'];
    protected $parentColumn = 'reports_to';

    
    // Don't forget to fill this array
    public $fillable = ['firstname',
                        'lastname',
                        'phone',
                        'address',
                        'lat',
                        'lng',
                        'reports_to',
                        'city',
                        'state',
                        'zip',
                        'geostatus',
                        'business_title',
                        'user_id',
                        'position'];
    protected $searchable = [
        'firstname',
        'lastname'
    ];
    /**
     * [reportsTo description]
     * 
     * @return [type] [description]
     */
    public function reportsTo()
    {
        return $this->belongsTo(Person::class, 'reports_to', 'id');
    }
    /**
     * [reportChain description]
     * 
     * @return [type] [description]
     */
    public function reportChain()
    {
        return $this->getAncestorsWithoutRoot();
    }
    /**
     * [directReports description]
     * 
     * @return [type] [description]
     */
    public function directReports()
    {
        return $this->hasMany(Person::class, 'reports_to');
    }

    /**
     * [salesRole description]
     * not sure this works!
     * 
     * @return [type] [description]
     */
    public function salesRole()
    {
        return $this->belongsTo(SalesOrg::class, 'id', 'position');
    }
    /**
     * [branchesServiced description]
     * 
     * @return [type] [description]
     */
    public function branchesServiced()
    {

        return $this->belongsToMany(Branch::class)
            ->withTimestamps()
            ->withPivot('role_id')
            ->orderBy('branchname');
    }
    /**
     * [getMyBranches finds branch managers in reporting
     * strucuture and returns their branches as array]
     * 
     * @return array list of branches serviced by reports
     */
    public function getMyBranches()
    {
       
        $branches = $this->descendantsAndSelf()
            ->withRoles([9])
            ->with('branchesServiced')
            ->get()
            ->map(
                function ($branch) { 
                    return $branch->branchesServiced->pluck('id')->toArray();
                }
            );
        return array_unique($branches->flatten()->toArray());
    }



    /**
     * [myBranches description]
     * 
     * @return [type] [description]
     */
    public function myBranches(Person $person=null)
    {
        
        if (! $person && auth()->user()->hasRole('admin')) {
            return Branch::all()->pluck('branchname', 'id')->toArray();
        }
        $myteam = $this->myTeam($person)->has('branchesServiced')->get();

        $data=[];

        $teammembers =  $myteam->map(
            function ($team) {
           
                return $team->branchesServiced;
            }
        );

        foreach ($teammembers as $member) {
       
            foreach ($member->pluck('branchname', 'id') as $id => $branchname) {
                if (! array_key_exists($id, $data)) {
                    $data[$id]=$branchname;
                }
            }
        }
        ksort($data);
        return $data;
    }
    /**
     * [myBranchTeam description]
     * 
     * @param array $myBranches [description]
     * 
     * @return [type]             [description]
     */
    public function myBranchTeam(array $myBranches)
    {
        
        $branches = Branch::whereIn('id', $myBranches)->with('manager')->get();
        
        $team = $branches->map(
            function ($branch) {
                return $branch->manager->pluck('user_id');
            }
        );
        return $team->flatten();
    }
    /**
     * [scopeMyReports description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeMyReports($query)
    {
        return $query->descendantsAndSelf();
    }

    public function team()
    {
        return $this->descendantsAndSelf()->with('branchesServiced');
    }

    /**
     * [myTeam description]
     * 
     * @param Person|null $person [description]
     * 
     * @return [type]              [description]
     */
    public function myTeam(Person $person=null)
    {
        
        if ($person) {
            return $person->descendantsAndSelf()->with('branchesServiced');
        }
       
        return $this->where('user_id', '=', auth()->user()->id)->firstOrFail()
            ->descendantsAndSelf()->with('branchesServiced');
    }
    /**
     * [lastUpdatedBranches description]
     * 
     * @return [type] [description]
     */
    public function lastUpdatedBranches()
    {
        return $this->belongsToMany(Branch::class)
            ->withTimestamps()
            ->addSelect('branch_person.updated_at', \DB::raw("MAX(branch_person.updated_at) AS lastdate"))->get();
    }
    public function mapminerUsage()
    {
        return $this->hasManyThrough(Track::class, User::class);
    }
    /**
     * [scopeStaleBranchAssignments description]
     * 
     * @param [type] $query [description]
     * @param [type] $roles [description]
     * 
     * @return [type]        [description]
     */
    public function scopeStaleBranchAssignments($query, $roles)
    {
        return $query->whereHas(
            'userdetails.roles', function ($q) use ($roles) {
                $q->whereIn('roles.id', $roles);
            }
        );
        
    }

    /**
     * [manages description]
     * 
     * @return [type] [description]
     */
    public function manages()
    {
        
        return $this->belongsToMany(Branch::class)
            ->withTimestamps()->withPivot('role_id');
    }
    /**
     * [comments description]
     * 
     * @return [type] [description]
     */
    public function comments()
    {
        
        return $this->hasMany(Comment::class);
    }
    /**
     * [managesAccount description]
     * 
     * @return [type] [description]
     */
    public function managesAccount()
    {
        
        return $this->hasMany(Company::class)->orderBy('companyname');
    }
    /**
     * [emailcampaigns description]
     * 
     * @return [type] [description]
     */
    public function emailcampaigns()
    {
        return $this->belongsToMany(Campaign::class)->withPivot('activity');
    }
    /**
     * [projects description]
     * 
     * @return [type] [description]
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class)->withPivot('status');
    }
    /**
     * Return user details of person
     * 
     * @return relation [description]
     */
    public function userdetails()
    {
          return $this->belongsTo(User::class, 'user_id', 'id');
    }
    /**
     * Author of news
     * 
     * @return relation [description]
     */
    public function authored()
    {
        
        return $this->hasMany(News::class);
    }
    /**
     * [scopeManages description]
     * 
     * @param [type] $query [description]
     * @param [type] $roles [description]
     * 
     * @return [type]        [description]
     */
    public function scopeManages($query, $roles)
    {
        return $query->wherehas(
            'userdetails.roles', function ($q) use ($roles) {

                    $q->whereIn('role_id', $roles);
            }
        );
    }
    /**
     * [scopeLeadsByType description]
     * 
     * @param [type] $query  [description]
     * @param [type] $id     [description]
     * @param [type] $status [description]
     * 
     * @return [type]         [description]
     */
    public function scopeLeadsByType($query, $id, $status)
    {
     
        return $query->belongsToMany(
            Lead::class, 'lead_person_status', 'person_id', 'related_id'
        )
            ->where('lead_source_id', '=', $id)
            ->withPivot('created_at', 'updated_at', 'status_id', 'rating')
            ->wherePivot('status_id', 2);
    }
    /**
     * Create concatenated full name
     * 
     * @return [type] [description]
     */
    public function fullName()
    {
        if (! isset($this->attributes['firstname'])) {
            return 'No longer a Mapminer User';
        }
        return addslashes($this->attributes['firstname']) . ' ' . addslashes($this->attributes['lastname']);
    }
    /**
     * Return concatenated name,
     *  
     * @return [type] [description]
     */
    public function postName()
    {

        return addslashes($this->attributes['lastname']) . ', ' . addslashes($this->attributes['firstname']);
    }
    /**
     * [distribution description]
     * 
     * @return [type] [description]
     */
    public function distribution()
    {
        return ['name'=>$this->fullName(), 'email'=>$this->userdetails->email];
    }
    /**
     * [currentleads description]
     * 
     * @return [type] [description]
     */
    public function currentleads()
    {
        return $this->belongsToMany(Lead::class, 'lead_person_status', 'person_id', 'related_id')
            
            ->whereHas(
                'leadsource', function ($q) {
                    $q->where('datefrom', '<=', date('Y-m-d'))
                        ->where('dateto', '>=', date('Y-m-d'));
                }
            )->withPivot('created_at', 'updated_at', 'status_id', 'rating');
    }
    /**
     * [leads description]
     * 
     * @return [type] [description]
     */
    public function leads()
    {
        return $this->belongsToMany(Lead::class, 'lead_person_status', 'person_id', 'related_id')
            ->withPivot('created_at', 'updated_at', 'status_id', 'rating');
    }
    /**
     * [offeredleads description]
     * 
     * @return [type] [description]
     */
    public function offeredleads()
    {
        return $this->belongsToMany(Lead::class, 'lead_person_status', 'person_id', 'related_id')
            ->wherePivot('status_id', 1)
            ->withPivot('created_at', 'updated_at', 'status_id', 'rating');
    }
    
    /**
     * [openleads description]
     * 
     * @return [type] [description]
     */
    public function openleads()
    {
        return $this->belongsToMany(Lead::class, 'lead_person_status', 'person_id', 'related_id')
            ->wherePivot('status_id', 2)
            ->withPivot('created_at', 'updated_at', 'status_id', 'rating');
    }
    /**
     * [closedleads description]
     * 
     * @return [type] [description]
     */
    public function closedleads()
    {
        return $this->belongsToMany(Lead::class, 'lead_person_status', 'person_id', 'related_id')
            ->wherePivot('status_id', 3)
            ->withPivot('created_at', 'updated_at', 'status_id', 'rating');
    }
    /**
     * [scopeLeadsWithStatus description]
     * 
     * @param [type] $query  [description]
     * @param [type] $status [description]
     * 
     * @return [type]         [description]
     */
    public function scopeLeadsWithStatus($query, $status)
    {
        
        return $query->whereHas(
            'leads', function ($q) use ($status) {
                $q->where('lead_person_status.status_id', $status);
            }
        );
    }
    /**
     * [industryfocus description]
     * 
     * @return [type] [description]
     */
    public function industryfocus()
    {
        return $this->belongsToMany(SearchFilter::class)->withTimestamps();
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
     * [personroles description]
     * 
     * @param [type] $roles [description]
     * 
     * @return [type]        [description]
     */
    public function personroles($roles)
    {


        return $this->wherehas(
            'userdetails.roles', function ($q) use ($roles) {
                    $q->whereIn('role_id', $roles);
            }
        )
        ->with('userdetails', 'userdetails.roles')
        ->orderBy('lastname')
        ->get();
    }
    /**
     * [scopeWithRoles description]
     * 
     * @param [type] $query [description]
     * @param [type] $roles [description]
     * 
     * @return [type]        [description]
     */
    public function scopeWithRoles($query, $roles)
    {

        return $query->wherehas(
            'userdetails.roles', function ($q) use ($roles) {
                    $q->whereIn('role_id', $roles);
            }
        );
    }
    /**
     * [getPersonsWithRole description]
     * 
     * @param [type] $roles [description]
     * 
     * @return [type]        [description]
     */
    public function getPersonsWithRole($roles)
    {
        return $this->select(
            \DB::raw("*, CONCAT(lastname,' ' ,firstname) AS fullname, id")
        )
            ->whereHas(
                'userdetails.roles',
                function ($q) use ($roles) {
                    $q->whereIn('role_id', $roles);
                }
            )
        ->orderBy('lastname')->get();
    }
    /**
     * [salesleads description]
     * 
     * @return [type] [description]
     */
    public function salesleads()
    {
        return $this->belongsToMany(Lead::class, 'lead_person_status', 'person_id', 'related_id')
            ->withTimestamps()
            ->withPivot('status_id', 'rating');
    }
    /**
     * [webleads description]
     * 
     * @return [type] [description]
     */
    public function webleads()
    {
        return $this->belongsToMany(WebLead::class, 'lead_person_status', 'person_id', 'related_id')
            ->withTimestamps()
            ->wherePivot('type', '=', 'web')
            ->withPivot('status_id', 'rating', 'type');
    }
    /**
     * [leadratings description]
     * 
     * @return [type] [description]
     */
    public function leadratings()
    {
        return  $this->belongsToMany(Lead::class, 'lead_person_status', 'person_id', 'related_id')
            ->withTimestamps()
            ->withPivot('status_id', 'rating')
            ->whereNotNull('rating');
    }
    /**
     * [fullAddress description]
     * 
     * @return [type] [description]
     */
    public function fullAddress()
    {
        return $this->address . ' '. $this->city . ' ' . $this->state . ' ' . $this->zip;
    }
    /**
     * [findPersonsRole description]
     * 
     * @param [type] $people [description]
     * 
     * @return [type]         [description]
     */
    public function findPersonsRole($people)
    {

        foreach ($people->userdetails->roles as $role) {             
            $result[] = $role->name;
        }

        return $result;

    }
    /**
     * [findRole description]
     * 
     * @return [type] [description]
     */
    public function findRole()
    {

        foreach ($this->userdetails->roles as $role) {
            $result[] = $role->id;
        }

        return $result;

    }

    /**
     * [activities description]
     * 
     * @return [type] [description]
     */
    public function activities()
    {
        return $this->hasMany(Activity::class, 'user_id', 'user_id');
    }
    
    /**
     * [salesLeadsByStatus description]
     * 
     * @param [type] $id [description]
     * 
     * @return [type]     [description]
     */
    public function salesLeadsByStatus($id)
    {
        $leads = $this->with('salesleads')
            ->whereHas(
                'salesleads.leadsource', function ($q) {
                    $q->where('datefrom', '<=', date('Y-m-d'))
                        ->where('dateto', '>=', date('Y-m-d'));
                }
            )
            ->find($id);

        foreach ($leads->salesleads as $lead) {
            if (! isset($statuses[$lead->pivot->status_id])) {
                $statuses[$lead->pivot->status_id]['status']=$lead->pivot->status_id;
                $statuses[$lead->pivot->status_id]['count']=0;
            }
            $statuses[$lead->pivot->status_id]['count']+=1;
        }
        return $statuses;
    }
    
    /**
     * [scopeInServiceLine description]
     * 
     * @param [type] $query        [description]
     * @param [type] $servicelines [description]
     * 
     * @return [type]               [description]
     */
    public function scopeInServiceLine($query, $servicelines)
    {
        
        return $query->whereHas(
            'userdetails.serviceline', function ($q) use ($servicelines) {
                $q->whereIn('servicelines.id', $servicelines);
            }
        );
    }
    /**
     * [ownedLeads description]
     * 
     * @return [type] [description]
     */
    public function ownedLeads()
    {
        return $this->belongsToMany(Lead::class, 'lead_person_status', 'person_id', 'related_id')
            ->withTimestamps()
            ->withPivot('status_id', 'rating')
            ->whereIn('status_id', [2,3]);
    }

    /**
     * [myOwnedLeads description]
     * 
     * @return [type] [description]
     */
    public function myOwnedLeads()
    {
        return $this->belongsToMany(Lead::class, 'lead_person_status', 'person_id', 'related_id')
            ->withTimestamps()
            ->withPivot('status_id', 'rating')
            ->whereIn('status_id', [2])
            ->where('person_id', '=', auth()->user()->person->id);
    }

    
    /**
     * [campaigns description]
     * 
     * @return [type] [description]
     */
    public function campaigns()
    {
        return $this->belongsToMany(Salesactivity::class);
    }
    /**
     * [campaignparticipants description]
     * 
     * @param [type] $vertical [description]
     * 
     * @return [type]           [description]
     */
    public function campaignparticipants($vertical)
    {
        return $this->whereHas(
            'industryfocus', function ($q) use ($vertical) {
                    $q->whereIn('search_filter_id', $vertical);
            }
        )
        ->whereHas(
            'userdetails', function ($q) {
                $q->where('confirmed', '=', 1);
            }
        )
        ->where(
            function ($q) {
                $q->whereNull('active_from')
                    ->orWhere('active_from', '<=', date('Y-m-d'));
            }
        );
    }
    /**
     * [jsonify description]
     * 
     * @param [type] $people [description]
     * 
     * @return [type]         [description]
     */
    public function jsonify($people)
    {
        $key=0;
        $salesrepmarkers= [];
        foreach ($people as $person) {
            $salesrepmarkers[$key]['id']=$person->id;
            $salesrepmarkers[$key]['lat']=$person->lat;
            $salesrepmarkers[$key]['lng']=$person->lng;
            $salesrepmarkers[$key]['name']=$person->fullName();
            $key++;
        }
      
        return collect($salesrepmarkers)->toJson();
    }
    /**
     * [updatePersonsAddress description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    public function updatePersonsAddress($data)
    {
        if (! empty($data['address'])) {
            $data = $this->getGeoCode(app('geocoder')->geocode($data['address'])->get());
            unset($data['fulladdress']);
        } else {
            $data['address']=null;
            $data['city']=null;
            $data['state']=null;
            $dta['zip']=null;
            $data['lat']=null;
            $data['lng']=null;
        }
        return $data;
    }
    /**
     * [myAddress description]
     * 
     * @return [type] [description]
     */
    public function myAddress()
    {
        if (! $this->address) {
            return config('mapminer.default_address');
        } else {
            return $this->address;
        }
    }
    /**
     * [scopePrimaryRole description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopePrimaryRole($query)
    {

        return $query->with('userdetails.roles');
                    //->userdetails;
    }
    /**
     * [getPrimaryRole description]
     * 
     * @param [type] $person [description]
     * 
     * @return [type]         [description]
     */
    public function getPrimaryRole($person)
    {

        return $person->userdetails->roles()->first()->id;
                    //->userdetails;
    }
    /**
     * [scopeSalesReps description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeSalesReps($query)
    {
        return $query->whereHas(
            'userdetails.roles', function ($q) {
                $q->whereIn('roles.id', $this->salesroles);
            }
        );
    }
    /**
     * [rankings description]
     * 
     * @return [type] [description]
     */
    public function rankings()
    {
        return $this->belongsToMany(Address::class)
            ->withPivot('ranking', 'comments')
            ->withTimeStamps();
    }
}
