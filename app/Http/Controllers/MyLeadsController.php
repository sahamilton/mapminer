<?php
namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use App\Person;
use App\LeadStatus;

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
      
        $data = $this->cleanseInput($request);

        $lead = $this->lead->create($data['lead']);
  
        $lead->branchLead()->attach($data['branch']);
        
        
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
        
        if(! $geodata = $this->lead->geoCodeAddress(request('address'))){
            return redirect()->back()->withError('Unable to geocode that address');
        }

        $data['lead'] = array_merge(request()->all(),$geodata);
        $data['lead']['businessname'] = $data['lead']['companyname'];
        $data['lead']['phone'] = preg_replace("/[^0-9]/","",$data['lead']['phone']);
        $data['lead']['lead_source_id']=4;
        $data['lead']['type'] = 'lead';
        $data['team']['user_id'] = auth()->user()->id;
        $data['team']['type'] = 'mylead';
        $data['team']['status_id'] =2;
        $data['branch']=request()->branch_id;
        return $data;
    }

}

