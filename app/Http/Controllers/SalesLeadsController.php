<?php
namespace App\Http\Controllers;
use App\Person;
use App\User;
use App\Lead;
use App\Note;
use App\LeadStatus;
use Excel;

use Illuminate\Http\Request;

class SalesLeadsController extends Controller
{
    public $salesleads;
    public $person;
    public $leadstatus;

    public function __construct(Lead $saleslead, Person $person, LeadStatus $status){

        $this->salesleads = $saleslead;
        $this->person = $person;
        $this->leadstatus = $status;
        
        $this->ownedLimit =\Config::get('leads.owned_limit');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
        // limit to active verticals
        $statuses = $this->salesleads->statuses;
        $title = ' Prospects Assigned to ';
        $rankingstatus = $this->salesleads->getStatusOptions;
        $limit = $this->ownedLimit;
        $leads = $this->person->where('user_id','=',auth()->user()->id)
            ->with('ownedLeads','offeredLeads','ownedLeads.vertical','offeredLeads.vertical')->firstOrFail();
        if($this->person->where('user_id','=',auth()->user()->id)->first()->isLeaf()){
            $manager=false;
            return response()->view('salesleads.index',compact('leads','statuses','manager','title','rankingstatus','limit'));
        }else{
            $manager = true;
            return response()->view('salesleads.managers',compact('leads','statuses','manager','title','rankingstatus','limit'));
        }

        
    }


    public function showrep($pid){
        $leads = $this->person
            ->with('ownedLeads','offeredLeads','ownedLeads.vertical','offeredLeads.vertical')->findOrFail($pid);
        $statuses = $this->leadstatus->pluck('status','id')->toArray();
        $title = ' Prospects Assigned to ';
        $manager=true;
        $limit = $this->ownedLimit;
        return response()->view('salesleads.index',compact('leads','statuses','title','manager','limit'));
    
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

        
        // refactor ... clumsy way to get owned
        $lead = $this->salesleads->with('leadsource','vertical','relatedNotes')
        ->whereHas('ownedBy',function ($q){
                $q->where('persons.id','=',auth()->user()->person->id);
        })
        ->findOrFail($id);
     
        $rankingstatus = $this->salesleads->getStatusOptions;
        $statuses = $this->salesleads->getStatusOptions;
        $rank = $this->salesleads->rankMyLead($lead->salesteam); 
        $manager=false;

        return response()->view('salesleads.show',compact('lead','statuses','rankingstatus','rank','manager'));
    }
     /*
     * @param  int  $id
     * @query( select logged in users owned lead by id)
     * @return \Illuminate\Http\Response
     */
    public function showrepdetail($id,$pid)
    {
     
        $sources = $this->leadstatus->pluck('status','id')->toArray();
        $lead = $this->salesleads
            ->whereHas('salesteam',function ($q) use ($sources,$pid){
                $q->where('person_id','=',$pid)
                ->where('status_id','=',array_search('Owned',$sources));
            })
            ->where('datefrom','<=',date('Y-m-d'))
            ->where('dateto','>=',date('Y-m-d'))
            ->with('leadsource','vertical','relatedNotes','salesteam')
            ->findOrFail($id);
        $rank = $this->salesleads->rankMyLead($lead->salesteam); 
        $manager=true;

        return response()->view('salesleads.show',compact('lead','sources','rank','manager'));
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
    return response()->view('salesleads.leadsxml', compact('mapleads'))->header('Content-Type', 'text/xml');
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
            $lead->salesteam()->detach($id);
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

      $lead->salesteam()->detach(auth()->user()->person->id);

       return redirect()->route('salesleads.index');
    }

    public function rank(Request $request)
    {


       $user = User::where('api_token','=',request('api_token'))
       ->with('person')->first();
       if($user->person->salesleads()->sync([request('id') => ['person_id'=>$user->person->id,'rating' => request('value')]],false))

            {
                
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
    /**
     * Close prospect
     * @param  Request $request post contents
     * @param  int  $id      prospect (lead) id
     * @return [type]           [description]
     */
    public function close(Request $request, $id){
    
      $lead = $this->salesleads->with('salesteam')->findOrFail($id);
    
      $lead->salesteam()
        ->updateExistingPivot(auth()->user()->person->id,['rating'=>request('ranking'),'status_id'=>3]);
        $this->addClosingNote($request,$id);
        return redirect()->route('salesleads.index')->with('message', 'Prospect closed');
     }
    

    private function addClosingNote($request){
        $note = new Note;

        $note->note = "Prospect Closed:" .request('comments');
        $note->type = 'prospect';
        $note->related_id = request('lead_id');
        $note->user_id = auth()->user()->id;
        $note->save();
    }

    public function download(Request $request){
         
     if(request()->has('type')){
        $type = request('type');

    }else{
        $type = 'xlsx';
    }
    
    Excel::download('Prospects'.time(),function($excel) {
            $excel->sheet('Prospects',function($sheet) {
                $leads = $this->person->where('user_id','=',auth()->user()->id)
                ->with('ownedLeads','ownedLeads.relatedNotes')->firstOrFail();
               $statuses = $this->leadstatus->all()->pluck('status','id')->toArray();
  
                $sheet->loadView('salesleads.export',compact('leads','statuses'));
            });
        })->download('csv');
    }
}