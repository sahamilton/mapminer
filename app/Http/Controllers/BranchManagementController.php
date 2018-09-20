<?php

namespace App\Http\Controllers;
use App\Branch;
use App\User;
use App\Person;
use Mail;
use App\Mail\NotifyBranchAssignments;
use App\BranchManagement;
use App\Http\Requests\BranchManagementRequest;
use Illuminate\Http\Request;

class BranchManagementController extends Controller
{
    
    public $branchmanagement;
    public $person;
    public $user;
    public $branchTeamRoles =[3,5,9,11,13];
    public function __construct(Person $person, Branch $branch,BranchManagement $branchmanagement, User $user){

        $this->branchmanagement = $branchmanagement;
        $this->branch = $branch;
        $this->person = $person;
        $this->user = $user;
    }


    /**
     * Display a listing of the resource.
     * Get list of sales people with stale branch assignments
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(! auth()->user()->hasRole('Admin')){
            return redirect()->route('branchmanagement.show',auth()->user()->id);
        }else{
            // choose which roles to email
            // $roles = this->roles-whereIn($this->branchTeamRoles);
            // return response()->view('branchassignments.select',compact('roles'));
        }
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        if(! auth()->user()->hasRole('Admin')){
           
            $id = auth()->user()->id;
        }

        $details = $this->person->whereUser_id($id)
        ->with('userdetails.roles')
        ->with('branchesServiced')
        ->firstOrFail();
       
        return response()->view('branchassignments.show',['details'=>$details]);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  App\Request\BranchManagementRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BranchManagementRequest $request, $id)
    {
       
        if(! auth()->user()->hasRole('Admin')){
            
            $id = auth()->user()->id;
        }
        
        $person = $this->person
                    ->whereUser_id($id)
                    ->with('userdetails.roles')
                    ->firstOrFail();
        $role = $person->userdetails->roles->first()->id;
        $branches = explode(",",request('branches'));
        foreach ($branches as $branch){
           $data[$branch]=['role_id' => $role]; 
        }
        $person->branchesServiced()->sync($data);
        return redirect()->route('branchmanagement.show',$person->user_id)
        ->withMessage('You have successfully updated your branch assignments');
       

    }

    public function correct($token){
        
        if($user = $this->user->getAccess($token)){
            dd('thats valid');
            // update branch_user table
            // login
            //display thank you
        }else{
            //go to home screen
            
            return redirect()->route('welcome')->withMessage("Invalid or expired token");
        }
    }

    public function confirm($token){
     
        if($user = $this->user->getAccess($token)){
            auth()->login($user);
            return redirect()->route('branchmanagement.show',$user->id);
            // login
            //
            // go to route branchmanagement update with person id

        }else{
            return redirect()->route('guest')->withMessage("Invalid or expired token");
        }
    }

}
