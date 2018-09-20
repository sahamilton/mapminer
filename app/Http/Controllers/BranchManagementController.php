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

    public function index(){
        $id = auth()->user()->id;
        return redirect()->route('branchassignments.show',$id);
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
       
        return response()->view('branchmanagement.show',['details'=>$details]);
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
            $person = $this->user->person()->id;
            $this->BranchManagement->updateConirmed($person);
            auth()->login($user);
            dd('yay!');
            return redirect()->route('home',$user->id)
            ->withMessage("Thank You. Your branch associations have been confirmed.");;
            
        }else{
            //go to home screen
            
            return redirect()->route('welcome')->withMessage("Invalid or expired token");
        }
    }

    public function confirm($token){
     
        if($user = $this->user->getAccess($token)){
            auth()->login($user);
            return redirect()->route('branchmanagement.show',$user->id);

        }else{
            return redirect()->route('guest')->withMessage("Invalid or expired token");
        }
    }

}
