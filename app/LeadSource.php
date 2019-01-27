<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LeadSource extends Model
{	
	public $table='leadsources';
	public $dates = ['created_at','updated_at','datefrom','dateto'];
	public $fillable = ['source','description','reference','datefrom','dateto','user_id','filename'];


    public function verticals (){
        return $this->belongsToMany(SearchFilter::class,'leadsource_searchfilter','leadsource_id','searchfilter_id');
    }

    public function leads(){
    	return $this->hasMany(Address::class, 'lead_source_id');
    }

    
    public function assigned (){

      return $this->whereHas('addresses',function ($q){
         $q->has('assignedToBranch');
      })->with('addresses');

     /* return $this->selectRaw('`leadsources`.*, count(`address`.`id`) as assigned') 
          ->join('leads','leadsources.id','=','leads.lead_source_id')
          ->join('lead_person_status','leads.id','=','lead_person_status.related_id')
          ->groupBy('leadsources.id');
*/
    

    }
    public function unassigned (){

      return $this->whereHas('addresses',function ($q){
        $q->doesntHave('assignedToBranch');
      });
    } 
    public function addresses(){
      return $this->hasMany(Address::class,'lead_source_id','id');
    }
    

    public function author(){
    	return $this->belongsTo(User::class, 'user_id','id')->with('person');
       
    }

    public function salesteam($id){
      $query ="SELECT persons.id as id,concat_ws(' ',`firstname`,`lastname`) as `name`,`lead_person_status`.`status_id`, count(*) as count
      FROM `lead_person_status` ,addresses,persons
      where related_id = addresses.addressable_id 
      and person_id = persons.id
      and addresses.lead_source_id = ". $id . "
      group by name,id,lead_person_status.status_id
      order by persons.id,lead_person_status.status_id";

      return \DB::select($query); 
    }

     public function assignedTo($id= null){
        $leads = $this->with('leads')->findOrFail($id);

        $salesreps = array();
        foreach ($leads as $lead){
            $reps = $lead->salesteam->pluck('id')->toArray();
            $salesreps = array_unique(array_merge($reps, $salesreps));
        }
        return $salesreps;
     }

     public function unassignedLeads(){
          return $this->hasMany(Lead::class, 'lead_source_id')->doesntHave('salesteam');

     }
     public function closedLeads(){
       return $this->hasMany(Lead::class, 'lead_source_id')->has('closedLead');
     }
    
    public function assignedLeads(){
            return $this->hasMany(Lead::class, 'lead_source_id')->has('salesteam');

     }

     public function leadStatusSummary(){

      return $this->withCount('addresses');

     
     /*return $this->select(array('leadsources.*', 
                  \DB::raw('COUNT(leads.id) as allleads,
                    COUNT(b.related_id) as ownedleads,
                    COUNT(a.related_id) as closedleads,
                    avg(a.rating) as ranking')))
                ->leftjoin('leads', 'leads.lead_source_id', '=', 'leadsources.id')
                ->leftjoin('lead_person_status as a', function($join){
                    $join->on('leads.id', '=', 'a.related_id')->where('a.status_id','=',3);
                })
                ->leftjoin('lead_person_status as b','leads.id', '=', 'b.related_id')
                ->groupBy('leadsources.id');
                */
    }

    public function leadRepStatusSummary($id){
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

     
}