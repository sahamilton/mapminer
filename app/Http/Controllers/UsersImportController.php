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

    public function __construct(Person $person, User $user,UserImport $import){
        $this->person = $person;
        $this->user = $user;
        $this->import = $import;


        
    }
//
    // query to get all without manager in system
    //SELECT * from usersimport where employee_id in (select mgr_emp_id from `usersimport` where reports_to is null)

    public function index(){

       $imports = $this->import->whereNull('person_id')->orWhereNull('user_id')->get();
       return response()->view('admin.users.import.index',compact('imports')); 
    }

    public function getFile(){
      if($this->import->count()>0){
        return redirect()->route('importcleanse.index');
      }else{
       $requiredFields = $this->import->requiredFields;
       //$servicelines = Serviceline::pluck('ServiceLine','id');

		return response()->view('admin.users.import',compact('requiredFields'));
  }
        
    }


    public function import(UsersImportFormRequest $request) {


        $this->import->truncate();
        $data = $this->uploadfile(request()->file('upload'));
        $data['table']='usersimport';
           
        $data['type']=request('type');

        
        $data['route'] = 'users.mapfields';
        $fields = $this->getFileFields($data); 

        $data['additionaldata'] = [];//['serviceline'=>implode(",",request('serviceline'))];
        $addColumns = ['branches','role_id','mgr_emp_id','manager','reports_to','industry','address','city','state','zip','serviceline','hiredate','business_title','fullname'];
        $addColumn = $this->addColumns($addColumns);

   		$columns = array_merge($this->import->getTableColumns('users'),$this->import->getTableColumns('persons'),$addColumn);


        $requiredFields = $this->import->requiredFields;
        $skip = ['id','password','confirmation_code','remember_token','created_at','updated_at','nonews','lastlogin','api_token','user_id','lft','rgt','depth','geostatus'];
        return response()->view('imports.mapfields',compact('columns','fields','data','skip','requiredFields'));
    }
    
    public function mapfields(Request $request){

    
       $data = $this->getData(request()->all());  
       $this->import->setFields($data);
       if($multiple = $this->import->detectDuplicateSelections(request('fields'))){
            return redirect()->route('users.importfile')->withError(['You have to mapped a field more than once.  Field: '. implode(' , ',$multiple)]);
        }
        
       if($missing = $this->import->validateImport(request('fields'))){

             
            return redirect()->route('users.importfile')->withError(['You have to map all required fields.  Missing: '. implode(' , ',$missing)]);
       }
      
       if($this->import->import()) {

         	$this->import->postImport();


         // copy all data from import to persons and users where not null person_id, user_id
          
           return redirect()->route('importcleanse.index');


        }
        
	}


    public function newUsers(){

        $newusers = $this->import->whereNull('person_id')->get();

        //$newusers = $this->import->addUserFields($newusers);
        if($newusers->count()>0){
           return response()->view('admin.users.importnew',compact('newusers'));
        }
        if($importerrors = $this->import->setUpAllUsers()){
            
            $persons = $this->import->whereIn('person_id',array_keys($importerrors))->get();
            
             return response()->view('admin.users.import.errors',compact('importerrors','persons'));
          }else{
           
            return redirect()->route('usersimport.index')->withMessage('All Imported and Updated');
          }
    }


    public function createNewUsers(Request $request){

      if($errors = $this->import->createNewUsers($request)){
          if(! is_array($errors)){
           return redirect()->back()->withMessage($errors);
          }else{
            return $this->inputUserErrors($errors);
          }
      }

      if(! $errors = $this->import->setUpAllUsers()){
        return redirect()->route('usersimport.index')->withMessage('All Imported and Updated');
      
      }else{

        return $this->inputErrors($errors);
      }
    }
    private function inputUserErrors($importerrors){
        if(! is_array($importerrors)){
          return redirect()->back()->withMessage($errors);
        }
        $ids = array_keys($importerrors['email']);
    
        $persons = $this->import->whereIn('employee_id',$ids)->get();

        return response()->view('admin.users.import.createerrors',compact('importerrors','persons'));
    }

    private function inputErrors($importerrors){  
        if(! is_array($importerrors)){
          return redirect()->back()->withMessage($errors);
        }
        $ids = array_keys($importerrors);
        $persons = $this->import->whereIn('person_id',$ids)->get();
        return response()->view('admin.users.import.errors',compact('importerrors','persons'));
    }

    public function fixUserErrors(Request $request){
        //$data['email'] = request('email');
        $data = request(['email']);
       
        $imports = $this->import->whereIn('employee_id',array_keys(request('email')))->get();
        foreach ($imports as $import){
         
          $import->email = $data['email'][$import->employee_id];
         
          $import->save();
        }
        return $this->newUsers();
    }

    public function fixerrors(Request $request){
      
      $data['branches'] = request('branch');
      $data['industry'] = request('industry');
      // we need to convert the industry name to 

      $imports = $this->import->whereIn('person_id',array_keys(request('branch')))->get();

      foreach ($imports as $import){
        
        $import->branches = $data['branches'][$import->person_id];
        $import->industry = $data['industry'][$import->person_id];
        $import->save();
      }
      if($importerrors = $this->import->setUpAllUsers()){
           $persons = $this->import->whereIn('person_id',array_keys($importerrors))->get();
            
            return response()->view('admin.users.import.errors',compact('importerrors','persons'));
           // this should now cycle through the check again 
        }else{
          return redirect()->route('usersimport.index')->withMessage('All Imported and Updated');
        }
    }

    private function addColumns($columns){
        foreach ($columns as $column){
            $columns = new \stdClass;
            $columns->Field = $column;
            $addColumn[] = $columns;
        }
        return $addColumn;
    }   
    
}
