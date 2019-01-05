<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class OrderImport extends Model
{
    public $table = 'customerimport';

    public $fillable = ['account_id','company_id','address_id'];
     public function addAddressContacts(){
        // no idea how to do this
        // how do we deduplicate?
    }
    public function getImportUpdates(){
    	$data['missing'] = array();//$this->missingCompanies();
    	$data['matching'] = $this->getMatchingAddresses();
    	$data['companymatch'] = $this->getMatchingCompanies();

    	
    	return $data;
    }

    private function getMatchingCompanies(){

    return \DB::select(\DB::raw("SELECT customerimport.customer_id, customerimport.id as importid, customerimport.businessname,customerimport.street,customerimport.city,companies.id as existingid, companies.companyname FROM customerimport,companies where  position(businessname in companyname) and customerimport.company_id is null"));

    }

    public function matchCompanies(Request $request){
    	
    	if (request()->has('match')){
	    	foreach (request('match') as $key=>$value){
	    		$import = $this->findOrFail($key);

	    		$import->update(['company_id'=>$value]);
	    		
	    		if($import->customer_id){

	    			$company = Company::findOrFail($value);
	    			
	    			$company->update(['customer_id'=>$import->customer_id]);
	    		}
	    	}
	    }
	    return true;
	}
    public function matchAddresses(Request $request){
    	if(request()->has('match')){
	    	foreach (request('match') as $key=>$value){
	    		$import = $this->findOrFail($value);
	    		$import->update(['address_id'=>$key]);

	    	}
	    }
    	return true;
    }


    public function addNewAddresses(){
        $newAddresses = $this->whereNull('address_id')
	->select('id','businessname','lat','lng','street','address2','city','state','zip','customer_id','company_id')
	        ->distinct()
	        ->get();
	       
        foreach ($newAddresses as $newaddress){
            $data = $newaddress->toArray();
            
            $data['addressable_type'] = 'customer';
            
            $address = Address::create($data);
        
            $newaddress->update(['address_id'=>$address->id]);
           
            }
        
    }

    public function storeOrders(){
        $orders = $this->whereNotNull('address_id')->select('address_id','branch_id','orders')->get();

        foreach ($orders as $order){

            $address = Address::findOrFail($order->address_id);
           
            $data = ['period'=>'201811','orders'=>$order->orders];         
         
            $address->orders()->attach($order->branch_id,$data);
            
        }
    }

    public function missingCompanies(){
        if ($missing = $this->getCompaniesToCreate()){

            return $missing;
        }
        return false;
    }

     public function matchedLocations(){
        if ($missing = $this->getLocationsToCreate()){

            
        }
        return false;
    }

    public function getCompaniesToCreate(){

    	return $this->leftJoin('companies', function($join) {
      			$join->on('customerimport.id', '=', 'companies.customer_id');
		    })
		    ->whereNull('companies.customer_id')
		    ->select("customerimport.*")->get();


		/*return \DB::select(\DB::raw("SELECT distinct customerimport.businessname,customerimport.accounttypes_id, customerimport.customer_id FROM `customerimport` left join companies on customerimport.customer_id = companies.customer_id where companies.customer_id is null and customerimport.customer_id "));*/

    }

    private function getMatchingAddresses(){
    	return \DB::select(\DB::raw("SELECT addresses.id as existingid, customerimport.id as importid, addresses.businessname as existingbusiness, companies.companyname as parent, addresses.street as existingstreet,addresses.city as existingcity,customerimport.businessname as importbusiness, customerimport.street as importstreet,customerimport.city as importcity from customerimport, addresses left join companies on addresses.company_id = companies.id where customerimport.address_id is null and addresses.lat = customerimport.lat and addresses.lng = customerimport.lng and accuracy = 1"));
    }
    public function createMissingCompanies(){
    		$newcompany = $this->getCompaniesToCreate();

        	foreach ($newcompany as $newco){
        	   
        		$data = array();
        		$data['companyname'] = $newco->businessname;
        		$data['customer_id'] = $newco->customer_id;
        		$data['accounttypes_id'] = $newco->accounttypes_id;
        	
        		$company = Company::create($data);
        		$newco->update(['company_id'=>$company->id]);
        		
        	}


		return true;
    }

    public function updateMatchedLocations(Request $request){
        // update import with location ids

    }

}
