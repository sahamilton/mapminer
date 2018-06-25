<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LeadSource extends Model
{	
	public $table='leadsources';
	public $dates = ['created_at','updated_at','datefrom','dateto'];
	
    public function verticals (){
        return $this->belongsToMany(SearchFilter::class,'leadsource_searchfilter','leadsource_id','searchfilter_id');
    }

    public function leads(){
    	return $this->hasMany(Lead::class, 'lead_source_id');
    }
    function assigned (){
      return $this->selectRaw('`leadsources`.*, count(`leads`.`id`) as assigned') 
          ->join('leads','leadsources.id','=','leads.lead_source_id')
          ->join('lead_person_status','leads.id','=','lead_person_status.related_id')
          ->groupBy('leadsources.id');

    

    }

    function leadranking(){


    }
    public $fillable = ['source','description','reference','datefrom','dateto','user_id','filename'];

    public function author(){
    	return $this->belongsTo(User::class, 'user_id','id')->with('person');
       
    }

     public function assignedTo(){
        $leads = $this->leads()->with('salesteam')->has('salesteam')->get();
        $salesreps = array();
        foreach ($leads as $lead){
            $reps = $lead->salesteam->pluck('id')->toArray();
            $salesreps = array_unique(array_merge($reps, $salesreps));
        }
        return count($salesreps);
     }

     public function unassignedLeads(){
          return $this->select('leads.*') 
          ->join('leads','leadsources.id','=','leads.lead_source_id')
          ->leftjoin('lead_person_status','leads.id','=','lead_person_status.related_id')
          ->whereRaw('lead_person_status.related_id is null');



            return $this->hasMany(Lead::class, 'lead_source_id')->doesntHave('salesteam');

     }
      public function assignedLeads(){
            return $this->hasMany(Lead::class, 'lead_source_id')->has('salesteam');

     }

     
}