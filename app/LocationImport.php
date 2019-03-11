<?php

namespace App;

use App\Company;
use Illuminate\Database\Eloquent\Model;

class LocationImport extends Imports
{
    public $table = 'addresses';
    public $requiredFields = ['businessname','street','city','state','zip','lat','lng'];
    public $temptable = 'addresses_import';
    public $dontCreateTemp = true;
    public $distance; 
    public $company;
    public function __construct(Company $company)
    {
    	$this->company = $company;

    }

    public function getAddressMatchData($request)
    {
    	$company_id = request('additionaldata')['company_id'];
    	return returnAddressMatchData($company_id);
    	
    }

    public function returnAddressMatchData($company_id)
    {
    	
    	$data['company'] = $this->company->findOrFail($company_id);
    	$this->distance = "ST_Distance_Sphere(". $this->table .".position," . $this->temptable .".position)";
    	
    	$data['matched'] = $this->geoMatchAddresses($company_id);
    	$this->updateImportTable($data['matched']);
    	$data['add'] = $this->geoAddAddresses($data['matched']);
    	$data['delete']  = $this->geoDeleteAddress( $company_id);
    	return $data;
    }
    /*
    function geoMatchAddress
    @return array
	join between import table and final table based on proximity

     */
    private function geoMatchAddresses($company_id)
    {
    	
    	$query = "select " 
    	. $this->temptable.".id  as id," 
    	. $this->table . ".id as import_ref
    	 from " . $this->table . ",". $this->temptable . 
		" where " . $this->table .".company_id = " . $this->temptable .".company_id 
		and " . $this->temptable. ".company_id = " .$company_id . 
		" and " . $this->distance ." < 10";
		$data =  \DB::select($query); 
    	// update import table with existing id
    	return $data;
    }
    /*
    geoAddAddress
    return array 
    difference between matched and imported set
     */
    private function geoAddAddresses($data)
    {
    	$match=[];
    	foreach($data as $el){

    	    		$match[] = $el->id;
    	    	}

    	return \DB::table($this->temptable)->whereNotIn('id',$match)->get(); 
 
    }
    /*
    function geoDeleteAddress
    @ return array
    find unmatched between import table and final table
     */
    private function geoDeleteAddress( $company_id)
    {
    	
    	$query = "select " .  $this->table.".*  FROM " . $this->table ." left join ". $this->temptable ." on " . $this->distance . " < 100  where " . $this->table .".company_id = " .$company_id. " and " . $this->temptable.".id is null";
    	
  		return \DB::select($query); 
  	
    }
    /*
    @function updateImportTable
    @return boolean
    insert matched id into import table
     */
    private function updateImportTable($data)
    {

    	foreach ($data as $el){
    		\DB::table($this->temptable)
		    ->where('id', $el->id)
		    ->update(['import_ref' => $el->import_ref]);
    		
    	}
    	
    	return true;
    }
}