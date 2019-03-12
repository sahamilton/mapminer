<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationPostImport extends Model
{
    
	public $table = 'addresses_import';
    public $fillable = ['address_id'];

	public function getAddressMatchData($request)
    {
    	$company_id = request('additionaldata')['company_id'];
    	return returnAddressMatchData($company_id);
    	
    }

    public function returnAddressMatchData(Company $company)
    {
    	
    	$data['company'] = $company;
    	$this->distance = "ST_Distance_Sphere(addresses.position,addresses_import.position)";
    	
    	$data['matched'] = $this->geoMatchAddresses($company->id);
    	$this->updateImportTable($data['matched']);
    	$data['add'] = $this->geoAddAddresses($data['matched']);
    	$data['delete']  = $this->geoDeleteAddress( $company->id);
    	return $data;
    }
    /*
    function geoMatchAddress
    @return array
	join between import table and final table based on proximity

     */
    private function geoMatchAddresses($company_id)
    {
    	
    	$query = "select addresses_import.id  as id, 
    	addresses.id as import_ref
    	 from addresses,addresses_import where addresses.company_id = addresses_import.company_id 
		and addresses_import.company_id = " .$company_id . 
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

    	return \DB::table('addresses_import')->whereNotIn('id',$match)->get(); 
 
    }
    /*
    function geoDeleteAddress
    @ return array
    find unmatched between import table and final table
     */
   private function geoDeleteAddress( $company_id)
    {
    	
    	$query = "select addresses.*  FROM addresses left join addresses_import  on ". $this->distance . " < 100  where addresses.company_id = " .$company_id. " and addresses_import.id is null";
    	
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
    		\DB::table('addresses_import')
		    ->where('id', $el->id)
		    ->update(['import_ref' => $el->import_ref]);
    		
    	}
    	
    	return true;
    }
    
   /* private function addNewLocations($data)
    {
       /* insert into table
        update import_ref
        if contacts
            add contacts
            delete from import table*/
 
  /*      $company = Company::findOrFail('275');

        $import = new LocationImport($company);
        $insert = $import->whereIn('id',$data)->get();
        dd($insert);
       // $insert = \DB::table($this->temptable);
        $insert = $this->setimport_ref($insert);
        dd($insert->toArray());
        \DB::table('addresses')->insert($insert);
    }

    private function updateLocations($data)
    {
        
        

        /*update table
        if contacts
            add contacts
        delete from import table
    }

    private function deleteLocations($data)
    {
    
       return  \DB::table('addresses')->whereIn('id',$data)->delete();
    }

      private function setimport_ref($collection)
    {
        $collection->map(function ($item)
        {
            $item->import_ref = $item->id;
            $item->user_id = auth()->user()->id;
           
            return $item;
        });

        return $collection;
    }    
*/
}
