<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Models\LeadSource;
use App\Models\Lead;
use App\Models\Branch;
use App\Models\Person;
use App\Models\LeadStatus;
use App\Mail\NotifyLeadsAssignment;
use App\Mail\NotifyManagersLeadsAssignment;
use App\Mail\NotifySenderLeadsAssignment;
use App\Http\Requests\LeadSourceFormRequest;
use Carbon\Carbon;

class LeadsEmailController extends Controller
{
    
    public $leadsource;
    public $branch;


    public function __construct(LeadSource $leadsource, Branch $branch)
    {
        $this->leadsource = $leadsource;
        $this->branch = $branch;
    }

    public function announceLeads($leadsource)
    {
        
        $source = $leadsource->load('leads', 'leads.assignedToBranch', 'verticals');
        $data = $this->_getBranches($source);
        
        $data['branches'] = $this->branch->whereIn('id', array_keys($data))->with('manager', 'manager.userdetails', 'manager.reportsTo')->get();
        $data['people'] = $this->_getBranchManagers($data['branches']);
        $message = $this->_createMessage($source);
        return response()->view('leadsource.salesteam', compact('source',  'data', 'message'));
    }

    private function _getBranches($source)
    {
        $branches = $source->leads->map(function ($lead) {
            return $lead->assignedToBranch->pluck('branchname', 'id');
        });
       
        foreach ($branches as $leads) {
            foreach ($leads as $id => $branch) {
                if (isset($data[$id])) {
                    $data[$id]=1+$data[$id];
                } else {
                    $data[$id]=1;
                }
            }
        }
        // data [ 'branch_id'=>number of leads]
        return $data;
    }
    /**
     * [_getBranchManagers description]
     * 
     * @param [type] $branches [description]
     * 
     * @return [type]           [description]
     */
    private function _getBranchManagers($branches) :array
    {
        return $branches->map(
            function ($branch) {
                return $branch->manager;
            }
        );
        
    }
    /**
     * [branches description]
     * 
     * @param [type] $leads [description]
     * 
     * @return [type]        [description]
     */
    public function branches($leads) :array
    {
        

        $branches =  $leads->map(
            function ($lead) {
                return $lead->assignedToBranch->pluck('id');
            }
        )->flatten();
        $branchManagers = Branch::whereIn('id', array_unique($branches->toArray()))->with('manager')->get();
        $managers = $branchManagers->map(
            function ($branch) {
                return $branch->manager->load('userdetails');
            }
        );
        return $managers;
    }
    /**
     * [_createMessage description]
     * 
     * @param [type] $source [description]
     * 
     * @return [type]         [description]
     */
    private function _createMessage($source) : string
    {
        $message = "You have new leads offered to you in the " . $source->source." lead campaign. ";
        $message .= $source->description;
        $message .= "<p>These leads are available from ".$source->datefrom->format('M j, Y') . " until "  .$source->dateto->format('M j, Y')."</p>";
        $message .= "Leads in this campaign are for the following sales verticals:";
        $message .="<ul>";
        foreach ($source->verticals as $vertical) {
            if ($vertical->isLeaf()) {
                $message .= "<li>".$vertical->filter."</li>";
            }
        }
        $message .= "</ul>";
        $message .="Check out <strong><a href=\"".route('salesleads.index'). "\">MapMiner</a></strong> to accept these leads and for other resources to help you with these leads.";
        return $message;
    }
    /**
     * [email description]
     * 
     * @param Request $request    [description]
     * @param [type]  $leadsource [description]
     * 
     * @return [type]              [description]
     */
    public function email(Request $request, $leadsource)
    {

       
        $data = request()->except('_token');
        $data['branches'] = $this->_getBranches($leadsource);
        $branches = $this->branch->whereIn('id', array_keys($data['branches']))
            ->has('manager')
            ->with('manager', 'manager.userdetails', 'manager.reportsTo')
            ->get();

        $data['count'] = $branches->count();
       // $this->_notifyBranchTeam($data,$branches,$leadsource);

        if (request()->has('managers')) {
            $this->_notifyBranchTeam($data, $branches, $leadsource);
            $this->_notifyManagers($data, $branches, $leadsource);
        }
        
        $this->_notifySender($data, $leadsource);
   
        return response()->view('leadsource.senderleads', compact('data', 'leadsource'));
    }
    /**
     * [_notifyBranchTeam description]
     * 
     * @param [type] $data       [description]
     * @param [type] $branches   [description]
     * @param [type] $leadsource [description]
     * 
     * @return [type]             [description]
     */
    private function _notifyBranchTeam($data, $branches, $leadsource)
    {
        if (isset($data['test'])) {
            $branch = $branches->random();

            foreach ($branch->manager as $manager) {
                Mail::to(auth()->user()->email, $manager->fullName())
                    ->queue(new NotifyLeadsAssignment($data, $manager, $leadsource, $branch));
            }
        } else {
            foreach ($branches as $branch) {
                foreach ($branch->manager as $manager) {
                    Mail::to($manager->userdetails->email, $manager->fullName())
                        ->queue(new NotifyLeadsAssignment($data, $manager, $leadsource, $branch));
                }
            }
        }
    }
    /**
     * [_notifyManagers description]
     * 
     * @param [type] $data       [description]
     * @param [type] $branches   [description]
     * @param [type] $leadsource [description]
     * 
     * @return [type]             [description]
     */
    private function _notifyManagers($data, $branches, $leadsource)
    {
      

        $managers = $branches->map(
            function ($branch) {
                return $branch->manager->first()->reportsTo;
            }
        );
        
        if (isset($data['test'])) {
            foreach ($managers as $manager) {
                Mail::to(auth()->user()->email, $manager->reportsTo->fullName())
                    ->queue(new NotifyManagersLeadsAssignment($data, $manager, $leadsource, $branches));
            }
        } else {
            foreach ($managers as $manager) {
                Mail::to($manager->userdetails->email, $manager->fullName())
                    ->queue(new NotifyManagersLeadsAssignment($data, $manager, $leadsource, $branches));
            }
        }
    }
    /**
     * [_notifySender description]
     * 
     * @param [type]     $data       [description]
     * @param LeadSource $leadsource [description]
     * 
     * @return [type]                 [description]
     */
    private function _notifySender($data, LeadSource $leadsource)
    {
       
       
        Mail::to(auth()->user()->email)->queue(new NotifySenderLeadsAssignment($data, $leadsource));
    }

}
