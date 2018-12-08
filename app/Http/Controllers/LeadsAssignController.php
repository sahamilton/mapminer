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

    public function assignLeads($sid){
      $leadsource = $this->leadsource->findOrFail($sid);
      $leadroles = $this->setLeadAcceptRoles();

      return response()->view('leads.bulkassign',compact('leadroles','leadsource'));

    }
     public function geoAssignLeads(Request $request){
       
        $leadsource = $this->leadsource->findOrFail(request('sid'));
        $this->leadroles = $this->setLeadAcceptRoles(request());
       
        $data['verticals'] = $leadsource->verticals()->pluck('searchfilters.id')->toArray();

        $leads = $this->lead->whereDoesntHave('salesteam')
          ->where('lead_source_id','=',$leadsource->id)
          ->whereHas('leadsource', function ($q){
            $q->where('datefrom','<=',date('Y-m-d'))
            ->where('dateto','>=',date('Y-m-d'));
          })

        ->get();

     
        $count = null;
        foreach ($leads as $lead) {
          
          $people = $this->person
          ->with('userdetails')
          ->whereHas('userdetails.roles',function($q) {
            $q->whereIn('name',$this->leadroles);

          })->nearby($lead,$this->distance)
          ->limit($this->limit)
          ->get();
          foreach ($people as $person){
          	$count++;
              $lead->salesteam()->attach($person->id,['status_id'=>1]);
          }
        }
        return redirect()->route('leadsource.show',$leadsource->id)->with('status',$count . ' leads assigned');
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

    private function setLeadAcceptRoles(Request $request=null){
        if ($request && request()->has('roles')){
          return request('roles');
        }else{
          return $this->setleadRoles();
        }


    }

}
