<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;
use App\User;
use App\Company;
use App\Http\Requests\UsersImportFormRequest;
use App\Serviceline;
use App\UserImport;
class UsersImportController extends ImportController
{

	public $company;
    public $person;
    public $user;
    public $import;
    public $userfields =[];
    public $personfields =[];

    public function __construct(Person $person, User $user,Company $company,UserImport $import){
        $this->person = $person;
        $this->user = $user;
        $this->import = $import;
        $this->company = $company;
        parent::__construct($this->company);  
        
    }

    public function getFile(Request $request){
       
       $servicelines = Serviceline::whereIn('id',$this->userServiceLines)
				->pluck('ServiceLine','id');
		return response()->view('admin/users/import',compact('servicelines'));
        
    }


    public function import(UsersImportFormRequest $request) {
      
        $data = $this->uploadfile($request->file('upload'));
        $data['table']='usersimport';
           
        $data['type']=$request->get('type');
        
        $data['route'] = 'users.mapfields';
        $fields = $this->getFileFields($data); 

        $data['additionaldata'] = ['serviceline'=>$request->get('serviceline')];
        $addColumns = new \stdClass;
        $addColumns->Field = 'role_id';
        $addColumn[] = $addColumns;
   		$columns = array_merge($this->company->getTableColumns('users'),$this->company->getTableColumns('persons'),$addColumn);
        $requiredFields = $this->import->requiredFields;
        $skip = ['id','password','confirmation_code','remember_token','created_at','updated_at','nonews','lastlogin','api_token','user_id','lft','rgt','depth','geostatus'];
        return response()->view('imports.mapfields',compact('columns','fields','data','skip','requiredFields'));
    }
    
    public function mapfields(Request $request){
       
       $data = $this->getData($request);  
       $this->import->setFields($data);
       if($multiple = $this->import->detectDuplicateSelections($request->get('fields'))){
            return redirect()->route('users.importfile')->withError(['You have to mapped a field more than once.  Field: '. implode(' , ',$multiple)]);
        }
        
       if($missing = $this->import->validateImport($request->get('fields'))){
             
            return redirect()->route('users.importfile')->withError(['You have to map all required fields.  Missing: '. implode(' , ',$missing)]);
       }
      
       if($this->import->import()) {
         	$this->import->createUserNames();
         	if($importerrors = $this->import->checkUniqueFields()){
 					$field = end($importerrors)->Field;
 					array_pop($importerrors); 
 	         		return response()->view('admin.users.importerrors',compact('field','importerrors'));
 	         	
         	}
         	$this->import->postImport();
           return redirect()->route('users.index')->with('success','Users imported');

        }
        
	}
    public function fixerrors(Request $request){

    	if($request->has('fixInput')){
    		if($request->has('skip')){
    			$this->import->destroy($request->get('skip'));
    		}
    		$field = $request->get('field');
    		foreach ($request->get('error') as $key=>$value){

    			$this->import->where($field,'=',$key)->update([$field=>$value]);

    		}
    	}
    	//now we need to continue checks and import
    	if($importerrors = $this->import->checkUniqueFields()){
 	
 	      return response()->view('admin.users.importerrors',compact('field','importerrors'));
 	         	
      }
       $this->import->postImport();

       return redirect()->route('users.index')->with('success','Users imported');
    }
    
    /*private function checkUniqueFields(){
    	
    	foreach ($this->import->uniqueFields as $field){
         	

         		return $importerrors = $this->import->checkUniqueFields($field);
         			 
         		
         	}
         return false;
    }*/

   
    
}
