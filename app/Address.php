<?php

namespace App;

class Address extends Model
{
    use Geocode,Filters, FullTextSearch;
    public $table = 'addresses';

    public $timestamps = true;
    
    public $fillable = ['addressable_id','addressable_type','street','address2','city','state','zip','lat','businessname','lng','company_id','user_id','phone','position','lead_source_id','description'];
    
    protected $searchable = [
        'businessname',
        'street',
        'city',
        'state'

    ];
    protected $hidden = ['position'];
    public $requiredfields = ['companyname',
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
    public $addressType = ['location'=>'National Account Location','project'=>'Construction Project', 'lead'=>'Web Lead','customer'=>'Customer'];
    
    public function lead()
    {
        return $this->hasOne(Lead::class, 'address_id');
    }

    public function weblead()
    {
        return $this->hasOne(WebLead::class, 'address_id');
    }
    public function location()
    {
        return $this->hasOne(Location::class, 'address_id');
    }
    public function customer()
    {
        return $this->hasOne(Customer::class, 'address_id');
    }
    public function project()
    {
        return $this->hasOne(Project::class, 'address_id');
    }
    public function watchedBy()
    {

        return $this->belongsToMany(User::class, 'location_user', 'address_id', 'user_id')->withPivot('created_at', 'updated_at');
    }
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'address_id', 'id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function relatedNotes()
    {
           return $this->hasMany(Note::class, 'related_id', 'addressable_id')
        ->with('writtenBy');
    }
    
    public function state()
    {
        return $this->belongsTo(State::class,'state','statecode');
    }


    public function orders()
    {
 
        return $this->hasManyThrough(Orders::class, AddressBranch::class, 'address_id', 'address_branch_id', 'id', 'id');
    }
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
    public function fullAddress()
    {
        return $this->street." ". $this->address2." ".$this->city." ".$this->state." ".$this->zip;
    }
    public function industryVertical()
    {
        return $this->hasOne(SearchFilter::class, 'id', 'vertical');
    }

    public function scopeFiltered($query)
    {
        
        if ((! $keys= $this->getSearchKeys(['companies'], ['vertical'])) && session('geo.addressType')) {
            return $query->whereIn('addressable_type', session('geo.addressType'));
        } elseif (session('geo.addressType')) {
            return $query->whereIn('vertical', $keys)->whereIn('addressable_type', session('geo.addressType'));
        } else {
            return $query;
        }
    }
    
    public function assignedToBranch()
    {
        return $this->belongsToMany(Branch::class, 'address_branch', 'address_id', 'branch_id')
        ->withPivot('rating', 'person_id', 'status_id', 'comments')->withTimeStamps();
    }
    public function claimedByBranch()
    {
        return $this->belongsToMany(Branch::class, 'address_branch', 'address_id', 'branch_id')
        ->withPivot('rating', 'person_id', 'status_id', 'comments')->withTimeStamps()->whereIn('status_id', [2]);
    }
    public function closed()
    {
        return $this->belongsToMany(Branch::class, 'address_branch', 'address_id', 'branch_id')
        ->withPivot('rating', 'person_id', 'status_id', 'comments')->withTimeStamps()->whereIn('status_id', [3]);
    }

    public function assignedToPerson()
    {
        return $this->belongsToMany(Person::class, 'address_branch', 'address_id', 'person_id')
        ->withPivot('rating', 'branch_id', 'status_id', 'comments')->withTimeStamps();
    }
    public function scopeType($query, $type)
    {
        return $query->where('addressable_type', '=', $type);
    }
   /* public function opportunities(){
        return $this->belongsTo(Opportunity::class,'id','address_id');
    }*/

    public function opportunities()
    {
 
        return $this->hasManyThrough(Opportunity::class, AddressBranch::class, 'address_id', 'address_branch_id', 'id', 'id');
    }

    public function servicedBy()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function leadsource()
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id', 'id');
    }

    public function ranking()
    {
        return $this->belongsToMany(Person::class)->withPivot('ranking', 'comments', 'status_id')->withTimeStamps();
    }

    public function currentRating()
    {
        return $this->ranking()->average('ranking');
    }

    public function getMyRanking($rankings)
    {
       
        foreach ($rankings as $ranking) {
            if ($ranking->pivot->person_id == auth()->user()->person->id) {
                return $ranking->pivot;
            }
        }
        return false;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->with('person');
    }

    public function getExtraFields($type)
    {
        $fields = \App\MapFields::whereType($type)
                      ->whereDestination('extra')
                      ->whereNotNull('fieldname')
                      ->pluck('fieldname')->toArray();
        return array_unique($fields);
    }
}
