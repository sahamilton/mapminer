<?php

namespace App\Http\Controllers;

use App\Lead;
use App\Address;
use App\Person;
use App\Branch;
use App\BranchLead;
use Illuminate\Http\Request;

class BranchLeadController extends Controller
{
    public $lead;
    public $branch;
    public $address;
    public $person;
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
        Address $address
    ) {
        $this->lead = $lead;
        $this->address = $address;
        $this->branch = $branch;
        $this->branchlead = $branchlead;
        $this->person = $person;
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
        dd(request()->all());
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
        $this->branchlead->create(
            [
                'address_id'=>request('address_id'),
                'branch_id'=>request('branch_id'),
                'status_id'=>'2'
            ]
        );
        return redirect()->route('address.show', request('address_id'));
    }

    /**
     * [show description]
     * 
     * @param Branch $branch [description]
     * 
     * @return [type]         [description]
     */
    public function show(Branch $branch)
    {
       
        $branch = $branch->load(
            'leads', 'manager', 'leads.industryVertical', 'leads.leadsource'
        );

        return response()->view('branchleads.show', compact('branch'));
    }

    /**
     * [edit description]
     * 
     * @param BranchLead $branchLead [description]
     * 
     * @return [type]                 [description]
     */
    public function edit(BranchLead $branchLead)
    {
        //
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
        
        $branch = $this->branch->findOrFail($branchLead->branch_id);
        $branchLead->update(['status_id'=>4, 'comments'=>request('comment')]);
        return redirect()->route('branchdashboard.show', $branch->id);
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
}
