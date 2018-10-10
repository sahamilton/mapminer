<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeadSource;
use App\Lead;
use App\Person;
use App\Role;
use App\Permission;
class LeadsAssignController extends Controller
{
    public $leadsource;
    public $lead;
    public $person;
    public $leadroles;
    public $distance = 100;
    public $limit = 5;
	public function __construct(LeadSource $leadsource,Lead $lead, Person $person){
		$this->leadsource = $leadsource;
		$this->lead = $lead;
		$this->person = $person;
    $this->leadroles = $this->setLeadRoles();


	}


     public function geoAssignLeads($sid){

        $leadsource = $this->leadsource->findOrFail($sid);
        //$leadroles = $this->leadroles;
        $data['verticals'] = $leadsource->verticals()->pluck('searchfilters.id')->toArray();

        $leads = $this->getUnassignedLeads($sid);

        $count = null;
        foreach ($leads as $lead) {
          $data['lat']=$lead->lat;
          $data['lng']=$lead->lng;

          $people = $this->getPeopleToAcceptLeads($lead);
                foreach ($people as $person){
                	$count++;
                    $lead->salesteam()->attach($person->id,['status_id'=>1]);
                }
        }
        return redirect()->route('leadsource.show',$sid)->with('status',$count . ' leads assigned');
    }

    public function assignLead(Request $request){

     $count=0;
      $lead = $this->lead->findOrFail($request->get('lead_id'));

      foreach($request->get('salesrep') as $key=>$value){
        $lead->salesteam()->attach($value,['status_id'=>1]);
        $count++;

      }
      return redirect()->route('leadsource.index')->with(['status'=>'Lead assigned to ' .$count . 'reps']);
    }

    private function setleadRoles(){

      $roles = Permission::where('name','=','accept_prospects')->with('roles')->first();
      return  $roles->roles->pluck('name')->toArray();


    }

    private function getUnassignedLeads($sid){
      return $this->lead->whereDoesntHave('salesteam')
          ->where('lead_source_id','=',$sid)
          

        ->get();

    }

    private function getPeopleToAcceptLeads($lead){
      return $this->person->nearby($lead,$this->distance)
          ->with('userdetails')
          ->whereHas('userdetails.roles',function($q) {
            $q->whereIn('name',$this->leadroles);

          })
          ->limit($this->limit)
          ->get();
    }
    public function reassignLeads($pid){

      $person = $this->person->with('openleads')->findOrFail($pid);
      $person->openleads()->detach();
      $leads = $this->lead->has('openleads')->nearby($person,25)->pluck('id')->toArray();
      $data = array();
      foreach ($leads as $lead){
        $data[$lead]=['status_id'=>2];
      }
      
      $person->leads()->attach($data);
      return redirect()->route('salesrep.newleads',$pid);
    }
}
