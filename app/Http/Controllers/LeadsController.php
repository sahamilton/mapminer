<?php

namespace App\Http\Controllers;

use App\Address;
use App\Branch;
use App\Http\Requests\BatchLeadImportFormRequest;
use App\Http\Requests\LeadAddressFormRequest;
use App\Http\Requests\LeadFormRequest;
use App\Http\Requests\LeadInputAddressFormRequest;
use App\Lead;
use App\LeadSource;
use App\LeadStatus;
use App\Mail\NotifyWebLeadsAssignment;
use App\Mail\NotifyWebLeadsBranchAssignment;
use App\MapFields;
use App\Note;
use App\Person;
use App\SearchFilter;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Mail;

class LeadsController extends BaseController
{
    public $person;
    public $address;
    public $lead;
    public $leadsource;
    public $vertical;
    public $leadstatus;
    public $assignTo;
    public $salesroles = [5, 6, 7, 8];

    public function __construct(
        Person $person,
        Lead $lead,
        LeadSource $leadsource,
        SearchFilter $vertical,
        LeadStatus $status,
        Address $address
    ) {
        $this->person = $person;
        $this->address = $address;
        $this->vertical = $vertical;
        $this->lead = $lead;
        $this->leadsource = $leadsource;
        $this->leadstatus = $status;
        $this->assignTo = config('leads.lead_distribution_roles');
        parent::__construct($this->lead);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($vertical = null)
    {
        $reps = $this->person->has('leads')
            ->withCount(['leads', 'openleads', 'closedleads'])
            ->with('reportsTo', 'reportsTo.userdetails.roles', 'closedleads', 'leads.leadsource')
            ->get();

        $rankings = $this->lead->rankLead($reps);

        return response()->view('templeads.index', compact('reps', 'rankings'));
    }

    /**
     * [salesLeadsDetail description].
     *
     * @param [type] $id [description]
     *
     * @return [type]     [description]
     */
    public function salesLeadsDetail($id)
    {
        $lead = $this->lead
            ->with('leadsource')
            ->findOrFail($id);

        $leadsourcetype = $lead->leadsource->type.'leads';
        $people = null;

        $lead = $this->lead

            ->with('salesteam', 'contacts', 'relatedNotes')
            ->ExtraFields($leadsourcetype)
            ->findOrFail($id);

        if ($lead->doesntHave('salesteam')) {
            $people = $this->person
                ->whereHas(
                    'userdetails.roles', function ($q) {
                        $q->whereIn('roles.id', [9, 5]);
                    }
                )
            ->nearby($lead, '1000')
            ->limit(5)
            ->get();
        }

        $extrafields = array_diff(array_keys($lead->getAttributes()), $this->lead->fillable);

        $dropFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
        foreach ($dropFields as $field) {
            if (($key = array_search($field, $extrafields)) !== false) {
                unset($extrafields[$key]);
            }
        }

        $leadStatuses = LeadStatus::pluck('status', 'id')->toArray();

        $branches = Branch::with('manager', 'businessmanager', 'marketmanager')->nearby($lead, 100, 5)->get();

        $rankingstatuses = $this->lead->getStatusOptions;

        return response()->view('templeads.detail', compact('lead', 'branches', 'leadStatuses', 'rankingstatuses', 'extrafields', 'people'));
    }

    /**
     * [branches description].
     *
     * @param [type] $id [description]
     *
     * @return [type]     [description]
     */
    public function branches($id = null)
    {
        if ($id) {
            $id = [$id];
        }
        $branches = $this->_getBranchData($id);
        if (! $id) {
            return response()->view('templeads.branchsummary', compact('branches'));
        }
        $leadStatuses = LeadStatus::pluck('status', 'id')->toArray();

        return response()->view('templeads.branchleads', compact('branches', 'leadStatuses'));
    }

    /**
     * [_getBranchData description].
     *
     * @param [type] $id [description]
     *
     * @return [type]     [description]
     */
    private function _getBranchData($id = null)
    {
        if ($id) {
            if (! is_array($id)) {
                $id = [$id];
            }

            return $this->lead->whereIn('branch_id', $id)
                ->with('branches', 'branches.manager')
                ->orderBy('branch_id')
                ->get();
        } else {
            return Branch::has('leads')
                ->withCount('leads')
                ->with('manager', 'manager.reportsTo')->get();
        }
    }

    /**
     * [show description].
     *
     * @param [type] $lead [description]
     *
     * @return [type]       [description]
     */
    public function show($lead)
    {
        $table = $this->leadsource->findOrFail($lead->lead_source_id);

        $id = $lead->id;
        $table = $table->type.'leads';

        $lead = $this->lead
            ->with('contacts')
            ->ExtraFields($table)
            ->find($id);
        $extrafields = $this->_getExtraFields($table);

        $branches = $this->_findNearByBranches($lead);

        $people = $this->findNearbySales($branches, $lead);
        $salesrepmarkers = $this->person->jsonify($people);
        $branchmarkers = $branches->toJson();

        return response()->view('leads.show', compact('lead', 'branchmarkers', 'salesrepmarkers', 'people', 'branches', 'extrafields'));
    }

    /**
     * [create description].
     *
     * @return [type] [description]
     */
    public function create()
    {
        $uid = auth()->user()->id;
        $lead_source_id = 3;
        // note hard coded ;  Need to change
        return response()->view('leads.create', compact('uid', 'lead_source_id'));
    }

    /**
     * [store description].
     *
     * @param LeadFormRequest $request [description]
     *
     * @return [type]                   [description]
     */
    public function store(LeadFormRequest $request)
    {
        $input = request()->all();

        $leadsource = $this->leadsource->findOrFail($input['lead_source_id']);
        $table = 'addresses';

        $data = $this->_extractLeadTableData($input, $table);

        $lead = $this->lead->fill($data['lead']);

        $lead->save();

        $lead->contacts()->create($data['contact']);
        if ($table == 'webleads') {
            $lead->webLead()->create($data['extra']);
        } else {
            $lead->tempLead()->create($data['extra']);
        }

        return redirect()->route('leads.show', $lead->id);
    }

    /**
     * [edit description].
     *
     * @param Lead $lead [description]
     *
     * @return [type]       [description]
     */
    public function edit(Lead $lead)
    {
        $lead->load('leadsource');
        $table = $lead->leadsource->type.'leads';
        $lead = $this->lead
            ->with('contacts', 'leadsource')
            ->extraFields($table)
            ->find($lead->id);

        $sources = $this->leadsource->pluck('source', 'id');
        $extrafields = $this->_getExtraFields($table);

        return response()->view('leads.edit', compact('lead', 'sources', 'extrafields'));
    }

    /**
     * [update description].
     *
     * @param LeadFormRequest $request [description]
     * @param [type]          $lead    [description]
     *
     * @return [type]                   [description]
     */
    public function update(LeadFormRequest $request, $lead)
    {
        $lead->load('leadsource');
        $table = $lead->leadsource->type.'leads';

        $data = $this->_extractLeadTableData(request()->all(), $table);
        $lead->update($data['lead']);
        $lead->contacts()->update($data['contact']);
        if ($table == 'webleads') {
            $lead->webLead()->update($data['extra']);
        } else {
            $lead->tempLead()->update($data['extra']);
        }

        return redirect()->route('leads.show', $lead->id);
    }

    /**
     * [_extractLeadTableData description].
     *
     * @param [type] $input [description]
     * @param [type] $table [description]
     *
     * @return [type]        [description]
     */
    private function _extractLeadTableData($input, $table)
    {
        $lead_source_id = $input['lead_source_id'];
        $input = $this->_geoCodeAddress($input);

        $input = $this->_renameFields($input);
        foreach ($input[0] as $key => $value) {
            $data['lead'][$value] = $input[1][$key];
        }
        $data['lead']['lead_source_id'] = $lead_source_id;

        $data['contact'] = $this->_getContactDetails($data['lead'], 'contacts');
        $data['extra'] = $this->_getExtraFieldData($data['lead'], 'leads');

        return $data;
    }

    /**
     * [destroy description].
     *
     * @param Lead $lead [description]
     *
     * @return [type]       [description]
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('leads.index');
    }

    /**
     * [leadrank description].
     *
     * @param Request $request [description]
     *
     * @return [type]           [description]
     */
    public function leadrank(Request $request)
    {
        $person = $this->person->whereHas(
            'userdetails', function ($q) use ($request) {
                $q->where('api_token', '=', request('api_token'));
            }
        )->firstOrFail();

        if ($person->salesleads()->sync([request('id') => ['rating' => request('value')]], false)) {
            return 'success';
        }

        return 'error';
    }

    /**
     * [searchAddress description].
     *
     * @return [type] [description]
     */
    public function searchAddress()
    {
        $leadsources = $this->leadsource->pluck('source', 'id');

        return response()->view('leads.search', compact('leadsources'));
    }

    /**
     * [search description].
     *
     * @param  LeadInputAddressFormRequest $request [description]
     *
     * @return [type]                               [description]
     */
    public function search(LeadInputAddressFormRequest $request)
    {
        $geoCode = app('geocoder')->geocode(request('address'))->get();

        if (count($geoCode) > 0) {
            // create the lead object
            $lead = $this->address->getGeoCode($geoCode);

            $lead = new Address($lead);
            $lead->lead_source_id = 2;

            $extrafields = $this->_getExtraFields('webleads');

            $sources = $this->leadsource->pluck('source', 'id');
            // find nearby branches
            $branches = $this->_findNearByBranches($lead, 500);

            // we should also add serviceline filter?
            $people = $this->_findNearByPeople($lead, 500);

            $salesrepmarkers = $this->person->jsonify($people);

            $branchmarkers = $branches->toJson();
            $address = request('address');
            $type = 'new';

            return response()->view('leads.showsearch', compact('lead', 'branches', 'people', 'salesrepmarkers', 'branchmarkers', 'extrafields', 'sources', 'address', 'type'));
        } else {
            return back()->withError('Unable to geo code '.request('address'));
        }
    }

    /**
     * [getPersonsLeads description].
     *
     * @param [type] $id [description]
     *
     * @return [type]     [description]
     */
    public function getPersonsLeads($id)
    {
        $statuses = $this->leadstatus->pluck('status', 'id')->toArray();
        $leads = $this->person->with('salesleads', 'salesleads.vertical', 'salesleads.leadsource')
            ->whereHas(
                'salesleads.leadsource', function ($q) {
                    $q->where('datefrom', '<=', date('Y-m-d'))
                        ->where('dateto', '>=', date('Y-m-d'));
                }
            )
        ->findOrFail($id);

        return response()->view('leads.person', compact('leads', 'statuses'));
    }

    /**
     * [getPersonSourceLeads description].
     *
     * @param [type] $pid [description]
     * @param [type] $sid [description]
     *
     * @return [type]      [description]
     */
    public function getPersonSourceLeads($pid, $sid)
    {
        $statuses = $this->leadstatus->pluck('status', 'id')->toArray();
        $leads = $this->person->with('salesleads', 'salesleads.leadsource', 'salesleads.vertical')
            ->whereHas(
                'salesleads.leadsource', function ($q) use ($sid) {
                    $q->whereId($sid)
                        ->where('datefrom', '<=', date('Y-m-d'))
                        ->where('dateto', '>=', date('Y-m-d'));
                }
            )
        ->findOrFail($pid);
        $source = $this->leadsource->findOrFail($sid);

        return response()->view('leads.person', compact('leads', 'statuses', 'source'));
    }

    // this really belongs in the sales org controller

    /**
     * [_findNearByBranches description].
     *
     * @param [type] $data [description]
     *
     * @return [type]       [description]
     */
    private function _findNearByBranches($data)
    {
        if (is_array($data)) {
            $location = new \stdClass;
            $location->lat = $data['lat'];
            $location->lng = $data['lng'];
        } else {
            $location = $data;
            $data['distance'] = 100;
            $data['number'] = 5;
        }

        return \App\Branch::whereHas(
            'servicelines', function ($q) {
                $q->whereIn('servicelines.id', $this->userServiceLines);
            }
        )
        ->with('salesTeam')->nearby($location, $data['distance'], $data['number'])

        ->get();
    }

    /**
     * [_findNearByPeople description].
     *
     * @param [type] $data [description]
     *
     * @return [type]       [description]
     */
    private function _findNearByPeople($data)
    {
        if (is_array($data)) {
            $location = new \stdClass;
            $location->lat = $data['lat'];
            $location->lng = $data['lng'];
        } else {
            $location = $data;
            $data['distance'] = 100;
            $data['number'] = 5;
        }

        return Person::with('userdetails', 'reportsTo', 'userdetails.roles', 'industryfocus')
            ->nearby($location, $data['distance'], $data['number'])

            ->get();
    }

    /**
     * [exportLeads description].
     *
     * @param Request $request [description]
     *
     * @return [type]           [description]
     */
    public function exportLeads(Request $request)
    {
        if (request()->has('type')) {
            $type = request('type');
        } else {
            $type = 'csv';
        }

        Excel::download(
            'Prospects'.time(), function ($excel) {
                $excel->sheet(
                    'Prospects', function ($sheet) {
                        $leads = $this->person->where('user_id', '=', auth()->user()->id)
                            ->with('ownedLeads', 'ownedLeads.relatedNotes', 'ownedLeads.contacts')
                            ->firstOrFail();
                        $statuses = LeadStatus::pluck('status', 'id')->toArray();
                        $sheet->loadView('salesleads.export', compact('leads', 'statuses'));
                    }
                );
            }
        )->download($type);
    }

    /**
     * [salesLeads description].
     *
     * @param [type] $pid [description]
     *
     * @return [type]      [description]
     */
    public function salesLeads($pid)
    {
        $person = $this->_getSalesRep($pid);

        if ($person->userdetails->can('accept_leads')) {
            return $this->_showSalesLeads($person);
        } elseif ($person->userdetails->hasRole('admin') or $person->userdetails->hasRole('sales_operations')) {
            return redirect()->route('leadsource.index');
        } else {
            return $this->_showSalesTeamLeads($person);
        }
    }

    /**
     * [_showSalesLeads description].
     *
     * @param [type] $person [description]
     *
     * @return [type]         [description]
     */
    private function _showSalesLeads($person)
    {
        $openleads = $this->_getLeadsByType('openleads', $person);

        $openleads = $openleads->limit('200')
            ->with('leadsource')
            ->get();

        $openleads = $this->lead->distanceFromMe($openleads);

        $closedleads = $this->_getLeadsByType('closedleads', $person);
        $closedleads = $closedleads->with('relatedNotes', 'leadsource')
            ->limit('200')
            ->get();

        return response()->view('templeads.show', compact('openleads', 'closedleads', 'person'));
    }

    /**
     * [_showSalesTeamLeads description].
     *
     * @param [type] $person [description]
     *
     * @return [type]         [description]
     */
    private function _showSalesTeamLeads($person)
    {
        $reports = $person->descendantsAndSelf()->pluck('id')->toArray();
        $reps = $this->person->whereHas('leads')
            ->withCount(['leads', 'openleads', 'closedleads'])
            ->with('reportsTo', 'reportsTo.userdetails.roles', 'closedleads')
            ->whereIn('id', $reports)
            ->get();
        $rankings = $this->lead->rankLead($reps);

        return response()->view('templeads.team', compact('reps', 'person', 'rankings'));
    }

    /**
     * [salesLeadsMap description].
     *
     * @param [type] $pid [description]
     *
     * @return [type]      [description]
     */
    public function salesLeadsMap($pid)
    {
        $person = $this->_getSalesRep($pid);
        $data['title'] = $person->postName();
        $data['datalocation'] = '/api/newleads/'.$person->id.'/map';
        $data['lat'] = $person->lat;
        $data['lng'] = $person->lng;
        $data['listviewref'] = route('salesrep.newleads', $pid);
        $data['zoomLevel'] = 10;
        $data['type'] = 'leads';
        $leads = $this->lead->whereHas(
            'openleads', function ($q) use ($pid) {
                $q->where('person_id', '=', $pid);
            }
        )
        ->limit('200')

        ->get();

        $data['count'] = count($leads);

        return response()->view('templeads.showmap', compact('data'));
    }

    /**
     * [getMapData description].
     *
     * @param [type] $pid [description]
     *
     * @return [type]      [description]
     */
    public function getMapData($pid)
    {
        $person = $this->_getSalesRep($pid);

        $leads = $this->lead->whereHas(
            'openleads', function ($q) use ($person) {
                $q->where('person_id', '=', $person->id);
            }
        )
        ->with('leadsource')
        ->limit('200')
        ->get();

        return response()->view('templeads.xml', compact('leads'));
    }

    /**
     * [branchLeadsMap description].
     *
     * @param [type] $bid [description]
     *
     * @return [type]      [description]
     */
    public function branchLeadsMap($bid)
    {
        $branch = Branch::findOrFail($bid);
        $data['title'] = $branch->branchname.' Branch';
        $data['datalocation'] = route('newleads.branch.mapdata', $branch->id);
        $data['lat'] = $branch->lat;
        $data['lng'] = $branch->lng;
        $data['listviewref'] = route('templeads.branchid', $branch->id);
        $data['zoomLevel'] = 10;
        $data['type'] = 'leads';
        $leads = $this->lead->whereHas(
            'branches', function ($q) use ($bid) {
                $q->where('id', '=', $bid);
            }
        )
        ->limit('200')

        ->get();

        $data['count'] = count($leads);

        return response()->view('templeads.branchmap', compact('data'));
    }

    /**
     * [branchLeads description].
     *
     * @param [type] $bid [description]
     *
     * @return [type]      [description]
     */
    public function branchLeads($bid)
    {
        $branch = Branch::with('leads', 'manager', 'leads.salesteam', 'leads.ownedBy', 'leads.vertical')->findOrFail($bid);
        $sources = $this->leadsource->pluck('source', 'id');
        $statuses = $this->leadstatus->pluck('status', 'id')->toArray();

        return response()->view('leads.branchdetails', compact('branch', 'sources', 'statuses'));
    }

    /**
     * [getBranchMapData description].
     *
     * @param [type] $bid [description]
     *
     * @return [type]      [description]
     */
    public function getBranchMapData($bid)
    {
        $leads = $this->_getBranchData($bid);

        return response()->view('templeads.xml', compact('leads'));
    }

    /**
     * [_getSalesRep description].
     *
     * @param [type] $pid [description]
     *
     * @return [type]      [description]
     */
    private function _getSalesRep($pid = null)
    {
        $me = $this->person->findOrFail(auth()->user()->person->id);
        if (! $pid or $me->id == $pid) {
            return $me;
        }

        $person = $this->person->findOrFail($pid);

        if (auth()->user()->hasRole('admin') or auth()->user()->hasRole('sales_operations')) {
            return $person;
        }
        $peeps = $this->person->myTeam()->pluck('id')->toArray();

        if (in_array($pid, $peeps)) {
            return $person;
        }

        return $this->person->findOrFail(auth()->user()->person->id);
    }

    /**
     * [_getLeadsByType description].
     *
     * @param [type] $type   [description]
     * @param [type] $person [description]
     *
     * @return [type]         [description]
     */
    private function _getLeadsByType($type, $person)
    {
        return $this->lead->whereHas(
            $type, function ($q) use ($person) {
                $q->where('person_id', '=', $person->id);
            }
        )
        ->whereHas(
            'leadsource', function ($q) {
                $q->where('datefrom', '<=', date('Y-m-d'))
                    ->where('dateto', '>=', date('Y-m-d'));
            }
        )
        ->with($type);
    }

    /**
     * Close prospect.
     *
     * @param Request $request post contents
     * @param int     $id      prospect (lead) id
     *
     * @return [type]           [description]
     */
    public function close(Request $request, $id)
    {
        $lead = $this->lead->with('salesteam')->findOrFail($id);

        $lead->salesteam()
            ->updateExistingPivot(auth()->user()->person->id, ['rating'=>request('ranking'), 'status_id'=>3]);
        $this->_addClosingNote($request, $id);

        return redirect()->route('salesrep.newleads.show', $id)->with('message', 'Lead closed');
    }

    /**
     * Claim prospect.
     *
     * @param Request $request post contents
     * @param int     $id      prospect (lead) id
     *
     * @return [type]           [description]
     */
    public function claim(Request $request, $id)
    {
        $lead = $this->lead->with('salesteam')->findOrFail($id);

        $lead->salesteam()->sync([auth()->user()->person->id=>['status_id'=>2]]);

        // need to remove all other branches that have this as a lead;
        //
        //
        return redirect()->route('myleads.show', $id)->with('message', 'Lead claimed');
    }

    /**
     * [_addClosingNote description].
     *
     * @param [type] $request [description]
     * @param [type] $id      [description]
     *
     * @return null [<description>]
     */
    private function _addClosingNote($request, $id)
    {
        $note = new Note;
        $note->note = 'Lead Closed:'.request('comments');
        $note->type = 'lead';
        $note->related_id = $id;
        $note->user_id = auth()->user()->id;
        $note->save();
    }

    /**
     * [unAssignLeads description].
     *
     * @param Request $request [description]
     *
     * @return [type]           [description]
     */
    public function unAssignLeads(Request $request)
    {
        $lead = $this->lead->findOrFail(request('lead'));
        $lead->branches()->dissociate();
        $lead->salesteam()->detach(request('rep'));

        return redirect()->route('leads.show', $lead->id);
    }

    /**
     * [_getExtraFields description].
     *
     * @param [type] $type [description]
     *
     * @return [type]       [description]
     */
    private function _getExtraFields($type)
    {
        $fields = \App\MapFields::whereType($type)
            ->whereDestination('extra')
            ->whereNotNull('fieldname')
            ->pluck('fieldname')->toArray();

        return array_unique($fields);
    }

    /**
     * [_getExtraFieldData description].
     *
     * @param [type] $newdata [description]
     * @param string $type    [description]
     *
     * @return [type]          [description]
     */
    private function _getExtraFieldData($newdata, $type = 'webleads')
    {
        $extraFields = \App\MapFields::whereType($type)
            ->whereDestination('extra')
            ->whereNotNull('fieldname')
            ->pluck('fieldname')->toArray();

        $extra = [];
        foreach ($extraFields as $key => $value) {
            if ($newdata[$value]) {
                $extra[$value] = $newdata[$value];
            }
        }

        return $extra;
    }

    /**
     * [unassignedleads description].
     *
     * @return [type] [description]
     */
    public function unassignedleads()
    {
        $leads = $this->lead->doesntHave('ownedBy')->get();

        $data = [];
        foreach ($leads as $lead) {
            $people = $this->person
                ->whereHas(
                    'userdetails.roles', function ($q) {
                        $q->whereIn('roles.id', [5, 6, 7, 8]);
                    }
                )
              ->nearby($lead, '100')
              ->limit(1)
              ->first();

            if (count($people) > 0) {
                $lead->salesteam()->attach($people, ['status_id' => 2]);
                $data[$lead->id] = $people;
            }
        }

        return response()->view('leads.assignable', compact('leads', 'data'));
    }
    public function assign()
    {
        return response()->view('leads.assignleads');
    }
    /**
     * [assignLeads description].
     *
     * @param Request $request [description]
     *
     * @return [type]           [description]
     */
    public function assignLeads(Request $request)
    {
        $lead = $this->lead->with('contacts', 'leadsource')->findOrFail(request('lead_id'));
        $branch = Branch::with('manager', 'manager.userdetails')->findOrFail(request('branch'));

        $lead->branches()->associate($branch);
        $lead->save();
        if (request('salesrep') != '') {
            $rep = $this->person->findOrFail(request('salesrep'));
            $rep = $this->_checkIfTest($rep);

            $lead->salesteam()->attach(request('salesrep'), ['status_id' => 2]);
            Mail::queue(new NotifyWebLeadsAssignment($lead, $branch, $rep));
        } else {
            foreach ($branch->manager as $manager) {
                $manager = $this->_checkIfTest($manager);
                $lead->salesteam()->attach($manager->id, ['status_id' => 2]);
                Mail::queue(new NotifyWebLeadsBranchAssignment($lead, $branch, $manager));
            }
        }
        if (request('notifymgr')) {
            foreach ($branch->manager as $manager) {
                $manager = $this->_checkIfTest($manager);
                Mail::queue(new NotifyWebLeadsBranchAssignment($lead, $branch, $manager));
            }
        }

        return redirect()->route('leadsource.show', $lead->lead_source_id);
    }

    /**
     * [_checkIfTest description].
     *
     * @param [type] $rep [description]
     *
     * @return [type]      [description]
     */
    private function _checkIfTest($rep)
    {
        if (\Config::get('leads.test')) {
            $rep = $this->person->with('userdetails')->where('user_id', '=', auth()->user()->id)->first();
        }

        return $rep;
    }

    /**
     * [getAssociatedBranches description].
     *
     * @param [type] $pid [description]
     *
     * @return [type]      [description]
     */
    public function getAssociatedBranches($pid = null)
    {
        if (auth()->user()->hasRole('branch_manager')) {
            $branchmgr = $this->person
                ->where('user_id', '=', auth()->user()->id)
                ->with('manages')
                ->first();
        } elseif (auth()->user()->hasRole('admin')) {
            $branches = Branch::has('leads')
                ->withCount('leads')
                ->with('manager')
                ->get();

            return response()->view('leads.branches', compact('branches'));
        } else {
            $branchmgr = $this->person
                ->with('manages')
                ->findOrFail($pid);
        }

        $branchlist = $branchmgr->manages->pluck('id')->toArray();
        if ($branchlist) {
            $branchleads = $this->_getBranchData($branchlist);
            $leadStatuses = LeadStatus::pluck('status', 'id')->toArray();

            return response()->view('templeads.branchmgrleads', compact('branchleads', 'branchmgr', 'leadStatuses'));
        }

        return redirect()->back()->with('error', 'Sorry '.$branchmgr->postName().' is not assigned to any branch. Please contact Sales Ops');
    }

    /**
     * [_geoCodeAddress description].
     *
     * @param [type] $input [description]
     *
     * @return [type]        [description]
     */
    private function _geoCodeAddress($input)
    {
        $address = null;
        if (isset($input['address'])) {
            $address = $input['address'];
        }
        $address = $address.' '.$input['city'].' '.$input['state'];
        $geoCode = app('geocoder')->geocode($address)->get();
        $location = $this->lead->getGeoCode($geoCode);
        $input['lat'] = $location['lat'];
        $input['lng'] = $location['lng'];

        return $input;
    }

    /**
     * [_renameFields description].
     *
     * @param [type] $input [description]
     *
     * @return [type]        [description]
     */
    private function _renameFields($input)
    {
        $valid = $this->_getValidFields();

        foreach (array_keys($input) as $key => $value) {
            if (isset($valid[$value])) {
                $fields[0][$key] = $valid[$value];
            } else {
                $fields[0][$key] = $value;
            }
        }
        $fields[1] = array_values($input);

        return $fields;
    }

    /**
     * [_getValidFields description].
     *
     * @param string $type [description]
     *
     * @return [type]       [description]
     */
    private function _getValidFields($type = 'webleads')
    {
        $validFields = MapFields::whereType($type)->whereNotNull('fieldname')->get();

        return $validFields->reduce(
            function ($validFields, $validField) {
                $validFields[$validField->aliasname] = $validField->fieldname;

                return $validFields;
            }
        );
    }

    /**
     * [_getContactDetails description].
     *
     * @param [type] $newdata [description]
     * @param string $type    [description]
     *
     * @return [type]          [description]
     */
    private function _getContactDetails($newdata, $type = 'webleads')
    {
        $contactFields = MapFields::whereType('webleads')
            ->whereDestination('contact')
            ->whereNotNull('fieldname')->pluck('fieldname')->toArray();

        $contact['contact'] = null;
        foreach ($contactFields as $field) {
            if (isset($newdata[$field])) {
                $contact[$field] = $newdata[$field];
            }
        }

        return  $contact;
    }
}
