<?php

namespace App\Http\Controllers;

use App\Company;
use App\Howtofield;
use App\Http\Requests\SalesNotesFormRequest;
use App\Salesnote;
use Illuminate\Http\Request;

class SalesNotesController extends BaseController
{
    public $salesnote;
    public $howtofield;
    public $company;
    public $attachmentField;

    /**
     * [__construct description].
     *
     * @param Company   $company   [description]
     * @param Salesnote $salesnote [description]
     */
    public function __construct(
        Company $company,
        Salesnote $salesnote,
        Howtofield $howtofield
    ) {
        $this->company = $company;
        $this->salesnote = $salesnote;
        $this->howtofield = $howtofield;
        $this->attachmentField = $howtofield->where('type', '=', 'attachment')->pluck('id');
        parent::__construct($salesnote);
    }

    /**
     * [index description].
     *
     * @param [type] $companyid [description]
     *
     * @return [type]            [description]
     */
    public function index($companyid = null)
    {
        if (isset($companyid)) {
            return redirect()->route('salesnotes', $companyid);
        }
        $companies = $this->company->with('salesNotes', 'serviceline')
            ->orderBy('companyname', 'asc')->get();

        return response()->view('salesnotes.index', compact('companies'));
    }

    /**
     * [create description].
     *
     * @param Request $request [description]
     *
     * @return [type]           [description]
     */
    public function create(Request $request, Company $company)
    {
        $fields = $this->howtofield->where('active', 1)->orderBy('sequence', 'asc')->get();

        return response()->view('salesnotes.create', compact('company', 'fields'));
    }

    /**
     * [store description].
     *
     * @param SalesNotesFormRequest $request [description]
     *
     * @return [type]                         [description]
     */
    public function store(SalesNotesFormRequest $request)
    {
        if (isset($companyid)) {
            return redirect()->route('salesnotes', $companyid);
        }
        $companies = $this->company->with('salesNotes', 'serviceline')
            ->orderBy('companyname', 'asc')->get();

        return response()->view('salesnotes.index', compact('companies'));
    }

    /**
     * [show description].
     *
     * @param Company $company [description]
     *
     * @return [type]           [description]
     */
    public function show(Company $company)
    {
        $company->load('managedBy', 'salesnotes', 'managedBy.userdetails');

        $salesnote = $this->salesnote->where('company_id', $company->id)->get();

        $fields = $this->howtofield->where('active', 1)->orderBy('sequence', 'asc')->get();

        return response()->view('salesnotes.shownote', compact('salesnote', 'company', 'fields'));
    }

    /**
     * [edit description].
     *
     * @param SalesNotesFormRequest $request   [description]
     * @param [type]                $salesnote [description]
     *
     * @return [type]                           [description]
     */
    public function edit(Company $company)
    {
        $company->load('salesnotes');
        $fields = $this->howtofield->where('depth', 1)->where('active', 1)->orderBy('sequence', 'asc')->get();

        return response()->view('salesnotes.edit', compact('company', 'fields'));
    }

    /**
     * [update description].
     *
     * @param Request $request [description]
     * @param Company $company [description]
     *
     * @return [type]           [description]
     */
    public function update(Request $request, Company $company)
    {
        $data = $this->_reformatRequestData($request);
        $company->salesnotes()->sync($data);

        return redirect()->route('salesnotes.show', $company->id);
    }

    /**
     * [destroy description].
     *
     * @param [type] $id [description]
     *
     * @return [type]     [description]
     */
    public function destroy(SalesNote $salesnote)
    {
        $salesnote->delete();

        return redirect()->back()->withMessage('Sales note deleted');
    }

    /**
     * [fileDelete description].
     *
     * @param [type] $file [description]
     *
     * @return [type]       [description]
     */
    public function fileDelete($file)
    {
        $company_id = substr($file, 0, strpos($file, '_'));

        $attachments = $this->salesnote
            ->whereIn('howtofield_id', $this->attachmentField)
            ->where('company_id', $company_id)
            ->firstorFail();

        if (count($attachments) != 0) {
            $data = unserialize(urldecode($attachments->value));

            foreach ($data as $key=>$value) {
                if ($value['filename'] == $file) {
                    unset($data[$key]);
                }
            }
            $value = urlencode(serialize($data));
            $attachments->value = $value;
            $attachments->save();
            // unset file from directory;
            $path = (public_path('documents/attachments/'.$company_id.'/'));

            \File::delete($path.$file);
        }

        return redirect()->to('salesnotes/'.$company_id);
    }

    /**
     * [createSalesNotes description].
     *
     * @param SalesNotesFormRequest $request [description]
     * @param Company               $company [description]
     *
     * @return [type]                         [description]
     */
    private function _getSalesNotes(Company $company)
    {
        $company->load('salesNotes');

        $fields = Howtofield::orderBy('group', 'asc')->get();

        $salesnote = $company->salesNotes;
        $groups = Howtofield::select('group')->distinct()->get();

        $data = [];
        // Fields that need to be convereted to an array

        foreach ($fields as $field) {
            $field_id = $field->id;
            $data[$field_id]['type'] = $field->type;
            $data[$field_id]['id'] = $field->id;
            $data[$field_id]['group'] = $field->group;
            $data[$field_id]['fieldname'] = $field->fieldname;
            $data[$field_id]['values'] = $field->values;
            $data[$field_id]['value'] = null;
        }
        foreach ($salesnote as $note) {
            $field_id = $note->howtofield_id;
            if ($note->type == 'checkbox' || $note->type == 'multiple') {
                $data[$field_id]['value'] = unserialize(urldecode($note->pivot->value));
            } else {
                $data[$field_id]['value'] = $note->pivot->value;
            }
        }

        return $data;
    }

    /**
     * [printSalesNotes description].
     *
     * @param [type] $id [description]
     *
     * @return [type]     [description]
     */
    public function printSalesNotes($id)
    {
        $company = $this->company->with('managedBy')->get();
        $company = $company->find($id);

        $data = Salesnote::where('company_id', $id)->with('fields')->get();
        //$data = $this->getSalesNotes($id);
        $fields = Howtofield::orderBy('group', 'asc')
            ->orderBy('sequence', 'asc')->get();

        return response()->view('salesnotes.printnote', compact('data', 'fields', 'company'));
    }

    /**
     * [_reformatRequestData description].
     *
     * @param Request $request [description]
     *
     * @return [type]           [description]
     */
    private function _reformatRequestData(Request $request)
    {
        foreach (request()->except(['_token', 'submit', '_method']) as $key=>$value) {
            if ($value) {
                $data[$key] = ['fieldvalue'=>$value];
            }
        }

        return $data;
    }
}
