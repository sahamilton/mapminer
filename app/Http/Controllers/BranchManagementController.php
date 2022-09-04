<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use App\Models\Person;
use App\Models\Campaign;
use Mail;
use App\Mail\NotifyBranchAssignments;
use App\Models\BranchManagement;
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
    public function show(User $user)
    {

        if (! auth()->user()->hasRole('admin')) {
            $user = auth()->user();
        }

        $person = $user->person;
           
        $branches = Branch::whereIn('id', $person->getMyBranches())
            ->with('manager', 'branchteam')
            ->nearby($person, 3000)
            ->get();
         
        return response()->view(
            'branchassignments.show', ['details'=>$person,'branches'=>$branches]
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
    public function update(BranchManagementRequest $request, $user)
    {
        
        if (! auth()->user()->hasRole('admin')) {
            $user = auth()->user()->id;
        }

        $person = $this->person->whereUser_id($user)->firstorFail();
        $role = $person->findRole();
        $branches = $this->branchmanagement->getBranches($request, $role[0]);
        $validate = $this->_checkIfBranchesDontBelongToUser($branches, $person);
        if (count($validate) > 0) {
            return redirect()->back()->withError(count($validate) . ' branch(es) already has a manager who reports to someone who is not in your team. Try again');
        } else {
            $person->branchesServiced()->sync($branches);
            return redirect()->route('user.show', $user)
                ->withMessage("Thank You. Your branch associations have been updated. Check out the rest of your profile.");
        }
        
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
    /**
     * _checkIfBranchesDontBelongToUser 
     * 
     * Validate the branches offered to ensure that they either have no 
     * current manager or are managed by part of the persons team.
     * 
     * @param Array  $branches List of branches requested to sync
     * @param Person $person   Manager requesting update to branch assignments.
     * 
     * @return Array  if empty the branches are validated else return managers not in team
     */
    private function _checkIfBranchesDontBelongToUser(Array $branches, Person $person) :array
    {
      
        $branches = Branch::whereIn('branches.id', array_keys($branches))
            ->has('relatedPeople')
            ->with('relatedPeople')
            ->get();
       
        $managers = $branches->map(
            function ($branch) {
                
                return $branch->relatedPeople->pluck('reports_to')->toArray();
            }
        );
        $team =$person->getDescendantsAndSelf()->pluck('reports_to')->unique()->toArray();
        return array_diff($managers->flatten()->unique()->toArray(), $team);
    }   
}
