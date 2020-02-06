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
    

    /**
     * [__construct description]
     * 
     * @param Person           $person           [description]
     * @param Branch           $branch           [description]
     * @param BranchManagement $branchmanagement [description]
     * @param User             $user             [description]
     * @param Campaign         $campaign         [description]
     */
    public function __construct(
        Person $person,
        Branch $branch,
        BranchManagement $branchmanagement,
        User $user,
        Campaign $campaign
    ) {

        $this->branchmanagement = $branchmanagement;
        $this->branch = $branch;
        $this->person = $person;
        $this->user = $user;
        $this->campaign = $campaign;
    }


    /**
     * Display a listing of the resource.
     * Get list of sales people with stale branch assignments
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = auth()->user()->id;
       
        return redirect()->route('branchassignments.show', $id);
    }

    /**
     * [show description]
     * 
     * @param [type] $id [description]
     * 
     * @return [type]     [description]
     */
    public function show($id)
    {

        if (! auth()->user()->hasRole('admin')) {
            $id = auth()->user()->id;
        }

        $details = $this->person->whereUser_id($id)

            ->with('userdetails.roles')
            ->with('branchesServiced')
            ->firstOrFail();
    
        $branches = [];
           
        if ($details->geostatus == 1) {
            $branches = $this->branch->nearby($details, 100, 5)->get();
        }
 
        $branches = $details->branchesServiced->merge($branches);
        return response()->view(
            'branchassignments.show', ['details'=>$details,'branches'=>$branches]
        );
    }

    /**
     * [update description]
     * 
     * @param BranchManagementRequest $request [description]
     * @param [type]                  $id      [description]
     * 
     * @return [type]                           [description]
     */
    public function update(BranchManagementRequest $request, $id)
    {

        if (! auth()->user()->hasRole('admin')) {
            $id = auth()->user()->id;
        }

        $person = $this->person->whereUser_id($id)->firstorFail();
        $role = $person->findRole();
        $branches = $this->branchmanagement->getBranches($request, $role[0]);
        $person->branchesServiced()->sync($branches);

        return redirect()->route('user.show', $id)
            ->withMessage("Thank You. Your branch associations have been updated. Check out the rest of your profile.");
    }
    /**
     * [correct description]
     * 
     * @param [type] $token [description]
     * @param [type] $cid   [description]
     * 
     * @return [type]        [description]
     */
    public function correct($token, $cid = null)
    {
 

        //validate user from token
        if ($user = $this->user->getAccess($token)) {
            $person = $user->person()->first();
            // set updated_at to now
            $this->branchmanagement->updateConfirmed($person);
            //login user
            auth()->login($user);
            // update token - single use
            // insert activity_person_cid
            if ($cid) {
                $campaign = $this->campaign->findOrFail($cid);
                
                $campaign->participants()->attach($person, ['activity'=>'correct']);
                //insert the campaign_person_activity
            }
            //
            $user->update(['apitoken' => $user->setApiToken()]);
            return redirect()->route('user.show', $user->id)
                ->withMessage("Thank You. Your branch associations have been confirmed. Check out the rest of your profile.");
        } else {
            //go to home screen
            
            return redirect()->route('welcome')
                ->withMessage("Invalid or expired token.");
        }
    }
    /**
     * [confirm description]
     * 
     * @param [type] $token [description]
     * @param [type] $cid   [description]
     * 
     * @return [type]        [description]
     */
    public function confirm($token, $cid = null)
    {
        
        //validate user from token
        if ($user = $this->user->getAccess($token)) {
            $person = $user->person()->first();
            //login user
            auth()->login($user);
            // update token - single useauth()->login($user);
            $user->update(['apitoken' => $user->setApiToken()]);

            if ($cid) {
                $campaign = $this->campaign->findOrFail($cid);
                $campaign->participants()->attach($person, ['activity'=>'confirm']);
            }
            // insert activity_person_cid
            return redirect()->route('branchassignments.show', $user->id);
        } else {
            return redirect()->route('welcome')
                ->withMessage("Invalid or expired token");
        }
    }
    /**
     * [change description]
     * 
     * @param Request $request [description]
     * @param User    $user    [description]
     * 
     * @return [type]           [description]
     */
    public function change(Request $request, User $user)
    {
        
        $person = $user->person()->with('branchesServiced')->first();
        $role = $user->roles()->first();
     
        if ($person->branchesServiced->contains('id', request('id'))) {
            $person->branchesServiced()->detach(request('id'));
        } else {

            $person->branchesServiced()
                ->attach([request('id')=>['role_id'=>$role->id]]);
        }
    }
}
