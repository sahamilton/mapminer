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
use Carbon\Carbon;;
use App\Jobs\AssignLeadsToPeople;
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
    /**
     * [__construct description]
     * 
     * @param LeadSource $leadsource [description]
     * @param Lead       $lead       [description]
     * @param Address    $address    [description]
     * @param Person     $person     [description]
     * @param Branch     $branch     [description]
     */
    public function __construct(LeadSource $leadsource, Lead $lead, Address $address, Person $person, Branch $branch)
    {
        $this->leadsource = $leadsource;
        $this->lead = $lead;
        $this->branch = $branch;
        $this->person = $person;
        $this->address = $address;
        $this->leadroles = $this->_setleadRoles();
    }
    /**
     * [assignLeads description]
     * 
     * @param  [type] $leadsource [description]
     * 
     * @return [type]             [description]
     */
    public function assignLeads($leadsource)
    { 
       
        $leadroles = $this->_setLeadAcceptRoles();
        $branches = $this->branch->orderBy('id')->get();
        return response()->view('leads.bulkassign', compact('leadroles', 'leadsource', 'branches'));
    }
     
    /**
     * [geoAssignLeads description]
     * 
     * @param GeoAssignLeadsRequest $request    [description]
     * @param LeadSource            $leadsource [description]
     * 
     * @return [type]                            [description]
     */
    public function geoAssignLeads(GeoAssignLeadsRequest $request, LeadSource $leadsource)
    {
       
        if (request('type') == 'specific') {

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
                    $assigned = $this->address->where('lead_source_id', '=', $leadsource->id)
                        ->has('assignedToPerson')
                        ->get();
 
                    $message = $assigned->count() . ' Leads have been assigned to '. count($count) . ' people';
                }
            } else {
                $message ="No leads to assign";
            }
        }
        return redirect()->route('leadsource.show', $leadsource->id)->withMessage($message);
    }
    /**
     * [show description]
     * 
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    public function show(Address $address)
    {
      
        $lead = $address->load('contacts', $address->addressable_type);
        $extrafields = $this->address->getExtraFields('webleads');

        // find nearby branches
        $branches = $this->branch->nearby($address, 25, 5)->get();
    
   
        // we should also add serviceline filter?
        $people = $this->person->nearby($address, 25, 5);
      
        $salesrepmarkers =null;

        $branchmarkers=$branches->toJson();
        $address = $address->fullAddress();

        $sources = [];
        return response()->view(
            'leads.showsearch', compact(
                'lead', 
                'branches', 
                'people', 
                'salesrepmarkers', 
                'branchmarkers', 
                'extrafields', 
                'sources', 
                'address'
            )
        );
    }
    /**
     * [store description]
     * 
     * @param Request $request [description]
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    public function store(Request $request, Address $address) 
    {
     
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
        return redirect()->route('address.show', $address->id)
            ->withMessage('Lead has been assigned');
    }
    /**
     * [singleleadassign description]
     * 
     * @param Address $lead [description]
     * 
     * @return [type]        [description]
     */
    public function singleleadassign(Address $lead)
    {

        $lead->load('assignedToBranch');

        $branches = $this->branch->nearby($lead, 25, 5)->get();
        $branchmarkers = $branches->toJson();
        return response()->view('leads.singleassign', compact('branches', 'lead', 'branchmarkers'));
    }


    /**
     * [_setleadRoles description]
     * 
     * @return [type] [description]
     */
    private function _setleadRoles()
    {

        $roles = Permission::where('name', '=', 'accept_prospects')->with('roles')->first();

        return  $roles->roles->pluck('display_name', 'id')->toArray();
    }

    /**
     * [_setLeadAcceptRoles description]
     * 
     * @param Request|null $request [description]
     *
     * @return [<description>]
     */
    private function _setLeadAcceptRoles(Request $request = null)
    {
        if ($request && request()->has('roles')) {
            return request('roles');
        } else {
            return $this->_setleadRoles();
        }
    }
    /**
     * [_assignToSpecificBranches description]
     * 
     * @param Request    $request    [description]
     * @param LeadSource $leadsource [description]
     * 
     * @return [type]                 [description]
     */
    private function _assignToSpecificBranches(
        Request $request, 
        LeadSource $leadsource
    ) {
     
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
    /**
     * [_assignLeadsToPeople description]
     * 
     * @param LeadSource $leadsource [description]
     * @param Array      $box        [description]
     * @param Array      $roles      [description]
     * 
     * @return [type]                 [description]
     */
    private function _assignLeadsToPeople(
        LeadSource $leadsource, 
        Array $box, 
        Array $roles
    ) {
        //dd($leadsource, $box, $roles);
        //$addresses = $this->_unassignedLeads($leadsource);
        $count = [];
        // move this to a queued job
        $this->address->where('lead_source_id', $leadsource->id)
            ->doesntHave('assignedToBranch')
            ->doesntHave('assignedToPerson')
            ->chunk(
                200, function ($addresses) use ($roles) {
                    
                    AssignLeadsToPeople::dispatch(
                        $addresses,
                        $roles,
                        $this->limit,
                        $this->distance
                    );
                } 
            );
        /* foreach ($addresses as $address) {
            $people = $this->person->withRoles($roles)->nearby($address, $this->distance, $this->limit)->get();
            
            foreach ($people as $person) {

                if (isset($count[$person->id])) {
                    $count[$person->id]++;
                } else {
                    $count[$person->id] = 1;
                }
               
                AddressPerson::insert(['address_id'=>$address->id, 'person_id'=>$person->id, 'status_id'=>2, 'created_at' => Carbon::now()]);
            }
            
            
        }*/
        
        return $count;
    }
    /**
     * [_assignBranchesToLeads description]
     * 
     * @param  LeadSource $leadsource [description]
     * 
     * @return [type]                 [description]
     */
    private function _assignBranchesToLeads(LeadSource $leadsource)
    {
        $leadsource->load('servicelines');
        $addresses = $this->_unassignedLeads($leadsource);
        // add assingment query here
        foreach ($addresses as $address) {
            $branches = $this->branch
                
                ->when(
                    $leadsource->servicelines->count(), function ($q) use ($leadsource) {
                        $q->whereHas(
                            'servicelines', function ($q) use ($leadsource) {
                                $q->whereIn('serviceline_id', [$leadsource->servicelines->pluck('id')->toarray()]);
                            }
                        );
                    }
                )
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

    /**
     * [_unassignedLeads description]
     * 
     * @param LeadSource $leadsource [description]
     * 
     * @return [type]                 [description]
     */
    private function _unassignedLeads(LeadSource $leadsource)
    {
        return $this->address->where('lead_source_id', $leadsource->id)
            ->doesntHave('assignedToBranch')
            ->doesntHave('assignedToPerson')
            ->get();
    
    }


}
