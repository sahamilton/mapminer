<?php

namespace App\Http\Controllers;

use App\MyLead;
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
    public function __construct(MyLead $lead,Person $person){
        $this->lead = $lead;
        $this->person = $person;
        parent::__construct($lead);
       
    }

    /**
     * Display a listing of all leads.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = $this->lead->myLeads()->get();
       
    
        $leads = $this->lead->distanceFromMe($leads);
        
        return response()->view('myleads.index',compact('leads'));
    }


    public function closedleads()
    {
        $leads = $this->lead->myLeads([3])->get();
       
        $leads = $this->lead->distanceFromMe($leads);
        
        $statuses = LeadStatus::all()->pluck('status','id')->toArray();
        return response()->view('myleads.closed',compact('leads','statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('myleads.create');
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
       
        $lead = $this->lead->fill($data['lead']);
        $lead->save();
        $lead->salesteam()->attach($lead->id, $data['team']);
        
        return redirect()->route('myleads.show',$lead)->withMessage('Lead Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MyLead  $myLeads
     * @return \Illuminate\Http\Response
     */
    public function show(MyLead $mylead)
    {
        $people = $this->lead->findNearByPeople($mylead);
        $branches = $this->lead->findNearByBranches($mylead);

        $mylead = $mylead->with('salesteam','relatedLeadNotes','relatedLeadNotes.relatedContact','contacts')->findOrFail($mylead->id);
      
        $rankingstatuses = $this->lead->getStatusOptions;

        return response()->view('myleads.show',compact('mylead','people','rankingstatuses','branches'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MyLead  $myLeads
     * @return \Illuminate\Http\Response
     */
    public function edit(MyLead $mylead)
    {
        return response()->view('myleads.edit',compact('mylead'));
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
        $data = $this->cleanseInput($request);
        if($myLead->update($data)){
            return redirect()->route('myleads.show',$mylead->id)->withMessage("Lead Updated");
        }else{
            return redirect()->route('myleads.show',$mylead->id)->withError("Unable to update lead");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MyLead  $myLeads
     * @return \Illuminate\Http\Response
     */
    public function destroy(MyLead $myleads)
    {
        if($myleads->destroy()){
            return redirect()->back()->withMessage('Lead deleted');
        }else{
            return redirect()->back()->withError('Unable to delete lead');
        }
    }

    public function close(Request $request){
        $lead = $this->lead->with('salesteam')->findOrFail(request('lead_id'));

        $lead->salesteam()
        ->updateExistingPivot(auth()->user()->person->id,['rating'=>request('ranking'),'status_id'=>3]);
        $lead->addClosingNote($request);
        return redirect()->route('myleads.index')->withMessage('Lead Closed');
    }

    private function cleanseInput(Request $request){
        
        if(! $geodata = $this->lead->geoCodeAddress(request('address'))){
            return redirect()->back()->withError('Unable to geocode that address');
        }

        $data['lead'] = array_merge(request()->all(),$geodata);
        $data['lead']['businessname'] = $data['lead']['companyname'];
        $data['lead']['phone'] = preg_replace("/[^0-9]/","",$data['lead']['phone']);
        $data['lead']['lead_source_id']=4;
        $data['team']['person_id'] = auth()->user()->person->id;
        $data['team']['type'] = 'mylead';
        $data['team']['status_id'] =2;
        return $data;
    }

}
