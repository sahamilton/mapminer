<?php

namespace App\Http\Controllers\Admin;
use App\Branch;
use App\Person;
use App\Role;
use App\BranchManagement;
use Mail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchManagementController extends Controller
{
    protected $branch;
    protected $person;
    protected $role;
    protected $branchmanagement;
    
    protected $branchRoles = [3,5,11,9,13];
    public function __construct(Branch $branch, Person $person, Role $role,BranchManagement $branchmanagement){

    	$this->branch = $branch;
    	$this->person = $person;
        $this->role = $role;
        $this->branchmanagement = $branchmanagement;
    }

    public function index(){

        // show all branches that do not have managers

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
    public function select()
    {
            
            $roles = $this->role->wherehas('permissions',function ($q){
                    $q->where('permissions.name','=','service_branches');
            })
            ->pluck('name','id')->toArray();
            // we need to move this to a model
            $message = "It is important that we keep Mapminer data up to date as we use this information to assign leads among other things. Please help us help you by confirming or correcting the following information:";
     
            return response()->view('admin.branches.select',compact('roles','message'));

    }

    public function confirm(Request $request){

        $recipients = $this->branchmanagement->getRecipients($request);
        $test = request('test');

        return response()->view('admin.branches.confirm',compact('recipients','test'));

    }
    /**
     * Email the selected roles 
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */

    public function emailAssignments(Request $request){
            $emails = 0;
            if(request('id')){
            
            $recipients = $this->branchmanagement->getConfirmedRecipients($request);

            $emails = $this->branchmanagement->sendEmails($recipients,$request);   
             }
            return redirect()->route('home')->withMessage($emails . ' emails sent.');
        }

    
}
