<?php
namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Branch;
use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\LeadStatus;
use App\Mail\NotifyWebLeadsBranchAssignment;
use App\Http\Requests\MyLeadFormRequest;
use App\Http\Requests\LeadReassignFormRequest;
use App\Mail\NotifyLeadReassignment;

class MyLeadsController extends BaseController
{
   
    public $lead;
    public $me;
    public $user;
    public $person;
    public $branch;


    /**
     * [__construct description]
     * 
     * @param Address $lead   [description]
     * @param Person  $person [description]
     * @param Branch  $branch [description]
     */
    public function __construct(
        Address $lead, Person $person, Branch $branch
    ) {

        $this->lead = $lead;
        $this->person = $person;
        $this->branch = $branch;
    }

    /**
     * [index description]
     * 
     * @param  [type] $branch [description]
     * @return [type]         [description]
     */
    public function index(Branch $branch=null)
    {
        // Validate branch assignements
        if (!  $myBranches = $this->person->myBranches()) {
            return redirect()->back()->withError('You are not assigned to any branches');
        }

        if (! $this->_checkMyBranch($branch)) {

            return redirect()->back()->withError(' That is not one of your branches');
        }
        // set / read session variable
        if (! $branch && ! session('branch')) {
            $branch_ids = array_keys($myBranches);
            $branch_id = $branch_ids[0];
            
            session(['branch'=>$branch_id]);
        } elseif ($branch) {

            session(['branch'=>$branch->id]); 
            
        }
        
        $branch = $this->branch->findOrFail(session('branch'));
        // necessary if using impersonate
        session()->forget('manager');
        
        $search = null;
        $campaigns = $this->_getCurrentOpenCampaigns($branch->id);
        
        return response()->view('myleads.branches', compact('branch', 'myBranches', 'campaigns', 'search'));
        
    }
    public function list()
    {
        return response()->view('companies.nearby');
    }

