<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Company;
use App\Http\Requests\ServiceLineFormRequest;
use App\Serviceline;
use Illuminate\Http\Request;

class ServicelinesController extends BaseController
{
    public $serviceline;
    public function __construct(Serviceline $serviceline)
    {
        $this->serviceline = $serviceline;
        parent::__construct($serviceline);
    }
    
    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
        $servicelines = Serviceline::
            with('companyCount', 'userCount')
            ->get();
        
        
        return response()->view('servicelines.index', compact('servicelines'));
    }

    /**
     * Show the form for creating a new serviceline.
     *
     * @return Response
     */
    public function create()
    {
        return response()->view('servicelines.create');
    }

    /**
     * Store a newly created serviceline in storage.
     *
     * @return Response
     */
    public function store(ServiceLineFormRequest $request)
    {
        $this->serviceline->create(request()->all());

        return \redirect()->route('serviceline.index');
    }

    /**
     * [show description]
     * 
     * @param [type] $id   [description]
     * @param [type] $type [description]
     * 
     * @return [type]       [description]
     */
    public function show($id, $type = null)
    {
       
        // Can the user see this service line?
                
        if (! in_array($id, $this->userServiceLines)) {
            return redirect()->route('serviceline.index');
        }
       
  
        $serviceline = $this->serviceline->findOrFail($id);
      
        if (! $type) {
            $branches = Branch::with('region', 'manager')
                ->whereHas(
                    'servicelines', function ($q) use ($id) {
                        $q->where('serviceline_id', '=', $id);
                    }
                )
                ->get();
            
            return response()->view('servicelines.show', compact('serviceline', 'branches'));
        } else {
            $companies = Company::with('managedBy', 'managedBy.userdetails', 'industryVertical', 'serviceline', 'countlocations')
                ->whereHas(
                    'serviceline', function ($q) use ($id) {
                        $q->where('serviceline_id', '=', $id)
                            ->whereIn('serviceline_id', $this->userServiceLines);
                    }
                )
            ->get();
            $locationFilter = 'both';
            
            $filtered=null;
            $title = 'All ' .$serviceline->ServiceLine .' Accounts';
        
            return response()->view('companies.index', compact('companies', 'title', 'filtered', 'locationFilter'));
        }
    }
}
