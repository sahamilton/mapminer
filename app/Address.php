<?php

namespace App;
use Carbon\Carbon;

class Address extends Model
{
    use Geocode,Filters, FullTextSearch;
    public $table = 'addresses';
    public $dates = ['dateAdded'];
    public $timestamps = true;
    
    public $fillable = [
        'addressable_id',
        'addressable_type',
        'street',
        'address2',
        'city',
        'state',
        'zip',
        'lat',
        'lng',
        'position',
        'businessname',
        'company_id',
        'user_id',
        'phone',
        'lead_source_id',
        'customer_id',
        'description',
        'duns',
        'naic',
        'isCustomer', 
    ];
    
    protected $searchable = [
        'businessname',
        'street',
        'city',
        'zip',
        'state'

    ];
    protected $hidden = ['position'];

    public $requiredfields = [
            'companyname',
            'businessname',
            'address',
            'city',
            'state',
            'zip',
            'lat',
            'lng',];
    
    public $addressStatusOptions =  [
        1=>'Location data is completely inaccurate.',
        2=>'Location data is incomplete and / or not useful.',
        3=>'Location data is mostly accurate but contact data is inaccurate.',
        4=>'Location data is accurate and contact data is mostly accurate.',
        5=>'Location and contact data is very accurate'
      ];

