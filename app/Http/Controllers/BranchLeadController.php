<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Address;
use App\Models\Person;
use App\Models\Branch;
use App\Models\Note;
use App\Models\BranchLead;
use Illuminate\Http\Request;

class BranchLeadController extends Controller
{
    public $lead;
    public $branch;
    public $address;
    public $person;
    public $note;
    public $branchlead;

    /**
     * [__construct description]
     * 
     * @param Lead       $lead       [description]
     * @param Person     $person     [description]
     * @param BranchLead $branchlead [description]
     * @param Branch     $branch     [description]
     * @param Address    $address    [description]
     */
    public function __construct(
        Lead $lead, 
        Person $person, 
        BranchLead $branchlead, 
        Branch $branch, 
        Address $address,
        Note $note
    ) {
        $this->lead = $lead;
        $this->address = $address;
        $this->branch = $branch;
        $this->branchlead = $branchlead;
        $this->person = $person;
        $this->note = $note;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if (count($this->person->myBranches())>0) {
            $branches = $this->branch->whereIn(
                'id', array_keys($this->person->myBranches())
            )
                ->withCount('leads')->with('manager')->get();
        } else {
            $branches = $this->branch->withCount('leads')->with('manager')->get();
        }
    
        return response()->view('branchleads.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request [description]
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

       
        $exists = $this->branchlead
            ->where('branch_id', '=', request('branch_id'))
            ->where('address_id', '=', request('address_id'))
            ->get();
        // check if one of the users branches

        if ($exists->count() > 0) {
                return redirect()->back()->withError('Branch ' . request('branch_id') . ' already owns this lead');
        }

        if (in_array(request('branch_id'), auth()->user()->person->getMyBranches())) {

            $this->branchlead->create(
                [
                    'address_id'=>request('address_id'),
                    'branch_id'=>request('branch_id'),
                    'status_id'=>'2',
                    'person_id'=>auth()->user()->person->id
                ]
            );
          
             return redirect()->route('address.show', request('address_id'));
        } else {
            return redirect()->back()->withError(request('branch_id') . ' is not one of your branches');
        }
    }
    /**
     * [show description]
     * 
     * @param Branch $branch [description]
     * 
     * @return [type]         [description]
     */
    public function show(BranchLead $branch)
    {
        
        return response()->view('branchleads.show', compact('branch'));
    }

    
    /**
     * [update description]
     * 
     * @param Request    $request    [description]
     * @param BranchLead $branchLead [description]
     * 
     * @return [type]                 [description]
     */
    public function update(Request $request, BranchLead $branchLead)
    {
        
        $branchLead->update(['status_id'=>2]);
       
        return redirect()->route('address.show', $branchLead->address_id);
    }

    /**
     * [destroy description]
     * 
     * @param BranchLead $branchLead [description]
     * 
     * @return [type]                 [description]
     */
    public function destroy(Request $request, BranchLead $branchLead)
    {
        $data['note'] = auth()->user()->person->fullName() . " removed this lead from branch " . $branchLead->branch_id ." on " .now()->format('Y-m-d');
        $data['user_id'] = auth()->user()->id;
        $data['address_id'] = $branchLead->address_id;
        $this->note->create($data);
        $branchLead->delete();
        return redirect()->back()->withMessage('Lead removed from branch '. $branchLead->branch_id);
    }
    /**
     * [assign description]
     * 
     * @return [type] [description]
     */
    public function assign()
    {
        $leads = $this->address       
            ->where('addressable_type', '=', 'lead')
            ->get();
        $a=$leads->count();
        foreach ($leads as $lead) {
            $branch = $this->branch->nearby($lead, '25', 1)->get();
            if ($branch->count()>0) {
                $data = ['branch_id'=>$branch->first()->id, 'address_id'=>$lead->id];
                $a--;
                BranchLead::create($data);
            }
        }
       
    }
    /**
     * [branchStaleLeads description]
     * 
     * @param Branch $branch [description]
     * 
     * @return [type]         [description]
     */
    public function branchStaleLeads(Branch $branch)
    {
        $branch->load('staleLeads');
        return response()->view('branchleads.staledetail', compact('branch'));
    }
    /**
     * [staleLeads description]
     * 
     * @return [type] [description]
     */
    public function staleLeads()
    {
        if (count($this->person->myBranches()) >0 ) {
            $branches = $this->branch->select('id', 'branchname')->whereIn(
                'id', array_keys($this->person->myBranches())
            )->withCount('staleLeads')->get();
        
       
            return response()->view('branchleads.staleleads', compact('branches'));
        } else {
            return redirect()->back()->withError("You have no branches");
        }
    }
    public function showDuplicates()
    {

        
        return response()->view('branchleads.duplicates', compact('leads'));
    }
}
