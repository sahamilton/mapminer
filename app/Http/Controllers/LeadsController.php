<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lead;
use App\Person;
use Excel;
use Carbon\Carbon;
use App\LeadSource;
use App\LeadStatus;
use App\SearchFilter;
use App\Http\Requests\LeadFormRequest;
use App\Http\Requests\LeadAddressFormRequest;
use App\Http\Requests\BatchLeadImportFormRequest;


class LeadsController extends BaseController
{
    public $person;
    public $lead;
    public $leadsource;
    public $vertical;
    public $leadstatus;

    public function __construct(Person $person, Lead $lead,LeadSource $leadsource,SearchFilter $vertical,LeadStatus $status){

    	$this->person = $person;
        $this->vertical = $vertical;
        $this->lead = $lead;
        $this->leadsource = $leadsource;
        $this->leadstatus = $status;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $statuses = $this->leadstatus->pluck('status','id')->toArray();
        $leads = $this->lead->with('salesteam','leadsource','vertical','ownedBy')->get();
   
        $sources = $this->leadsource->pluck('source','id');
        $salesteams = $this->getSalesTeam($leads);
       
        return response()->view('leads.index',compact('leads','sources','statuses','salesteams'));
    }
    private function getSalesTeam($leads){
        $salesreps = array();
        foreach($leads as $lead){
            $leadreps = $lead->salesteam->pluck('id')->toArray();
            $salesreps = array_unique(array_merge($salesreps,$leadreps));
        }
        return $this->person->with('userdetails','reportsTo','salesleads')
       ->whereIn('id',$salesreps)
       ->whereHas('salesleads',function ($q) use($leads){
            $q->whereIn('lead_id',$leads->pluck('id')->toArray());
       })->get();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $verticals = $this->vertical->vertical();

        $sources = $this->leadsource->pluck('source','id');
        return response()->view('leads.create',compact('sources','verticals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeadFormRequest $request)
    {
        
        if(! is_numeric($request->get('lead_source_id'))){
            $request = $this->createNewSource($request);
        }
        
        $lead = $this->lead->create($request->all());
        $geoCode = app('geocoder')->geocode($this->getAddress($request))->get();
        $lead->update($this->getGeoCode($geoCode));

        $lead->vertical()->attach($request->get('vertical'));
        return redirect()->route('leads.index')->with(['message','New Lead Created']);
    }

    /**
    *   Return address for geocoding
    *   if its a one line address return that
    *   else concatenate full address
    * @param  \Illuminate\Http\Request  $request
    * @return string address
    **/

    private function getAddress($request){
        // if its a one line address return that
        if(! $request->has('city')){
            return $address = $request->get('address') ;
        }
        // else build the full address
        return $address = $request->get('address') . " " . $request->get('city') . " " . $request->get('state') . " " . $request->get('zip');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $sources = $this->leadstatus->pluck('status','id')->toArray();
        $lead = $this->lead->with('salesteam','leadsource','vertical','relatedNotes')
        ->findOrFail($id);

        $rank = $this->lead->rankLead($lead->salesteam);

        return response()->view('leads.show',compact('lead','sources','rank','history'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $lead = $this->lead->with('vertical')->findOrFail($id);
        $verticals = $this->vertical->vertical();
        $sources = $this->leadsource->pluck('source','id');
  
        return response()->view('leads.edit',compact('lead','sources','verticals'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(LeadFormRequest $request, $id)
    {
       $lead = $this->lead->whereId($id)->update($request->except('_method', '_token'));
       $geoCode = app('geocoder')->geocode($this->getAddress($request))->get();
       $lead->update($this->getGeoCode($geoCode));
       $lead->vertical()->sync($request->get('vertical'));
        return redirect()->route('leads.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $this->lead->destroy($id);
        return redirect()->route('leads.index');
    }

    /*public function address(){
    	$people=array();
    	return response()->view('leads.address',compact('people'));
    }*/

    /**
     * Display people near to address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPersonsLeads($id){
        $statuses = $this->leadstatus->pluck('status','id')->toArray();
        $leads = $this->person->with('salesleads','salesleads.vertical','salesleads.leadsource')->findOrFail($id);
       
        return response()->view('leads.person',compact('leads','statuses'));
    }

    public function getPersonSourceLeads($pid,$sid){
        $statuses = $this->leadstatus->pluck('status','id')->toArray();
        $leads = $this->person->with('salesleads','salesleads.vertical')
        ->whereHas('salesleads', function ($q) use($sid){
            $q->where('lead_source_id','=',$sid);
        })
        ->findOrFail($pid);
        $source=$this->leadsource->findOrFail($sid);
        return response()->view('leads.person',compact('leads','statuses','source'));
    }



    public function find(LeadAddressFormRequest $request){
            $data = $request->all();
           
    		$geoCode = app('geocoder')->geocode($request->get('address'))->get();
	           
			if(! $geoCode)
			{
				dd('bummer');
				
			}else{
                $locationdata = $this->getGeoCode($geoCode);

                $data['lat']= $locationdata['lat'];
                $data['lng']= $locationdata['lng'];
            }

            $people = $this->findNearBy($data);

			return response()->view('leads.address',compact('people','data'));
			
    }
    /**
     * Process GeoCode either array or object.
     *
     * @param  \Geocoder\Laravel\Facades\Geocoder
     * @return array $data
     */


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
    /**
     * Find nearby sales people.
     *
     * @param  array $data
     * @return People object
     */

    private function findNearBy($data){
        
        if (! isset($data['number'])){
            $data['number'] = null;
        }
        if(! isset($data['distance'])){
            $data['distance']=\Config::get('leads.search_radius');
        }

        return $this->person->findNearByPeople($data['lat'],$data['lng'],$data['distance'],$data['number'],'Sales');
    }

    private function createNewSource($request){
        $source = $this->leadsource->create(['source'=>$request->get('lead_source_id'),
            'datefrom'=>Carbon::createFromFormat('m/d/Y',$request->get('datefrom')),
            'dateto'=>Carbon::createFromFormat('m/d/Y',$request->get('dateto')),
            'user_id'=>auth()->user()->id,
            'filename'=>$request->get('filename')]);
    
        $request->merge(['lead_source_id'=>$source->id]);
        return $request;
    }


    public function batchImport(){

        $sources = $this->leadsource->pluck('source','id');
        $verticals = $this->vertical->vertical();
        return response()->view('leads.batchimport',compact('sources','verticals'));
    }
    

    public function leadImport(BatchLeadImportFormRequest $request){
        
        $file= $request->file('file');
        $validFiles = ['xlsx','xls','csv'];
        if(!in_array($file->getClientOriginalExtension(),$validFiles)){
            $validator = \Validator::make($request->all(), [
                'file' => 'mimes:xls,xlsx,csv',]);

            return redirect()
            ->back()
            ->withInput()
            ->withErrors($validator);
        }

        $file->store('public/library');

        if(! is_numeric($request->get('lead_source_id'))){
                    
                    $request->merge(['filename'=>basename($file)]); 
                    $request = $this->createNewSource($request);
                }

        $source = $this->leadsource->findOrFail($request->get('lead_source_id'));

        $leads = Excel::load($file,function($reader){
           
        })->get();
        $count = null;
        foreach ($leads->toArray() as $lead) {
            $lead['user_id'] = auth()->user()->id;
            
            $lead['datefrom'] = $source->datefrom->format('m/d/Y');
            $lead['dateto'] = $source->dateto->format('m/d/Y');
            $lead['lead_source_id'] = $request->get('lead_source_id');
            $newLead = $this->lead->create($lead);
            $newLead->vertical()->attach($request->get('vertical'));
            
            $count++;

        }
        return redirect()->route('leads.index')->withMessage('Imported ' . $count . ' leads');
     }
    
    public function assignLeads($id=null){

        if(! $id){
            return $this->geoAssignLeads();
        }else{
            
            return $this->manuallyAssignLead($id);
        }
    }
    private function manuallyAssignLead($id){

            $lead = $this->lead->findOrFail($id);
            $people = $this->person->findNearByPeople($lead->lat,$lead->lng,'5000',5,'Sales');
            $branch = new \App\Branch;
            $branches = $branch->findNearbyBranches($lead->lat,$lead->lng,500,5,[4,5]);
         
            return response()->view('leads.assign',compact('lead','people','branches'));

    }
    private function geoAssignLeads(){
        $leads = $this->lead->whereDoesntHave('salesteam')
        ->where('datefrom','<=',date('Y-m-d'))
        ->where('dateto','>=',date('Y-m-d'))
        ->get();
        $count = null;
        foreach ($leads as $lead) {
           $data['lat']=$lead->lat;
           $data['lng']=$lead->lng;
           if($people = $this->findNearBy($data)){
            
            $count++;
                foreach ($people as $person){
                    $lead->salesteam()->attach($person->id,['status_id'=>1]);
                }
           }
           
        }
        return redirect()->route('leads.index')->with('status',$count . ' leads assigned');
    }
}
