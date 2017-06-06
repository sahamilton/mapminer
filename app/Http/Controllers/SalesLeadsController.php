<?php

namespace App\Http\Controllers;
use App\Person;
use App\User;
use App\Lead;
use App\LeadStatus;
use Illuminate\Http\Request;

class SalesLeadsController extends Controller
{
    public $salesleads;
    public $person;
    public $leadstatus;

    public function __construct(Lead $saleslead, Person $person, LeadStatus $status){

        $this->salesleads = $saleslead;
        $this-> person = $person;
        $this->leadstatus = $status;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // limit to active verticals
        $statuses = $this->leadstatus->pluck('status','id')->toArray();
        $title = ' Leads Assigned to ';
        $leads = $this->person->where('user_id','=',auth()->user()->id)
        ->with('ownedLeads','offeredLeads','ownedLeads.vertical','offeredLeads.vertical')->firstOrFail();
        if(count($leads->ownedLeads) >= \Config::get('leads.owned_limit')) { 
            $owned = $this->ownedLimit;      
            return response()->view('salesleads.index',compact('leads','statuses','title','owned'));
        }

        return response()->view('salesleads.index',compact('leads','statuses','title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the owned lead.
     *
     * @param  int  $id
     * @query( select logged in users owned lead by id)
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
     
        $sources = $this->leadstatus->pluck('status','id')->toArray();

        $lead = $this->salesleads
            ->whereHas('salesteam',function ($q) use ($sources){
                $q->where('person_id','=',auth()->user()->person->id)
                ->where('status_id','=',array_search('Owned',$sources));
            })->with('leadsource','vertical','relatedNotes','salesteam')
            ->findOrFail($id);
        $rank = $this->salesleads->rankMyLead($lead->salesteam); 

        return response()->view('salesleads.show',compact('lead','sources','rank'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function mapleads($pid){

        $leads = $this->person->with('salesleads','ownedLeads')->findOrFail($pid);
        if(count($leads->ownedLeads) >= $this->ownedLimit){
            $mapleads = $leads->ownedleads;
        }else{
            $mapleads = $leads->salesleads;
        }
        $dom = new \DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);
        foreach($mapleads as $lead){
          // ADD TO XML DOCUMENT NODE
         
          $node = $dom->createElement("marker");
          $newnode = $parnode->appendChild($node);
          $newnode->setAttribute("name",$lead->businessname);
          $newnode->setAttribute("address", $lead->fullAddress());
          $newnode->setAttribute("lat", $lead->lat);
          $newnode->setAttribute("lng", $lead->lng);
         
        }
        
        echo $dom->saveXML();
        //return response()->make($markers->salesleads, '200')->header('Content-Type', 'text/xml');
    }
    public function accept($id){
     
      $lead = $this->salesleads->with('salesteam')->find($id);
      if($sales = $this->owned($lead->salesteam)){
        return redirect()->route('salesleads.index')->with('warning','This lead has already claimed been by ' . $sales->postName());
      }
      $salesteam = $lead->salesteam->pluck('id');
      
      foreach ($salesteam as $id){
        if($id == auth()->user()->person->id){
            $lead->salesteam()->updateExistingPivot($id,['status_id'=>2]);
        }else{
            $lead->salesteam()->updateExistingPivot($id,['status_id'=>3]);
        }
      }
      
       return redirect()->route('salesleads.index');
    }
    private function owned($salesteam){
        foreach ($salesteam as $sales) {
            if($sales->pivot->status_id == 2){
               return $sales;
            }
        }
        return false;
    }
    public function decline($id){
     
      $lead = $this->salesleads->with('salesteam')->find($id);

      $lead->salesteam()->updateExistingPivot(auth()->user()->person->id,['status_id'=>4]);

       return redirect()->route('salesleads.index');
    }

    public function rank(Request $request)
    {

       $user = User::where('api_token','=',$request->get('api_token'))
       ->with('person')->first();
     ;
       if($user->person->salesleads()->sync([$request->get('id') => ['person_id'=>$user->person->id,'rating' => $request->get('value')]],false))
            {
                dd($request->get('value'));
                return 'success';
            }
        return 'error';
    
       
    }

    private function filterLeadsByStatus($leads, Array $statuses){
     
        foreach ($leads->salesleads as $lead) {
            
            if(! in_array($lead->pivot->status_id,$statuses)){
             
                $leads->salesleads->forget($lead->id);
        
            }
        }
        return $leads;
    }

    public function close(Request $request, $id){
     
      $lead = $this->salesleads->with('salesteam')->findOrFail($id);
     // $lead->update(['leadstatus'=>$request->get('status_id')]);
      $lead->salesteam()
        ->updateExistingPivot(auth()->user()->person->id,['status_id'=>$request->get('status_id')]);
    return redirect()->route('salesleads.index')->with('message', 'Lead closed');
  }
}
