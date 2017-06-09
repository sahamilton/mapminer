<?php

namespace App\Http\Controllers;

use Mail;
use Illuminate\Http\Request;
use App\LeadSource;
use App\Lead;
use App\Person;
use App\LeadStatus;
use App\Mail\NotifyLeadsAssignment;
use App\Mail\NotifyManagersLeadsAssignment;
use App\Mail\NotifySenderLeadsAssignment;
use App\Http\Requests\LeadSourceFormRequest;
use Carbon\Carbon;
class LeadSourceController extends Controller
{
    public $leadsource;
    public $leadstatus;
    public $person;
    public $lead;
    public function __construct(LeadSource $leadsource, LeadStatus $status, Lead $lead, Person $person){
        $this->leadsource = $leadsource;
        $this->leadstatus = $status;
        $this->person = $person;
        $this->lead = $lead;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leadsources = $this->leadsource->with('leads')->get();
        return response()->view('leadsource.index', compact('leadsources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return response()->view('leadsource.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeadSourceFormRequest $request)
    {
        $request->merge(['user_id'=>auth()->user()->id]);
        $leadsource = $this->leadsource->create($request->except(['datefrom','dateto']));
        $leadsource->update([
            'datefrom'=>Carbon::createFromFormat('m/d/Y',$request->get('datefrom')),
            'dateto'=>Carbon::createFromFormat('m/d/Y',$request->get('dateto')),
            'user_id'=>auth()->user()->id
            ]);

        return redirect()->route('leadsource.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $statuses = $this->leadstatus->pluck('status','id')->toArray();

        $leadsource = $this->leadsource
                ->with('leads','leads.salesteam','author')
                ->whereHas('leads.salesteam',function($q){
                    $q->where('datefrom','<=',date('Y-m-d'))
                        ->where('dateto','>=',date('Y-m-d'));
                })
                
               ->findOrFail($id);
        $salesteams = $this->salesteam($leadsource->leads);
        return response()->view('leadsource.show',compact('leadsource','statuses','salesteams'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leadsource = $this->leadsource->with('leads')->findOrFail($id);
        return response()->view('leadsource.edit',compact('leadsource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LeadSourceFormRequest $request, $id)
    {
        $leadsource= $this->leadsource->findOrFail($id);
        $leadsource->update($request->except('_method', '_token','datefrom','dateto'));
        $leadsource->update([
            'datefrom'=>Carbon::createFromFormat('m/d/Y',$request->get('datefrom')),
            'dateto'=>Carbon::createFromFormat('m/d/Y',$request->get('dateto'))]);
        return redirect()->route('leadsource.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->leadsource->destroy($id);
        return redirect()->route('leadsource.index');
    }

    public function announce($id){

        $source = $this->leadsource->with('leads','leads.salesteam','leads.vertical')
            ->whereHas('salesteam',function($q){
                    $q->where('datefrom','<=',date('Y-m-d'))
                        ->where('dateto','>=',date('Y-m-d'));
                })

        ->findOrFail($id);
        
        $salesteam = $this->salesteam($source->leads);
        
        $verticals = $this->verticals($source->leads);
        $message = $this->createMessage($source,$verticals);
        return response()->view('leadsource.salesteam',compact('source','salesteam','message'));
    }


    private function salesteam($leads){
        $salesreps = array();
        
        foreach ($leads as $lead){
            if(count($lead->salesteam)>0){
                $reps = $lead->salesteam->pluck('id')->toArray();
                foreach ($reps as $rep){
                    if(! in_array($rep,$salesreps)){
                        $salesreps[] = $rep;
                    }
                }          
            }
        }

       return $this->person->with('userdetails','reportsTo','salesleads')
               ->whereIn('id',$salesreps)
               ->whereHas('salesleads',function ($q) use($leads){
                    $q->whereIn('lead_id',$leads->pluck('id')->toArray())
                    ->where('datefrom','<=',date('Y-m-d'))
                     ->where('dateto','>=',date('Y-m-d'));

               })->get();
      
       
    }

    private function verticals($leads){
        $verticals = array();
        
        foreach ($leads as $lead){
            if(count($lead->vertical)>0){
                $filters = $lead->vertical->pluck('filter','id')->toArray();
               
                foreach ($filters as $vertical){
                    if(! in_array($vertical,$verticals)){
                        $verticals[] = $vertical;
                    }
                }          
            }
        }
      
       return $verticals;
      
       
    }
    private function createMessage($source,$verticals){
        $message = "You have new leads offered to you in the " . $source->source." lead campaign. ";
        $message .= $source->description;
        $message .= "<p>These leads are available from ".$source->datefrom->format('M j, Y') . " until "  .$source->dateto->format('M j, Y')."</p>";
        $message .= "Leads in this campaign are for the following sales verticals:";
        $message .="<ul>";
        foreach ($verticals as $key=>$filter){
            $message .= "<li>".$filter."</li>";
        }
        $message .= "</ul>";
        $message .="Check out <strong><a href=\"".route('salesleads.index'). "\">MapMiner</a></strong> to accept these leads and for other resources to help you with these leads.";
        return $message;
}

    public function email(Request $request, $id){


        $data['source'] = $this->leadsource->with('leads','leads.salesteam')
        ->whereHas('leads.salesteam',function($q){
                    $q->where('datefrom','<=',date('Y-m-d'))
                        ->where('dateto','>=',date('Y-m-d'));
                })->findOrFail($id);
        $salesteam = $this->salesteam($data['source']->leads);
        
        $data['message'] = $request->get('message');;
        $data['count'] = count($salesteam);
        $this->notifySalesTeam($data,$salesteam);
        $this->notifyManagers($data,$salesteam);
        $this->notifySender($data);
        return response()->view('leadsource.senderleads',compact('data'));

    }
    private function notifySalesTeam($data,$salesteam){
        
        foreach ($salesteam as $team){
            
            
                Mail::queue(new NotifyLeadsAssignment($data,$team));
            
        }
    }

    private function notifySender($data){
        $data['sender'] = auth()->user()->email;
        Mail::queue(new NotifySenderLeadsAssignment($data));

    }

    private function notifyManagers($data,$salesteam){
        $managers = array();
        foreach ($salesteam as $salesrep){
            if($salesrep->reportsTo){
                $data['managers'][$salesrep->reportsTo->id]['team'][]=$salesrep->firstname ." ". $salesrep->lastname;
                $data['managers'][$salesrep->reportsTo->id]['email']=$salesrep->reportsTo->userdetails->email;
                $data['managers'][$salesrep->reportsTo->id]['firstname']=$salesrep->reportsTo->firstname;
                $data['managers'][$salesrep->reportsTo->id]['lastname']= $salesrep->reportsTo->lastname;
            }
        }
        
        foreach ($data['managers'] as $manager){
           
                Mail::queue(new NotifyManagersLeadsAssignment($data,$manager));
          
            
        }

    }
    private function constructMessage($leadsource,$verticals){

        $message = 
        $leadsource->title .  " These leads are available from  " . $leadsource->datefrom->format('M j, Y'). " until " . $leadsource->dateto->format('M j, Y').
        ". ".$leadsource->description."</p>";
        $message.="These leads are for the following sales verticals:";
        $message .='<ul>';
 
        
            $message.= "<li>" . implode("</li><li>",$verticals). "</li>";
        
        $message.="</ul></p>";
        $message.="<p>Check out <strong><a href=\"".route('saleslead.index')."\">MapMiner</a></strong> to accept these leads and for resources  to help you with close new business.</p>";

        return $message;
    }

    public function assignLeads($id){

        $leads = $this->lead->where('lead_source_id','=',$id)
        ->with('leadsource')
        ->whereNotNull('lat')
        ->whereNotNull('lng')
        ->has('salesteam', '<', 1)
        ->get();
        $data['reps'] = $this->findClosestRep($leads);
        $data['branches'] = $this->findClosestBranches($leads);
        return response()->view('leadsource.leadsassign',compact('leads','data'));
    }
    

    private function findClosestRep($leads){

        foreach ($leads as $lead){
            $data['lat'] = $lead->lat;
            $data['lng'] = $lead->lng;
            $data['distance'] = 1000;
            $data['number'] = 1;
            $leadinfo[$lead->id]=$this->person->findNearByPeople($data['lat'],$data['lng'],$data['distance'],$data['number'],'Sales');

        }
        return $leadinfo;
    }

     private function findClosestBranches($leads){

        foreach ($leads as $lead){
            $data['lat'] = $lead->lat;
            $data['lng'] = $lead->lng;
            $data['distance'] = 1000;
            $data['number'] = 1;
            $branch = new \App\Branch;
            $leadinfo[$lead->id]=$branch->findNearByBranches($data['lat'],$data['lng'],$data['distance'],$data['number'],[5]);

        }
        return $leadinfo;
    }
}
