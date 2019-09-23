<?php
namespace App\Http\Controllers;

use App\Address;
use App\Branch;
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
    public function index($branch=null)
    {
        
        if (!  $myBranches = $this->person->myBranches()) {
            return redirect()->back()->withError('You are not assigned to any branches');
        }

        if (! $branch && ! session('branch')) {

            $branch = array_keys($myBranches);
            // get first branch
            $branch = reset($branch);
            session(['branch'=>$branch]);

        } elseif (session('branch')) {

            if (! in_array(session('branch'), array_keys($this->person->myBranches()))) {
                
                return redirect()->back()->withError('Something has gone wrong here. Please advise Sales Operations');
            }
            $branch = session('branch');
        
        } else {   
            if (! in_array($branch->id, array_keys($this->person->myBranches()))) {
                return redirect()->back()->withError('That is not one of your branches');
            }
            $branch = $branch->id;
            session(['branch'=>$branch]);
        }
        
        $data = $this->_getBranchLeads([$branch]);
        
        $title= $data['branches']->first()->branchname . " leads";

        return response()->view('myleads.branches', compact('data', 'myBranches', 'title'));
        // how to get the distance for each branch
        // get my branches
        // get addresses that are leads that are assigned to a branch
        //
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
            $branch = request('branch');
        } else {
            $branch = $branch->id;
        }
        $myBranches = $this->person->myBranches();
       
        if (! ( $myBranches)  or ! in_array($branch, array_keys($myBranches))) {
            return redirect()->back()->withError('You are not assigned to any branches');
        }
       
         
        $data = $this->_getBranchLeads([$branch]);
       
        $title= $data['branches']->first()->branchname . " leads";
        return response()->view('myleads.branches', compact('data', 'myBranches', 'title'));
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
        $data['leads'] = $this->lead->wherehas(
            'assignedToBranch', function ($q) use ($branch) {
                $q->whereIn('branches.id', $branch);
            }
        )
        ->whereDoesntHave('opportunities')
        ->with('assignedToBranch', 'opportunities', 'leadsource', 'lastActivity')
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
     * [closedleads description]
     * 
     * @return [type] [description]
     */
    public function closedleads()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
       

        // we need to geocode this address
        if (! $data = $this->_cleanseInput($request)) {
            return redirect()->back()->withError('Unable to geocode that address');
        }

        $data['branch'] = $this->branch->findOrFail(request('branch'));
        
        $dupes = $this->lead->duplicate($data['lead']['lng'], $data['lead']['lat'])->get();

        //if ($dupes->count()>0) {
            //return response()->view('addresses.duplicates', compact('dupes', 'data'));
        //} 
        
        
        $lead = $this->lead->create($data['lead']);
        
        $lead->assignedToBranch()->attach($data['branch']);
       
        
        if (isset($data['contact'])) {
        
            $lead->contacts()->create($data['contact']);
        }
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
    

        if (request('notify')==1) {
            $branches = \App\Branch::with('manager', 'manager.userdetails')->whereIn('id', array_keys($data['branch']))->get();
           
            foreach ($branches as $branch) {
                foreach ($branch->manager as $manager) {
                    \Mail::queue(new NotifyWebLeadsBranchAssignment($lead, $branch, $manager));
                }
            }
        }

        if (request()->has('source') && request('source') == 'mobile') {
                return redirect()->route('mobile.show', $lead)->withMessage('Lead Created');
        } else {
            return redirect()->route('address.show', $lead)->withMessage('Lead Created');
        }
        
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
        $data['contact'] = $this->cleanseContactData($request);

       
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
     * Extract contact information from request
     * 
     * @param Request $request [description]
     * 
     * @return array $data [description]
     */
    public function cleanseContactData(Request $request)
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
     * 
     * @return [type]                           [description]
     */
    public function reassign(LeadReassignFormRequest $request)
    {
        
        if (! request()->filled('branch')) {
            $branch = $this->_validateBranches($request);
            if (! $branch) {
                return redirect()->back()->withError('Invalid branch ids');
            }
        } else {
            $branch = request('branch');
        }
        
        $address = $this->lead->with('openActivities', 'openOpportunities')
            ->findOrFail(request('address_id'));
    
        $this->_reassignToBranch($address, $branch);
        $address->load('activities', 'opportunities', 'assignedToBranch');
        // auth()->user()->person;
        // address
      
        $this->_notifyLeadReassignment($branch, $address);
        
         // branch manager
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

    private function _reassignToBranch(Address $address, Array $branches)
    {
        if ($address->activities->count()) {
            $this->_reassignActivities($address->activities, $branches);
        }
        if ($address->opportunities->count()) {
            $this->_reassignOpportunities($address->opportunities, $branches);
        }
        
        return $address->assignedToBranch()->sync($branches);
    }
    private function _reassignActivities($activities, $branches)
    {
        foreach ($branches as $branch) {
            foreach ($activities as $activity) {
                $activity->update(['branch_id'=> $branch]);
            }
        }
       
    }

    private function _reassignOpportunities($opportunities, $branches)
    {
        foreach ($branches as $branch) {
            foreach ($opportunities as $opportunity) {
              
                $opportunity->update(['branch_id'=> $branch]); 
            } 
        } 
    }
}
