<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LeadSource extends Model
{
   
    public $table='leadsources';
    public $dates = ['created_at','updated_at','datefrom','dateto'];
    public $fillable = ['source','description','reference','datefrom','dateto','user_id','filename','type'];
    const TYPES = ['web'=>'Web', 'purchased'=>'Purchased', 'mylead' =>'branch','campaign'=>'Campaign'];

    /**
     * [verticals description]
     * 
     * @return [type] [description]
     */
    public function verticals()
    {
        return $this->belongsToMany(SearchFilter::class, 'leadsource_searchfilter', 'leadsource_id', 'searchfilter_id');
    }
    /**
     * [leads description]
     * 
     * @return [type] [description]
     */
    public function leads()
    {
        return $this->hasMany(Address::class, 'lead_source_id');
    }
    /**
     * [scopeSummary description]
     * 
     * @param Builder $query [description]
     * 
     * @return Builder        [description]
     */
    public function scopeSummary($query)
    {

        $query->withCount(
            [
              'leads', 
              'leads as unassigned'=>function ($q) {
                  $q->doesntHave('assignedToBranch');
              },
              'leads as assigned'=>function ($q) {
                  $q->has('assignedToBranch');
              },

            ]
        );


    }
    /**
     * [assigned description]
     * 
     * @return [type] [description]
     */
    public function assigned()
    {

        return $this->whereHas(
            'leads', function ($q) {
                $q->has('assignedToBranch');
            }
        );

     
    }
    /**
     * [branchleads description]
     * 
     * @return [type] [description]
     */
    public function branchleads()
    {

        return $this->hasManyThrough(AddressBranch::class, Address::class, 'lead_source_id', 'address_id');
    }
    /**
     * [unassigned description]
     * 
     * @return [type] [description]
     */
    public function unassigned()
    {

        return $this->whereHas(
            'leads', function ($q) {
                $q->doesntHave('assignedToBranch');
            }
        );
    }
    /**
     * [addresses description]
     * 
     * @return [type] [description]
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'lead_source_id', 'id');
    }
    /**
     * [serviceline description]
     * 
     * @return [type] [description]
     */
    public function servicelines()
    {
      
      return $this->belongsToMany(Serviceline::class);

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
     * [author description]
     * 
     * @return [type] [description]
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->with('person');
    }
    /**
     * [salesteam description] obsolete
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function salesteam($id)
    {
        $query ="SELECT persons.id as id,concat_ws(' ',`firstname`,`lastname`) as `name`,`address_person`.`status_id`, count(*) as count
              FROM `address_person` ,addresses,persons
              where address_id = addresses.id 
              and person_id = persons.id
              and addresses.lead_source_id = ". $id . "
              group by name,id,address_person.status_id
              order by persons.id,address_person.status_id";

        return \DB::select($query);
    }
    /**
     * [branches description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function branches($id)
    {

        return \App\AddressBranch::whereHas(
            'address', function ($q) use ($id) {
                $q->where('lead_source_id', '=', $id);
            }
        )
        ->select('branch_id', DB::raw('count(*) as leads'))
             ->groupBy('branch_id')->with('branch')->get();
   
    }
    /**
     * [assignedTo description] obsolete
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function assignedTo($id = null)
    {
        $leads = $this->with('leads')->findOrFail($id);

        $salesreps = [];
        foreach ($leads as $lead) {
            $reps = $lead->salesteam->pluck('id')->toArray();
            $salesreps = array_unique(array_merge($reps, $salesreps));
        }
        return $salesreps;
    }
    /**
     * [unassignedLeads description]
     * @return [type] [description]
     */
    public function unassignedLeads()
    {
        return $this->hasMany(Lead::class, 'lead_source_id')->doesntHave('salesteam');
    }
    /**
     * [closedLeads description]
     * 
     * @return [type] [description]
     */
    public function closedLeads()
    {
        return $this->hasMany(Lead::class, 'lead_source_id')->has('closedLead');
    }
    /**
     * [assignedLeads description]
     * 
     * @return [type] [description]
     */
    public function assignedLeads()
    {
            return $this->hasMany(Lead::class, 'lead_source_id')->has('salesteam');
    }
    /**
     * [leadStatusSummary description]
     * 
     * @return [type] [description]
     */
    public function leadStatusSummary()
    {

        return $this->withCount('addresses');

    
    }
    public function scopeActive()
    {
        return $this->where('datefrom', '<=', now()->startOfDay())
            ->where('dateto', '>=', now()->endOfDay());
    }
    /**
     * [leadRepStatusSummary description]
     * 
     * @param [type] $id [description]
     * 
     * @return [type]     [description]
     */
    public function leadRepStatusSummary($id)
    {
        $query ="select persons.id, 
              persons.firstname,
              persons.lastname, 
              count(leads.id) as leadcount, 
              avg(lead_person_status.rating) as rating, 
              lead_person_status.status_id as status 
              from persons,
              leads,
              lead_person_status 
              where persons.id = lead_person_status.person_id 
              and lead_person_status.related_id = leads.id 
              and leads.lead_source_id = ".$id." 
              group by persons.id,status";

        return \DB::select($query);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('source', 'like', "%{$search}%");
    }
}