    public $addressType = [
            'location'=>'National Account Location',
            'project'=>'Construction Project', 
            'lead'=>'Web Lead',
            'customer'=>'Customer'];
    public function getPhoneNumberAttribute()
    {
        $cleaned = preg_replace('/[^[:digit:]]/', '', $this->phone);
        if (preg_match('/(\d{3})(\d{3})(\d{4})/', $cleaned, $matches)) {
            return "({$matches[1]}) {$matches[2]}-{$matches[3]}";
        }
        return $this->phone;
        
    }
    /**
     * [lead description]
     * 
     * @return [type] [description]
     */
    public function lead()
    {
        return $this->hasOne(Lead::class, 'address_id');
    }
    /**
     * [weblead description]
     * 
     * @return [type] [description]
     */
    public function weblead()
    {
        return $this->hasOne(WebLead::class, 'address_id');
    }
    /**
     * [location description]
     * 
     * @return [type] [description]
     */
    public function location()
    {
        return $this->hasOne(Location::class, 'address_id');
    }
    /**
     * [customer description]
     * 
     * @return [type] [description]
     */
    public function customer()
    {
        return $this->hasOne(Customer::class, 'address_id');
    }
    /**
     * [campaigns description]
     * 
     * @return [type] [description]
     */
    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }

    public function currentcampaigns()
    {
        return $this->belongsToMany(Campaign::class)
            ->where('datefrom', '<=', now()->startOfDay())
            ->where('dateto', '>=', now()->endOfDay());
    }
    /**
     * [project description]
     * 
     * @return [type] [description]
     */
    public function project()
    {
        return $this->hasOne(Project::class, 'address_id');
    }
    /**
     * [watchedBy description]
     * 
     * @return [type] [description]
     */
    public function watchedBy()
    {

        return $this->belongsToMany(User::class, 'location_user', 'address_id', 'user_id')
            ->withPivot('created_at', 'updated_at');
    }

    
    /**
     * [contacts description]
     * 
     * @return [type] [description]
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'address_id', 'id');
    }

    public function primaryContact()
    {
        return $this->hasMany(Contact::class, 'address_id', 'id')
            ->where('primary', 1);
    }
    /**
     * [company description]
     * 
     * @return [type] [description]
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    /**
     * [relatedNotes description]
     * 
     * @return [type] [description]
     */
    public function relatedNotes()
    {
        return $this->hasMany(Note::class, 'related_id', 'addressable_id')
            ->with('writtenBy');
    }
    /** 
     * [state description]
     * 
     * @return [type] [description]
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'state', 'statecode');
    }

    /**
     * [orders description]
     * 
     * @return [type] [description]
     */
    public function orders()
    {
 
        return $this->hasManyThrough(
            Orders::class, AddressBranch::class, 
            'address_id', 'address_branch_id', 'id', 'id'
        );
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
        return $this->hasMany(Activity::class)->whereNull('completed');
    }
    /**
     * [lastActivity description]
     * 
     * @return [type] [description]
     */
    public function lastActivity()
    {
        return $this->belongsTo(Activity::class);
    }
    /**
     * [scopeWithLastActivityId description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeWithLastActivityId($query)
    {
         return $query
             ->select('addresses.*')
             ->selectSub('select id as last_activity_id from activities where address_id = addresses.id and completed=1 order by activities.activity_date desc limit 1', 'last_activity_id');
       
    }
    public function scopeDateAdded($query)
    {
         return $query
             
             ->selectSub('select created_at as dateAdded from address_branch where address_id = addresses.id and status_id=2 order by address_branch.created_at desc limit 1', 'dateAdded');
       
    }
    
    /**
     * [currentlyActive description]
     * 
     * @return [type] [description]
    */
    public function currentlyActive()
    {
        return $this->hasMany(Activity::class)
            ->where('completed', 1)
            ->where('activity_date', '>', now()->subMonth())
            ->latest()
            ->limit(1);
        
        
    } 
    /**
     * [fullAddress description]
     * 
     * @return [type] [description]
     */
    public function fullAddress()
    {
        return $this->street ." "
        . $this->address2." ".$this->city." ".$this->state." ".$this->zip;
    }
    /**
     * [industryVertical description]
     * 
     * @return [type] [description]
     */
    public function industryVertical()
    {
        return $this->hasOne(SearchFilter::class, 'id', 'vertical');
    }
    /**
     * [scopeFiltered description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeFiltered($query)
    {
        
        if ((! $keys= $this->getSearchKeys(['companies'], ['vertical'])) 
            && session('geo.addressType')
        ) {
            return $query->whereIn('addressable_type', session('geo.addressType'));
        } elseif (session('geo.addressType')) {
            return $query->whereIn('vertical', $keys)
                ->whereIn('addressable_type', session('geo.addressType'));
        } else {
            return $query;
        }
    }
    public function assignedToMyBranch()
    {
        return $this->assignedToBranch->whereIn('branches.id', auth()->user()->person->getMyBranches());
      
    }
    /**
     * [assignedToBranch description]
     * 
     * @return [type] [description]
     */
    public function assignedToBranch()
    {
        return $this->belongsToMany(
            Branch::class, 'address_branch', 'address_id', 'branch_id'
        )
            ->withPivot('id', 'rating', 'person_id', 'status_id', 'comments')
            ->withTimeStamps();
    }
    /**
     * [claimedByBranch description]
     * 
     * @return [type] [description]
     */
    public function claimedByBranch()
    {
        return $this->belongsToMany(
            Branch::class, 'address_branch', 'address_id', 'branch_id'
        )
            ->withPivot('rating', 'person_id', 'status_id', 'comments')
            ->withTimeStamps()
            ->whereIn('status_id', [2]);
    }
    /**
     * [closed description]
     * 
     * @return [type] [description]
     */
    public function closed()
    {
        return $this->belongsToMany(
            Branch::class, 'address_branch', 'address_id', 'branch_id'
        )
            ->withPivot('rating', 'person_id', 'status_id', 'comments')
            ->withTimeStamps()->whereIn('status_id', [3]);
    }
    /**
     * [assignedToPerson description]
     * 
     * @return [type] [description]
     */
    public function assignedToPerson()
    {
        return $this->belongsToMany(
            Person::class, 'address_person', 'address_id', 'person_id'
        )
            ->withPivot('rating', 'person_id', 'status_id', 'comments')
            ->withTimeStamps();
    }
    /**
     * [scopeType description]
     * 
     * @param [type] $query [description]
     * @param [type] $type  [description]
     * 
     * @return [type]        [description]
     */
    public function scopeType($query, $type)
    {
        return $query->where('addressable_type', '=', $type);
    }
   
    /**
     * [opportunities description]
     * 
     * @return [type] [description]
     */
    public function opportunities()
    {
 
        return $this->hasMany(Opportunity::class, 'address_id', 'id');
    }

    public function scopeOrderByColumn($query, $field, $dir) 
    {
        
        switch($field) {
        case 'lastActivity': return $query->orderByLastActivityDate();
       
        default: return $query->orderBy($field, $dir);
        }


    }
    
    public function scopeOrderByLastActivityDate($query, $dir='asc')
    {
        $query->orderBy(
            Activity::select('activity_date')
                ->whereColumn('activities.address_id', 'addresses.id')
                ->whereCompleted(1)
                ->latest()
                ->take(1)->get(),
            $dir
        );

    }
    
    /**
     * Return addresses assigned to users branch(es)
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeMyLeads($query)
    {
        return $query->whereHas(
            'assignedToBranch', function ($q) { 
                $q->whereIn('branches.id', auth()->user()->person->getMyBranches()); 
            }
        );
    }
    /**
     * [opportunities description]
     * 
     * @return [type] [description]
     */
    public function openOpportunities()
    {
 
        return $this->hasManyThrough(
            Opportunity::class, AddressBranch::class, 
            'address_id', 'address_branch_id', 'id', 'id'
        )->where('closed', 0);
    }

    


    /**
     * [servicedBy description]
     * 
     * @return [type] [description]
     */
    public function servicedBy()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    /**
     * [leadsource description]
     * 
     * @return [type] [description]
     */
    public function leadsource()
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id', 'id');
    }
    /**
     * [ranking description]
     * 
     * @return [type] [description]
     */
    public function ranking()
    {
        return $this->belongsToMany(Person::class)
            ->withPivot('ranking', 'comments', 'status_id')
            ->withTimeStamps();
    }
    /**
     * [currentRating description]
     * 
     * @return [type] [description]
     */
    public function currentRating()
    {
        return $this->ranking()->average('ranking');
    }
    /**
     * [getMyRanking description]
     * 
     * @param [type] $rankings [description]
     * 
     * @return [type]           [description]
     */
    public function getMyRanking($rankings)
    {
       
        foreach ($rankings as $ranking) {
            if ($ranking->pivot->person_id == auth()->user()->person->id) {
                return $ranking->pivot;
            }
        }
        return false;
    }
    /**
     * [createdBy description]
     * 
     * @return [type] [description]
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->with('person');
    }
    /**
     * [getExtraFields description]
     * 
     * @param [type] $type [description]
     * 
     * @return [type]       [description]
     */
    public function getExtraFields($type)
    {
        $fields = \App\MapFields::whereType($type)
            ->whereDestination('extra')
            ->whereNotNull('fieldname')
            ->pluck('fieldname')->toArray();
        return array_unique($fields);
    }
    /**
     * [duplicates description]
     * 
     * @return [type] [description]
     */
    public function duplicates()
    {
        return $this->hasMany(Address::class, 'position', 'position');
    }
    
    /**
     * [scopeDuplicate description]
     * 
     * @param [type] $query     [description]
     * @param [type] $longitude [description]
     * @param [type] $latitude  [description]
     * 
     * @return [type]            [description]
     */
    public function scopeDuplicateDistance($query, $longitude, $latitude, $commpany_id= null)
    {
        $close_in_metres = 5;
  
        $query = $query->whereRaw(
            "ST_Distance_Sphere(
                point(lng, lat),
                point(". $longitude . ", " . $latitude .")
            )  < ".$close_in_metres 
        );
        if (isset($company_id)) {
            $query = $query->where('company_id', $company_id);
        }
        return $query;
    }
    /**
     * [addressType description]
     * 
     * @return [type] [description]
     */
    public function addressType()
    {
        if ($this->has('opportunities')) {
            return 'opportunity';
        } elseif ($this->has('assignedToBranch')) {
            return 'lead';

        } else {
            return 'prospect';
        }
    }

    public function scopeSearch($query, $search)
    { 
        return  $query->where('businessname', 'like', "%{$search}%")
            ->Orwhere('street', 'like', "%{$search}%")
            ->Orwhere('city', 'like', "%{$search}%");
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
            ->whereIn('lead_source_id', $leadsource)
            ->whereHas(
                'branchleads', function ($qb) use ($branches) {
                        $qb->whereIn('branch_id', $branches);
                }
            )
            ->where('created_at', '<=', $before)
            ->doesntHave('activities')
            ->doesntHave('opportunities')
            ->get();
    }

    public function scopeUnassigned($query, $period=null)
    {
        return $query->whereDoesntHave('assignedToBranch')
            ->when(
                $period, function ($q1) use ($period) {
                    $q1->orWhereHas(
                        'assignedToBranch', function ($q1) {
                            $q1->where('address_branch.created_at', '>', $this->period['to']);
                        }
                    );
                }
            );
    }

    public function scopeTop25($query, $period=null)
    {
        return $query->whereHas(
            'assignedToBranch', function ($q1) {
                $q1->where('top50',  1);
            }
        )->when(
            $period,  function ($q) use ($period) {
                $q->where('address_branch.created_at', '>=', $this->period['to']);
            }
        );
         
    }

    public function scopeNewLeads($query, $period)
    {
        return $query->whereHas(
            'assignedToBranch', function ($q1) use ($period) {
                $q1->whereBetween('address_branch.created_at',  [$period['from'], $period['to']]);
            }
        );
         
    }
    /**
     * [scopePeriodActivities description]
     * 
     * @param [type] $query  [description]
     * @param Array  $period Period[from], Period[to]
     * 
     * @return [type]         [description]
     */
    public function scopePeriodActivities($query,Array $period)
    {
        return $query->whereBetween(
            'addresses.created', [$period['from'], $period['to']]
        );
    }
    public function scopeSuppliedLeads($query, $period)
    {
        return $query->whereHas(
            'assignedToBranch', function ($q) use ($period) {
                $q->where('address_branch.created_at', '<=', $period['to']);
            }
        );
    }

    public function scopeOpenLeads($query, $period)
    {
        return $query->whereHas(
            'assignedToBranch', function ($q1) use ($period) {
                $q1->where('status_id', 2)
                    ->where('address_branch.created_at', '>=', $period['to']);
            }
        );
    }

    public function scopeOfferedLeads($query, $period)
    {
        return $query->whereHas(
            'assignedToBranch', function ($q) use ($period) {
                $q->where('status_id', 1)
                    ->whereBetween('address_branch.created_at', [$period['from'], $period['to']]);
            }
        );
    }

    public function scopeWorkedLeads($query, $period)
    {
        return $query->whereHas(
            'assignedToBranch', function ($q) use ($period) {
                $q->where('status_id', 2)
                    ->where('address_branch.created_at', '<=', $period['to']);
            }
        );
    }

    public function scopeActiveLeads($query, $period)
    {
        return $query->whereHas(
            'activities', function ($q) use ($period) {
                $q->whereBetween('activity_date', [$period['from'], $period['to']])
                    ->where('completed', 1);
            }
        );
    }

    public function scopeTouchedLeads($query, $period)
    {
        return $query->whereHas(
            'assignedToBranch', function ($q) use ($period) {
                $q->where('status_id', '>', 1)
                    ->whereBetween('address_branch.created_at', [$period['from'], $period['to']]);
            }
        )
        ->whereHas(
            'activities', function ($q) use ($period) {
                $q->whereBetween('activity_date', [$period['from'], $period['to']]);
            }
        );
    }

    public function scopeRejectedLeads($query, $period)
    {
        return $query->whereHas(
            'assignedToBranch', function ($q) use ($period) {
                $q->where('status_id', 4)
                    ->whereBetween('address_branch.created_at', [$period['from'], $period['to']])
                    ->whereDoesntHave('opportunities')
                    ->whereDoesntHave('activities');
            }
        );
    }
}
