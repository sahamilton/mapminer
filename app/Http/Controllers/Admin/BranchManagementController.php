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
 
    	$branches = $this->branch
        ->doesntHave('manager')
        ->orWhere(function ($q){
            $q->doesntHave('businessmanager')
            ->doesntHave('marketmanager');
        })
        

        ->with('servicelines','manager','marketmanager','businessmanager')
        ->get();
		

        $people = $this->person
        ->with('userdetails.roles','reportsTo','userdetails.serviceline')
        ->doesntHave('manages')
            ->manages($this->branchRoles)
        ->get();
		return response()->view('admin.branches.manage',compact('branches','people','roles'));

    }

        /**
     * Display a listing of the resource.
     * Get list of sales people with stale branch assignments
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        
            $roles = $this->role->whereIn($this->branchTeamRoles);
            return response()->view('admin.branches.select',compact('roles'));

    }
    /**
     * Email the selected roles 
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */

    public function emailAssignments(Request $request){
            $roles = request('roles');

            $branchmanagement = 
                $this->person
                    ->staleBranchAssignments($roles)
                    ->with('userdetails','branchesServiced')
                    ->inRandomOrder()
                    ->limit(5)
                    ->get();
            
            foreach ($branchmanagement as $assignment){
                Mail::to($assignment->userdetails->email)->queue(new NotifyBranchAssignments($assignment));
            }
        }

    
}
