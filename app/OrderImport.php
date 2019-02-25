<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Jobs\ProcessOrderImport;
use App\Jobs\ProcessNewCompanies;
use App\Jobs\ProcessNewAddresses;

class OrderImport extends Model
{
    public $table = 'customerimport';

    public $fillable = ['address_id','account_id','company_id','address_id'];
    public function addAddressContacts()
    {
        // no idea how to do this
        // how do we deduplicate?
    }
    public function getImportUpdates()
    {
        
        $data['matching'] = $this->getMatchingAddresses();
        $data['companymatch'] = $this->getMatchingCompanies();

        
        return $data;
    }

    private function getMatchingCompanies()
    {

        return \DB::select(\DB::raw("SELECT customerimport.customer_id, customerimport.id as importid, customerimport.businessname,customerimport.street,customerimport.city,companies.id as existingid, companies.companyname FROM customerimport,companies where (position(companyname in businessname) or companies.customer_id = customerimport.customer_id) and customerimport.company_id is null"));
    }

    public function matchCompanies(Request $request)
    {
        
        if (request()->has('match')) {
            foreach (request('match') as $key => $value) {
                $import = $this->findOrFail($key);
                if (! $company = Company::where('customer_id', '=', $import->customer_id)->first()) {
                    $company = Company::findOrFail($value);
                }
                
                if ($company->customer_id and $company->customer_id != $import->customer_id) {
                    $data=array();
                    $data['companyname'] = $import->businessname;
                    $data['parent_id'] = $value;
                    $data['accounttypes_id'] = 3;
                    
                    $company = Company::create($data);
                }
                $import->update(['company_id'=>$company->id]);
                $company->update(['customer_id'=>$import->customer_id]);
            }
        }
        return true;
    }
    public function matchAddresses(Request $request)
    {
        if (request()->has('match')) {
            foreach (request('match') as $key => $value) {
                $import = $this->findOrFail($value);
                $import->update(['address_id'=>$key]);
            }
        }
        return true;
    }


    public function addNewAddresses()
    {
        $newAddresses = $this->whereNull('address_id')
           ->select('id', 'businessname', 'lat', 'lng', 'street', 'address2', 'city', 'state', 'zip', 'customer_id', 'company_id')
            ->distinct()
            ->get();
       
        foreach ($newAddresses as $newaddress) {
                ProcessNewAddresses::dispatch($newaddress);
        }
    }

    public function storeOrders()
    {
        $orders = $this->whereNotNull('address_id')
                
                ->get();
       
        foreach ($orders as $order) {
            ProcessOrderImport::dispatch($order);
        }
    }

    public function missingCompanies()
    {
        if ($missing = $this->getCompaniesToCreate()) {
            return $missing;
        }
        return false;
    }

    /*public function matchedLocations(){
        if ($missing = $this->getLocationsToCreate()){

            
        }
        return false;
    }*/

    public function getCompaniesToCreate()
    {

        return $this->leftJoin('companies', function ($join) {
                $join->on('customerimport.customer_id', '=', 'companies.customer_id');
        })
            ->whereNull('companies.customer_id')
         
            ->select("customerimport.*")->get();


        /*return \DB::select(\DB::raw("SELECT distinct customerimport.businessname,customerimport.accounttypes_id, customerimport.customer_id FROM `customerimport` left join companies on customerimport.customer_id = companies.customer_id where companies.customer_id is null and customerimport.customer_id "));*/
    }

    private function getMatchingAddresses()
    {
        return \DB::select(\DB::raw("SELECT addresses.id as existingid, customerimport.id as importid, addresses.businessname as existingbusiness, companies.companyname as parent, addresses.street as existingstreet,addresses.city as existingcity,customerimport.businessname as importbusiness, customerimport.street as importstreet,customerimport.city as importcity from customerimport, addresses left join companies on addresses.company_id = companies.id where customerimport.address_id is null and addresses.lat = customerimport.lat and addresses.lng = customerimport.lng and accuracy = 1"));
    }
    public function createMissingCompanies()
    {
            $newcompany = $this->getCompaniesToCreate();
          
        foreach ($newcompany as $newco) {
            ProcessNewCompanies::dispatch($newco);
        }


        return true;
    }

    
   /* public function updateMatchedLocations(Request $request){
        // update import with location ids

    }*/
}
