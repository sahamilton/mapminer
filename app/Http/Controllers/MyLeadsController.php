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

       dd(request()->all());
      
        if(! $data = $this->cleanseInput($request)){
            return redirect()->back()->withError('Unable to geocode that address');
        }
        $data['addressable_type'] = 'lead';
      
        $lead = $this->lead->create($data['lead']);
        if(count($data['branch'])>0){
            $lead->assignedToBranch()->attach($data['branch']);
        }
        
        if($data['contact']){
            $lead->contacts()->create($data['contact']);
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
    public function destroy()
    {
       
    }
    

    private function cleanseInput(Request $request){
        $address = request('address'). ' ' . request('city').' ' .request('state').' ' .request('zip');
        if(! $geodata = $this->lead->geoCodeAddress($address)){
            return false;
            
        }
       
        $data['lead'] = array_merge(request()->all(),$geodata);
        $data['contact']['fullname'] = request('fullname');
        $data['contact']['firstname'] = request('firstname');
        $data['contact']['lastname'] = request('lastname');
        $data['contact']['title'] = request('title');
        $data['contact']['email'] = request('email');
        $data['contact']['phone'] =  preg_replace("/[^0-9]/","",request('phone'));



        $data['lead']['businessname'] = $data['lead']['companyname'];
        $data['lead']['phone'] = preg_replace("/[^0-9]/","",$data['lead']['phone']);
       
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
      
        return $data;
    }

}

