<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Project;
use App\Imports;
use App\Model;
use App\Http\Requests\ImportFormRequest;

class ImportController extends BaseController
{
    public $userServiceLines;
    

    public function index()
    {
        $imports = ['branches','branch_team','companies','locations','users'];
        $exports = ['allcompanies','companies','branches','branches_team','companies','person','vertical','nomanager','projects','watch'];
        return response()->view('imports.index', compact('imports', 'exports'));
    }


    protected function uploadfile($file)
    {
       
        $file = $file->store('public/uploads');
        $data['file'] = $file;
        $data['linkfile'] = asset(\Storage::url($file));
       
        $data['filename'] = storage_path()."/app/".$file;
        return $data;
    }

    protected function getFileFields($data)
    {
        $content = fopen($data['filename'], "r");
        $row=1;
        for ($i=0; $i<10; $i++) {
            $fields[$i]= fgetcsv($content);
        }

        return $fields;
    }

    
    protected function getData($request)
    {
      
        $data = request()->all();
        $data['fields'] = array_values(request('fields'));
        return $data;
    }
    
    protected function validateInput(Request $request)
    {

        if ($fields = $this->import->detectDuplicateSelections(request('fields'))) {
            return $error = ['You have to mapped a field more than once.  Field: '. implode(' , ', $fields)];
        }
  
        if ($fields = $this->import->validateImport(request('fields'))) {
            return $error = ['You have to map all required fields.  Missing: '. implode(' , ', $fields)];
        }
        return false;
    }
}
