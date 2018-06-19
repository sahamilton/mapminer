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
    public $assignTo;
    public $salesroles = [5,6,7,8];
    public function __construct(Person $person,
                                Lead $lead,
                                LeadSource $leadsource,
                                SearchFilter $vertical,
                                LeadStatus $status){

    	  $this->person = $person;
        $this->vertical = $vertical;
        $this->lead = $lead;
        $this->leadsource = $leadsource;
        $this->leadstatus = $status;
        $this->assignTo = \Config::get('leads.lead_distribution_roles'); 
        parent::__construct($this->lead);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($vertical = null)
    {

        $statuses = $this->leadstatus->pluck('status','id')->toArray();
        $query = $this->lead->query();

        $query = $query->with('salesteam','leadsource','ownedBy');
            // I dont think this works now
        if($vertical){
          $query = $query->whereHas('leadsource.verticals',function ($q) use($vertical){
              $q->whereIn('searchfilter_id',[$vertical]);
          });
        }
        $leads = $query->get();

        $sources = $this->leadsource->pluck('source','id')->toArray();
        $salesteams = $this->person->whereHas('salesleads')->with('salesleads')->get();

        return response()->view('leads.index',compact('leads','statuses','sources','salesteams'));
    }


    private function getSalesTeam($leads){
        $salesreps = array();
        foreach($leads as $lead){
            $leadreps = $lead->salesteam->pluck('id')->toArray();
            $salesreps = array_unique(array_merge($salesreps,$leadreps));
        }

        return $this->person->with('userdetails','industryfocus','reportsTo','salesleads')
           ->whereIn('id',$salesreps)

       ->get();
    }


    public function show($id)
    {

        $sources = $this->leadstatus->pluck('status','id')->toArray();
        $lead = $this->lead->with('salesteam','leadsource','relatedNotes')
        ->whereHas('leadsource',function($q){
          $q->where('datefrom','<=',date('Y-m-d'))
              ->where('dateto','>=',date('Y-m-d'));
        })
        ->findOrFail($id);
        $verticals = $lead->leadsource->verticals()->pluck('searchfilters.id')->toArray();
        $rank = $this->lead->rankLead($lead->salesteam);
        $branch = new \App\Branch;
        $branches = $branch->nearby($lead,500)->limit(5)->get();

        if(count($lead->salesteam)==0){
             $people = $this->person->nearby($lead,'1000')->with('userdetails')
                    ->whereHas('userdetails.roles',function($q) {
                      $q->whereIn('name',$this->assignTo);

              })
              ->whereHas('industryfocus',function ($q) use ($verticals){
                  $q->whereIn('searchfilters.id',$verticals);
              })
              ->limit(5)
              ->get();

        }

        return response()->view('leads.show',compact('lead','sources','rank','people','branches'));


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
       $lead->update($this->lead->getGeoCode($geoCode));
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

    public function leadrank(Request $request){
      $person = $this->person->whereHas('userdetails',function($q) use($request){
        $q->where('api_token','=',$request->get('api_token'));
      })->firstOrFail();

      if($person->salesleads()->sync([$request->get('id') => ['rating' => $request->get('value')]],false))
        {
            return 'success';
        }
      return 'error';


    }

    private function getAddress($request){
        // if its a one line address return that
        if(! $request->filled('city')){
            return $address = $request->get('address') ;
        }
        // else build the full address
        return $address = $request->get('address') . " " . $request->get('city') . " " . $request->get('state') . " " . $request->get('zip');
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
        $leads = $this->person->with('salesleads','salesleads.vertical','salesleads.leadsource')

        ->whereHas('salesleads.leadsource', function ($q) {
            $q->where('datefrom','<=',date('Y-m-d'))
             ->where('dateto','>=',date('Y-m-d'));
        })
        ->findOrFail($id);


        return response()->view('leads.person',compact('leads','statuses'));
    }



    public function getPersonSourceLeads($pid,$sid){
        $statuses = $this->leadstatus->pluck('status','id')->toArray();
        $leads = $this->person->with('salesleads','salesleads.leadsource','salesleads.vertical')
        ->whereHas('salesleads.leadsource', function ($q) use($sid){
            $q->whereId($sid)
            ->where('datefrom','<=',date('Y-m-d'))
             ->where('dateto','>=',date('Y-m-d'));
        })
        ->findOrFail($pid);
        $source=$this->leadsource->findOrFail($sid);
        return response()->view('leads.person',compact('leads','statuses','source'));
    }



    public function find(LeadAddressFormRequest $request){


      $geoCode = app('geocoder')->geocode($request->get('address'))->get();

      if(! $geoCode or count($geoCode)==0)
      {
        return redirect()->back()->withInput()->with('error','Unable to Geocode address:'.$request->get('address') );

      }else{
        $request->merge($this->lead->getGeoCode($geoCode));
      }
      $data = $request->all();
      if(! $request->has('number')){
          $data['number']=5;
        }
        \Session::put('geo', $data);
      if($request->type =='branch'){
          $branches = $this->findNearByBranches($data);
          return response()->view('salesorg.nearbybranches',compact('data','branches'));
        }else{
          $people= $this->findNearByPeople($data);
          return response()->view('salesorg.nearbypeople',compact('data','people'));
        }

    }

    /**
     * Find nearby sales people.
     *
     * @param  array $data
     * @return People object
     */

    private function findNearByBranches($data){
        $location = new \stdClass;
        $location->lat = $data['lat'];
        $location->lng = $data['lng'];
        return \App\Branch::whereHas('servicelines',function ($q){
              $q->whereIn('servicelines.id',$this->userServiceLines );
          })->nearby($location,$data['distance'],$data['number'])
          
          ->get();

    }

     private function findNearByPeople($data){
        $location = new \stdClass;
        $location->lat = $data['lat'];
        $location->lng = $data['lng'];

        return Person::whereHas('userdetails.serviceline', function ($q) {
              $q->whereIn('servicelines.id',$this->userServiceLines);
          })
          ->whereHas('userdetails.roles', function ($q) {
              $q->whereIn('roles.id',$this->salesroles);
          })
          ->with('userdetails','reportsTo','userdetails.roles')
          ->nearby($location,$data['distance'],$data['number'])
          
          ->get();
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


    public function getIndustryAssociation($people){
        foreach ($people as $key=>$person){
            $rep = Person::find($person->id);
            $people[$key]->industry = $rep->industryfocus()->pluck('filter','searchfilters.id')->toArray();
        }

        return $people;
    }

    public function exportLeads(Request $request){
       if($request->has('type')){
        $type = $request->get('type');
    }else{
        $type = 'xlsx';
    }

    Excel::create('Prospects'.time(),function($excel) {
            $excel->sheet('Prospects',function($sheet) {
                $leads = $this->person->where('user_id','=',auth()->user()->id)
                ->with('ownedLeads','ownedLeads.relatedNotes')->firstOrFail();
                $sheet->loadView('salesleads.export',compact('leads'));
            });
        })->download($type);
    }


}
