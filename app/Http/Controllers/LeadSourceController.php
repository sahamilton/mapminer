<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeadSource;
use App\Lead;
use App\Address;
use Excel;
use App\Person;
use App\SearchFilter;
use App\LeadStatus;
use App\Branch;
use App\Http\Requests\LeadSourceFormRequest;
use App\Http\Requests\LeadSourceAddLeadsFormRequest;
use Carbon\Carbon;

class LeadSourceController extends Controller
{
    public $leadsource;
    public $leadstatus;
    public $person;
    public $vertical;
    public $lead;
    public $branch;
    public $address;
    public function __construct(
        LeadSource $leadsource,
        LeadStatus $status,
        SearchFilter $vertical,
        Lead $lead,
        Person $person,
        Branch $branch,
        Address $address
    ) {
        $this->leadsource = $leadsource;
        $this->leadstatus = $status;
        $this->person = $person;
        $this->vertical=$vertical;
        $this->lead = $lead;
            $this->branch = $branch;
        $this->address = $address;
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
         return response()->view('leadsource.create', compact('verticals'));
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
        $leadsource->update([
            'datefrom'=>Carbon::createFromFormat('m/d/Y', request('datefrom')),
            'dateto'=>Carbon::createFromFormat('m/d/Y', request('dateto')),
            ]);
        $leadsource->verticals()->sync(request('vertical'));

        return redirect()->route('leadsource.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($leadsource)
    {


        $leadsource = $leadsource->whereId($leadsource->id)
        ->withCount(
            ['addresses',
            'addresses as assigned'=>function ($query) {
                $query->has('assignedToBranch');
            },
            'addresses as unassigned' => function ($query) {
                $query->whereDoesntHave('assignedToBranch');
            },
            'addresses as closed' => function ($query) {
                    $query->has('closed');
            }]
        )->first();
       
        $teamStats=[];
        $team = $leadsource->salesteam($leadsource->id);
        foreach ($team as $person) {
            $teamStats[$person->id][$person->status_id]= $person->count;
            $teamStats[$person->id]['name'] = $person->name;
        }
     
        $branches = $this->branch->whereHas('leads', function ($q) use ($leadsource) {
            $q->where('lead_source_id', '=', $leadsource->id);
        })->withCount(["leads",
        'leads as assigned'=>function ($query) use ($leadsource) {
                       $query->where('lead_source_id', $leadsource->id)->has('assignedToBranch');
        },
        'leads as claimed' => function ($query) use ($leadsource) {
                           $query->where('lead_source_id', $leadsource->id)->has('claimedByBranch');
        },
                   
        'leads as closed' => function ($query) use ($leadsource) {
                           $query->where('lead_source_id', $leadsource->id)->has('closed');
        }])->get();
   
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

    private function getOwnedBy($leads)
    {
    }

    private function getSalesTeam($id)
    {

        $leads = $this->lead->where('lead_source_id', '=', $id)
        ->with('salesteam')
        ->get();
        $statuses = [1,2,3];
        $teamStats = [];
        foreach ($leads as $lead) {
            foreach ($lead->salesteam as $member) {
                if (! array_key_exists($member->id, $teamStats)) {
                    foreach ($statuses as $status) {
                        $teamStats[$member->id][$status]=0;
                    }
                }
                $teamStats[$member->id][$member->pivot->status_id]++;
            }
        }
        return $teamStats;
    }

    private function reformatRepsData($data)
    {
        $newdata = [];
        $statuses = $this->lead->statuses;

        foreach ($data as $rep) {
            $newdata[$rep->id]['name'] = $rep->firstname . ' '. $rep->lastname;
            $newdata[$rep->id]['id'] = $rep->id;
            $newdata[$rep->id][$statuses[$rep->status]]['count'] = $rep->leadcount;
            $newdata[$rep->id][$statuses[$rep->status]]['rating'] = $rep->rating;
        }

        return $newdata;
    }
    public function branches($id)
    {
           $leadsource = $this->leadsource->findOrFail($id);
           $branches = Branch::whereHas('leads', function ($q) use ($id) {
                $q->where('lead_source_id', '=', $id);
           })
           ->withCount('leads')
                ->with('leads.ownedBy')
                ->with('manager')
                ->orderBy('id')
                ->get();
           
            return response()->view('leads.branches', compact('branches', 'leadsource'));
    }
    
    public function unassigned($leadsource)
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
    /*
    
    */
    public function unassignedstate(LeadSource $leadsource, $state)
    {
        
        $leadsource = $this->leadsource
        ->with(['addresses' => function ($query) use($state){
                $query->whereDoesntHave('assignedToBranch')->whereDoesntHave('assignedToPerson')
                ->where('state','=',trim($state));
            }],'addresses.state')
        ->findOrFail($leadsource->id);

        return response()->view('leadsource.stateunassigned', compact('leadsource', 'state'));
    }
    /*
    


    */
    private function getLeads($id)
    {

        return $this->lead->where('lead_source_id', '=', $id)
        /*->wherehas('leadsource',function($q){
            $q->where('datefrom','<=',date('Y-m-d'))
                ->where('dateto','>=',date('Y-m-d'));
            })
*/
        ->with('salesteam', 'salesteam.industryfocus')
        ->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($leadsource)
    {
        $leadsource->load('leads', 'verticals');

        $verticals = $this->vertical->industrysegments();
        return response()->view('leadsource.edit', compact('leadsource', 'verticals'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LeadSourceFormRequest $request, $leadsource)
    {
       
       
        $leadsource->update(request()->except('_method', '_token', 'datefrom', 'dateto'));
        $leadsource->update([
            'datefrom'=>Carbon::createFromFormat('m/d/Y', request('datefrom')),
            'dateto'=>Carbon::createFromFormat('m/d/Y', request('dateto'))]);
        $leadsource->verticals()->sync(request('vertical'));
        return redirect()->route('leadsource.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($leadsource)
    {
        $leadsource->delete();
        return redirect()->route('leadsource.index');
    }

    public function flushLeads($leadsource)
    {
        $leadsource->leads()->delete();
        $this->address->where('lead_source_id', '=', $leadsource->id)->delete();
        return redirect()->route('leadsource.index')->withWarning('all addresses removed from lead source');
    }

    public function addLeads($id)
    {
        $leadsource = $this->leadsource->findOrFail($id);
        return response()->view('leadsource.addleads', compact('leadsource'));
    }
    public function importLeads(LeadSourceAddLeadsFormRequest $request, $id)
    {
        $leadsource = $this->leadsource->findOrFail($id);
        if ($request->hasFile('file')) {
            return $this->leadImport($request, $id);
        } else {
            request()->merge(['lead_source_id'=>$id]);
            $data = $this->cleanseData(request()->all());
            $lead = $this->lead->create($data);
            $geoCode = app('geocoder')->geocode($this->getAddress($request))->get();
            $data = $this->lead->getGeoCode($geoCode);

            $lead->update($data);
            return redirect()->route('leadsource.index');
        }
    }
    // Method to remove commas from fields that cause problem with maps
    private function cleanseData($data)
    {
        $fields = ['companyname','businessname'];
        foreach ($fields as $field) {
            $data[$field] = strtr($data[$field], ['.' => '', ',' => '']);
        }
        return $data;
    }




    public function assignLeads($leadsource)
    {

        $leads = $this->lead->where('lead_source_id', '=', $leadsource->id)
                ->with('leadsource')
                ->whereNotNull('lat')
                ->whereNotNull('lng')
                ->has('salesteam', '<', 1)
                ->get();
                $data['reps'] = $this->findClosestRep($leads);
                $data['branches'] = $this->findClosestBranches($leads);
        return response()->view('leadsource.leadsassign', compact('leads', 'data'));
    }

    private function getAddress($request)
    {
        // if its a one line address return that

        if (! request()->has('city')) {
            return $address = request('address') ;
        }
        // else build the full address
        return $address = request('address') . " " . request('city') . " " . request('state') . " " . request('zip');
    }


    private function findClosestRep($leads)
    {
        $leadinfo = [];
        foreach ($leads as $lead) {
            $leadinfo[$lead->id] = $this->person->nearby($lead, 1000)
            ->whereHas('userdetails.roles', function ($q) {
                $q->whereIn('name', 'Sales');
            })
            ->limit(1)
            ->get();
        }
        return $leadinfo;
    }

    private function findClosestBranches($leads)
    {
        $leadinfo = null;
        foreach ($leads as $lead) {
            $leadinfo[$lead->id] = $this->branch->whereHas('servicelines', function ($q) use ($userservicelines) {
                $q->whereIn('servicelines.id', $userservicelines);
            })
            ->nearby($lead, 1000)
            ->limit(1)
            ->get();
        }
        return $leadinfo;
    }

    private function salesteam($leads)
    {
        $salesreps = [];

        foreach ($leads as $lead) {
            if (count($lead->salesteam)>0) {
                foreach ($lead->salesteam as $rep) {
                    $salesrep = $lead->salesteam->where('id', $rep->id)->first();


                    if (! array_key_exists($rep->id, $salesreps)) {
                        $salesreps[$rep->id]['details'] = $salesrep;
                        $salesreps[$rep->id]['count'] = 0;
                        $salesreps[$rep->id]['status'][1] = 0;
                        $salesreps[$rep->id]['status'][2] = 0;
                        $salesreps[$rep->id]['status'][3] = 0;
                       /* $salesreps[$rep->id]['status'][4] = 0;
                        $salesreps[$rep->id]['status'][5] = 0;
                        $salesreps[$rep->id]['status'][6] = 0;*/
                    }
                    $salesreps[$rep->id]['count'] = $salesreps[$rep->id]['count'] ++;
                    $salesreps[$rep->id]['status'][$salesrep->pivot->status_id] ++;
                }
            }
        }

        return $salesreps;
    }

    /**


    **/
    public function export(Request $request, $id)
    {

        if (request()->has('type')) {
            $type = request('type');
        } else {
            $type = 'xlsx';
        }

        Excel::download('Prospects'.time(), function ($excel) use ($id) {
            $excel->sheet('Prospects', function ($sheet) use ($id) {
                $statuses = $this->lead->statuses;
                $leadsource = $this->leadsource
                ->with('leads', 'leads.relatedNotes')
                ->has('leads.ownedBy')
                ->with('leads.ownedBy')
                ->findOrFail($id);

                $sheet->loadView('leadsource.export', compact('leadsource', 'statuses'));
            });
        })->download('csv');
    }

    
}
