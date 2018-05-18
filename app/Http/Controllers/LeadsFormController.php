<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Lead;
use App\LeadImport;
use App\LeadSource;
use App\Http\Requests\WebLeadFormRequest;


class LeadsFormController extends Controller
{
    public function index(){
    	return response()->view('leads.leadform');
    }

    public function getLeadFormData(WebLeadFormRequest $request){
    	// first get teh rows of data
		$rows = explode(PHP_EOL,$request->get('weblead'));
		// then create the
    	foreach ($rows as $row){
    		$field = explode("\t",$row);
    		if(is_array($field) && count($field)==2){
    			$data[str_replace(" ","_",strtolower($field[0]))]=$field[1];
    		}
    	}
       
    	dd($data);
    }
}
