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
    

    public function index(){
        $imports = ['branches','branch_team','prospects','locations','projects','project_company','users'];
        return response()->view('imports.index',compact('imports'));
    }


    protected function uploadfile($file){
        $file = $file->store('public/uploads'); 
        $data['file'] = $file;
        $data['linkfile'] = asset(\Storage::url($file));
        $data['filename'] = base_path()."/public".\Storage::url($file);
        return $data;
    }

    protected function getFileFields($data){
        $content = fopen($data['filename'], "r");
        $row=1;
        for ($i=0; $i<10; $i++){
            $fields[$i]= fgetcsv($content);
        }
        return $fields;
    }

    
    protected function getData($request){
        $data = $request->all();
        $data['fields'] = array_values($request->get('fields'));
        return $data;
    }
    
    protected function validateInput(Request $request){


       
        if($multiple = $this->import->detectDuplicateSelections($request->get('fields'))){
            return redirect()->route('branches.importfile')->withError(['You have to mapped a field more than once.  Field: '. implode(' , ',$multiple)]);
        }
        if($missing = $this->import->validateImport($request->get('fields'))){
             
            return redirect()->route('branches.importfile')->withError(['You have to map all required fields.  Missing: '. implode(' , ',$missing)]);
       }     
       return false;
    }   
}
