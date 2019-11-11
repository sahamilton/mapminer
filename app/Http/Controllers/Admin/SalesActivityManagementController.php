<?php

namespace App\Http\Controllers\Admin;

use App\Salesactivity;
use App\State;
use App\SearchFilter;
use App\SalesProcess;
use App\SalesOrg;
use App\Document;
use App\Location;
use App\Address;
use App\Lead;
use App\LeadStatus;
use App\Person;
use App\Http\Controllers\BaseController;

use App\Http\Requests\SalesActivityFormRequest;
use Illuminate\Http\Request;

class SalesActivityManagementController extends BaseController
{
   
    public $activity;
    public $vertical;
    public $process;
    public $document;
    public $location;
    public $salesorg;
    public $person;
    public $state;


    /**
     * [__construct description]
     * 
     * @param Address       $location [description]
     * @param Document      $document [description]
     * @param Person        $person   [description]
     * @param Salesactivity $activity [description]
     * @param SalesProcess  $process  [description]
     * @param SalesOrg      $salesorg  [description]
     * @param SearchFilter  $vertical [description]
     * @param State         $state    [description]
     */
    public function __construct(
        Address $location,
        Document $document, 
        Person $person,
        Salesactivity $activity, 
        SalesProcess $process, 
        SalesOrg $salesorg, 
        SearchFilter $vertical,
        State $state
    ) {
        $this->location = $location;
        $this->document = $document;
        $this->activity = $activity;
       
        
        $this->person = $person;
        $this->process = $process;
        $this->salesorg = $salesorg;
        $this->vertical = $vertical;
        $this->state = $state;
        parent::__construct($location);
    }

    /**
     * [index description]
     * 
     * @param [type] $vertical [description]
     * 
     * @return [type]           [description]
     */
    public function index($vertical = null)
    {

        $query = $this->activity->with('salesprocess', 'vertical', 'states');
        if ($vertical) {
            $query = $query->whereHas(
                'vertical', function ($q) use ($vertical) {
                    $q->whereIn('vertical_id', [$vertical]);
                }
            );
        }
        $activities = $query->get();

        $calendar = \Calendar::addEvents($activities);
       
        return response()->view('salesactivity.index', compact('activities', 'calendar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $verticals = $this->vertical->industrysegments();
        $states = $this->state->all();
        $process = $this->process->pluck('step', 'id');
        $salesorg = $this->salesorg->first();
        $salesorgJson = $salesorg->getSalesOrgJson();
       

        return response()->view('salesactivity.create', compact('verticals', 'process', 'states', 'salesorgJson'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request 
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(SalesActivityFormRequest $request)
    {

        $data = $this->setDates(request()->all());

        $activity = $this->activity->create($data);
        foreach (request('salesprocess') as $process) {
            foreach (request('vertical') as $vertical) {
                $activity->salesprocess()->attach($process, ['vertical_id'=>$vertical]);
            }
        }
        foreach (request('org') as $role=>$id) {
            $activity->campaignparticipants()->attach($id, ['role'=>$role]);
        }
        $branches = $activity->getCampaignBranches($data);
       
        $activity->campaignBranches()->sync(array_keys($branches));

        return redirect()->route('salesactivity.index');
    }
    /**
     * [mycampaigns description]
     * 
     * @return [type] [description]
     */
    public function mycampaigns()
    {
       
        $activities = $this->activity->with('salesprocess', 'vertical')
         
            ->where('datefrom', '<=', date('Y-m-d'))
            ->where('dateto', '>=', date('Y-m-d'))
            ->get();
        $calendar = \Calendar::addEvents($activities);

        return response()->view('salesactivity.calendar', compact('calendar'));
    }

    /**
     * [show description]
     * 
     * @param Salesactivity $activity [description]
     * 
     * @return [type]                  [description]
     */
    public function show(Salesactivity $activity)
    {
        
        $activity = $activity->load('salesprocess', 'vertical', 'campaignBranches', 'campaignBranches.manager', 'campaignparticipants', 'campaignparticipants.userdetails.roles');

        $verticals = array_unique($activity->vertical->pluck('id')->toArray());

        $statuses = LeadStatus::pluck('status', 'id')->toArray();
  
        return response()->view('salesactivity.manageshow', compact('activity', 'verticals', 'statuses'));

    }

    /**
     * [edit description]
     * 
     * @param SAlesactivity $activity [description]
     * 
     * @return [type]                  [description]
     */
    public function edit(Salesactivity $activity)
    {
        
        $activity = $activity->load('salesprocess', 'vertical', 'campaignBranches', 'campaignparticipants');

        $verticals = $this->vertical->industrysegments();
    
        $process = $this->process->pluck('step', 'id');
        
        return response()->view('salesactivity.edit', compact('activity', 'process', 'verticals'));
    }

    /**
     * [update description]
     * 
     * @param SalesActivityFormRequest $request  [description]
     * @param Salesactivity            $activity [description]
     * 
     * @return [type]                             [description]
     */
    public function update(SalesActivityFormRequest $request, Salesactivity $activity)
    {
        
        $data = $this->setDates(request()->all());

        $activity->update($data);
        $activity->salesprocess()->detach();

        foreach ($data['salesprocess'] as $process) {
            foreach ($data['vertical'] as $vertical) {
                $activity->salesprocess()->attach($process, ['vertical_id'=>$vertical]);
            }
        }
        $reps = $activity->campaignSalesReps();
        $activity->campaignparticipants()->sync($reps);
        return redirect()->route('salesactivity.index');
    }

    /**
     * [destroy description]
     * 
     * @param SAlesactivity $activity [description]
     * 
     * @return [type]                  [description]
     */
    public function destroy(Salesactivity $activity)
    {
        $activity->delete();
        return redirect()->route('salesactivity.index');
    }
    /**
     * [campaignDocuments description]
     * 
     * @param [type] $id [description]
     * 
     * @return [type]     [description]
     */
    public function campaignDocuments($id)
    {
        $activity = $this->activity->findOrFail($id);
        $documents = $activity->relatedDocuments();
        return response()->view('salesactivity.campaigndocuments', compact('activity', 'documents'));
    }
    /**
     * [changeteam description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function changeteam(Request $request)
    {


        $activity = $this->activity->findOrFail(request('campaign_id'));
        $team = request('id');
        switch (request('action')) {
        case 'add':
            if ($activity->campaignparticipants()->attach($team)) {
                return 'success';
                ;
            } else {
                return 'error';
            }
            break;
            
        case 'remove':
            if ($activity->campaignparticipants()->detach($team)) {
                return 'success';
                ;
            } else {
                return 'error';
            }

            
            break;
        }
    }
    /**
     * [updateteam description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function updateteam(Request $request)
    {


        $activity = $this->activity->findOrFail(request('campaign_id'));
        // need to get all the sales reps in these verticals
       

        $vertical = request('vertical');

        $reps = $this->person->campaignparticipants($vertical)
            ->pluck('id')->toArray();

        $activity->campaignparticipants()->sync($reps);

        return redirect()->route('campaign.announce', request('campaign_id'));
    }
}
