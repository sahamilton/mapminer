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



    public function __construct(Address $lead, Person $person, Branch $branch)
    {

        $this->lead = $lead;
        $this->person = $person;
        $this->branch = $branch;
    }

    /**
     * Display a listing of all leads.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
       if(!  $myBranches = $this->person->myBranches()){
        return redirect()->back()->withError('You are not assigned to any branches');
       }
       
        $branch = array_keys($myBranches);

        $data = $this->getBranchLeads([reset($branch)]);
        
        $title= $data['branches']->first()->branchname . " leads";
        return response()->view('myleads.branches', compact('data', 'myBranches','title'));
        // how to get the distance for each branch
        // get my branches
        // get addresses that are leads that are assigned to a branch
        //
    }

    public function branchLeads(Request $request, Branch $branch){

        if (request()->has('branch')) {
            $branch = request('branch');
        } else {
           $branch = $branch->id;
        }
        $myBranches = $this->person->myBranches();
       
        if(! ( $myBranches)  or ! in_array($branch,array_keys($myBranches))){
            return redirect()->back()->withError('You are not assigned to any branches');
       }
       
         
        $data = $this->getBranchLeads([$branch]);
       
        $title= $data['branches']->first()->branchname . " leads";
        return response()->view('myleads.branches', compact('data', 'myBranches','title'));
    }

    private function getBranchLeads(Array $branch){
        $data['leads'] = $this->lead->wherehas('assignedToBranch', function ($q) use ($branch) {
            $q->whereIn('branches.id', $branch);
        })->with('assignedToBranch')->get();

        $data['branches'] = $this->getBranches($branch);
        return $data;
    }

     private function getBranches(Array $branches)
       {
        return  $this->branch->with('leads', 'manager')
            ->whereIn('id', $branches)
            ->get();
       }
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MyLeadFormRequest $request)
    {

     
        if (! $data = $this->cleanseInput($request)) {
            return redirect()->back()->withError('Unable to geocode that address');
        }

             

        $lead = $this->lead->create($data['lead']);
        if (count($data['branch'])>0) {
            $lead->assignedToBranch()->attach($data['branch']);
        }
        
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
        return redirect()->route('address.show', $lead)->withMessage('Lead Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MyLead  $myLeads
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MyLead  $myLeads
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MyLead  $myLeads
     * @return \Illuminate\Http\Response
     */
    public function update(MyLeadFormRequest $request, MyLead $mylead)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MyLead  $myLeads
     * @return \Illuminate\Http\Response
     */
    public function destroy($mylead)
    {
    }
    

    

    private function cleanseInput(Request $request)
    {
        $address = request('address'). ' ' . request('city').' ' .request('state').' ' .request('zip');
        if (! $geodata = $this->lead->geoCodeAddress($address)) {
            return false;
        }
        $data['lead'] = array_merge(request()->all(), $geodata);
        $data['lead']['businessname'] = $data['lead']['companyname'];
        $data['lead']['phone'] = preg_replace("/[^0-9]/", "", $data['lead']['phone']);
        if (request()->filled('type')) {
            $data['lead']['addressable_type'] = request('type');
        } else {
            $data['lead']['addressable_type'] = 'lead';
        }
        
        $data['lead']['lead_source_id'] = '4';
        $data['lead']['type'] = 'lead';
        $data['lead']['user_id'] =auth()->user()->id;
    

        $data['team']['user_id'] = auth()->user()->id;
        $data['team']['type'] = 'mylead';
        $data['team']['status_id'] =2;
        if (request()->has('branch') && is_array(request('branch'))) {
            foreach (request('branch') as $branch) {
                $data['branch'][$branch]=['status_id'=>1];
            }
        } else {
            $data['branch'] = [request('branch')=>['status_id'=>2]];
        }
        if (request()->filled('contact')) {
             $data['contact']['fullname'] = request('contact');
            $name = explode(' ', request('contact'), 2);
            $data['contact']['firstname'] = $name[0];
            if (isset($name[1])) {
                $data['contact']['lastname'] = $name[1];
            }
            
            $data['contact']['title'] = request('contact_title');
            $data['contact']['email'] = request('contactemail');
            $data['contact']['phone'] =  preg_replace("/[^0-9]/", "", request('phone'));
        }
       

        return $data;
    }
    public function reassign(LeadReassignFormRequest $request)
    {
        
        if (! request()->filled('branch')) {
            $branch = $this->validateBranches($request);
        } else {
            $branch = request('branch');
        }
        $address = $this->lead->findOrFail(request('address_id'));
       
        $address->assignedToBranch()->sync($branch);
        // auth()->user()->person
        // address
       
        $this->notifyLeadReassignment($branch, $address);
        
         // branch manager
        return redirect()->back()->withSuccess('Lead reassigned');
    }
    /*




    */
    private function notifyLeadReassignment(array $branch, Address $address)
    {
        $branches = $this->branch->has('manager')->with('manager')->whereIn('id', $branch)->get();
        $sender = $this->person->with('userdetails')->where('user_id','=',auth()->user()->id)->first();
     
        foreach ($branches as $branch) {
            foreach ($branch->manager as $manager) {
                \Mail::queue(new NotifyLeadReassignment($address, $branch, $manager,$sender));
            }
        }
    }
    /*
    validate branch in branch string


    */
    private function validateBranches(Request $request)
    {

        $branch = explode(",", request('branch_id'));
        $branches = $this->branch->whereIn('id', $branch)->pluck('id')->toArray();
        if (array_diff($branch, $branches)) {
            return redirect()->back()->withError('Invalid branch id '. implode(",", array_diff($branch, $branches)));
        }
        return $branch;
    }
}
