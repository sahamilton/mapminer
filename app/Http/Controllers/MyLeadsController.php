<?php
namespace App\Http\Controllers;

use App\Address;
use App\Branch;
use Illuminate\Http\Request;
use App\Person;
use App\LeadStatus;
use App\Mail\NotifyWebLeadsBranchAssignment;
use App\Http\Requests\MyLeadFormRequest;
use App\Http\Requests\LeadReassignFormRequest;
use App\Mail\NotifyLeadReassignment;

class MyLeadsController extends BaseController
{
   
    public $lead;
    public $me;
    public $user;
    public $person;
    public $branch;



    public function __construct(Address $lead,Person $person,Branch $branch)
    {

        $this->lead = $lead;
        $this->person = $person;
        $this->branch = $branch;
    }

    /**
     * Display a listing of all leads.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $myBranches = $this->person->myBranches();

        $leads = $this->lead->wherehas('assignedToBranch',function($q) use($myBranches){
            $q->whereIn('branches.id',array_keys($myBranches));
        })->whereHas('assignedToBranch',function ($q){
            $q->selectRaw('ST_Distance_Sphere(addresses.position ,branches.position)/1609 AS distance');
        })->with('assignedToBranch')->get();
      
        return response()->view('myleads.branches',compact('leads','myBranches'));
        // how to get the distance for each branch
        // get my branches
        // get addresses that are leads that are assigned to a branch
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MyLeadFormRequest $request)
    {
    
        if(! $data = $this->cleanseInput($request)){
            return redirect()->back()->withError('Unable to geocode that address');
        }
      
        $lead = $this->lead->create($data['lead']);
        
        // assign to branch
        if(count($data['branch'])>0){
            $lead->assignedToBranch()->attach($data['branch']);
        }
        // store contact information
        if(isset($data['contact'])){
            $lead->contacts()->create($data['contact']);

        }
        // if extra data store in associated table
        if(request()->filled('addressable_type')){
           switch(request('addressable_type')){
            case 'weblead':

                $lead->weblead()->create(request()->all());
            break;


           }
           
            $lead->load('contacts',request('addressable_type'));

        }else{
          $lead->load('contacts');  
        }
    
        if(request('notify')==1){
            $this->notifyLeadReassignment($data,$lead);
            
        }
        return redirect()->route('address.show',$lead)->withMessage('Lead Created');
    }
    
    
    /**
     * Extract data from request and format for storage
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    private function cleanseInput(Request $request)
    {
        $address = request('address'). ' ' . request('city').' ' .request('state').' ' .request('zip');

        if(! $geodata = $this->lead->geoCodeAddress($address)){
            return false;
            
        } 
        $data = $this->cleanseLeadData($request, $geodata);      

        $data = $this->cleanseBranchData($request,$data);

        if(request()->filled('contact')){
            $data = $this->cleanseContactData($data, $request);
        }
       

        return $data;
    }
    

    /**
     * Prepare address data for storage
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    private function cleanseLeadData(Request $request,$geodata)
    {
        $data['lead'] = array_merge(request()->all(),$geodata);
        $data['lead']['businessname'] = $data['lead']['companyname'];
        $data['lead']['phone'] = preg_replace("/[^0-9]/","",$data['lead']['phone']);
        if(request()->filled('type')){
            $data['lead']['addressable_type'] = request('type');
        }else{
           $data['lead']['addressable_type'] = 'lead'; 
        }
        $userCreatedLeadSourceId = '4';
        $data['lead']['lead_source_id'] = $userCreatedLeadSourceId;
        $data['lead']['type'] = 'lead';
        $data['lead']['user_id'] =auth()->user()->id;
        return $data;
    }
    /**
     * Cleanse and assign address to branch
     * @param  Request $request [description]
     * @return array data['team'] assigned to branch
     */
    private function cleanseBranchData(Request $request,array $data)
    {
        $data['team']['user_id'] = auth()->user()->id;
        $data['team']['type'] = 'mylead';
        $data['team']['status_id'] =2;
        if(request()->has('branch') && is_array(request('branch'))){
            foreach (request('branch') as $branch){
                $data['branch'][$branch]=['status_id'=>1];
            }
        }else{
            $data['branch'] = [request('branch')=>['status_id'=>2]];
        }
        return $data;
    }

    /**
     * Extract contact information from request
     * @param  Array   $data    [description]
     * @param  Request $request [description]
     * @return array          [description]
     */
    
    public function cleanseContactData(Array $data, Request $request){

        $data['contact']['fullname'] = request('contact');
        // extract first last name
        $name = explode(' ', request('contact'), 2);
        $data['contact']['firstname'] = $name[0];
        
        if(isset($name[1])){
            $data['contact']['lastname'] = $name[1];
        }
        
        $data['contact']['title'] = request('contact_title');
        $data['contact']['email'] = request('email');
        $data['contact']['contactphone'] =  preg_replace("/[^0-9]/","",request('phone'));
        return $data;
    }

    /**
     * Incoming request to reassign to different branch
     * @param  LeadReassignFormRequest $request [description]
     * @return [type]                           [description]
     */
    public function reassign(LeadReassignFormRequest $request){
        
        if(! request()->filled('branch')){
            $branch = $this->validateBranches($request);
            
        }else{
            $branch = request('branch');
           
        }
        $address = $this->lead->findOrFail(request('address_id'));
       
        $address->assignedToBranch()->sync($branch);
        // auth()->user()->person
        // address
       
        $this->notifyLeadReassignment($branch,$address);
        
         // branch manager
        return redirect()->back()->withSuccess('Lead reassigned');
        
    }
    /**
     * [notifyLeadReassignment description]
     * @param  Array   $branch  [description]
     * @param  Address $address [description]
     * @return [type]           [description]
     */
    private function notifyLeadReassignment(Array $branch, Address $address)
    {
         $branches = $this->branch->has('manager')->with('manager')->whereIn('id',$branch)->get();
         $sender = $this->person->where('user_id','=',auth()->user()->id)->with('userdetails')->first();
         foreach ($branches as $branch){
                foreach($branch->manager as $manager){
                    \Mail::queue(new NotifyLeadReassignment($address,$branch,$manager,$sender));
                }
                
            }

    }
    /**
     * Validate incoming string of comma separated branches
     * Could move to branch model
     * @param  Request $request [description]
     * @return array of valid branches
     */
    private function validateBranches(Request $request)
    {

        $branch = explode(",",request('branch_id'));
        $branches = $this->branch->whereIn('id',$branch)->pluck('id')->toArray();
        if(array_diff($branch,$branches)){
            return redirect()->back()->withError('Invalid branch id '. implode(",",array_diff($branch,$branches)));
            
        }
        return $branch;
    }
}

