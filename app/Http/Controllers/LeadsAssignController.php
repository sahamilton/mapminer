<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Leadsource;
use App\Lead;
use App\Person;
class LeadsAssignController extends Controller
{
    public $leadsource;
    public $lead;
    public $person;

	public function __construct(LeadSource $leadsource,Lead $lead, Person $person){
		$this->leadsource = $leadsource;
		$this->lead = $lead;
		$this->person = $person;

	}


     public function geoAssignLeads($sid){

        $leadsource = $this->leadsource->with('verticals')->findOrFail($sid);
       
        $data['verticals'] = $leadsource->verticals->pluck('id')->toArray();
       
        $leads = $this->lead->whereDoesntHave('salesteam')
        ->where('lead_source_id','=',$sid)
        ->whereHas('leadsource', function ($q){
          $q->where('datefrom','<=',date('Y-m-d'))
          ->where('dateto','>=',date('Y-m-d'));
        })
        
        ->get();
        $count = null;
        foreach ($leads as $lead) {
           $data['lat']=$lead->lat;
           $data['lng']=$lead->lng;
          $people = $this->person->findNearByPeople($lead->lat,$lead->lng,'100','5', $role=['Sales'],$data['verticals']);
          
            
            
                foreach ($people as $person){
                	$count++;
                    $lead->salesteam()->attach($person->id,['status_id'=>1]);
                }
           
           
        }
        return redirect()->route('leadsource.show',$sid)->with('status',$count . ' leads assigned');
    }
}
