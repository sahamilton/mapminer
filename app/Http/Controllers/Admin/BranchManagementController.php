<?php

namespace App\Http\Controllers\Admin;
use App\Branch;
use App\Person;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchManagementController extends Controller
{
    protected $branch;
    protected $person;
    protected $role;
    protected $branchRoles = [3,11,9];
    public function __construct(Branch $branch, Person $person, Role $role){

    	$this->branch = $branch;
    	$this->person = $person;
        $this->role = $role;
    }

    public function index(){
        $roles = $this->role->whereIn('id',$this->branchRoles)->get();
 
    	$branches = $this->branch->doesntHave('manager')->with('servicelines')->get();
		$people = $this->person->with('userdetails.roles','reportsTo','userdetails.serviceline')->doesntHave('manages')->manages($this->branchRoles)
		->get();

		return response()->view('admin.branches.manage',compact('branches','people','roles'));

    }
    
}
