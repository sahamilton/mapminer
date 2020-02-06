<?php

namespace App\Http\Controllers\Admin;

use App\Branch;
use App\Person;
use App\Campaign;
use App\Role;
use Carbon\Carbon;
use App\Serviceline;
use App\Http\Controllers\BaseController;
use App\BranchManagement;
use App\Http\Requests\BranchAssignmentRequest;
use Mail;
use Excel;
use App\Exports\BranchManagementSheet;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchManagementController extends BaseController
{
    public $branch;
    public $person;
    public $role;
    public $campaign;
    public $serviceline;

    public $branchmanagement;
    
    public $branchRoles = [3,5,11,9,13];
    /**
     * [__construct description]
     * 
     * @param Branch           $branch           [description]
     * @param Person           $person           [description]
     * @param Role             $role             [description]
     * @param BranchManagement $branchmanagement [description]
     * @param Serviceline      $serviceline      [description]
     * @param Campaign         $campaign         [description]
     */
    public function __construct(
        Branch $branch,
        Person $person,
        Role $role,
        BranchManagement $branchmanagement,
        Serviceline $serviceline,
        Campaign $campaign
    ) {


        $this->branch = $branch;
        $this->person = $person;
        $this->role = $role;
        $this->serviceline = $serviceline;
        $this->campaign = $campaign;
        $this->branchmanagement = $branchmanagement;
        parent::__construct($this->branch);
    }
    /**
     * [index description]
     * 
     * @return [type] [description]
     */
    public function index()
    {

        // show all branches that do not have managers


        $roles = $this->role->whereIn('id', $this->branchRoles)->get();
 
        $branches = $this->_branchesWithoutManagers();
        $people = $this->_managersWithoutBranches();

        return response()->view('admin.branches.manage', compact('branches', 'people', 'roles'));
    }

    public function export()
    {
        $roles = $this->role->whereIn('id', $this->branchRoles)->get();
        $branches = $this->_branchesWithoutManagers();
        $people = $this->_managersWithoutBranches();
        $views = ['branches', 'managers'];
        foreach($views as $view) {

         return Excel::download(new BranchManagementSheet($roles, $branches, $people, $view), now()->format('Y-m-d') ." " . $view. ' branchmanagement.csv');
        }
    }
    /**
     * [select description]
     * 
     * @return [type] [description]
     */
    public function select()
    {

            $roles = $this->role->wherehas(
                'permissions', function ($q) {
                    $q->where('permissions.name', '=', 'service_branches');
                }
            )
                ->pluck('name', 'id')->toArray();
                
                $servicelines = $this->serviceline->whereIn('id', $this->userServiceLines)->get()->pluck('ServiceLine', 'id')->toArray();

            
            // we need to move this to a model, db or config
            $message = "It is important that we keep Mapminer data up to date as we use this information to assign leads among other things. Please help us help you by confirming or correcting the following information:";
     
            return response()->view('admin.branches.select', compact('roles', 'message', 'servicelines'));
    }
    /**
     * [confirm description]
     * 
     * @param BranchAssignmentRequest $request [description]
     * 
     * @return [type]                           [description]
     */
    public function confirm(BranchAssignmentRequest $request)
    {
        
    
        $recipients = $this->branchmanagement->getRecipients($request);
        $test = request('test');
        $campaign = $this->_createCampaign($request);
      
        return response()->view('admin.branches.confirm', compact('recipients', 'test', 'campaign'));
    }
    /**
     * [emailAssignments description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function emailAssignments(Request $request)
    {

        $emails = 0;
           
        if (request('id')) {
            $campaign = $this->campaign->findOrFail(request('campaign_id'));
            $recipients = $this->branchmanagement->getConfirmedRecipients($request);
            $this->_addRecipients($campaign, $recipients);
            $campaign->update(['expiration' => Carbon::now()->addDays(request('days'))]);
                
            //$campaign = $this->createCampaign($recipients,$request);
            $emails = $this->branchmanagement->sendEmails($recipients, $request, $campaign);
                 
            return redirect()->route('branchassignment.check')->withMessage($emails . ' emails sent.');
        }
            return redirect()->route('branchassignment.check')->withMessage('No emails sent.');
    }
    /**
     * [_createCampaign description]
     * 
     * @param [type] $request [description]
     * 
     * @return [type]          [description]
     */
    private function _createCampaign($request)
    {
        
        return $this->campaign->create(['type'=>'branch assignment email','test'=>request('test'),'route'=>'branchassignment.check','message'=>request('message'),'created_by'=>auth()->user()->id]);
    }
    /**
     * [_addRecipients description]
     * 
     * @param [type] $campaign   [description]
     * @param [type] $recipients [description]
     */
    private function _addRecipients($campaign, $recipients)
    {
        return $campaign->participants()->attach($recipients);
    }
    
    /**
     * [_branchesWithoutManagers description]
     * 
     * @return [type] [description]
     */
    private function _branchesWithoutManagers()
    {
        return $this->branch
            ->doesntHave('manager')
            ->orWhere(
                function ($q) {
                    $q->doesntHave('businessmanager')
                        ->doesntHave('marketmanager');
                }
            )
            ->with('servicelines', 'manager', 'marketmanager', 'businessmanager')
            ->get();
    }
    /**
     * [_managersWithoutBranches description]
     * 
     * @return [type] [description]
     */
    private function _managersWithoutBranches()
    {
        return $this->person
            ->with('userdetails.roles', 'reportsTo', 'userdetails.serviceline')
            ->doesntHave('manages')
            ->manages($this->branchRoles)
            ->get();
    }
    /**
     * [noManagers description]
     * 
     * @param string $mgr [description]
     * 
     * @return [type]      [description]
     */
    public function noManagers(string $mgr)
    {
        return Excel::download(new BranchManagerExport($mgr), 'ManagerLessBranch.csv');
    }
}
