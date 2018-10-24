<?php

namespace App\Http\Controllers;
use App\Branch;
use App\User;
use App\Person;
use App\Campaign;
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
    public $campaign;
    public $branchTeamRoles =[3,5,9,11,13];
    public function __construct(
        Person $person, 
        Branch $branch,
        BranchManagement $branchmanagement, 
        User $user,
        Campaign $campaign){

        $this->branchmanagement = $branchmanagement;
        $this->branch = $branch;
        $this->person = $person;
        $this->user = $user;
        $this->campaign = $campaign;
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

    public function correct($token, $cid=null){
 

        //validate user from token
        if($user = $this->user->getAccess($token)){
            $person = $user->person()->first();
            // set updated_at to now
            $this->branchmanagement->updateConfirmed($person);
            //login user
            auth()->login($user);
            // update token - single use
            // insert activity_person_cid
            if($cid){
               
                $campaign = $this->campaign->findOrFail($cid);
                
                $campaign->participants()->attach($person,['activity'=>'correct']);
                //insert the campaign_person_activity
            }
            //
            $user->update(['apitoken' => $user->setApiToken()]);
            return redirect()->route('user.show',$user->id)
            ->withMessage("Thank You. Your branch associations have been confirmed. Check out the rest of your profile.");
            

        }else{
            //go to home screen
            
            return redirect()->route('welcome')->withMessage("Invalid or expired token.");

        }
    }

    public function confirm($token, $cid=null){
        
        //validate user from token
        if($user = $this->user->getAccess($token)){
            $person = $user->person()->first();
            //login user
            auth()->login($user);
            // update token - single useauth()->login($user);
            $user->update(['apitoken' => $user->setApiToken()]);
           // update token - single use
            if($cid){
                
                $campaign = $this->campaign->findOrFail($cid);
                $campaign->participants()->attach($person,['activity'=>'confirm']);
            }
            // insert activity_person_cid
            return redirect()->route('branchassignments.show',$user->id);

        }else{
            return redirect()->route('welcome')->withMessage("Invalid or expired token");

        }
    }

}
