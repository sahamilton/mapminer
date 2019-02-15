<?php
namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use App\Person;
use App\LeadStatus;
use App\Mail\NotifyWebLeadsBranchAssignment;
use App\Http\Requests\MyLeadFormRequest;

class MyLeadsController extends BaseController
{
   
    public $lead;
    public $me;
    public $user;
    public $person;
    public function __construct(Address $lead,Person $person){

        $this->lead = $lead;
        $this->person = $person;

       
    }

    /**
     * Display a listing of all leads.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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

     
        if(! $data = $this->cleanseInput($request)){
            return redirect()->back()->withError('Unable to geocode that address');
        }

             

        $lead = $this->lead->create($data['lead']);
        if(count($data['branch'])>0){
            $lead->assignedToBranch()->attach($data['branch']);
        }
        
        if(isset($data['contact'])){
           
            $lead->contacts()->create($data['contact']);

        }
        if(request()->filled('addressable_type')){
           switch(request('addressable_type')){
            case 'weblead':

                $lead->weblead()->create(request()->all());
            break;


           }
           
            $lead->load('contacts',request('addressable_type'));

        }else{
          $lead->load('contacts');  
        }
    

        if(request('notify')==1){
            $branches = \App\Branch::with('manager','manager.userdetails')->whereIn('id',array_keys($data['branch']))->get();
           
            foreach ($branches as $branch){
                foreach($branch->manager as $manager){
                    \Mail::queue(new NotifyWebLeadsBranchAssignment($lead,$branch,$manager));
                }
                
            }
            
        }
        return redirect()->route('address.show',$lead)->withMessage('Lead Created');
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
    public function destroy( $mylead)
    {
        
    }
    

    public function addToBranchLeads(Address $address, Request $request){
        dd($address,$request);
        $address->assignedToBranch()->firstOrCreate(request()->except('_token'));
        return redirect()->back()->withMessage('Added to Branch Leads');
    }

    private function cleanseInput(Request $request){
        $address = request('address'). ' ' . request('city').' ' .request('state').' ' .request('zip');
        if(! $geodata = $this->lead->geoCodeAddress($address)){
            return false;
            
        }       
        $data['lead'] = array_merge(request()->all(),$geodata);
        $data['lead']['businessname'] = $data['lead']['companyname'];
        $data['lead']['phone'] = preg_replace("/[^0-9]/","",$data['lead']['phone']);
        if(request()->filled('type')){
            $data['lead']['addressable_type'] = request('type');
        }else{
           $data['lead']['addressable_type'] = 'lead'; 
        }
        
        $data['lead']['lead_source_id'] = '4';
        $data['lead']['type'] = 'lead';
        $data['lead']['user_id'] =auth()->user()->id;
    

        $data['team']['user_id'] = auth()->user()->id;
        $data['team']['type'] = 'mylead';
        $data['team']['status_id'] =2;
        if(request()->has('branch') && is_array(request('branch'))){
            foreach (request('branch') as $branch){
                $data['branch'][$branch]=['status_id'=>1];
            }
        }else{
            $data['branch'] = [request('branch')=>['status_id'=>2]];
        }
        if(request()->filled('contact')){
             $data['contact']['fullname'] = request('contact');
            $name = explode(' ', request('contact'), 2);
            $data['contact']['firstname'] = $name[0];
            if(isset($name[1])){
                $data['contact']['lastname'] = $name[1];
            }
            
            $data['contact']['title'] = request('contact_title');
            $data['contact']['email'] = request('contactemail');
            $data['contact']['phone'] =  preg_replace("/[^0-9]/","",request('phone'));
        }
       

        return $data;
    }

}

