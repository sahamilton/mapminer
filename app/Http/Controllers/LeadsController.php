<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Person;
use App\Lead;
use App\LeadSource;
use App\SearchFilter;
use App\Http\Requests\LeadAddressFormRequest;
use App\Http\Requests\LeadFormRequest;

class LeadsController extends BaseController
{
    public $person;
    public $lead;
    public $leadsource;
    public $vertical;

    public function __construct(Person $person, Lead $lead,LeadSource $leadsource,SearchFilter $vertical){

    	$this->person = $person;
        $this->vertical = $vertical;
        $this->lead = $lead;
        $this->leadsource = $leadsource;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = $this->lead->with('salesteam')->get();
        return response()->view('leads.index',compact('leads'));
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
        $lead = $this->lead->create($request->all());
        dd($this->geoCodeLead($request));
        $lead->vertical()->attach($request->get('vertical'));
        return redirect()->route('leads.index')->with(['message','New Lead Created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lead = $this->lead->with('salesteam','source')->findOrFail($id);
        return response()->view('leads.show',compact('lead'));
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

    public function address(){
    	$people=array();
    	return response()->view('leads.address',compact('people'));
    }



    public function find(LeadAddressFormRequest $request){
           // dd($request->all());
    		$geoCode = app('geocoder')->geocode($request->get('address'))->get();
	 
			if(! $geoCode)
			{
				dd('bummer');
				
			}
            if(is_array($geoCode)){
                $people = $this->person->findNearByPeople($geoCode[0]['latitude'],$geoCode[0]['longitude'],$request->get('distance'),$request->get('number'),'Sales');
            }else{
                $people = $this->person->findNearByPeople($geoCode->first()->getLatitude(),$geoCode->first()->getLongitude(),$request->get('distance'),$request->get('number'),'Sales');
            }
		  $data = $request->all();
			return response()->view('leads.address',compact('people','data'));
			
    }

    private function geoCodeLead($request){
        $address = $request->get('address') . ", ". $request->get('city') . " ". $request->get('state') . " " . $request->get('zip');

        $geocode = \Geocoder::geocode($address)->get();
        
            if(! $geocode){

                return redirect()->back()->withInput()->with('message', 'Unable to Geocode that address');
            }
            

                $data['lat']=$geocode[0]['latitude'];
                $data['lng'] =$geocode[0]['longitude'];

            return $data;
    
            
      
    }
}
