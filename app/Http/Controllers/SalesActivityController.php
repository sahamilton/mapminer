<?php

namespace App\Http\Controllers;


use App\Salesactivity;
use App\SearchFilter;
use App\SalesProcess;
use App\Document;
use App\Location;

use App\Lead;
use App\LeadStatus;
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


    public function __construct(Salesactivity $activity, SearchFilter $vertical, SalesProcess $process, Document $document,Location $location, Lead $lead){

        $this->activity = $activity;
        $this->vertical = $vertical; 
        $this->process = $process;
        $this->document = $document;
        $this->location = $location;
       
        $this->lead = $lead;
        parent::__construct($location);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($vertical = null)
    {
        
        $query = $this->activity->with('salesprocess','vertical');
        if($vertical){
          $query = $query->whereHas('vertical',function ($q) use($vertical){
              $q->whereIn('vertical_id',[$vertical]);
          });
        }
        $activities = $query->get();
        $calendar = \Calendar::addEvents($activities);

        return response()->view('salesactivity.index',compact('activities','calendar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $verticals = $this->vertical->industrysegments();
  
        $process = $this->process->pluck('step','id');

        return response()->view('salesactivity.create',compact('verticals','process'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SalesActivityFormRequest $request)
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
        
        $activity = $this->activity->with('salesprocess')->findOrFail($id);
        $statuses = LeadStatus::pluck('status','id')->toArray();
        $person = Person::findOrFail(auth()->user()->person->id);
        if($person->isLeaf()){
            if(auth()->user()->person->lat){
            $lat = auth()->user()->person->lat;
            $lng = auth()->user()->person->lng;
            $verticals = array_unique ($activity->vertical()->pluck('searchfilters.id')->toArray()); 

            $locations = $this->location->findNearbyLocations($lat,$lng,25,$number=null,$company=NULL,$this->userServiceLines, $limit=null, $verticals);
        }else{
            $locations = array();
        }
        // find all lead locations for the logged in user in these verticals
        $leads = $this->lead->myLeads($verticals)->get();

        return response()->view('salesactivity.show',compact('activity','locations','leads','statuses'));
        }
        
        
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
        $verticals = $this->vertical->industrysegments();
      
        $process = $this->process->pluck('step','id');
        return response()->view('salesactivity.edit',compact('activity','process','verticals'));
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

   public function campaignDocuments($id){
        $activity = $this->activity->findOrFail($id);
        $documents = $activity->relatedDocuments();
        return response()->view('salesactivity.campaigndocuments',compact('activity','documents'));



   }

     private function setDates($data){
        $data['datefrom'] = \Carbon\Carbon::createFromFormat('m/d/Y', $data['datefrom']);
        $data['dateto'] = \Carbon\Carbon::createFromFormat('m/d/Y', $data['dateto']);
        return $data;
    }
}