    public function create()
    {
        $branches = auth()->user()->person->myBranches();
        return response()->view('lead.create', compact('branches'));
    }
    /**
     * [branchLeads description]
     * 
     * @param Request $request [description]
     * @param Branch  $branch  [description]
     * 
     * @return [type]           [description]
     */
    public function branchLeads(Request $request, Branch $branch)
    {
        
        if (!  $myBranches = $this->person->myBranches()) {
            return redirect()->back()->withError('You are not assigned to any branches');
        }
        if (request()->has('branch')) {
            $branch_id = request('branch');
        } else {
            $branch_id = $branch->id;
        }
        
        if (! in_array($branch_id, array_keys($myBranches))) {
            return redirect()->back()->withError('You are not assigned to this branch');
        }
        session(['branch'=>$branch->id]);
        //$branch = $branch->id;
        //$branch = $this->_getBranchLeadData($branch->id);
        $campaigns = $this->_getCurrentOpenCampaigns($branch_id);
        
        $search =null;
        return response()->view('myleads.branches', compact('branch', 'myBranches', 'campaigns', 'search'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request 
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(MyLeadFormRequest $request)
    {
        
        $myBranches = auth()->user()->person->getMyBranches();

        // we need to geocode this address
        if (! $data = $this->_cleanseInput($request)) {
            return redirect()->back()->withError('Unable to geocode that address');
        }
       
        $data['branch'] = $this->branch->findOrFail(request('branch'));
        
        $address = $this->lead->create($data['lead']);

        $address->assignedToBranch()->attach($data['branch'], ['status_id'=>2]);

        if (request()->filled('campaign')) {
            $this->_assignToCampaign($address, request('campaign'));
        }
    
       
        $dupes = $this->_getDuplicateLeads($data);
        
        if ($data['contact']) {
            $data['contact']['primary'] = 1;
            $address->contacts()->create($data['contact']);
        }
        // probably do away with this....
        if (request()->filled('addressable_type')) {
            switch (request('addressable_type')) {
            case 'weblead':
                    $address->weblead()->create(request()->all());
                break;
            }
           
            $address->load('contacts', request('addressable_type'));
        } else {
            $address->load('contacts');
        }

        if ($dupes->count() > 1) {
            return response()->view('addresses.duplicates', compact('address', 'dupes', 'data', 'myBranches'));
        }

        // send this to a job
        if (request('notify')==1) {
            // 
            $branches = \App\Models\Branch::with('manager', 'manager.userdetails')
                ->whereIn('id', array_keys($data['branch']))
                ->get();
           
            foreach ($branches as $branch) {
                foreach ($branch->manager as $manager) {
                    \Mail::queue(new NotifyWebLeadsBranchAssignment($lead, $branch, $manager));
                }
            }
        }
        // not sure that this is being used anymore
        if (request()->has('source') && request('source') == 'mobile') {
                return redirect()->route('mobile.show', $address)->withMessage('Lead Created');
        } else {
            return redirect()->route('address.show', $address)->withMessage('Lead Created');
        }
        
    }

    public function claim(Address $address, Request $request)
    {

        //->sync([1 => ['expires' => true], 2, 3]);
        $address->assignedToBranch()->sync([request('branch_id') =>['status_id'=>2]]);
        return redirect()->route('address.show', $address->id);
    }

    /**
     * [findLocations description]
     * 
     * @param [type] $distance [description]
     * @param [type] $latlng   [description]
     * 
     * @return [type]           [description]
     */
    public function findNearbyLeads(Person $person, $distance = null, $latlng = null)
    {
        
        $location = $this->getLocationLatLng($latlng);
        $myBranches = array_keys($person->myBranches());
        $result = $this->lead
            ->nearby($location, $distance)
            ->where(
                function ($q) use ($myBranches) {
                    $q->doesntHave('assignedToBranch')
                        ->orWhereHas(
                            'assignedToBranch', function ($q) use ($myBranches) {
                                $q->whereIn('branches.id', $myBranches);
                            }
                        );
                }
            )->get();
       
        return response()->view('addresses.xml', compact('result'))->header('Content-Type', 'text/xml');
    }

    public function searchleads(Request $request)
    {
        
        $search = request('companyname');
        if (request()->has('branch_id')) {
                $branch = request('branch_id');
        } else {
            $branch = session('branch');
        }
        //check if in your branches
        $myBranches = auth()->user()->person->myBranches();
        if (! array_key_exists($branch, $myBranches)) {
            return redirect()->back()->withError('That is not one of your branches ' . $branch);
        }
        $branch = $this->branch->findOrFail($branch);
        $campaigns = $this->_getCurrentOpenCampaigns($branch->id);
        
        return response()->view('myleads.branches', compact('branch', 'myBranches', 'campaigns', 'search'));
    }
    /**
     * [_assignToCampaign Remove null campaign and assign address]
     * 
     * @param  Address $address   [description]
     * @param  array   $campaigns [description]
     * 
     * @return [type]             [description]
     */
    private function _assignToCampaign(Address $address, array $campaigns)
    {

        if (($key = array_search(0, $campaigns)) !== false) {
            
            unset($campaigns[$key]);

        }
        
        if (count($campaigns) >0) {
            $address->campaigns()->sync($campaigns); 
        }
        return $address;
    }
    /**
     * [_getBranchLeadData description]
     * 
     * @param  [type] $branch_id [description]
     * 
     * @return [type]            [description]
     */
    private function _getBranchLeadData($branch_id)
    {
        return $this->branch
            ->with(
                ['leads'=>function ($query) {
                    $query->withLastActivityId();
                },'leads.lastActivity','leads.leadsource','leads.currentcampaigns']
            )->find($branch_id);
    }
    /**
     * [_getCurrentOpenCampaigns 
     * Get the company ids in each current campaign]
     * 
     * @param string $branch_id [description]
     * 
     * @return array            [description]
     */
    private function _getCurrentOpenCampaigns($branch_id)
    {
        return Campaign::currentOpen([$branch_id])->get();
        
    }
    /**
     * [_cleanseInput description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    private function _cleanseInput(Request $request) 
    {
        $address = $this->_getAddress($request); 
        $geocode = app('geocoder')->geocode($address)->get();
       
        if ($geocode->count()===0) {
            
            return false;
        }
       
        $data['lead'] = $this->lead->getGeoCode($geocode);
       
        $data['lead'] = $this->_fillAddress($request, $data['lead']);
        $data['lead']['user_id'] = auth()->user()->id;
        $data['lead']['businessname'] =request('companyname');
        $data['lead']['isCustomer'] = request('isCustomer');
        $data['lead']['phone'] = preg_replace("/[^0-9]/", "", request('phone'));
        if (! request()->has('leadsource_id')) {
            $data['lead']['lead_source_id'] = 4;
        }
        $data['contact'] = $this->_cleanseContactData($request);

       
        return $data;
    }

    /**
     * [_fillAddress description]
     * 
     * @param Request $request [description]
     * @param Array   $lead    [description]
     * 
     * @return [type]           [description]
     */
    private function _fillAddress(Request $request, Array $lead)
    {
     
        $fields = ['address','city','state','zip'];
        foreach ($fields as $field) {
            if (! $lead[$field] or str_replace(" ", "", $lead[$field])=='') {
                $lead[$field] = request($field);
            }
        }
        return $lead;

    }



    /**
     * [_getAddress description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    private function _getAddress(Request $request)
    {
        
        return request('address'). ' ' .request('city'). ' ' .request('state'). ' ' .request('zip');
    }
    /**
     * _cleanseContactData Extract contact information from request
     * 
     * @param Request $request [description]
     * 
     * @return array $data [description]
     */
    private function _cleanseContactData(Request $request)
    {
        if (request()->filled('contact')) {
            $data['fullname'] = request('contact');
            // extract first last name
            $name = explode(' ', request('contact'), 2);
            $data['firstname'] = $name[0];
            
            if (isset($name[1])) {
                $data['lastname'] = $name[1];
            }
            
            $data['title'] = request('contact_title');
            $data['email'] = request('email');
            $data['contactphone'] =  preg_replace("/[^0-9]/", "", request('phone'));

            return array_filter($data);
        }
        return false;
        
    }
    /**
     * [reassign description]
     * 
     * @param LeadReassignFormRequest $request [description]
     * @param Address                 $address [description]
     * 
     * @return [type]                           [description]
     */
    public function reassign(LeadReassignFormRequest $request, Address $address)
    {
        
        if (! request()->filled('branch')) {
            $branch = $this->_validateBranches($request);
            if (! $branch) {
                return redirect()->back()->withError('Invalid branch ids');
            }
        } else {
            $branch = request('branch');
        }
        
        $address->load('openActivities', 'openOpportunities', 'assignedToBranch');
    
        $this->_reassignToBranch($address, $branch);
        $address->load('assignedToBranch');
      
        $this->_notifyLeadReassignment($branch, $address);

        return redirect()->back()->withSuccess('Lead reassigned');
    }
    /**
     * [_notifyLeadReassignment description]
     * 
     * @param array   $branch  [description]
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    private function _notifyLeadReassignment(array $branch, Address $address)
    {
        $branches = $this->branch->has('manager')->with('manager')->whereIn('id', $branch)->get();
        $sender = $this->person->with('userdetails')->where('user_id', auth()->user()->id)->first();
     
        foreach ($branches as $branch) {
            foreach ($branch->manager as $manager) {
                \Mail::queue(new NotifyLeadReassignment($address, $branch, $manager, $sender));
            }
        }
    }
    /**
     * Validate incoming string of comma separated branches
     * Could move to branch model
     * 
     * @param Request $request [description]
     * 
     * @return array of valid branches
     */
    private function _validateBranches(Request $request)
    {
        
        preg_match_all('/(([0-9]{4})+)/m', request('branch_id'), $branches, PREG_PATTERN_ORDER);

        if (count($branches[1])==0) {
          
            return false;
        }
        
        // check that the branches are valid
        $branches = $this->branch->whereIn('id', $branches)->pluck('id')->toArray();
        if (count($branches)==0) {
            return false;
        }
        return $branches;
    }
    /**
     * [_reassignToBranch description]
     * 
     * @param Address $address  [description]
     * @param Array   $branches [description]
     * 
     * @return [type]            [description]
     */
    private function _reassignToBranch(Address $address, Array $branches)
    {
        
        if ($address->openActivities->count()) {
            $this->_reassignActivities($address->openActivities, $branches);
        }
        if ($address->openOpportunities->count()) {
            $this->_reassignOpportunities($address->openOpportunities, $branches);
        }
        foreach ($branches as $branch) {
            $data[$branch]= ['status_id'=>2];
        }
       
        return $address->assignedToBranch()->sync($data);
    }
    /**
     * [_reassignActivities description]
     * 
     * @param [type] $activities [description]
     * @param [type] $branches   [description]
     * 
     * @return [type]             [description]
     */
    private function _reassignActivities($activities, $branches)
    {
    
        foreach ($branches as $branch) {
            foreach ($activities as $activity) {
                $activity->update(['branch_id'=> $branch]);
            }
        }
       
    }
    /**
     * [_reassignOpportunities description]
     * 
     * @param [type] $opportunities [description]
     * @param [type] $branches      [description]
     * 
     * @return [type]                [description]
     */
    private function _reassignOpportunities($opportunities, $branches)
    {
        
        foreach ($branches as $branch) {
            foreach ($opportunities as $opportunity) {
              
                $opportunity->update(['branch_id'=> $branch]); 
            } 
        } 
    }
    /**
     * [_getDuplicateLeads description]
     * 
     * @param Array $data [description]
     * 
     * @return Collection       [description]
     */
    private function _getDuplicateLeads(Array $data)
    {
        
        return $this->lead
            ->with('assignedToBranch')
            ->duplicateDistance($data['lead']['lng'], $data['lead']['lat'])
            ->get();
    }

    private function _checkMyBranch($branch = null)
    {
        if ($branch) {

        }
        return true;
    }
}
