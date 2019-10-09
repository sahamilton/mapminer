<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeadSource;
use App\Branch;
use App\Address;
use App\AddressBranch;
use App\AddressPerson;
use App\Lead;
use App\Person;
use App\Role;
use App\Permission;

use App\Mail\NotifyWebLeadsBranchAssignment;
use Mail;

use App\Http\Requests\GeoAssignLeadsRequest;
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
    
    public function __construct(LeadSource $leadsource, Lead $lead, Address $address, Person $person, Branch $branch)
    {
        $this->leadsource = $leadsource;
        $this->lead = $lead;
        $this->branch = $branch;
        $this->person = $person;
        $this->address = $address;
        $this->leadroles = $this->_setleadRoles();
    }

    public function assignLeads($leadsource)
    { 
       
        $leadroles = $this->_setLeadAcceptRoles();
        $branches = $this->branch->orderBy('id')->get();
        return response()->view('leads.bulkassign', compact('leadroles', 'leadsource', 'branches'));
    }
     
    /*
    

    */
    public function geoAssignLeads(GeoAssignLeadsRequest $request, LeadSource $leadsource)
    {
       
        if (request('type')== 'specific') {

            $message = $this->_assignToSpecificBranches($request, $leadsource);
        } else {

            $this->distance = request('distance');
            $this->limit = request('limit');
            $verticals  = null;

            $addresses = $this->address->where('lead_source_id', $leadsource->id)
                ->get();
           
            
            if ($addresses->count()>0) {
                $box = $this->address->getBoundingBox($addresses);
                
                if (request('type')=='branch') {
                      $branchCount = $this->_assignBranchesToLeads($leadsource);
                      $assignedCount = $this->address->where('lead_source_id', $leadsource->id)
                          ->has('assignedToBranch')
                          ->get()
                          ->count();
                    $message = $assignedCount . ' Leads have been assigned to '. $branchCount . ' branches';
                } else {
                    $count = $this->_assignLeadsToPeople($leadsource, $box, request('roles'));
                    $assignedCount = $this->address->where('lead_source_id', '=', $leadsource->id)
                        ->has('assignedToPerson')
                        ->get()->count();
                    $message = $assignedCount . ' Leads have been assigned to '. $count . ' people';
                }
            } else {
                $message ="No leads to assign";
            }
        }
        return redirect()->route('leadsource.show', $leadsource->id)->withMessage($message);
    }

    public function show(Address $address)
    {
      
          $lead = $address->load('contacts', $address->addressable_type);
          $extrafields = $this->address->getExtraFields('webleads');

          // find nearby branches
          $branches = $this->branch->nearby($address, 25, 5)->get();
        ;
       
            // we should also add serviceline filter?
          $people = $this->person->nearby($address, 25, 5);
          
          $salesrepmarkers =null;
    
          $branchmarkers=$branches->toJson();
          $address = $address->fullAddress();

          $sources = [];
          return response()->view('leads.showsearch', compact('lead', 'branches', 'people', 'salesrepmarkers', 'branchmarkers', 'extrafields', 'sources', 'address'));
    }

    public function store(Request $request, Address $address) {
     
        $branches = $this->branch
            ->whereIn('id', request('branch'))
            ->with('manager', 'manager.userdetails')
            ->get();
        $address->load('contacts', $address->addressable_type);
        $branchids = $branches->pluck('id')->toArray();
        foreach ($branchids as $branch) {
            $syncData[$branch] = ['status_id'=>1];
        }
   
        $address->assignedToBranch()->sync($syncData);

        if (request()->has('notify')) {       
            foreach ($branches as $branch) {
                Mail::queue(new NotifyWebLeadsBranchAssignment($address, $branch));
                
            }
        }
        return redirect()->route('address.show', $address->id)->withMessage('Lead has been assigned');
    }

    public function singleleadassign(Address $lead)
    {

        $lead->load('assignedToBranch');

        $branches = $this->branch->nearby($lead, 25, 5)->get();
        $branchmarkers = $branches->toJson();
        return response()->view('leads.singleassign', compact('branches', 'lead', 'branchmarkers'));
    }



    private function _setleadRoles()
    {

        $roles = Permission::where('name', '=', 'accept_prospects')->with('roles')->first();

        return  $roles->roles->pluck('display_name', 'id')->toArray();
    }


    private function _setLeadAcceptRoles(Request $request = null)
    {
        if ($request && request()->has('roles')) {
            return request('roles');
        } else {
            return $this->_setleadRoles();
        }
    }
    /*
    
    */

    private function _assignToSpecificBranches(Request $request, LeadSource $leadsource)
    {
     
        $addresses = $this->address
            ->where('lead_source_id', $leadsource->id)
            ->doesntHave('assignedToBranch')
            ->doesntHave('assignedToPerson')
            ->pluck('id')
            ->toArray();

        foreach (request('branch') as $branch) {
            $branch = $this->branch->findOrFail($branch);

            $branch->locations()->attach($addresses);
        }

        return  count($addresses) . " leads assigned to " . count(request('branch')) . ' branches';
    }

    private function _assignLeadsToPeople($leadsource, $box, $roles)
    {

        $people = $this->person->withRoles($roles)->get();
       
        foreach ($people as $person) {
            $addresses = $this->address->where('lead_source_id', '=', $leadsource->id)
                ->doesntHave('assignedToBranch')
                ->doesntHave('assignedToPerson')
                ->nearby($person, $this->distance, $this->limit)
                ->pluck('id')
                ->toArray();
            foreach ($addresses as $address_id) {
                $data[] = ['address_id'=>$address_id, 'person_id'=>$person->id, 'status_id'=>1];
            }
        }
        
        AddressPerson::insert($data);
        return $people->count();
    }
  
    private function _assignBranchesToLeads(LeadSource $leadsource) {
     
        $addresses = $this->_unassignedLeads($leadsource);
      
        foreach ($addresses as $address) {
            $branches = $this->branch
                ->nearby($address, $this->distance, $this->limit)
                ->pluck('id')
                ->toArray();
            if (count($branches)>0) {
                foreach ($branches as $branch_id) {
                    $data[] = ['address_id'=>$address->id, 'branch_id'=>$branch_id];
                }
            }
        }
        AddressBranch::insert($data);
        return $addresses->count();              
    }
               
    private function _assignLeadsToBranches($leadsource,$box) 
    {

        $branches = $this->branch->withinMBR($box)->get();
        // this gets all branches within bounding box
        foreach ($branches as $branch) {
            $addresses = $this->address->where('lead_source_id', $leadsource->id)
                ->doesntHave('assignedToBranch')
                ->doesntHave('assignedToPerson')
                ->nearby($branch, $this->distance, $this->limit)
                ->pluck('id')
                ->toArray();
            foreach ($addresses as $address_id) {
                $data[] = ['address_id'=>$address_id, 'branch_id'=>$branch->id];
            }
        }
        AddressBranch::insert($data);
        return $branches->count();

        // convert miles to meters
        /* $distance = $this->distance * 1609;

          $query = "insert into address_branch (branch_id,address_id) 
                select distinct branches.id as branch_id, addresses.id as address_id 
                from branches,addresses 
                left join address_branch
                on addresses.id = address_branch.address_id
                where ST_Distance_Sphere(branches.position,addresses.position) < '". $distance."'
                and lead_source_id = '" . $leadsource->id ."'
                and address_branch.address_id is null
                ORDER BY branches.id asc";
      
        return \DB::statement($query);*/
    }

    private function _unassignedLeads(LeadSource $leadsource)
    {
        return $this->address->where('lead_source_id', $leadsource->id)
            ->doesntHave('assignedToBranch')
            ->doesntHave('assignedToPerson')
            ->get();
    
    }


}
