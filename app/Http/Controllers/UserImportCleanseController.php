<?php

namespace App\Http\Controllers;
use App\UserImport;
use App\Person;
use App\User;
use Illuminate\Http\Request;

class UserImportCleanseController extends Controller
{
    public $import;
    public $user;
    public $person;

    public function __construct(User $user, UserImport $import,Person $person){
    	$this->import = $import;
    	$this->user = $user;
    	$this->person = $person;
    }

    public function index(){
    	// show users to delete


    	// show users to create

    	// show managers to create
    	
    	$missingmanagers = $this->import->whereNull('reports_to')->get();
    	
	    	foreach($missingmanagers as $missing){
	    		
	    		if($mgr = $this->import->where('employee_id','=',$missing->mgr_emp_id)->first()){
	    		dd($mgr->person_id,$missing->mgr_emp_id,$missing->manager);
	    		$missing->reports_to = $mgr->person_id;
	    		$missing->save();
	    	}
    	}
    	dd('here');
    	$missingmanagers = $this->import->whereNull('reports_to')->get();
    	// wont work becuase we dont have a fullname field
    	dd($mgr = array_unique($missingmanagers->pluck('manager','mgr_emp_id')->toArray()));
    	dd($this->import->whereIn('fullname',$mgr)->get());
		

		/*select usersimport.* from usersimport where employee_id in (select usersimport.mgr_emp_id from usersimport left join users on usersimport.mgr_emp_id = users.employee_id where users.employee_id is null)*/
    	/*
    	$newPeople = $this->import->leftJoin('users', function($join) {
      			$join->on('usersimport.employee_id', '=', 'users.employee_id');
    		})
	    	->select('usersimport.*')
	    	//->whereHas('manager')
	    	->with('role','manager')
		    ->whereNull('users.employee_id')
		    ->get();

	    return response()->view('admin.users.import.new',compact('newPeople'));
	    */

    	/*
    	$missingPeople = $this->user->leftJoin('usersimport', function($join) {
      			$join->on('users.employee_id', '=', 'usersimport.employee_id');
    		})
    	->with('person','roles')
	    ->whereNull('usersimport.employee_id')
	    ->select('users.*')
	    ->get();

	   return response()->view('admin.users.import.missing',compact('missingPeople'));
	   */
    }


    public function createNewUsers(Request $request){
    
    	foreach (request('insert') as $id){

    		$import = $this->import->findOrFail($id);
    		
    		$newuser = $this->user->create($import->toArray());
    		
    		
    		$newuser->roles()->sync([$import->role_id]);
    		$import->user_id=$newuser->id;
    		$person = $this->person->create($import->toArray());
    		$import->person_id = $person->id;
    		
    		$import->save();
    	}
    	return redirect()->route('importcleanse.index')->withMessage("All created");
    }



    public function bulkdestroy(Request $request){
    	
    	$this->user->destroy(request('delete'));
    	return redirect()->route('importcleanse.index')->withMessage("Deleted");

    }
}
