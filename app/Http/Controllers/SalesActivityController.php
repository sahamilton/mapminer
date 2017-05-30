<?php

namespace App\Http\Controllers;

use Mail;
use App\Salesactivity;
use App\SearchFilter;
use App\SalesProcess;
use App\Document;
use App\Location;
use App\Mail\SendCampaignMail;
use App\Mail\SendManagersCampaignMail;
use App\Mail\SendSenderCampaignMail;
use App\SalesOrg;
use App\Person;

use App\Http\Requests\SalesActivityFormRequest;
use Illuminate\Http\Request;

class SalesActivityController extends BaseController
{
   
    public $activity;
    public $vertical;
    public $process;
    public $document;
    public $location;
    public $salesorg;


    public function __construct(Salesactivity $activity, SearchFilter $vertical, SalesProcess $process, Document $document,Location $location, SalesOrg $salesorg){

        $this->activity = $activity;
        $this->vertical = $vertical; 
        $this->process = $process;
        $this->document = $document;
        $this->location = $location;
        $this->salesorg = $salesorg;
        parent::__construct($location);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activities = $this->activity->with('salesprocess','vertical')->get();
        
        return response()->view('salesactivity.index',compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $verticals = $this->vertical->vertical();
        $process = $this->process->pluck('step','id');

        return response()->view('salesactivity.create',compact('verticals','process'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->setDates($request->all());

        $activity = $this->activity->create($data);
        foreach ($request->get('salesprocess') as $process){
            foreach ($request->get('vertical') as $vertical){
                $activity->salesprocess()->attach($process,['vertical_id'=>$vertical]);
            }

        }
        return redirect()->route('salesactivity.index');
    }

    public function mycampaigns()
    { 
        
        $activities = $this->activity->with('salesprocess','vertical')
         ->when(count($this->userVerticals)>0,function($q) {
            $q->whereHas('vertical',function($q1) {
                $q1->whereIn('vertical_id',$this->userVerticals);
            });
        })
        ->where('datefrom','<=',date('Y-m-d'))
        ->where('dateto','>=',date('Y-m-d'))
        ->get();
        $calendar = \Calendar::addEvents($activities);
        return response()->view('salesactivity.calendar',compact('calendar'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
     
        $activity = $this->activity->with('salesprocess','vertical')->findOrFail($id);
        $lat = auth()->user()->person->lat;
        $lng = auth()->user()->person->lng;
        $verticals = array_unique ($activity->vertical->pluck('id')->toArray()); 

        $locations = $this->location->findNearbyLocations($lat,$lng,25,$number=null,$company=NULL,$this->userServiceLines, $limit=null, $verticals);
         

        return response()->view('salesactivity.show',compact('activity','locations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        $activity = $this->activity->with('salesprocess','vertical')->findOrFail($id);
        $verticals = $this->vertical->vertical();
        $process = $this->process->pluck('step','id');
        return response()->view('salesactivity.edit',compact('activity','verticals','process'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SalesActivityFormRequest $request, $id)
    {
        $activity = $this->activity->findOrFail($id);
        $data = $this->setDates($request->all());
        $activity->update($data);
        $activity->salesprocess()->detach();

        foreach ($data['salesprocess'] as $process){
            foreach ($data['vertical'] as $vertical){
                $activity->salesprocess()->attach($process,['vertical_id'=>$vertical]);
            }

        }

        return redirect()->route('salesactivity.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->activity->destroy($id);
        return redirect()->route('salesactivity.index');
    }

    public function announce($id){

        $activity = $this->activity->with('vertical')->findOrFail($id);
        $verticals = array_unique($activity->vertical->pluck('id')->toArray());
        $salesteam = $this->filterSalesReps($verticals);
        $verticals = array_unique($activity->vertical->pluck('filter')->toArray());
        $message = $this->constructMessage($activity,$verticals);
        return response()->view('salesactivity.salesteam',compact('salesteam','activity','message'));
    }


    public function email(Request $request, $id){

        $data['activity'] = $this->activity->with('vertical','salesprocess')->findOrFail($id);
        $data['verticals'] = array_unique($data['activity']->vertical->pluck('id')->toArray());
        $salesteam = $this->filterSalesReps($data['verticals']);

        $data['message'] = $request->get('message');;
        $data['count'] = count($salesteam);
        $this->notifySalesTeam($data,$salesteam);

        $this->notifyManagers($data,$salesteam);
        $this->notifySender($data);
        return response()->view('salesactivity.sendercampaign',compact('data'));

    }
    private function notifySalesTeam($data,$salesteam){
        foreach ($salesteam as $data['sales']){

            Mail::queue(new SendCampaignMail($data));
            
        }
    }

    private function notifySender($data){
        $data['sender'] = auth()->user()->email;
        Mail::queue(new SendSenderCampaignMail($data));

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
            Mail::queue(new SendManagersCampaignMail($data,$manager));
        }

    }
    private function constructMessage($activity,$verticals){

        $message = 
        $activity->title .  " campaign runs from " . $activity->datefrom->format('M j, Y'). " until " . $activity->dateto->format('M j, Y').
        ". ".$activity->description."</p>";
        $message.="This campaign focuses on: <ul>";
       
        $message.= "<li>" . implode("</li><li>",$activity->salesprocess->pluck('step')->toArray()). "</li>";
        
        $message .='</ul> for the following sales verticals:';
        $message .='<ul>';
 
        
            $message.= "<li>" . implode("</li><li>",$verticals). "</li>";
        
        $message.="</ul></p>";
        $message.="<p>Check out <strong><a href=\"".route('salesactivity.show',$activity->id)."\">MapMiner</a></strong> for resources, including nearby locations, to help you with this campaign.</p>";

        return $message;
    }

    private function filterSalesReps( $verticals){

        return Person::with('userdetails','reportsTo','reportsTo.userdetails')
        ->whereHas('userdetails.roles',function ($q){
            $q->where('role_id','=',5);
        })
        ->where(function($query) use($verticals){
            $query->whereHas('industryfocus',function ($q) use($verticals){
                $q->whereIn('search_filter_id',$verticals);
            })
            ->orHas('industryfocus','<',1);
        })
        ->whereNotNull('lat')
        ->whereNotNull('lng')
        ->get();
       
    }

     private function setDates($data){
        $data['datefrom'] = \Carbon\Carbon::createFromFormat('m/d/Y', $data['datefrom']);
        $data['dateto'] = \Carbon\Carbon::createFromFormat('m/d/Y', $data['dateto']);
        return $data;
    }
}
