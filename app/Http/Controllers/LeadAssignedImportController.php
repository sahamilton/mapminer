<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadImportFormRequest;
use App\Lead;
use App\LeadImport;
use App\LeadSource;
use Illuminate\Http\Request;

class LeadAssignedImportController extends ImportController
{
    public $lead;
    public $leadsources;
    public $import;

    /**
     * [__construct description].
     *
     * @param Lead       $lead       [description]
     * @param LeadSource $leadsource [description]
     *
     * @param LeadImport $import     [description]
     */
    public function __construct(Lead $lead, LeadSource $leadsource, LeadImport $import)
    {
        $this->lead = $lead;
        $this->import = $import;
        $this->leadsources = $leadsource;
    }

    /**
     * [getFile description].
     *
     * @param Request $request [description]
     * @param [type]  $id      [description]
     * @param [type]  $type    [description]
     *
     * @return [type]           [description]
     */
    public function getFile(Request $request, $id = null, $type = null)
    {
        $sources = $this->leadsources->all()->pluck('source', 'id');
        if ($sources->count() == 0) {
            return redirect()->route('leadsource.index')->with('error', 'You must create a lead source first');
        }
        if ($id) {
            $leadsource = $this->leadsources->find($id);
        }
        $requiredFields = $this->lead->requiredfields;
        $type = 'assignedleads';

        return response()->view('leads.import', compact('sources', 'leadsource', 'requiredFields', 'type'));
    }

    /**
     * [import description].
     *
     * @param LeadImportFormRequest $request [description]
     *
     * @return [type]                         [description]
     */
    public function import(LeadImportFormRequest $request)
    {
        $data = $this->uploadfile(request()->file('upload'));
        $title = 'Map the leads import file fields';

        $data['table'] = 'leadimport';
        $data['type'] = request('type');
        $data['additionaldata'] = request('additionaldata');

        $data['route'] = 'leads.mapfields';
        $fields = $this->getFileFields($data);
        $columns = $this->lead->getTableColumns($data['table']);
        $requiredFields = $this->import->requiredFields;
        $skip = ['id', 'created_at', 'updated_at', 'lead_source_id', 'pr_status'];

        return response()->view('imports.mapfields', compact('columns', 'fields', 'data', 'company_id', 'skip', 'title', 'requiredFields'));
    }

    /**
     * [mapfields description].
     *
     * @param Request $request [description]
     *
     * @return [type]           [description]
     */
    public function mapfields(Request $request)
    {
        $data = $this->getData($request);
        $this->validateInput($request);
        $this->import->setFields($data);
        if ($this->import->import()) {
            $this->_postimport();

            return redirect()->route('leadsource.index')->with('success', 'Leads imported');
        }
    }
}
