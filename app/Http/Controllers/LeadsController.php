<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lead;
use App\Person;
use App\Branch;
use App\Note;
use Excel;
use Mail;
use Carbon\Carbon;
use App\MapFields;
use App\LeadSource;
use App\LeadStatus;
use App\SearchFilter;
use App\Http\Requests\LeadFormRequest;
use App\Http\Requests\LeadAddressFormRequest;
use App\Http\Requests\BatchLeadImportFormRequest;
use App\Mail\NotifyWebLeadsAssignment;
use App\Mail\NotifyWebLeadsBranchAssignment;


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


        $reps = $this->person->has('leads')
                ->withCount(['leads','openleads','closedleads'])
                ->with('reportsTo','reportsTo.userdetails.roles','closedleads','leads.leadsource')
                ->get();
       
        $rankings = $this->lead->rankLead($reps);
        
        return response()->view('templeads.index',compact('reps','rankings'));
      }
     

    public function salesLeadsDetail ($id){

        $lead = $this->lead
        ->with('leadsource')
        ->findOrFail($id);

        $leadsourcetype = $lead->leadsource->type.'leads';
       $people = null;
   
        $lead = $this->lead
          //->join($leadsourcetype .' as ExtraFields','leads.id','=','ExtraFields.id')
          ->with('salesteam','contacts','relatedNotes')
          ->ExtraFields($leadsourcetype)
          ->findOrFail($id);
     
          if($lead->doesntHave('salesteam')){
           
            $people = $this->person
            ->whereHas('userdetails.roles',function ($q){
              $q->whereIn('roles.id',[9,5]);
            })
              ->nearby($lead,'1000')
              ->limit(5)
              ->get();
              
          }

        $extrafields = array_diff(array_keys($lead->getAttributes()),$this->lead->fillable);

          $dropFields = ['id','created_at','updated_at','deleted_at'];
          foreach ($dropFields as $field){
          if (($key = array_search($field, $extrafields)) !== false) {
              unset($extrafields[$key]);
          }
      }
    
        
        $leadStatuses = LeadStatus::pluck('status','id')->toArray();
       
        $branches = Branch::with('manager','businessmanager','marketmanager')->nearby($lead,100,5)->get();
       
        $rankingstatuses = $this->lead->getStatusOptions;
       
        return response()->view('templeads.detail',compact('lead','branches','leadStatuses','rankingstatuses','extrafields','people'));

    }

    public function branches($id=null){

        if($id){
             $id = [$id];
         }
        $branches = $this->getBranchData($id);
        if(! $id){
            
            return response()->view('templeads.branchsummary',compact('branches'));
        }
        $leadStatuses = LeadStatus::pluck('status','id')->toArray();

        return response()->view('templeads.branchleads',compact('branches','leadStatuses'));
        

    }
    private function getBranchData($id=null){

        if($id){
            if(! is_array($id)){
                $id = [$id];
            }
            return $this->lead->whereIn('branch_id',$id)
                ->with('branches','branches.manager')
                ->orderBy('branch_id')
                ->get();
        }else{

            return Branch::has('leads')
            ->withCount('leads')
            ->with('manager','manager.reportsTo')->get();
        }
        


    }


    public function show($lead)
    {
    
      $table = $this->leadsource->findOrFail($lead->lead_source_id);
      $id= $lead->id;
      $table = $table->type ."leads";
      $lead = $this->lead
                  ->with('contacts')
                  ->ExtraFields($table)
                  ->find($id);
      $extrafields = $this->getExtraFields($table);
      $branches = $this->findNearByBranches($lead);
      $people = $this->findNearbySales($branches,$lead);
      $salesrepmarkers = $this->person->jsonify($people);
      $branchmarkers=$branches->toJson();

      return response()->view('leads.show',compact('lead','branchmarkers','salesrepmarkers','people','branches','extrafields'));


    }
    public function store(LeadFormRequest $request){

      $input = $request->all();
      $leadsource = $this->leadsource->findOrFail($input['lead_source_id']);
      $table = $leadsource->type.'leads';
      $data = $this->extractLeadTableData($input,$table);
      $lead = $this->lead->create($data['lead']);
      $lead->contacts()->create($data['contact']);
      if($table =='webleads'){
        $lead->webLead()->create($data['extra']);
      }else{
        $lead->tempLead()->create($data['extra']);
      }
      
      return redirect()->route('leads.show',$lead->id);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($lead)
    {
        $lead->load('leadsource');
        $table = $lead->leadsource->type.'leads';
        $lead = $this->lead
                  ->with('contacts','leadsource')
                  ->extraFields($table)
                  ->find($lead->id);

        $sources = $this->leadsource->pluck('source','id');
        $extrafields = $this->getExtraFields($table);
        return response()->view('leads.edit',compact('lead','sources','extrafields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(LeadFormRequest $request, $lead)
    {
      $lead->load('leadsource');
      $table = $lead->leadsource->type.'leads';
      $data = $this->extractLeadTableData($input,$table);

      $lead->update($data['lead']);
      $lead->contacts()->update($data['contact']);
      if($table=='webleads'){
        $lead->webLead()->update($data['extra']);
      }else{
         $lead->tempLead()->update($data['extra']);
      }
      
      return redirect()->route('leads.show',$lead->id);

    }


      private function extractLeadTableData($input,$table){

      $input = $this->geoCodeAddress($input);
      $input = $this->renameFields($input); 
      foreach ($input[0] as $key=>$value){
         $data['lead'][$value]=$input[1][$key];
      }
      $data['lead']['lead_source_id'] = $request->get('lead_source_id');

      $data['contact'] = $this->getContactDetails($data['lead'],$table);
      $data['extra'] = $this->getExtraFieldData($data['lead'],$table);
      return $data;

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
    public function searchAddress(){

      return response()->view('leads.search');
    }


    public function search(Request $request){
      $geoCode = app('geocoder')->geocode($request->get('address'))->get();
      $lead = new Lead;
      $coords = $this->lead->getGeoCode($geoCode);
      $lead->lat = $coords['lat'];
      $lead->lng = $coords['lng'];
      $lead->address = $coords['address'];
      $lead->city = $coords['city'];
      $lead->state = $coords['state'];
      $lead->zip = $coords['zip'];
      $lead->lead_source_id = 2;
      $extrafields = $this->getExtraFields('webleads');
      $sources = $this->leadsource->pluck('source','id');

      $branch = new \App\Branch;
      $branches = $branch->nearby($lead,500)->limit(5)->get();
      $people = $this->person
                    ->whereHas('userdetails.roles',function($q) {
                      $q->whereIn('name',$this->assignTo);

              })->nearby($lead,'1000')->with('userdetails')
             
              ->limit(5)
              ->get();      
      $salesrepmarkers = $this->person->jsonify($people);
      $branchmarkers=$branches->toJson();

      return response()->view('leads.showsearch',compact('lead','branches','people','salesrepmarkers','branchmarkers','extrafields','sources'));



      //return response()->view('leads.showsearch',compact('lead','sources','rank','people','branches'));
    }

  
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

    // this really belongs in the sales org controller

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
      if(is_array($data)){
              $location = new \stdClass;
              $location->lat = $data['lat'];
              $location->lng = $data['lng'];
        }else{
          $location = $data;
          $data['distance']=100;
          $data['number']=5;
        }
        
        return \App\Branch::whereHas('servicelines',function ($q){
              $q->whereIn('servicelines.id',$this->userServiceLines );
          })->nearby($location,$data['distance'],$data['number'])
          
          ->get();

    }

     private function findNearByPeople($data){
      if(is_array($data)){
              $location = new \stdClass;
              $location->lat = $data['lat'];
              $location->lng = $data['lng'];
        }else{
          $location = $data;
          $data['distance']=100;
          $data['number']=5;
        }
        

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

   
    
    public function exportLeads(Request $request){
       if($request->has('type')){
        $type = $request->get('type');
    }else{
        $type = 'csv';
    }
   
    Excel::create('Prospects'.time(),function($excel) {
            $excel->sheet('Prospects',function($sheet) {
                $leads = $this->person->where('user_id','=',auth()->user()->id)
                ->with('ownedLeads','ownedLeads.relatedNotes','ownedLeads.contacts')->firstOrFail();
                $statuses = LeadStatus::pluck('status','id')->toArray();
                $sheet->loadView('salesleads.export',compact('leads','statuses'));
            });
        })->download($type);
    }
 
 public function salesLeads($pid){

        $person = $this->getSalesRep($pid);
      

        if($person->userdetails->can('accept_leads')){
           
            return $this->showSalesLeads($person);
        }elseif($person->userdetails->hasRole('Admin') or $person->userdetails->hasRole('Sales Operations')){
                return redirect()->route('leadsource.index');
        }else{

            return $this->showSalesTeamLeads($person);
        }

    }


    private function showSalesLeads($person){
        
        $openleads = $this->getLeadsByType('openleads',$person);
        $openleads =$openleads->limit('200')
                    ->with('leadsource')
                    ->get();
        

        $closedleads = $this->getLeadsByType('closedleads',$person);
        $closedleads = $closedleads->with('relatedNotes','leadsource')
                    ->limit('200')
                    ->get();
        
        return response()->view('templeads.show',compact('openleads','closedleads','person'));
    }


    private function showSalesTeamLeads($person){
        
        $reports = $person->descendantsAndSelf()->pluck('id')->toArray();
        $reps = $this->person->whereHas('leads')
        ->withCount(['leads','openleads','closedleads'])
        ->with('reportsTo','reportsTo.userdetails.roles','closedleads')
        ->whereIn('id',$reports)
        ->get();
        $rankings = $this->lead->rankLead($reps);
        return response()->view('templeads.team',compact('reps','person','rankings'));
    }

    public function salesLeadsMap($pid){

        $person = $this->getSalesRep($pid);
        $data['title']= $person->postName();
        $data['datalocation'] = "/api/newleads/".$person->id ."/map";
        $data['lat'] = $person->lat;
        $data['lng'] = $person->lng;
        $data['listviewref'] = route('salesrep.newleads',$pid);
        $data['zoomLevel'] =10;
        $data['type'] ='leads';
        $leads = $this->lead->whereHas('openleads', function ($q) use($pid){
            $q->where('person_id','=',$pid);
        })
        ->limit('200')
        
        ->get();

        $data['count']=count($leads);
        return response()->view('templeads.showmap',compact('data'));
    }

    public function getMapData($pid){
        $person = $this->getSalesRep($pid);
   
        $leads = $this->lead->whereHas('openleads', function ($q) use($person){
            $q->where('person_id','=',$person->id);
        })
        ->with('leadsource')
        ->limit('200')
        ->get();
     
      //  $leads = $this->templead->where('sr_id','=',$person->id)->get();
        return response()->view('templeads.xml',compact('leads'));

    }
    public function branchLeadsMap($bid){
        $branch = Branch::findOrFail($bid);
        $data['title']= $branch->branchname . " Branch";
        $data['datalocation'] = route('newleads.branch.mapdata',$branch->id);
        $data['lat'] = $branch->lat;
        $data['lng'] = $branch->lng;
        $data['listviewref'] = route('templeads.branchid',$branch->id);
        $data['zoomLevel'] =10;
        $data['type'] ='leads';
        $leads = $this->lead->whereHas('branches', function ($q) use($bid){
            $q->where('id','=',$bid);
        })
        ->limit('200')
        
        ->get();

        $data['count']=count($leads);
        return response()->view('templeads.branchmap',compact('data'));
    }

    public function branchLeads($bid){
      $branch = Branch::with('leads','manager','leads.salesteam','leads.ownedBy','leads.vertical')->findOrFail($bid);
      $sources = $this->leadsource->pluck('source','id');
      return response()->view('leads.branchdetails',compact('branch','sources'));
    }


    public function getBranchMapData($bid){
        
        $leads = $this->getBranchData($bid);
        
        
      //  $leads = $this->templead->where('sr_id','=',$person->id)->get();
        return response()->view('templeads.xml',compact('leads'));

    }
    private function getSalesRep($pid=null){

        if(! $pid){
            return $this->person->findOrFail(auth()->user()->person->id);
        }

        $person = $this->person->findOrFail($pid);

        if(auth()->user()->hasRole('Admin') or auth()->user()->hasRole('Sales Operations')){
           
           return $person;

        }
        $peeps = $person->descendants()->pluck('id')->toArray();
        
        if(in_array($pid,$peeps)){
            return $person;
        }
        
        return $this->person->findOrFail(auth()->user()->person->id);
        

        

    }
     private function getLeadsByType($type,$person){

        return $this->lead->whereHas($type, function ($q) use($person){
            $q->where('person_id','=',$person->id);
        })->with($type);

    }
 /**
     * Close prospect
     * @param  Request $request post contents
     * @param  int  $id      prospect (lead) id
     * @return [type]           [description]
     */
    public function close(Request $request, $id){
    
      $lead = $this->lead->with('salesteam')->findOrFail($id);
    
      $lead->salesteam()
        ->updateExistingPivot(auth()->user()->person->id,['rating'=>$request->get('ranking'),'status_id'=>3]);
        $this->addClosingNote($request,$id);
        return redirect()->route('salesrep.newleads',$lead->salesteam->first()->id)->with('message', 'Lead closed');
     }
     private function addClosingNote($request,$id){
        $note = new Note;
        $note->note = "Lead Closed:" .$request->get('comments');
        $note->type = 'lead';
        $note->related_id = $id;
        $note->user_id = auth()->user()->id;
        $note->save();
    }

     public function unAssignLeads(Request $request){
     
       $lead = $this->lead->findOrFail($request->get('lead'));
       $lead->salesteam()->detach($request->get('rep'));
       return redirect()->route('leads.show',$lead->id);
        
    }

    /**
     * Find nearby sales people.
     *
     * @param  array $data
     * @return People object
     */

    private function findNearBySales($branches,$lead){
        $branch_ids = $branches->pluck('id')->toArray(); 
        $data['distance']=\Config::get('leads.search_radius');

        $salesroles = $this->salesroles;
 
        $persons =  $this->person->whereHas('userdetails.roles',function ($q) use($salesroles){
          $q->whereIn('roles.id',$salesroles);
        })
       
        ->whereHas('branchesServiced',function ($q) use ($branch_ids){
            $q->whereIn('branches.id',$branch_ids);
        })
        ->with('userdetails','userdetails.roles','industryfocus','branchesServiced');
        return $persons->nearby($lead,$data['distance'])->limit(10)->get();
      

    }
    private function getExtraFields($type){
        return  \App\MapFields::whereType($type)
                      ->whereDestination('extra')
                      ->whereNotNull('fieldname')
                      ->pluck('fieldname')->toArray();

    }
    private function getExtraFieldData($newdata,$type='webleads'){

        $extraFields = \App\MapFields::whereType($type)
                      ->whereDestination('extra')
                      ->whereNotNull('fieldname')
                      ->pluck('fieldname')->toArray();

            foreach ($extraFields as $key=>$value){
              if($newdata[$value]){
                $extra[$value] = $newdata[$value];
              }
            }
        return $extra;
    }

    public function unassignedleads(){
      $leads= $this->lead->doesntHave('ownedBy')->get();

      $data = array();
      foreach ($leads as $lead){
        $people = $this->person
            ->whereHas('userdetails.roles',function ($q){
              $q->whereIn('roles.id',[5,6,7,8]);
            })
              ->nearby($lead,'100')
              ->limit(1)
              ->first();
          
          if(count($people)>0){
            $lead->salesteam()->attach($people, ['status_id' => 2]);
             $data[$lead->id] = $people;
          }
         
        
      }
         
      return response()->view('leads.assignable',compact('leads','data'));
    }

     public function assignLeads(Request $request){

        $lead = $this->lead->with('contacts','leadsource')->findOrFail($request->get('lead_id'));
        $branch = Branch::with('manager','manager.userdetails')->findOrFail($request->get('branch'));

        if($request->get('salesrep')!=''){

            $rep = $this->person->findOrFail($request->get('salesrep'));
            $rep = $this->checkIfTest($rep);
          
            $lead->salesteam()->attach($request->get('salesrep'), ['status_id' => 2]);
            Mail::queue(new NotifyWebLeadsAssignment($lead,$branch,$rep));
        }else{
    
            foreach($branch->manager as $manager){
                
                $manager = $this->checkIfTest($manager);
                $lead->salesteam()->attach($manager->id, ['status_id' => 2]);
                Mail::queue(new NotifyWebLeadsBranchAssignment($lead,$branch,$manager));
            }


        }
        if($request->get('notifymgr')){

            foreach ($branch->manager as $manager){

                $manager = $this->checkIfTest($manager);
                Mail::queue(new NotifyWebLeadsBranchAssignment($lead,$branch,$manager));
            }
       
        }  
        return redirect()->route('leadsource.show',$lead->lead_source_id);
    }

   

    private function checkIfTest($rep){

      if(\Config::get('leads.test')){
         $rep =  $this->person->with('userdetails')->where('user_id','=',auth()->user()->id)->first();
         
      }
      return $rep;
    }

    public function getAssociatedBranches($pid=null){

        if(auth()->user()->hasRole('Branch Manager')){

            $branchmgr = $this->person
                            ->where('user_id','=',auth()->user()->id)
                            ->with('manages')
                            ->first();

         }elseif(auth()->user()->hasRole('Admin')){
            $branches = Branch::has('leads')
            ->withCount('leads')
            ->with('manager')
            ->get();
            return response()->view('leads.branches',compact('branches'));
        

        }else{
             $branchmgr = $this->person     
                            ->with('manages')
                            ->findOrFail($pid);
         } 
                
        $branchlist = $branchmgr->manages->pluck('id')->toArray();
        if($branchlist){
            $branchleads = $this->getBranchData($branchlist);
            $leadStatuses = LeadStatus::pluck('status','id')->toArray();
            return response()->view('templeads.branchmgrleads',compact('branchleads','branchmgr','leadStatuses'));
        }
        return redirect()->back()->with('error', 'Sorry '. $branchmgr->postName() .' is not assigned to any branch. Please contact Sales Ops' );
        
    }

    private function geoCodeAddress($input){
        $address = null;
        if(isset($input['address'])){
            $address = $input['address'];
        }
        $address = $address .' ' . $input['city'] . ' ' . $input['state'];
        $geoCode = app('geocoder')->geocode($address)->get();
        $location =$this->lead->getGeoCode($geoCode);
        $input['lat']= $location['lat'];
        $input['lng']= $location['lng'];
        return $input;
    }

    private function renameFields($input){
           
            
            $valid = $this->getValidFields();
    
            foreach (array_keys($input) as $key=>$value){
              if(isset($valid[$value])){
                $fields[0][$key]=$valid[$value];
              }else{
                $fields[0][$key]=$value;
              }
              
            }
            $fields[1] = array_values($input);
            
            return $fields; 
    }
    private function getValidFields($type='webleads'){
      
        $validFields = MapFields::whereType($type)->whereNotNull('fieldname')->get();

      return $validFields->reduce(function ($validFields,$validField){
        $validFields[$validField->aliasname] = $validField->fieldname;
        return $validFields;

      });
    }


    private function getContactDetails($newdata,$type='webleads'){
        $contactFields = MapFields::whereType($type)
        ->whereDestination('contact')
        ->whereNotNull('fieldname')->pluck('fieldname')->toArray();

        $contact['contact'] = null;
            foreach ($contactFields as $field){
             
                if(in_array($field,['first_name','last_name'])){
                    $contact['contact'] = $contact['contact'] . $newdata[$field]." ";
                }else{
                    $contact[$field] = $newdata[$field];
                }
            }

        return  $contact;
    
    }

}
