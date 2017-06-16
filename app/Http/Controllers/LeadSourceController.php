<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeadSource;
use App\Lead;
use Excel;
use App\Person;
use App\Searchfilter;
use App\LeadStatus;
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
    public function __construct(LeadSource $leadsource, LeadStatus $status, Searchfilter $vertical, Lead $lead, Person $person){
        $this->leadsource = $leadsource;
        $this->leadstatus = $status;
        $this->person = $person;
        $this->vertical=$vertical;
        $this->lead = $lead;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leadsources = $this->leadsource->with('leads','verticals')->get();
        return response()->view('leadsource.index', compact('leadsources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $verticals = $this->vertical->vertical();
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
                ->with('author')
               ->findOrFail($id);
        
        $leads = $this->getLeads($id);
       
        $salesteams = $this->salesteam($leads,$id);

        return response()->view('leadsource.show',compact('leadsource','statuses','salesteams'));
    }


    private function getLeads($id){

        return $this->lead->where('lead_source_id','=',$id)
        ->wherehas('leadsource',function($q){
            $q->where('datefrom','<=',date('Y-m-d'))
                ->where('dateto','>=',date('Y-m-d'));
            })

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

        $verticals = $this->vertical->vertical();
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

    public function addLeads($id){
        $leadsource = $this->leadsource->findOrFail($id);
        return response()->view('leadsource.addleads',compact('leadsource'));

    }
    public function importLeads(LeadSourceAddLeadsFormRequest $request,$id){
        $leadsource = $this->leadsource->findOrFail($id);
        if($request->hasFile('file')){
            $this->leadImport($request,$id);
        }else{
            $request->merge(['lead_source_id'=>$id]);
            $data = $this->cleanseData( $request->all());
            $lead = $this->lead->create($data);
            $geoCode = app('geocoder')->geocode($this->getAddress($request))->get();
            $lead->update($this->getGeoCode($geoCode));
            
        }
        return redirect()->route('leadsource.index');

    }
    // Method to reove commas from fields that cause problem with maps
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

    private function getGeoCode($geoCode){

        if(is_array($geoCode)){
           
                $data['lat'] = $geoCode[0]['latitude'];
                $data['lng'] = $geoCode[0]['longitude']; 

            }elseif(is_object($geoCode)){
               
                $data['lat'] = $geoCode->first()->getLatitude();
                $data['lng'] = $geoCode->first()->getLongitude();
            }else{
              
                $data['lat'] = null;
                $data['lng'] = null;
            }

          return $data;
    }


    private function findClosestRep($leads){
        $leadinfo = null;
        foreach ($leads as $lead){
            $data['lat'] = $lead->lat;
            $data['lng'] = $lead->lng;
            $data['distance'] = 1000;
            $data['number'] = 1;
            $leadinfo[$lead->id] = $this->person->findNearByPeople($data['lat'],$data['lng'],$data['distance'],$data['number'],'Sales');

        }
        return $leadinfo;
    }

     private function findClosestBranches($leads){
        $leadinfo = null;
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
                        $salesreps[$rep->id]['status'][4] = 0;
                        $salesreps[$rep->id]['status'][5] = 0;
                        $salesreps[$rep->id]['status'][6] = 0;
                       
                    }
                    $salesreps[$rep->id]['count'] = $salesreps[$rep->id]['count'] ++;
                    $salesreps[$rep->id]['status'][$salesrep->pivot->status_id] ++;
                    
                }          
            }
        }
       
        return $salesreps;
    }

    private function leadImport(Request $request,$source_id){
        
        $file= $request->file('file');
        $file->store('public/library');

        $leads = Excel::load($file,function($reader){
           
        })->get();
        $count = null;
        foreach ($leads->toArray() as $lead) {
            $lead['user_id'] = auth()->user()->id;
            $lead['lead_source_id'] = $source_id;
            if(! $lead['lat'] or ! $lead['lng']){
                $geoCode = app('geocoder')->geocode($this->getAddress($request))->get();
                $lead[] = $this->getGeoCode($geoCode);
            }
            $newLead = $this->lead->create($lead);

            
            $count++;

        }
        return redirect()->route('leadsource.show',$source_id)->withMessage(['status'=>'Imported ' . $count . ' leads']);
     }
}
