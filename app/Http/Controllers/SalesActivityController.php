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
    public $person;


    public function __construct(Salesactivity $activity, SearchFilter $vertical, SalesProcess $process, Document $document,Location $location, Person $person,Lead $lead){

        $this->activity = $activity;
        $this->vertical = $vertical; 
        $this->process = $process;
        $this->document = $document;
        $this->location = $location;
        $this->person = $person;
       
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
        $data = $this->setDates(request()->all());

        $activity = $this->activity->create($data);
        foreach (request('salesprocess') as $process){
            foreach (request('vertical') as $vertical){
                $activity->salesprocess()->attach($process,['vertical_id'=>$vertical]);
            }

        }

        $reps = $activity->campaignSalesReps();
        $activity->campaignparticipants()->attach($reps);


        return redirect()->route('salesactivity.index');
    }

    public function mycampaigns()
    { 
       
        $activities = $this->activity->with('salesprocess','vertical')
         /*
         removed so all sales reps see all campaigns

          ->when(count($this->userVerticals)>0,function($q) {
            $q->whereHas('vertical',function($q1) {
                $q1->whereIn('vertical_id',$this->userVerticals);
            });
        })*/
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
        $verticals = array_unique($activity->vertical->pluck('id')->toArray());
        $statuses = LeadStatus::pluck('status','id')->toArray();
        $person = Person::findOrFail(auth()->user()->person->id);
        if($person->isLeaf()){
            if(auth()->user()->person->lat){
                $location = new Location;

                $location->lat = auth()->user()->person->lat;
                $location->lng = auth()->user()->person->lng;
                $locations = $this->locations
                    ->wherehas('company.serviceline',function ($q){
                        $q->whereIn('servicelines.id',$this->userServiceLines);
                    });
                if(count($verticals)>0){

                    $locations = $locations->whereHas('company.industryVertical',function ($q) use($verticals){
                        $q->whereIn('searchfilters.id',$verticals);
                    });
                }
              $locations = $locations->nearby($location,25)->get();
              
            }else{
                $locations = array();
            }
        //my watch list
            $mywatchlist = $this->activity->getWatchList();
            // find all lead locations for the logged in user in these verticals
            $leads = $this->lead->myLeads($verticals)->get();
            return response()->view('salesactivity.show',compact('activity','locations','leads','statuses','mywatchlist'));
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
        $data = $this->setDates(request()->all());
        $activity->update($data);
        $activity->salesprocess()->detach();

        foreach ($data['salesprocess'] as $process){
            foreach ($data['vertical'] as $vertical){
                $activity->salesprocess()->attach($process,['vertical_id'=>$vertical]);
            }

        }
        $reps = $activity->campaignSalesReps();
        $activity->campaignparticipants()->sync($reps);
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
   public function changeteam(Request $request){

        $activity = $this->activity->findOrFail(request('campaign_id'));
        $team = request('id');
        switch (request('action')) {
            case 'add':
                if($activity->campaignparticipants()->attach($team)){
                    return 'success';;
                }else{
                    return 'error';
                }
            break;
            
            case 'remove':

                if($activity->campaignparticipants()->detach($team)){
                    return 'success';;
                }else{
                    return 'error';
                }

                
            break;  
            
        }

   }
   public function updateteam(Request $request){

        $activity = $this->activity->findOrFail(request('campaign_id'));
       // need to get all the sales reps in these verticals
       

        $vertical = request('vertical');
        $reps = $this->person->campaignparticipants($vertical)
                ->pluck('id')->toArray();

        $activity->campaignparticipants()->sync($reps);
        return redirect()->route('campaign.announce',request('campaign_id'));
   }

    
}
