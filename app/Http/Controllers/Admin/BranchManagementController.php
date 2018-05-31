<?php

namespace App\Http\Controllers\Admin;
use App\Branch;
use App\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchManagementController extends Controller
{
    protected $branch;
    protected $person;

    public function __construct(Branch $branch, Person $person){

    	$this->branch = $branch;
    	$this->person = $person;
    }

    public function index(){
    	$branches = $this->branch->doesntHave('manager')->with('servicelines')->get();
		$people = $this->person->with('userdetails.roles','reportsTo','userdetails.serviceline')->doesntHave('manages')->manages([9])
		->get();
		return response()->view('admin.branches.manage',compact('branches','people'));

    }
    
}
