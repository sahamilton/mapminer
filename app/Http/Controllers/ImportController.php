<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Project;
use App\Import;
use App\Http\Requests\ImportFormRequest;

class ImportController extends Controller
{

    public function __construct(){
        
        
    }

    

    public function import(ImportFormRequest $request) {
        
        $data = $this->uploadProjects($request);
        $fields = $this->getFileFields($data);
        
        $source = $request->get('projectsource');

        $columns = $this->project->getTableColumns($data['table']);
        $skip = ['id','created_at','updated_at','serviceline_id','project_source_id','pr_status'];
        return response()->view('imports.mapfields',compact('columns','fields','data','source','skip'));
    }
    

    private function uploadfile($request){
        $file = $request->file('upload')->store('public/uploads'); 
        $data['file'] = $file;
        $data['linkfile'] = asset(\Storage::url($file));
        $data['basepath'] = base_path()."/public".\Storage::url($file);
        $data['table'] = $request->get('table');
        return $data;
    }

    private function getFileFields($data){
        $content = fopen($data['basepath'], "r");
        $row=1;
        for ($i=0; $i<10; $i++){
            $fields[$i]= fgetcsv($content);
        }
        return $fields;
}

    public function mapfields(Request $request){
        $data = $this->getData($request);
        
        
    }
    private function getData($request){
        $data['table']=$request->get('table');
        $data['filename'] = base_path()."/public".\Storage::url($request->get('filename'));
        $data['linkfile'] = asset(\Storage::url($request->get('filename')));
        $data['table'] = $request->get('table');
        $data['source_id'] = $request->get('projectsource');
        $data['fields'] = implode(",",$request->get('field'));
        return $data;
    }
    
    
}
