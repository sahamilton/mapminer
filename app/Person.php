<?php
namespace App;

use App\Presenters\LocationPresenter;
use App\Http\Requests\UserFormRequest;
use Illuminate\Database\Eloquent\SoftDeletes;
//use McCool\LaravelAutoPresenter\HasPresenter;
use OwenIt\Auditing\Contracts\Auditable; 

class Person extends NodeModel implements Auditable
{
    use Geocode, Filters, PeriodSelector, SoftDeletes, FullTextSearch, \OwenIt\Auditing\Auditable;
    public $salesroles = ['5','9', '17'];
    public $branchroles = ['9', '17'];
       
    
    protected $table ='persons';
    protected $hidden = ['created_at','updated_at','deleted_at','position'];

    protected $parentColumnName = 'reports_to';

    protected $leftColumnName = 'lft';
   
    protected $rightColumnName = 'rgt';
    
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
                        'position',
                        'country'];
    protected $searchable = [
        'firstname',
        'lastname'
    ];
    public function getCompleteNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }
    public function getPostNameAttribute()
    {
        return $this->lastname . ', ' . $this->firstname;
    }
    public function getPhoneNumberAttribute()
    {
        $cleaned = preg_replace('/[^[:digit:]]/', '', $this->phone);
        if (preg_match('/(\d{3})(\d{3})(\d{4})/', $cleaned, $matches)) {
            return "({$matches[1]}) {$matches[2]}-{$matches[3]}";
        }
        return $this->phone;
        
    }
    /**
     * [getParentIdName description]
     * 
     * @return [type] [description]
     */
    public function getParentIdName()
    {
        return 'reports_to';
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }
    /**
     * [reportsTo description]
     * 
     * @return [type] [description]
     */
    public function reportsTo()
    {
        return $this->belongsTo(Person::class, 'reports_to', 'id')
            ->withDefault();
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

    public function fullEmail()
    {
        return ['name'=>$this->fullName(), 
            'email'=>$this->userdetails->email];
    }
    /**
     * [scopeManagers description]
     * 
     * @param [type] $query [description]
     * @param [type] $roles [description]
     * 
     * @return [type]        [description]
     */
    public function scopeManagers(\Illuminate\Database\Eloquent\Builder $query, Array $roles=null, Array $servicelines=null)
    {
        
        if (! $roles) {
            $roles = [14,9,6,7,3];
        }
        
        return $this->wherehas(
            'userdetails.roles', function ($q) use ($roles) {

                    $q->whereIn('role_id', $roles);
            }
        )->when(
            $servicelines, function ($q) use ($servicelines) {
                $q->whereHas(
                    'userdetails.serviceline', function ($q) use ($servicelines) {
                        $q->whereIn('serviceline.id', $servicelines);
                    }  
                );
            }
        );
    }
    /**
     * [managers description]
     * 
     * @param Array|null $roles        [description]
     * @param Array|null $servicelines [description]
     * 
     * @return [type]                   [description]
     */
    public function managers(Array $roles=null, Array $servicelines=null)
    {
        
        // this sucks .... why are these hard coded?
        if (! $roles) {
            $roles = [14,3,6,7,9];
        }
        
        return $this->wherehas(
            'userdetails.roles', function ($q) use ($roles) {

                    $q->whereIn('role_id', $roles);
            }
        )
        ->when(
            $servicelines, function ($q) {
                $q->whereHas(
                    'userdetails.serviceline', function ($q) {
                        $q->whereIn('serviceline_id', array_keys($this->userdetails->currentServiceLineIds()));
                    }
                );
            }
        )
        ->orderBy('lastname')
        ->orderBy('firstname')
        ->get();
    }
    private function _getAllBranches(Array $servicelines=null) :array
    {   
        return Branch::when(
            $servicelines, function ($q) {
                $q->whereHas(
                    'servicelines', function ($q) {
                            $q->whereIn('servicelines.id', $servicelines);
                    }
                );
              
            }
        )->orderBy('id')
        ->pluck('id')
        ->toArray();
    }
    /**
     * GetMyBranches finds branch managers in reporting
     * strucuture and returns their branches as array]
     * 
     * @param Array|null $servicelines [description]
     * 
     * @return array                  [description]
     */
    public function getMyBranches(Array $servicelines=null) :array
    {
        

        if ($this->userdetails->hasRole(['admin', 'sales_operations'])) {
            return $this->_getAllBranches($servicelines);
        }        
        $branchMgrs = $this->descendantsAndSelf()->withRoles([3,9, 17]);
        
        $branches = $branchMgrs->with('branchesServiced')
            ->when(
                $servicelines, function ($q) use ($servicelines) {
                    $q->whereHas(
                        'userdetails.serviceline', function ($q1) use ($servicelines) {
                            $q1->whereIn('servicelines.id', $servicelines);
                        }
                    );
                }
            )

            ->get()
            ->map(
                function ($branch) { 
                    return $branch->branchesServiced->pluck('id')->toArray();
                }
            )->flatten()
            ->unique()
            ->toArray();
        asort($branches);
        return $branches;
           
            
    }
    /**
     * [getMyAccounts description]
     * 
     * @return [type] [description]
     */
    public function getMyAccounts()
    {
        if ($this->userdetails->hasRole(['sales_operations', 'admin'])) { 
            return Company::all()->pluck('id')->toArray();
        }
        return $this->managesAccount->pluck('id')->toArray();
        


    }
    /**
     * [branchesManaged description]
     * 
     * @return sorted array of all branches servicedS
     */
    public function branchesManaged()
    {
        $team = $this->descendantsAndSelf()
            ->withRoles([9, 17])
                
            ->with('branchesServiced.manager')->get();
     
        return $team->map(
            function ($people) {
                return $people->branchesServiced;
            }
        )->flatten()->unique()->sortBy('id');
    }
    /**
     * [scopeLastLogin description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeLastLogin($query, array $period=null)
    {

        return $query
            ->when(
                isset($period), function ($q) use ($period) {
                    $q->whereHas(
                        'userdetails', function ($q) use ($period) {
                            $q->lastLogin($period);
                        }
                    );
                }
            )->withMax('userdetails', 'lastlogin');
    }

    /**
     * [myBranches description]
     * 
     * @param Person|null $person       [description]
     * @param Array|null  $servicelines [description]
     * 
     * @return [type]                    [description]
     */
    public function myBranches(Person $person=null, Array $servicelines=null)
    {
           
        if (! $person ) {
            $user = $this->_getPersonFromAuth();
           
            $person = $user->person;
            if ($user->hasRole(['admin', 'sales_operations', 'serviceline_manager'])) {
                return $this->_getBranchesInServicelines($user->serviceline);
            } else {
                return $this->_getBranchesFromTeam($person); 
            }
        } else {
            $person->load('userdetails');
            if ($person->userdetails->hasRole(['admin', 'sales_operations'])) {
                return $this->_getBranchesInServicelines($person->userdetails->serviceline);
            }
            return $this->_getBranchesFromTeam($person);
        }
    }

    public function logins()
    {
        return $this->hasMany(Track::class, 'user_id', 'user_id');
    }  
    /**
     * [_getBranchesFromTeam description]
     * 
     * @param Person $person [description]
     * 
     * @return [type]         [description]
     */
    private function _getBranchesFromTeam(Person $person)
    {
        $mybranchteam = $this->myTeam($person)
            ->whereHas(
                'userdetails.roles', function ($q) {
                    $q->whereIn('role_id', $this->branchroles);
                }
            )
            ->with('branchesServiced')
            ->get();

        $branches =  $mybranchteam->map(
            function ($team) {
                return $team->branchesServiced;
            }
        );
        return $branches->flatten()->unique()->sortBy('branchname')->pluck('branchname', 'id')->toArray();
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
     * [_getPersonFromAuth description]
     * 
     * @return [type] [description]
     */
    private function _getPersonFromAuth()
    {
        
        return User::with('roles', 'person', 'serviceline')
            ->findOrFail(auth()->user()->id);
    }
    /**
     * [_getBranchesInServicelines description]
     * 
     * @param [type] $servicelines [description]
     * 
     * @return [type]               [description]
     */
    private function _getBranchesInServicelines($servicelines)
    {
        return Branch::whereHas(
            'servicelines', function ($q) use ($servicelines) {
                $q->whereIn('id', $servicelines->pluck('id')->toArray());
            }
        )
        ->orderBy('branches.id')
        ->pluck('branchname', 'id')
        ->toArray();
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
    /**
     * [ownBranches description]
     * 
     * @return [type] [description]
     */
    public function ownBranches()
    {
        return $this->descendantsAndSelf()
            ->whereHas(
                'userdetails.roles', function ($q) {
                    $q->whereIn('role_id', [9]);
                }
            )->with('branchesServiced');
    }
    /**
     * [team description]
     * 
     * @param array|null $roles [description]
     * 
     * @return [type]            [description]
     */
    public function team(array $roles=null)
    {
        return $this->descendantsAndSelf()
            ->when(
                $roles, function ($q) use ($roles) {
                    $q->whereHas(
                        'userdetails.roles', function ($q) use ($roles) {

                            $q->whereIn('role_id', $roles);
                        }
                    );
                }
            )
            ->with('branchesServiced');
    }
    /**
     * [scopeMyImmediateReports description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeMyImmediateReports($query)
    {
        return $query->descendantsAndSelf($this->id)->limitDepth(1);
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
    /**
     * [mapminerUsage description]
     * 
     * @return [type] [description]
     */
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
            ->where('branch_person.role_id', 9)
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
        
        return $this->hasMany(Company::class)
            ->orderBy('companyname');
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
     * [scopeSummaryActivitiesByManager description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeSummaryActivitiesByManager($query, $period)
    {
        $this->period = $period;
        
        return $query->leftJoin(
            'activities',
            function ($join) {
                $join->on(
                    'activities.user',
                    function ($q) {
                        $q->whereIn(
                            'activities.user_id', function ($q) {
                                $q->select('user_id')
                                    ->from('persons');
                            }, 
                            'reports'
                        )

                        ->where('reports.lft', '>=', 'persons.lft')
                        ->where('reports.rgt', '<=', 'persons.rgt');
                    }
                );

            }
        )
        ->selectRaw('COUNT(CASE when activitytype_id = 4  then 1 end) as sales_appointment')
        ->selectRaw('COUNT(CASE when activitytype_id = 5  then 1 end) as stop_by')
        ->selectRaw('COUNT(CASE when activitytype_id = 7  then 1 end) as proposal')
        ->selectRaw('COUNT(CASE when activitytype_id = 10  then 1 end) as site_visit')
        ->selectRaw('COUNT(CASE when activitytype_id = 11  then 1 end) as log_a_call')
        ->selectRaw('COUNT(CASE when activitytype_id = 131  then 1 end) as site_visit')
        ->selectRaw('COUNT(CASE when activitytype_id = 14  then 1 end) as in_person')
        ->selectRaw('COUNT(*) as all_activities')
        ->whereBetween('activities.activity_date', [$this->period['from'], $this->period['to']])
        ->whereCompleted(1);
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
        if (! isset($this->attributes['firstname'])) {
            return 'No longer a Mapminer User';
        }
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
    public function leads()
    {
        return $this->hasMany(Address::class, 'user_id', 'user_id');
    }
    /**
     * [leads description]
     * 
     * @return [type] [description]
    
    public function leads()
    {
        return $this->belongsToMany(Address::class, 'address_person', 'person_id', 'address_id')
            ->withPivot('created_at', 'updated_at', 'status_id', 'rating');
    } */
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
     * [activities description]
     * 
     * @return [type] [description]
     */
    public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'user_id', 'user_id');
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
     * This should be in a controller!
     * @param UserFormRequest $request [description]
     * 
     * @return [type]                   [description]
     */
    public function updatePersonsAddress(UserFormRequest $request)
    {
        if (request()->filled('address')) {
            $data = $this->getGeoCode(app('geocoder')->geocode(request('address'))->get());
            
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
     * [primaryRole description]
     * 
     * @return [type] [description]
     */
    public function primaryRole()
    {
        return $this->userdetails()->roles()->first();
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
     * [scopeSearch description]
     * 
     * @param [type] $query  [description]
     * @param [type] $search [description]
     * 
     * @return [type]         [description]
     */
    public function scopeSearch($query, $search)
    {
        return  $query->where('firstname', 'like', "%{$search}%")
            ->orWhereRaw("concat_ws(' ', firstname, lastname) like ?", "%{$search}%")
            ->Orwhere('lastname', 'like', "%{$search}%");
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
    /**
     * [scopeWithPrimaryRole description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeWithPrimaryRole($query)
    {
        return $query->userdetails->roles->first();
    }
    /**
     * [getCapoDiCapo id the top of the sales org
     * refactor to programmatically get topdog.
     * 
     * @return Person topDog
     */
    public function getCapoDiCapo()
    {

        return $this->findOrFail(config('mapminer.topdog'));
    }
    /**
     * [inMyTeam description]
     * 
     * @param Person $person [description]
     * 
     * @return [type]         [description]
     */
    public function inMyTeam(Person $person)
    {
   
        if (auth()->user()->hasRole('admin')) {
            return true;
        } elseif (auth()->user()->hasRole('serviceline_manager')) {
            return $this->inMyServiceLine($person);
        } else {
            return $person->isDescendantOf(auth()->user()->person);
        }
    }

    private function inMyServiceLine(Person $person)
    {
        $myServiceLines = auth()->user()->serviceline->pluck('id')->toArray();
        $personsServicelines = $person->userdetails->serviceline->pluck('id')->toArray();
        if (array_intersect($myServiceLines, $personsServicelines)) {
            return true;
        }
        return false;
    }
    /**
     * [inMyAccounts description]
     * 
     * @param Company $company [description]
     * 
     * @return [type]           [description]
     */
    public function inMyAccounts(Company $company)
    {
        if (auth()->user()->hasRole('admin')) {
            return true;
        }
        return auth()->user()->person->managesAccount->contains('id', $company->id);

    }
    /**
     * [scopeSummaryActivities description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */

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
                                'activity_date', [$this->period['from'],$this->period['to']]
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
     * [scopeSummaryOpportunities description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeSummaryOpportunities($query, $period)
    {
        
        $this->period = $period;

        $query->selectRaw('concat_ws(" ",persons.firstname, persons.lastname) as manager')
            ->join(
                'persons as reports', function ($join) {
                    $join->on('reports.lft', '>=', 'persons.lft')
                        ->on('reports.rgt', '<=', 'persons.rgt')
                        ->join('activities', 'reports.user_id', '=', 'activities.user_id');
                }
            )
        
        ->where('completed', 1)
        ->whereBetween('activity_date', [$this->period['from'], $this->period['to']])
        ->selectRaw('concat_ws(" ",persons.firstname, persons.lastname) as manager')
        ->selectRaw('COUNT( CASE WHEN activitytype_id = 4 THEN 1  END) AS sales_appointment')
        ->selectRaw('COUNT(CASE WHEN activitytype_id = 5 THEN 1 END) AS stop_by')
        ->selectRaw('COUNT(CASE WHEN activitytype_id = 7 THEN 1 END) AS proposal')
        ->selectRaw('COUNT(CASE WHEN activitytype_id = 10 THEN 1 END ) AS site_visit')
        ->selectRaw('COUNT(CASE WHEN activitytype_id = 13 THEN 1 END) AS log_a_call')
        ->selectRaw('COUNT(CASE WHEN activitytype_id = 14 THEN 1 END) AS in_person')
        ->selectRaw('COUNT(*) AS all_activities')
        ->groupBy('manager');
        
    }
}
