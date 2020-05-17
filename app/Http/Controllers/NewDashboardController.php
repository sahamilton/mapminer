<?php

namespace App\Http\Controllers;
use App\Branch;
use App\Person;
use App\Campaign;
use App\Company;
use App\ActivityType;

use Illuminate\Http\Request;

class NewDashboardController extends Controller
{
    public $branch;
    public $person;
    public $company;
    public $campaign;
    public $period;
    public $fields = [
                    "supplied_leads",
                    "offered_leads",
                    "worked_leads",
                    "rejected_leads",
                    "touched_leads",
                    "new_opportunities",
                    'top_25opportunities',
                    "won_opportunities",
                    "opportunities_open",
                    "won_value",
                    "open_value",
                ];

    public function __construct(Branch $branch, Campaign $campaign, Company $company, Person $person) 
    {
        $this->branch = $branch;
        $this->campaign = $campaign;
        $this->company = $company;
        $this->person = $person;
        $this->period = $this->branch->getPeriod('lastMonth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = auth()->user()->roles->pluck('name')->toArray();
   
        switch($roles[0]) {
        // check role
        case 'NAM':
            if (! $nam = $this->person->has('managesAccount')
                    ->with('managesAccount')
                    ->find(auth()->user()->person->id)
                ) {
                return redirect()->back()->withMessage('You are not assigned to any companies');
            } 
            $company = $nam->managesAccount->first();
            return $this->showCompany($company);

            break;     
        case 'admin':
            $managers = $this->person->wherehas(
                'userdetails.roles', function ($q) {
                    $q->whereIn('role_id', [3,4,6,7,9,14]);
                }
            )->with('userdetails.roles', 'reportsTo', 'branchesServiced')
            ->get();
            return response()->view('dashboards.select', compact('managers'));
            break; 

        default:
           
            // does person have any managers reporting to them
            dd($this->person->find(auth()->user()->person->id)->get()->descendants()->get());
            if ($team->count() >1) {

            } else {


            }
            
            break;     
        } 
    }

    public function show(Person $manager)
    {
       
        return $this->showManager($manager);
            
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showBranch(Branch $branch)
    {
        if ($this->_isValidBranch($branch)) {
            return $this->_getBranchDashboard($branch->id);
        } 
        return redirect()->back()->withError('You are not assigned to ' . $branch->branchname. ' branch');
        
    }
   
    public function showManager(Person $person)
    {
        
        if ($this->_isValidManager($person)) {

            $myBranches = $this->branch->whereIn('id', $person->getMyBranches())->get();
            if ($person->userdetails->hasRole(['national_account_manager'])) {
                
                if (! $companies = $this->_getNAMDashboard($person)) {
                    return redirect()->back()->withError($person->fullName() . ' is not assigned to any accounts');
                } 
                $data = $this->_getNAMChartData($companies);
                
                $period = $this->period;
                return response()->view('dashboards.namdashboard', compact('companies', 'person', 'period', 'data'));

            } elseif ($myBranches->count()==1) {
                
                return $this->_getBranchDashboard($myBranches->first());

            } elseif ($myBranches->count() >1) {
                
                return $this->_getManagerBranchDashboard($person);

            } else {
                return redirect()->back()->withError('You do not have any dashboards');    
            }
        }
        return redirect()->back()->withError($person->fullName() . ' is not part of your team');   
    }

    public function showCampaign(Campaign $company)
    {
        dd($campaign);
    }

    public function showCompany(Company $company)
    {
        if ($this->_isValidCompany($company)) {
            $branches = $this->_getCompanyDashboard($company);
            
            $person = $company->load('managedBy')->managedBy;
            $period = $this->period;
            $fields = $this->fields;
            return response()->view('dashboards.nambranchdashboard', compact('branches', 'person', 'period', 'fields', 'company'));
        }
        return redirect()->back()->witherror($company->companyname . ' is not one of your assigned accounts');
        
    }

   

    public function setPeriod(Request $request)
    {
        
        $this->period = $this->branch->setPeriod($request);
        
        session(['period'=>$this->period]);
    }

    public function setBranch(Request $request)
    {
        $this->branch = $this->branch->findOrFail(request('branch'));
        session(['branch'=>$this->branch->id]);
    }

    public function setManager(Request $request)
    {
        $this->manager = $this->person->findOrFail(request('manager'));
        session(['manager'=> $this->manager->id]);
    }

    public function setCompany(Request $request)
    {
        $this->company = $this->company->findOrFail(request('company'));
        session(['company'=>$this->company->id]);
    }

    public function setCampaign(Request $request)
    {
        $this->campaign = $this->campaign->findOrFail(request('campaign'));
        session(['campaign'=>$this->campaign->id]);
    }

    private function _isValidBranch()
    {

    }

    private function _isValidManager(Person $person)
    {
        return $this->person->inMyTeam($person);
    }
    
    private function _isValidCampaign()
    {

    }

    private function _isValidCompany(Company $company)
    {
        // is admin or user manages this company
        return $this->person->inMyAccounts($company);
    }

    private function _getBranchDashboard(Branch $branch)
    {
        dd(193, $branch);
    }

    private function _getManagerBranchDashboard(Person $person)
    {
        dd(198, $person);
    }

    private function _getNAMDashboard(Person $person)
    {
    
        $person->load('managesAccount');

        if ($person->managesAccount->count() >0 ) {
                $fields = ['Top25', 'won_opportunities', 'lost_opportunities', 'open_opportunities', 'open_leads', 'active_leads'];
                $accounts = $person->managesAccount->pluck('id')->toArray();
                return $this->company
                    ->pipeline()
                    ->activitySummary($this->period)
                    ->opportunitySummary($this->period, $fields)
                    ->leadSummary($this->period, $fields )
                    ->whereIn('id', $accounts)->get();
        }
        return false;
       
        return redirect()->route('newdashboard')->withMessage($person->fullName() . ' is not assigned to any accounts');
    }
    /**
     * [_getCompanyDashboard description]
     * 
     * @param Company $company [description]
     * 
     * @return colelction      [description]
     */
    private function _getCompanyDashboard(Company $company)
    { 
        // id branches that have assigned leads for this company
        $branches = $company->assigned->map(
            function ($address) {
                return $address->assignedToBranch->pluck('id');
            }
        );
        $branch_ids = array_unique($branches->flatten()->toArray());
        
        return $this->branch->summaryCompanyStats($this->period, [$company->id])
            ->whereIn('id', $branch_ids)->get();
        
    }
    private function _getNAMChartData($companies)
    {
        $data['team']['activitytypechart'] = $this->_getActivityChartData($companies);

        return $data;
    }
    private function _getActivityChartData($companies)
    {
        $activityTypes = ActivityType::all();
        $data['labels'] = $companies->pluck('companyname')->toArray();
       
        foreach ($companies as $company) {
           
            foreach ($activityTypes as $type) {
                $field = str_replace(" ", "_", $type->activity);
                
                if (isset($company->$field)) {
                    $data[$company->companyname]['data'][$field] = $company->$field;
                    $data[$company->companyname]['color'][$field] = $type->color;
                }
            }

        }
        return $data;

    }
    private function _getCampaignDashboard(Campaign $campaign)
    {
        dd(208, $campaign);
    }
}
