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
    public function __construct(Model $model){
        
        parent::__construct($model);
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

    public function mapfields(Request $request){

        $data = $this->getData($request);      
        $import = new Imports($data);

        if($import->import()) {
            return $this->returnToOrigin($data);

        }
        
    }
    private function returnToOrigin($data){
       
        switch ($data['type']){
            case 'projects':
                return redirect()->route('projectsource.index')->with('success','Projects imported');
            break;

            case 'locations':
                return redirect()->route('company.show',$data['addtionaldata']['company_id'])->with('success','Locations imported');
            break;

            case 'branches':
                return redirect()->route('branches.index')->with('success','Branches imported');
            break;

            case 'leads':
                return redirect()->route('leadsource.index')->with('success','Leads imported');
            break;
        }
    }
    private function getData($request){
        $data = $request->all();
        $data['fields'] = array_values($request->get('fields'));


        return $data;
    }
    
    
}
