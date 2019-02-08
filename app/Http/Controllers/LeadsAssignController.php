<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeadSource;
use App\Branch;
use App\Address;
use App\Lead;
use App\Person;
use App\Role;
use App\Permission;
use App\Mail\NotifyWebLeadsBranchAssignment;
use Mail;
use App\Jobs\AssignAddressesToBranches;
class LeadsAssignController extends Controller
{
    public $leadsource;
    public $lead;
    public $address;
    public $branch;
    public $person;
    public $leadroles;
    public $distance = 100;
    public $limit = 5;
    
	public function __construct(LeadSource $leadsource,Lead $lead, Address $address,Person $person,Branch $branch){
		$this->leadsource = $leadsource;
		$this->lead = $lead;
    $this->branch = $branch;
		$this->person = $person;
    $this->address = $address;
    $this->leadroles = $this->setLeadRoles();


	}

    public function assignLeads($leadsource){



     
      $leadroles = $this->setLeadAcceptRoles();

      return response()->view('leads.bulkassign',compact('leadroles','leadsource'));

    }
     public function geoAssignLeads(Request $request,$leadsource){

        // get parameters

        $this->distance = request('distance');
        $this->limit = request('limit');
        $verticals  = null;
        if(request('type')=='branch'){
          $count = $this->assignLeadsToBranches($leadsource,$verticals);
         return redirect()->route('leadsource.show',$leadsource->id)->withMessage('Leads assigned to branches');
        }else{
          $leads = $this->address->doesntHave('assignedToBranch')->where('lead_source_id','=',$leadsource->id)->get();
          $count = $this->assignLeadsToPeople($leads,$verticals);
        }
        

        return redirect()->route('leadsource.show',$leadsource->id)->with('status',$count . ' leads assigned');
    }

    public function show(Address $address){
      
          $lead = $address->load('contacts',$address->addressable_type);
          $extrafields = $this->address->getExtraFields('webleads');

          // find nearby branches
          $branches = $this->branch->nearby($address,25,5)->get();;
       
            // we should also add serviceline filter?
          $people = $this->person->nearby($address,25,5);
          
          $salesrepmarkers =null;
    
          $branchmarkers=$branches->toJson();
          $address = $address->fullAddress();

          $sources = array();
          return response()->view('leads.showsearch',compact('lead','branches','people','salesrepmarkers','branchmarkers','extrafields','sources','address'));

    }

    public function store(Request $request, Address $address){

      $branches = $this->branch->whereIn('id',request('branch'))->with('manager','manager.userdetails')->get();
      $address->load('contacts',$address->addressable_type);
      $branchids = $branches->pluck('id')->toArray();
   
      $address->assignedToBranch()->sync([$branchids, ['status_id'=>1]]);
      if(request()->has('notify'))
      {       
        foreach ($branches as $branch)
          {
                  Mail::queue(new NotifyWebLeadsBranchAssignment($address,$branch));
                
          }
        }
      return redirect()->route('address.show',$address->id)->withMessage('Lead has been assigned');
    }

    public function singleleadassign(Address $lead){

      $lead->load('assignedToBranch');

      $branches = $this->branch->nearby($lead,25,5)->get();
      $branchmarkers = $branches->toJson();
      return response()->view('leads.singleassign',compact('branches','lead','branchmarkers'));
    }

  /*  public function assignLead(Request $request){

      $count=0;

      $lead = $this->lead->findOrFail(request('lead_id'));

      foreach(request('salesrep') as $key=>$value){

        $lead->salesteam()->attach($value,['status_id'=>1]);
        $count++;

      }
      return redirect()->route('leadsource.index')->with(['status'=>'Lead assigned to ' .$count . 'reps']);
    }*/

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

   /* private function assignLeadsToPeople($leads,$verticals=null){
      
      $count = null;
      foreach ($leads as $lead) {
          $people = $this->person
                  ->with('userdetails')
                  ->whereHas('userdetails.roles',function($q) {
                    $q->whereIn('name',$this->leadroles);

                  });
          if($verticals){
            // assign only to people who have industry focus == to leadsource
            // or no industry focus
              $people = $people->where(function ($q) use ($verticals){
                $q->whereHas('industryfocus',function($q) use($verticals){
                  $q->whereIn('searchfilters.id',$verticals);
                })
                ->orWhereDoesntHave('industryfocus');
              });
          }
          $people = $people->nearby($lead,$this->distance)
                ->limit($this->limit)
                ->get();;
          
          foreach ($people as $person){
            $count++;
              $lead->salesteam()->attach($person->id);
          }
          
        }
        return $count;
    }
*/
    private function assignLeadsToBranches($leadsource,$distance){
      // convert miles to meters
      $distance = $this->distance * 1609;
     
      $query = "insert into address_branch (branch_id,address_id) 
                select distinct branches.id as branch_id, addresses.id as address_id 
                from branches,addresses 
                left join address_branch
                on addresses.id = address_branch.address_id
                where ST_Distance_Sphere(branches.position,addresses.position) < '". $distance."'
                and lead_source_id = '" . $leadsource->id ."'
                and address_branch.address_id is null
                ORDER BY branches.id asc";
      
     return \DB::statement($query);
    }


}
