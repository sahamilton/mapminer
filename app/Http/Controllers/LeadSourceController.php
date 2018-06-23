<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeadSource;
use App\Lead;
use Excel;
use App\Person;
use App\SearchFilter;
use App\LeadStatus;
use App\Branch;
use App\Http\Requests\LeadSourceFormRequest;
use App\Http\Requests\LeadSourceAddLeadsFormRequest;
use Carbon\Carbon;
class LeadSourceController extends Controller
{
    public $leadsource;
    public $leadstatus;
    public $person;
    public $vertical;
    public $lead;
    public $branch;
    public function __construct(LeadSource $leadsource,
                            LeadStatus $status,
                            SearchFilter $vertical,
                            Lead $lead,
                            Person $person,
                            Branch $branch){
        $this->leadsource = $leadsource;
        $this->leadstatus = $status;
        $this->person = $person;
        $this->vertical=$vertical;
        $this->lead = $lead;
        $this->branch = $branch;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leadsources = $this->leadsource->with('leads','leads.salesteam','verticals')->get();
        return response()->view('leadsource.index', compact('leadsources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $verticals = $this->vertical->industrysegments();
         return response()->view('leadsource.create',compact('verticals'));
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
            ]);
        $leadsource->verticals()->sync($request->get('vertical'));
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
                ->with('author','leads','assignedLeads','unassignedLeads')
               ->findOrFail($id);

        $leads = $leadsource->leads;

        $salesteams = $this->salesteam($leads,$id);
        return response()->view('leadsource.show',compact('leadsource','leads','statuses','salesteams'));
    }


    private function getLeads($id){

        return $this->lead->where('lead_source_id','=',$id)
        /*->wherehas('leadsource',function($q){
            $q->where('datefrom','<=',date('Y-m-d'))
                ->where('dateto','>=',date('Y-m-d'));
            })
*/
        ->with('salesteam','salesteam.industryfocus')
        ->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leadsource = $this->leadsource->with('leads','verticals')->findOrFail($id);

        $verticals = $this->vertical->industrysegments();
        return response()->view('leadsource.edit',compact('leadsource','verticals'));
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
        $leadsource->verticals()->sync($request->get('vertical'));
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

    public function flushLeads($id){
        $this->lead->where('lead_source_id','=',$id)->delete();
        return redirect()->route('leadsource.index');
    }

    public function addLeads($id){
        $leadsource = $this->leadsource->findOrFail($id);
        return response()->view('leadsource.addleads',compact('leadsource'));

    }
    public function importLeads(LeadSourceAddLeadsFormRequest $request,$id){
        $leadsource = $this->leadsource->findOrFail($id);
        if($request->hasFile('file')){
            return $this->leadImport($request,$id);
        }else{
            $request->merge(['lead_source_id'=>$id]);
            $data = $this->cleanseData( $request->all());
            $lead = $this->lead->create($data);
            $geoCode = app('geocoder')->geocode($this->getAddress($request))->get();
            $data = $this->lead->getGeoCode($geoCode);

            $lead->update($data);
            return redirect()->route('leadsource.index');
        }


    }
    // Method to remove commas from fields that cause problem with maps
    private function cleanseData($data){
        $fields = ['companyname','businessname'];
        foreach ($fields as $field){
            $data[$field] = strtr($data[$field], array('.' => '', ',' => ''));
        }
        return $data;

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

    private function getAddress($request){
        // if its a one line address return that
        if(! $request->has('city')){
            return $address = $request->get('address') ;
        }
        // else build the full address
        return $address = $request->get('address') . " " . $request->get('city') . " " . $request->get('state') . " " . $request->get('zip');
    }


    private function findClosestRep($leads){
        $leadinfo = array();
        foreach ($leads as $lead){
            $leadinfo[$lead->id] = $this->person->nearby($lead,1000)
            ->whereHas('userdetails.roles',function($q) {
                $q->whereIn('name','Sales');

            })
            ->limit(1)
            ->get();
        }
        return $leadinfo;
    }

     private function findClosestBranches($leads){
        $leadinfo = null;
        foreach ($leads as $lead){

            $leadinfo[$lead->id] = $this->branch->whereHas('servicelines',function ($q) use ($userservicelines){
                $q->whereIn('servicelines.id',$userservicelines);

            })
            ->nearby($lead,1000)
            ->limit(1)
            ->get();

        }
        return $leadinfo;
    }

     private function salesteam($leads){
        $salesreps = array();

        foreach ($leads as $lead){
            if(count($lead->salesteam)>0){


                foreach ($lead->salesteam as $rep){

                    $salesrep = $lead->salesteam->where('id',$rep->id)->first();


                    if(! array_key_exists($rep->id,$salesreps)){

                        $salesreps[$rep->id]['details'] = $salesrep;
                        $salesreps[$rep->id]['count'] = 0;
                        $salesreps[$rep->id]['status'][1] = 0;
                        $salesreps[$rep->id]['status'][2] = 0;
                        $salesreps[$rep->id]['status'][3] = 0;
                       /* $salesreps[$rep->id]['status'][4] = 0;
                        $salesreps[$rep->id]['status'][5] = 0;
                        $salesreps[$rep->id]['status'][6] = 0;*/

                    }
                    $salesreps[$rep->id]['count'] = $salesreps[$rep->id]['count'] ++;
                    $salesreps[$rep->id]['status'][$salesrep->pivot->status_id] ++;

                }
            }
        }

        return $salesreps;
    }

    /**


    **/
    public function export(Request $request,$id){
         if($request->has('type')){
        $type = $request->get('type');
    }else{
        $type = 'xlsx';
    }

    Excel::create('Prospects'.time(),function($excel) use($id){
            $excel->sheet('Prospects',function($sheet )use($id) {
                $statuses = $this->lead->statuses;
                $leadsource = $this->leadsource
                ->with('leads','leads.relatedNotes')
                ->has('leads.ownedBy')
                ->with('leads.ownedBy')
                ->findOrFail($id);

                $sheet->loadView('leadsource.export',compact('leadsource','statuses'));
            });
        })->download('csv');
    }

}
