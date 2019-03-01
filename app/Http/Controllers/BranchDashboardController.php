<?php

namespace App\Http\Controllers;

use App\Activity;
use App\ActivityType;
use App\Address;
use App\AddressBranch;
use App\Branch;
use App\Company;
use App\Contact;
use App\Note;
use App\Http\Requests\OpportunityFormRequest;
use App\Opportunity;
use App\Person;
use \Carbon\Carbon;
use Illuminate\Http\Request;

class BranchDashboardController extends Controller
{
    public $address;
    public $addressbranch;
    public $branch;
    public $contact;
    public $opportunity;
    public $activity;
    public $person;


    public function __construct(
        Activity $activity,
        Address $address,
        AddressBranch $addressbranch,
        Branch $branch,
        Contact $contact,
        Opportunity $opportunity,
        Person $person
    ) {
        $this->address = $address;
        $this->addressbranch = $addressbranch;
        $this->branch = $branch;
        $this->contact = $contact;
        $this->opportunity = $opportunity;
        $this->person = $person;
        $this->activity = $activity;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $activityTypes = ActivityType::all();
        if(auth()->user()->hasRole('admin') or auth()->user()->hasRole('sales_operations')){

             $myBranches = $this->branch->all()->pluck('branchname','id')->toArray();

             $data = $this->getSummaryBranchOpportunities(array_keys($myBranches));
            // dd($data['charts']['chart'][0]);
             return response()->view('opportunities.mgrindex', compact('data', 'activityTypes'));
        } else {
             $myBranches = $this->person->myBranches();
        }
    
        if(count($myBranches)==0){
            return redirect()->route('user.show',auth()->user()->id)->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
        }
        if ((! auth()->user()->hasRole('branch_manager') && $this->person->myTeam()->count() >1 )) {
            $data = $this->getSummaryBranchOpportunities(array_keys($myBranches));
            
            return response()->view('opportunities.mgrindex', compact('data'));
        } else {
                       $data = $this->getBranchOpportunities([array_keys($myBranches)[0]]);
                       $data['weekcount'] = $this->activity->where('user_id', '=', auth()->user()->id)
                       ->currentWeekCount()
                       ->pluck('activities', 'user_id')->toArray();
                     

                      return response()->view('opportunities.index', compact('data', 'activityTypes', 'myBranches'));
        }
        return redirect()->route('user.show', auth()->user()->id)->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
        // if no branches abort
        // if no branches then select branc / Sales OPs
    }

    
    public function getBranchOpportunities(array $branches)
    {
        
        $data['branches'] = $this->branch->with('opportunities', 'leads', 'manager')
            ->whereIn('id', $branches)
            ->get();
        $data['opportunities'] = $this->opportunity
                ->whereIn('branch_id', $branches)
                ->with('address', 'branch', 'address.activities')
                ->orderBy('branch_id')
                ->distinct()
                ->get();

        $data['addresses'] = $data['opportunities']->map(function ($opportunity) {
            return $opportunity->address;
        });

        $data['activities'] = $data['addresses']->map(function ($address) {
            if ($address) {
                return $address->activities;
            }
        });
       
        $data['branchorders'] = $this->branch->with('orders', 'orders.address')->whereIn('id', $branches)->get();
 
        $data['leads'] = $this->branch->with('leads', 'leads.leadsource', 'leads.createdBy')

                        ->whereIn('id', $branches)->get();
                   
        $opportunity = $data['opportunities']->pluck('address_id')->toArray();
        $customer = $data['branchorders']->pluck('address_id')->toArray();
        
        $data['contacts'] = $this->contact->whereIn('address_id', array_merge($opportunity, $customer))->with('location')->get();

        $data['notes'] = $this->getBranchNotes($branches);
      
        return $data;
    }

    private function getBranchNotes($branches)
    {

        return Note::whereHas('relatesToLocation', function ($q) use ($branches) {
            $q->whereHas('assignedToBranch', function ($q) use ($branches) {
                $q->whereIn('branch_id', $branches);
            });
        })->with('relatesToLocation', 'writtenBy', 'writtenBy.person')->get();
    }
}
