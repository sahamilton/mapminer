<?php

namespace App\Http\Controllers;

use App\Salesactivity;
use App\SearchFilter;
use App\SalesProcess;
use App\Document;
use App\Location;
use App\SalesOrg;

use App\Http\Requests\SalesActivityFormRequest;
use Illuminate\Http\Request;

class SalesActivityController extends Controller
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
        $data = $this->getDates($request->all());

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
        
        $userVerticals = $this->activity->getUserVerticals();
        $activities = $this->activity->with('salesprocess','vertical')
         ->when(count($userVerticals)>0,function($q) use ($userVerticals){
            $q->whereHas('vertical',function($q1) use($userVerticals){
                $q1->whereIn('vertical_id',$userVerticals);
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
        $userServiceLines = $this->location->getUserServiceLines();
       
        $activity = $this->activity->with('salesprocess','vertical')->findOrFail($id);
        $lat = \Auth::user()->person->lat;
        $lng = \Auth::user()->person->lng;
        $verticals = array_unique ($activity->vertical->pluck('id')->toArray()); 
        $locations = $this->location->findNearbyLocations($lat,$lng,25,$number=null,$company=NULL,$userServiceLines, $limit=null, $verticals);
        

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
        $data = $this->getDates($request->all());
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
        
        $salesorg = $this->salesorg->getSalesOrg();
        $salesorg = $this->filterSalesReps($salesorg,$verticals);
        dd($salesorg);
        //find all persons who have role sales reps 
        //in these verticals or who have no vertical
        //industryfocus
    }

    private function filterSalesReps($salesorg, $verticals){
        $data = array();
        foreach($salesorg as $sales){
            foreach ($sales->industryfocus as $focus){
                if(in_array($focus->id,$verticals)){
                    $data[] = $sales->id;
                    break;
                }
            }
        }
        return $data;
    }

    public function getSalesActivity($id){

        $activity = $this->activity->with('salesprocess','vertical')->findOrFail($id);
        $data['salesprocess'] = array();
        $data['verticals']= array();
        foreach($activity->salesprocess as $process)
        {
            if(! in_array($process->id, $data['salesprocess'])){

            $data['salesprocess'][]=$process->id;
            }
            if(! in_array($process->pivot->vertical_id,$data['verticals'])){
                 $data['verticals'][]=$process->pivot->vertical_id;
            }
           
            
        }
       
         $documents = $this->document->getDocumentsWithVerticalProcess($data);

        return response()->view('documents.index',compact('documents','data'));
    }

    private function getDates($data){
        $data['datefrom'] = \Carbon\Carbon::createFromFormat('m/d/Y', $data['datefrom']);
        $data['dateto'] = \Carbon\Carbon::createFromFormat('m/d/Y', $data['dateto']);
        return $data;
    }
}
