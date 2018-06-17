<?php

namespace App\Http\Controllers;
use Excel;
use App\Person;
use App\Note;
use App\Branch;
use App\Templead;
use App\LeadStatus;
use Illuminate\Http\Request;

class TempleadController extends Controller
{
    protected $templead;
    protected $person;

    public function __construct(Templead $lead, Person $person){
        $this->templead = $lead;
        $this->person = $person;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    
        $reps = $this->person->whereHas('templeads')
        ->withCount(['templeads','openleads','closedleads'])
        ->with('reportsTo','reportsTo.userdetails.roles','closedleads')
        ->get();

        return response()->view('templeads.index',compact('reps'));
    }

    
    /*public function salesteam(){
        $reps = $this->templead->select('sr_id')->where('sr_id','!=',0)->groupBy('sr_id')->pluck('sr_id');
        $salesteam = $this->person->with('userdetails','userdetails.roles')->whereIn('id',$reps)->get();
        return response()->view('templeads.salesteam',compact('salesteam'));

    }*/
    
    public function salesLeads($pid=null){
        $person = $this->getSalesRep($pid);
       // dd($person->findPersonsRole($person));
        // depending on role either return list of team and their leads
        // or the leads
        if($person->userdetails->can('accept_leads')){
        
            return $this->showSalesLeads($person);
        }elseif($person->userdetails->hasRole('Admin') or $person->userdetails->hasRole('Sales Operations')){
                return redirect()->route('newleads.index');
        }else{
            return $this->showSalesTeamLeads($person);
        }

    }


    private function showSalesLeads($person){
        $openleads = $this->getLeadsByTYpe('openleads',$person);
        $openleads =$openleads->limit('200')
                    ->get();
        

        $closedleads = $this->getLeadsByTYpe('closedleads',$person);
        $closedleads = $closedleads->with('relatedNotes')
                    ->limit('200')
                    ->get();
      
        return response()->view('templeads.show',compact('openleads','closedleads','person'));
    }


    private function showSalesTeamLeads($person){
        //dd($person);
        $reports = $person->descendantsAndSelf()->pluck('id')->toArray();
        $reps = $this->person->whereHas('templeads')
        ->withCount(['templeads','openleads','closedleads'])
        ->with('reportsTo','reportsTo.userdetails.roles','closedleads')
        ->whereIn('id',$reports)
        ->get();
        $rankings = $this->templead->rankLead($reps);
        return response()->view('templeads.team',compact('reps','person','rankings'));
    }

    public function salesLeadsMap($pid=null){
        $person = $this->getSalesRep($pid);
        $data['title']= $person->postName();
        $data['datalocation'] = "/api/newleads/".$person->id ."/map";
        $data['lat'] = $person->lat;
        $data['lng'] = $person->lng;
        $data['listviewref'] = route('salesrep.newleads',$pid);
        $data['zoomLevel'] =10;
        $data['type'] ='leads';
        $leads = $this->templead->whereHas('openleads', function ($q) use($pid){
            $q->where('person_id','=',$pid);
        })
        ->limit('200')
        
        ->get();

        $data['count']=count($leads);
        return response()->view('templeads.showmap',compact('data'));
    }

    public function getMapData($pid){
        $person = $this->getSalesRep($pid);
   
        $leads = $this->templead->whereHas('openleads', function ($q) use($person){
            $q->where('person_id','=',$person->id);
        })
        ->limit('200')
        ->get();
     
      //  $leads = $this->templead->where('sr_id','=',$person->id)->get();
        return response()->view('templeads.xml',compact('leads'));

    }

    private function getSalesRep($pid=null){
        if(! $pid){
            return $this->person->findOrFail(auth()->user()->person->id);
        }

        $person = $this->person->findOrFail($pid);

        if(auth()->user()->hasRole('Admin') or auth()->user()->hasRole('Sales Operations')){
           
           return $person;

        }
        $peeps = $person->descendantsAndSelf()->pluck('id')->toArray();
        if(in_array($pid,$peeps)){
            return $person;
        }
        
        return $this->person->findOrFail(auth()->user()->person->id);
        

        

    }

    private function getLeadsByTYpe($type,$person){
        return $this->templead->whereHas($type, function ($q) use($person){
            $q->where('person_id','=',$person->id);
        });

    }

    public function salesLeadsDetail ($id){

        $lead = $this->templead->with('salesrep','contacts','relatedNotes')->findOrFail($id);

        $leadStatuses = LeadStatus::pluck('status','id')->toArray();
       
        $branches = Branch::nearby($lead,100,5)->get();
        $rankingstatuses = $this->templead->getStatusOptions;
       
        return response()->view('templeads.detail',compact('lead','branches','leadStatuses','rankingstatuses'));

    }

    private function getSalesReps($leads){


        foreach ($leads as $lead){

            $salespersons =  $this->person
            ->whereHas('userdetails.roles',function ($q) {
             $q->whereIn('roles.id',[5]);
            })
            ->whereHas('userdetails.serviceline', function($q) {
                        $q->whereIn('serviceline_id', [5]);

            });
    
            $sr_id = $salespersons->nearby($lead,'100')->first();
            
            if( $sr_id ){

                $lead->sr_id = $sr_id->id;
             
            }else{
                $lead->sr_id = 0;
                
            }
           
            $lead->update();
        };

    }

     /**
     * Close prospect
     * @param  Request $request post contents
     * @param  int  $id      prospect (lead) id
     * @return [type]           [description]
     */
    public function close(Request $request, $id){
    
      $lead = $this->templead->with('salesrep')->findOrFail($id);
    
      $lead->salesrep()
        ->updateExistingPivot(auth()->user()->person->id,['rating'=>$request->get('ranking'),'status_id'=>3]);
        $this->addClosingNote($request,$id);
        return redirect()->route('salesrep.newleads',$lead->salesrep->first()->id)->with('message', 'Lead closed');
     }
    

    private function addClosingNote($request,$id){
        $note = new Note;
        $note->note = "Lead Closed:" .$request->get('comments');
        $note->type = 'newlead';
        $note->related_id = $id;
        $note->user_id = auth()->user()->id;
        $note->save();
    }

    public function export ($pid){
        $person = $this->getSalesRep($pid);
        

        Excel::create('Leads for '.$person->postName(),function($excel) use($person){
            $excel->sheet('Leads',function($sheet) use($person) {
                $leads = $this->templead->whereHas('salesrep', function ($q) use($person){
                    $q->where('person_id','=',$person->id);
                    })
                ->get();
                $statuses = LeadStatus::pluck('status','id')->toArray();
                $sheet->loadview('templeads.export',compact('leads','statuses'));
            });
        })->download('csv');
    }
}
