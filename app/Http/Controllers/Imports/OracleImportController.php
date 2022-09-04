<?php

namespace App\Http\Controllers\Imports;

use Illuminate\Http\Request;
use App\Models\Oracle;
use App\Models\OracleImport;

use App\Http\Requests\OracleImportFormRequest;

class OracleImportController extends ImportController
{
    public $oracle;
    public $import;

   
    public function __construct(Oracle $oracle, OracleImport $import)
    {
        $this->oracle = $oracle;
        $this->import = $import;
    }
    /**
     * [getFile description]
     * 
     * @param  Request $request [description]
     * 
     * @param  [type]  $id      [description]
     * @param  [type]  $type    [description]
     * @return [type]           [description]
     */
    public function getFile(Request $request)
    {

        
        $requiredFields = $this->oracle->requiredfields;
        $types = $this->import->types;

        return response()->view('oracle.import', compact('requiredFields', 'types'));
    }

    /**
     * [import description]
     * 
     * @param LeadImportFormRequest $request [description]
     * 
     * @return [type]                         [description]
     */
    public function import(OracleImportFormRequest $request)
    {
        
        $file = request()->file('upload');

        $data = $this->uploadfile(request()->file('upload'));
        $data['originalFilename'] = $file->getClientOriginalName();
        $title="Map the Oracle import file fields";
        $requiredFields = $this->oracle->requiredfields;
        $data['type'] = request('type');
        $data['table']='oracle';
        $data['skip'] = request('offset');
        $data['route'] = 'oracle.mapfields';
        $fields = $this->getFileFields($data);
        $columns = $this->import->getTableColumns($data['table']);

        $skip = ['id','deleted_at','created_at','updated_at',];
        return response()->view('imports.mapfields', compact('columns', 'fields', 'data', 'title', 'requiredFields'));
    }
    /**
     * [mapfields description]
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
    
        if ($this->import->import($request)) {
            
            return redirect()->route('oracle.index')->with('success', 'Oracle Data ('.request('type').') imported');
        }
    }
    
        
}
