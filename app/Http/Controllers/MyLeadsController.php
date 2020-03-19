<?php
namespace App\Http\Controllers;

use App\Address;
use App\Branch;
use App\Campaign;
use Illuminate\Http\Request;
use App\Person;
use App\LeadStatus;
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
    public function index()
    {
        // Do you have any branches
        // If branch chosen is it one of yours
        // Else if session is set
        // then get the first branch
        if (!  $myBranches = $this->person->myBranches()) {
            return redirect()->back()->withError('You are not assigned to any branches');
        }

        $branch_ids = array_keys($myBranches);
        // get first branch
        $branch_id = reset($branch_ids);
        session(['branch'=>$branch_id]);

        $branch = $this->branch
            ->with(
                ['leads'=> function ($query) {
                    $query->withLastActivityId();
                },'leads.lastActivity','leads.leadsource']
            )->find($branch_id);
        
        
        $title= $branch->branchname . " leads";
        $campaigns = Campaign::current([$branch_id])->with('companies')->get();
        $campaign_ids = $campaigns->map(function ($campaign){
                return [$campaign->title=>$campaign->companies->pluck('id')->toArray()];
            }
        );
        
 
        
        return response()->view('myleads.branches', compact( 'branch', 'myBranches', 'campaign_ids', 'title'));
        
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
        
        if (request()->has('branch')) {
            $branch_id = request('branch');
        } else {
            $branch_id = $branch->id;
        }
        $myBranches = $this->person->myBranches();
       
        if (! ( $myBranches)  or ! in_array($branch_id, array_keys($myBranches))) {
            return redirect()->back()->withError('You are not assigned to any branches');
        }
       
         
        $branch = $this->branch
            ->with(
                ['leads','leads.lastActivity','leads.leadsource']
            )->find($branch_id);
        $campaigns = Campaign::current([$branch_id])->with('companies')->get();
        $campaign_ids = $campaigns->map(function ($campaign){
                return [$campaign->title=>$campaign->companies->pluck('id')->toArray()];
            }
        );
        
        $title= $branch->branchname . " leads";
        return response()->view('myleads.branches', compact( 'branch', 'myBranches', 'campaign_ids', 'title'));
    }
    /**
     * [_getBranchLeads description]
     * 
     * @param Array $branch [description]
     * 
     * @return [type]         [description]
     */
    private function _getBranchLeads(Array $branch)
    {
        
        $data['leads'] = $this->lead->whereHas(
            'assignedToBranch', function ($q) use ($branch) {
                $q->whereIn('branches.id', $branch);
            }
        )
        ->whereDoesntHave('opportunities')
        ->with('assignedToBranch', 'leadsource', 'lastActivity', 'campaigns')
        ->get();
        
        $data['branches'] = $this->_getBranches($branch);
        return $data;
    }
    /**
     * [_getBranches description]
     * 
     * @param Array $branches [description]
     * 
     * @return [type]           [description]
     */
    private function _getBranches(Array $branches)
    {
        return  $this->branch->with('leads', 'manager')
            ->whereIn('id', $branches)
            ->get();
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

        $lead = $this->lead->create($data['lead']);
        
        $lead->assignedToBranch()->attach($data['branch']->id, ['status_id'=>2]);
        $dupes = $this->_getDuplicateLeads($data);
      
        if (isset($data['contact'])) {
        
            $lead->contacts()->create($data['contact']);
        }
        // probably do away with this....
        if (request()->filled('addressable_type')) {
            switch (request('addressable_type')) {
            case 'weblead':
                    $lead->weblead()->create(request()->all());
                break;
            }
           
            $lead->load('contacts', request('addressable_type'));
        } else {
            $lead->load('contacts');
        }

        if ($dupes->count() > 1) {
            return response()->view('addresses.duplicates', compact('dupes', 'data', 'myBranches'));
        }

        // send this to a job
        if (request('notify')==1) {
            // 
            $branches = \App\Branch::with('manager', 'manager.userdetails')->whereIn('id', array_keys($data['branch']))->get();
           
            foreach ($branches as $branch) {
                foreach ($branch->manager as $manager) {
                    \Mail::queue(new NotifyWebLeadsBranchAssignment($lead, $branch, $manager));
                }
            }
        }
        // not sure that this is being used anymore
        if (request()->has('source') && request('source') == 'mobile') {
                return redirect()->route('mobile.show', $lead)->withMessage('Lead Created');
        } else {
            return redirect()->route('address.show', $lead)->withMessage('Lead Created');
        }
        
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

        if ($geocode->count()==0) {
            
            return false;
        }
        
        $data['lead'] = $this->lead->getGeoCode($geocode);
       
        $data['lead'] = $this->_fillAddress($request, $data['lead']);
        $data['lead']['user_id'] = auth()->user()->id;
        $data['lead']['businessname'] =request('companyname');
      
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
        foreach ($branches as $branch){
            $data[$branch]= ['status_id'=>1];
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
}
