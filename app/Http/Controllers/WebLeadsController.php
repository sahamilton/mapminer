<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\WebLead;
use App\WebLeadImport;
use App\LeadSource;
use App\Branch;
use App\Person;
use App\Http\Requests\WebLeadFormRequest;


class WebLeadsController  extends ImportController
{
    public $salesroles = [5,6,7,8];
    protected $person;
    protected $branch;
    public function __construct(WebLead $lead, LeadSource $leadsource,WebLeadImport $import, Person $person, Branch $branch){
        $this->lead = $lead;
        $this->import = $import;
        $this->leadsources = $leadsource;
        $this->person = $person;
        $this->branch = $branch;

        
    }
    public function index(){

        $webleads = WebLead::all();
  
        return response()->view('webleads.index',compact('webleads'));
    }
     
    public function create(){

    	return response()->view('webleads.leadform');
    }

    public function show($lead){
        $lead = $this->lead->with('salesteam')->findOrFail($lead);
        
        $branches = $this->findNearByBranches($lead);

        $people = $this->findNearbySales($branches,$lead); 
        $salesrepmarkers = $this->jsonify($people);
        $branchmarkers=$branches->toJson();
        return response()->view('webleads.show',compact('lead','branches','people','salesrepmarkers','branchmarkers'));

    }


    public function getLeadFormData(WebLeadFormRequest $request){
    	// first get the rows of data
		
       $input = $this->parseInputData($request);
       $data = $input;
       $title="Map the leads import file fields";
       $requiredFields = $this->import->requiredFields;

        $data['type']=$request->get('type');
        if($data['type']== 'assigned'){
            $data['table']='leadimport';
            $requiredFields[]='employee_id';
        }else{
            $data['table']='webleads';
        }
       
        $data['filename'] = null;
        $data['additionaldata'] = array();
        $data['route'] = 'leads.mapfields';
        $fields[0] = array_keys($input);      
        $fields[1] = array_values($input); 
        $data['route'] = 'webleads.store';
        $columns = $this->lead->getTableColumns($data['table']);
        
        $skip = ['id','deleted_at','created_at','updated_at','lead_source_id','pr_status'];
        return response()->view('imports.mapfields',compact('columns','fields','data','company_id','skip','title','requiredFields'));
    	
    }

    private function parseInputData($request){
        $rows = explode(PHP_EOL,$request->get('weblead'));
        // then create the
        foreach ($rows as $row){
            $field = explode("\t",$row);
            if(is_array($field) && count($field)==2){
                $input[str_replace(" ","_",strtolower($field[0]))]=$field[1];
            }
        }
        return $input;
    }
    public function store(Request $request){
        $data = $request->except('fields');
        $fields = $request->get('fields');
        foreach ($fields as $key=>$value){
            if (($key = array_search('@ignore', $fields)) !== false) {
                unset($fields[$key]);
            }
        }
        foreach ($fields as $key=>$value){
            $newdata[$value]= $data[$key];
        }

        // geocode lead
        $geocode = $this->getLatLng($newdata['city'] .", " . $newdata['state']);
        // 
        $newdata['lat'] = $geocode['lat'];
        $newdata['lng'] = $geocode['lng'];
        $lead = $this->lead->create($newdata);

        return redirect()->route('webleads.show',$lead->id);
}

    public function edit($id){

    }

    public function update(Request $request){

    }

    public function destroy($lead){
        dd($lead);
        $this->lead->destroy($lead);
        return redirect()->route('webleads.index');
    }

        private function getLatLng($address)
    {
        $geoCode = app('geocoder')->geocode($address)->get();
        return $this->lead->getGeoCode($geoCode);

    }
    public function assignLeads(Request $request){

       $assign = $request->get('assign');
        $lead = $this->lead->find($request->get('lead_id'))->firstOrFail();
        //$salesteam = $this->person->whereIn('id',$assign)->get();
        $lead->salesteam()->attach($assign, ['status_id' => 2,'type'=>'web']);
        // send email to assignees
        return redirect()->route('webleads.index');
    }

    public function unAssignLeads(Request $request){
        
       
       $lead = $this->lead->find($request->get('lead'))->firstOrFail();
        //$salesteam = $this->person->whereIn('id',$assign)->get();
        $lead->salesteam()->detach($request->get('rep'));
        // send email to assignees
        return redirect()->route('webleads.index');
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

     private function findNearByBranches($lead){
        $data['distance']=\Config::get('leads.search_radius');
       
       return  $this->branch->with('manager')->nearby($lead,$data['distance'])->limit(10)->get();


    }
    public function jsonify($people) {
        $key=0;
        foreach ($people as $person){
            $salesrepmarkers[$key]['id']=$person->id;
            $salesrepmarkers[$key]['lat']=$person->lat;
            $salesrepmarkers[$key]['lng']=$person->lng;
            
            $salesrepmarkers[$key]['name']=$person->fullName();
            $key++;
        }
      
      return collect($salesrepmarkers)->toJson();
}
}

