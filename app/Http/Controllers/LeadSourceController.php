<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Address;
use App\Branch;
use App\AddressBranch;
use App\Company;
use App\Lead;
use App\LeadSource;
use App\LeadStatus;
use App\Person;
use App\SearchFilter;
use App\Serviceline;


use Excel;
use Carbon\Carbon;

use App\Http\Requests\LeadSourceFormRequest;
use App\Http\Requests\LeadSourceAddLeadsFormRequest;
use App\Http\Requests\FlushStaleLeadsRequest;

use App\Exports\LeadSourceExport;
use App\Jobs\StaleLeads;
use App\Exports\StaleLeadsExport;

class LeadSourceController extends Controller
{
    public $address;
    public $addressbranch;
    public $branch;
    public $company;
    public $lead;
    public $leadsource;
    public $leadstatus;
    public $person;
    public $vertical;
    
    
    /**
     * [__construct description]
     * 
     * @param Address       $address       [description]
     * @param AddressBranch $addressbranch [description]
     * @param Branch        $branch        [description]
     * @param Company       $company       [description]
     * @param Lead          $lead          [description]
     * @param LeadSource    $leadsource    [description]
     * @param LeadStatus    $status        [description]
     * @param Person        $person        [description]
     * @param SearchFilter  $vertical      [description]
     */
    public function __construct(
        Address $address,
        AddressBranch $addressbranch,
        Branch $branch,
        Company $company,
        Lead $lead,
        LeadSource $leadsource,
        LeadStatus $status,
        Person $person,
        SearchFilter $vertical
    ) {
        $this->address = $address;
        $this->addressbranch = $addressbranch;
        $this->branch = $branch;
        $this->company = $company;
        $this->lead = $lead;
        $this->leadsource = $leadsource;
        $this->leadstatus = $status;
        $this->person = $person;
        $this->vertical=$vertical;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        $leadsources = $this->leadsource->withCount(
            ['addresses',
            'addresses as assigned'=>function ($query) {
                $query->has('assignedToBranch')->orHas('assignedToPerson');
            },
            'addresses as unassigned' => function ($query) {
                $query->whereDoesntHave('assignedToBranch')->whereDoesntHave('assignedToPerson');
            },
            'addresses as closed' => function ($query) {
                    $query->has('closed');
            }]
        )->get();

        return response()->view('leadsource.index', compact('leadsources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $verticals = $this->vertical->industrysegments();
         $servicelines = Serviceline::all();
         return response()->view('leadsource.create', compact('verticals', 'servicelines'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeadSourceFormRequest $request)
    {


        request()->merge(['user_id'=>auth()->user()->id]);
        $leadsource = $this->leadsource->create(request()->except('datefrom', 'dateto'));
        $leadsource->update(
            [
            'datefrom'=>Carbon::createFromFormat('m/d/Y', request('datefrom')),
            'dateto'=>Carbon::createFromFormat('m/d/Y', request('dateto')),
            ]
        );
        $leadsource->verticals()->sync(request('vertical'));
        $leadsource->servicelines()->sync(request('serviceline'));

        return redirect()->route('leadsource.index');
    }

    /**
     * [show description]
     * 
     * @param [type] $leadsource [description]
     * 
     * @return [type]             [description]
     */
    public function show(LeadSource $leadsource)
    {


        $leadsource = $leadsource->summary()->findOrFail($leadsource->id);
     
        $teamStats=[];
        $team = $leadsource->salesteam($leadsource->id);

        foreach ($team as $person) {
            $teamStats[$person->id][$person->status_id]= $person->count;
            $teamStats[$person->id]['name'] = $person->name;
        }
     
        $branches = $this->branch
            ->whereHas(
                'leads', function ($q) use ($leadsource) {
                    $q->where('lead_source_id', '=', $leadsource->id);
                }
            )
            ->withCount(
                ["leads",
                'leads as assigned'=>function ($query) use ($leadsource) {
                               $query->where('lead_source_id', $leadsource->id)->has('assignedToBranch');
                },
                'leads as claimed' => function ($query) use ($leadsource) {
                                   $query->where('lead_source_id', $leadsource->id)->has('claimedByBranch');
                },
                           
                'leads as closed' => function ($query) use ($leadsource) {
                                   $query->where('lead_source_id', $leadsource->id)->has('closed');
                }]
            )->get();
   
        $branchStats['assigned']=0;
        $branchStats['claimed']=0;
        $branchStats['closed']=0;
      
        foreach ($branches as $branch) {
            foreach ($branchStats as $key => $count) {
                $branchStats[$key] =  $branchStats[$key] + $branch->$key;
            }
        }

        $branchStats['leads_count']=$leadsource->addresses_count;
        $branchStats['branch_count'] = $branches->count();


        // $data = $this->leadsource->leadRepStatusSummary($id);
        $statuses = LeadStatus::pluck('status', 'id')->toArray();
   

        return response()->view('leadsource.show', compact('statuses', 'teamStats', 'branches', 'branchStats', 'leadsource'));
    }

    
   
    /**
     * [branches description]
     * 
     * @param LeadSource $leadsource [description]
     * 
     * @return [type]                 [description]
     */
    public function branches(LeadSource $leadsource)
    {
           
        $branches = Branch::whereHas(
            'leads', function ($q) use ($leadsource) {
                $q->where('lead_source_id', '=', $leadsource->id);
            }
        )
        ->withCount('leads')
        ->with('leads.ownedBy')
        ->with('manager')
        ->orderBy('id')
        ->get();
           
        return response()->view('leads.branches', compact('branches', 'leadsource'));
    }
    /**
     * [unassigned description]
     * 
     * @param LeadSource $leadsource [description]
     * 
     * @return [type]                 [description]
     */
    public function unassigned(LeadSource $leadsource)
    {
       
        $leadsource = $this->leadsource->withCount(
            ['addresses',
            'addresses as assigned'=>function ($query) {
                $query->has('assignedToBranch')->orHas('assignedToPerson');
            },
            'addresses as unassigned' => function ($query) {
                $query->whereDoesntHave('assignedToBranch')->whereDoesntHave('assignedToPerson');
            },
            'addresses as closed' => function ($query) {
                    $query->has('closed');
            }]
        )
        ->findOrFail($leadsource->id);

        $states = Address::where('lead_source_id', '=', $leadsource->id)
                ->whereDoesntHave('assignedToBranch')->whereDoesntHave('assignedToPerson')

               ->selectRaw('state, count(*) as statetotal')
             ->groupBy('state')
             ->pluck('statetotal', 'state')->all();
       

        return response()->view('leads.unassigned', compact('leadsource', 'states'));
    }
    /**
     * [unassignedstate description]
     * 
     * @param LeadSource $leadsource [description]
     * @param [type]     $state      [description]
     * 
     * @return [type]                 [description]
     */
    public function unassignedstate(LeadSource $leadsource, $state)
    {
        
        $leadsource = $this->leadsource
            ->with(
                ['addresses' => function ($query) use ($state) {
                    $query->whereDoesntHave('assignedToBranch')
                        ->whereDoesntHave('assignedToPerson')
                        ->where('state', trim($state));
                }], 
                'addresses.state'
            )
            ->findOrFail($leadsource->id);

        return response()->view('leadsource.stateunassigned', compact('leadsource', 'state'));
    }
    

    /**
     * [edit description]
     * 
     * @param [type] $leadsource [description]
     * 
     * @return [type]             [description]
     */
    public function edit(LeadSource $leadsource)
    {
        $leadsource->load('leads', 'verticals');
        $servicelines = Serviceline::all();
        $verticals = $this->vertical->industrysegments();
        return response()->view('leadsource.edit', compact('leadsource', 'verticals', 'servicelines'));
    }

    /**
     * [update description]
     * 
     * @param LeadSourceFormRequest $request    [description]
     * @param LeadSource            $leadsource [description]
     * 
     * @return [type]                            [description]
     */
    public function update(LeadSourceFormRequest $request, LeadSource $leadsource)
    {
       
       
        $leadsource->update(request()->except('_method', '_token', 'datefrom', 'dateto'));
        $leadsource->update(
            ['datefrom'=>Carbon::createFromFormat('m/d/Y', request('datefrom')),
            'dateto'=>Carbon::createFromFormat('m/d/Y', request('dateto'))]
        );
        $leadsource->verticals()->sync(request('vertical'));
        $leadsource->servicelines()->sync(request('serviceline'));
        return redirect()->route('leadsource.index');
    }

    /**
     * [destroy description]
     * 
     * @param LeadSource $leadsource [description]
     * 
     * @return [type]                 [description]
     */
    public function destroy(LeadSource $leadsource)
    {
       
        $leadsource->delete();
        return redirect()->route('leadsource.index');
    }
    /**
     * [flushLeads description]
     * 
     * @param LeadSource $leadsource [description]
     * 
     * @return [type]                 [description]
     */
    public function flushLeads(LeadSource $leadsource)
    {
        $leadsource->leads()->delete();
        $this->address->where('lead_source_id', '=', $leadsource->id)->delete();
        return redirect()->route('leadsource.index')->withWarning('all addresses removed from lead source');
    }
    /**
     * [flushManagerLeads description]
     * 
     * @return [type] [description]
     */
    public function flushManagerLeads()
    {
        
        $leadsources = $this->leadsource
            ->whereHas(
                'branchleads', function ($q) {
                    $q->doesntHave('activities')
                        ->doesntHave('opportunities');
                }
            )
            ->withCount(
                ['branchleads'=>function ($q) {
                    $q->doesntHave('activities')
                        ->doesntHave('opportunities');
                }
                ]
            )->get();
        
        $managers = $this->person->managers();
        
        return response()->view('leadsource.flush', compact('managers', 'leadsources'));
    }
    /**
     * [flushManagerLeadsConfirm description]
     * 
     * @param  Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function flushManagerLeadsConfirm(FlushStaleLeadsRequest $request)
    {
        
        $before = Carbon::parse(request('before'));

        $leadsource = request('leadsource');
        $branches = $this->_getBranches($request);
        

        $leads = $this->addressbranch->staleLeads($leadsource, $branches, $before)->get()->count();

        if ($leads ==0) {
            return redirect()->route('leadsource.flush')
                ->withMessage(
                    "There are zero stale leads assigned to " . 
                    $manager->fullName() . 
                    "'s branches from the selected lead sources."
                );
        }
        return response()->view('leadsource.confirmflush', compact('leadsource', 'leads', 'manager', 'before'));
    }
    /**
     * [flushManagerLeadsFinal description]
     * 
     * @param  Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function flushManagerLeadsFinal(Request $request)
    {
        $before = Carbon::parse(request('before'));
        $leadsource = explode(",", str_replace("'", "", request('leadsource')));
        $branches = $this->_getBranches($request);
        $leads = $this->addressbranch
            ->staleLeads($leadsource, $branches, $before)
            ->with('branch')
            ->get();
        dd(request()->all(), $leads);
        if (request()->has('export')) {
            $file =  $this->_downloadDeletedLeads($manager, $leads);
            
        }
        $deleted = $leads->count();
        // delete the address / branch relationship
        $this->addressbranch->destroy($leads->pluck('id')->toArray());
        // delete the actual lead
        if (request()->has('delete')) {
            $this->address->destroy($leads->pluck('address_id')->toArray());
        }
        $addresses = $this->address
            ->whereDoesntHave('activities')
            ->whereDoesntHave('opportunities')
            ->whereIn('id', $leads->pluck('address_id')->toArray())
            ->delete();
        $message = $deleted . " stale leads assigned to " . 
                isset($manager) ? $manager->fullName() : "All manager" . 
                "'s branches from the selected leadsources have been deleted";
        if (request()->has('delete')) {         
                $message .=" The deleted leads are stored <a href=\"" . secure_url('storage/'. $file) ."\">here</a>";
        }
        return redirect()->route('leadsource.flush')
            ->withMessage($message);
    }
    /**
     * [_downloadDeletedLeads description]
     * 
     * @param [type] $manager [description]
     * @param [type] $leads   [description]
     * 
     * @return [type]          [description]
     */
    private function _downloadDeletedLeads($manager, $leads)
    {
        $file = 'flushed/staleLeads_'. $manager->id ."_".now()->timestamp . ".xlsx";
            dispatch(new StaleLeads($leads, $manager, $file));
            return $file;
    }
    /**
     * [addLeads description]
     * 
     * @param LeadSource $leadsource [description]
     *
     * @return Response view
     */
    public function addLeads(LeadSource $leadsource)
    {
        
        return response()->view('leadsource.addleads', compact('leadsource'));
    }
    
    /**
     * [importLeads description]
     * 
     * @param LeadSourceAddLeadsFormRequest $request [description]
     * @param [type]                        $id      [description]
     * 
     * @return [type]                                 [description]
     */
    public function importLeads(LeadSourceAddLeadsFormRequest $request, $id)
    {
        $leadsource = $this->leadsource->findOrFail($id);
        if ($request->hasFile('file')) {
            return $this->leadImport($request, $id);
        } else {
            request()->merge(['lead_source_id'=>$id]);
            $data = $this->_cleanseData(request()->all());
            $lead = $this->lead->create($data);
            $geoCode = app('geocoder')->geocode($this->_getAddress($request))->get();
            $data = $this->lead->getGeoCode($geoCode);

            $lead->update($data);
            return redirect()->route('leadsource.index');
        }
    }
    /**
     * [_getBranches description]
     * 
     * @param  Request $request [description]
     * 
     * @return [type]           [description]
     */
    private function _getBranches(Request $request)
    {
       
        if (request('manager') == 'all') {
            return $this->branch->get()->pluck('id')->toArray();
        } else {
            $manager = $this->person->findOrFail(request('manager'));
            return  $manager->branchesManaged()->pluck('id')->toArray();
        }
    }
    /**
     * Method to remove commas from fields that cause problem with maps
     * 
     * @param array $data [description]
     * 
     * @return array       [description]
     */
    private function _cleanseData($data)
    {
        $fields = ['companyname','businessname'];
        foreach ($fields as $field) {
            $data[$field] = strtr($data[$field], ['.' => '', ',' => '']);
        }
        return $data;
    }
    /**
     * [selectCompaniesToAdd description]
     * 
     * @param LeadSource $leadsource [description]
     * 
     * @return [type]                 [description]
     */
    public function selectCompaniesToAdd(LeadSource $leadsource)
    {
        
        $companies = $this->company
            ->has('locations')
            ->withCount('locations')
            ->orderBy('companyname')
            ->get();
        return response()->view('leadsource.addcompany', compact('companies', 'leadsource'));
    }

    /**
     * [addCompanyLocationsToLeadSource description]
     * 
     * @param Request    $request    [description]
     * @param LeadSource $leadsource [description]
     *
     * @return Redirect [<description>]
     */
    public function addCompanyLocationsToLeadSource(Request $request,LeadSource $leadsource)
    {
        $company = $this->company
            ->withCount('locations')            
            ->find(request('company_id'));
        
        $affected = $this->address->where('company_id', $company->id)
            ->update(['lead_source_id'=>$leadsource->id]);
        
        return redirect()->route('leadsource.show', $leadsource->id)
            ->withMessage($affected.' ' .$company->companyname . ' locations added to lead source');
    }
    /**
     * [assignLeads description]
     * 
     * @param  [type] $leadsource [description]
     * 
     * @return [type]             [description]
     */
    public function assignLeads(LeadSource $leadsource)
    {
        
        $leads = $this->lead->where('lead_source_id', '=', $leadsource->id)
            ->with('leadsource')
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->has('salesteam', '<', 1)
            ->get();
                $data['reps'] = $this->_findClosestRep($leads);
                $data['branches'] = $this->_findClosestBranches($leads);
        return response()->view('leadsource.leadsassign', compact('leads', 'data'));
    }
    /**
     * [_getAddress description]
     * 
     * @param  [type] $request [description]
     * 
     * @return [type]          [description]
     */
    private function _getAddress($request)
    {
        // if its a one line address return that

        if (! request()->has('city')) {
            return $address = request('address');
        }
        // else build the full address
        return $address = request('address') . " " . request('city') . " " . request('state') . " " . request('zip');
    }

    /**
     * [_findClosestRep description]
     * 
     * @param [type] $leads [description]
     * 
     * @return [type]        [description]
     */
    private function _findClosestRep($leads)
    {
        $leadinfo = [];
        foreach ($leads as $lead) {
            $leadinfo[$lead->id] = $this->person->nearby($lead, 1000)
            ->whereHas(
                'userdetails.roles', function ($q) {
                    $q->whereIn('name', 'Sales');
                }
            )
            ->limit(1)
            ->get();
        }
        return $leadinfo;
    }
    /**
     * [_findClosestBranches description]
     * 
     * @param [type] $leads [description]
     * 
     * @return [type]        [description]
     */
    private function _findClosestBranches($leads)
    {
        $leadinfo = null;
        foreach ($leads as $lead) {
            $leadinfo[$lead->id] = $this->branch->whereHas(
                'servicelines', function ($q) use ($userservicelines) {
                    $q->whereIn('servicelines.id', $userservicelines);
                }
            )
            ->nearby($lead, 1000)
            ->limit(1)
            ->get();
        }
        return $leadinfo;
    }
    

    /**
     * [export description]
     * 
     * @param Request $request [description]
     * @param [type]  $id      [description]
     * 
     * @return [type]           [description]
     */
    public function export(Request $request, LeadSource $leadsource)
    {
       
        $statuses = $this->lead->statuses;
        $leadsource = $this->leadsource
            ->with(
                ['leads'=>function ($q) {
                         $q->has('assignedToBranch')
                             ->orHas('assignedToPerson');
                }
                ]
            )
            ->findOrFail($leadsource->id);

        return Excel::download(new LeadSourceExport($leadsource, $statuses), 'Prospects'.time().'.csv');
        
    }
    /**
     * [leadSourceBranchResults description]
     * 
     * @param LeadSource $leadsource [description]
     * 
     * @return [type]                 [description]
     */
    public function leadSourceBranchResults(LeadSource $leadsource)
    {

        $this->period['from'] = Carbon::now()->subMonths(2);
        $this->period['to'] = Carbon::now();
        // find all branches that have addresses
            $branches = $this->branch
                ->whereHas(
                    'addresses', function ($q) use ($leadsource) {
                        $q->where('lead_source_id', $leadsource->id);
                    }
                )
                ->with(
                    ['addresses'=>function ($q) use ($leadsource) {
                        $q->where('lead_source_id', $leadsource->id);
                    }]
                )
            
            ->with('addresses.opportunities')
            
            ->get();
            $data = $this->branch->branchData($branches);
            return response()->view('leadsource.results', compact('data', 'leadsource'));
        // find all activities on leads assigned back to branch
        // find all opportunities on addresses grouped by branch, status
    }
}
