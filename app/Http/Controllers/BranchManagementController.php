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
        $branches = array();
           
        if($details->geostatus ==1){
            $branches = $this->branch->nearby($details,100,5)->get();
        }
    
        return response()->view('branchassignments.show',['details'=>$details,'branches'=>$branches]);


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

        $person = $this->person->whereUser_id($id)->firstorFail();
        // this is odd!  Why both role?
        //$role = $person->getPrimaryRole($person);
        $role = $person->whereUser_id($id)->primaryRole();                  
        $branches = $this->branchmanagement->getBranches($request,$role);
        $person->branchesServiced()->sync($branches);

        return redirect()->route('user.show',$id)
            ->withMessage("Thank You. Your branch associations have been updated. Check out the rest of your profile.");
                

    }

    public function correct($token){


        //validate user from token
        if($user = $this->user->getAccess($token)){
            $person = $user->person()->first();
            // set updated_at to now
            $this->branchmanagement->updateConfirmed($person);
            //login user
            auth()->login($user);
            // update token - single use
            $user->update(['apitoken' => $user->setApiToken()]);
            return redirect()->route('user.show',$user->id)
            ->withMessage("Thank You. Your branch associations have been confirmed. Check out the rest of your profile.");
            

        }else{
            //go to home screen
            
            return redirect()->route('welcome')->withMessage("Invalid or expired token.");

        }
    }

    public function confirm($token){

        //validate user from token
        if($user = $this->user->getAccess($token)){
            
            //login user
            auth()->login($user);
            // update token - single useauth()->login($user);
            $user->update(['apitoken' => $user->setApiToken()]);
           
            return redirect()->route('branchassignments.show',$user->id);

        }else{
            return redirect()->route('welcome')->withMessage("Invalid or expired token");

        }
    }

}
