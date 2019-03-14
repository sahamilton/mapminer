<?php

namespace App\Http\Controllers;

use App\LocationPostImport;
use Illuminate\Http\Request;
use App\Company;
use App\Address;
use Illuminate\Database\Eloquent\Collection;
class LocationPostImportController extends Controller
{
    public $company;
    public $import;
    public $address;
    public function __construct(LocationPostImport $import, Company $company,Address $address)
    {
        $this->company = $company;
        $this->import = $import;
        $this->address = $address;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $import = $this->import->first();

        $this->company = $this->company->findOrFail($import->company_id);

        $data = $this->import->returnAddressMatchData($this->company);

        return response()->view('location.imports',compact('data'));
    }

   
    
    


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company = $this->company->findOrFail(request('company_id'));
        $data = $this->import->returnAddressMatchData($company);
        
        if(count($data['delete'])>0){
            $this->deleteLocations($data['delete']);
       }
        if(count($data['add'])>0){
            $data = $this->addNewLocations($data);
            $this->copyAddressIdToImport($data);
        }
      
        if(count($data['matched'])>0){
            $this->updateLocations($data);
        }
        
        /// copy all contact information to contacts
        $this->import->truncate();
      
        return redirect()->route('company.show',$data['company']->id)->withSuccess('Locations imported and updated');
    }

    
    
    /**
    * Insert new locations into addresses
    * @param array
    */
    private function addNewLocations($data)
    {
        $m = $this->getIdsFromArray($data['add']);
        $insert = $this->import->whereIn('id',$m)->get();
        $insert = $this->setimport_ref($insert);
        if($insert->count()>0){
            \DB::table('addresses')->insert($insert->toArray());
        }
        return $data;

    }
    
    /**
    * Copy the effected address id back to import table
    * 
    * @param array
    */

    private function copyAddressIdToImport($data)
    {
    
        $locations = $this->address
            ->where('company_id','=',$data['company']->id)
            ->whereNotNull('import_ref')
            ->pluck('id','import_ref')->toArray();
      
        foreach ($locations as $id=>$ref)
        {
          
            $loc = $this->import->findOrFail($id);
           
            $loc->update(['address_id'=>$ref]);

        }
        

    }
    /**
    * Update all the matched addresses
    * 
    * @param array
    */

    private function updateLocations($data)
    {
        //get ids
        
        $this->updateImportTable($data['matched']);
        $imports = $this->getMatchedAddresses($data);
        foreach ($imports as $import){
            
            $address = $this->address->findOrFail($import->address_id);
            $address->update($import->toArray());
        }
        return true;
    }
    /**
     * [getMatchedAddresses description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    private function getMatchedAddresses($data)
    {
        $match = $this->getIdsFromArray($data['matched']);
       
        return $this->import->whereNotNull('address_id')->whereIn('id',$match)->get(); 
    
    }

    /**
    * Remove unmatched / new locations from addresses
    *   
    * @param array
    *
    *
    */
    private function deleteLocations($data)
    {
       
       $m = $this->getIdsFromArray($data);
       
       return  $this->address->whereIn('id',$m)->delete();
    }
    /**
     * [getIdsFromArray description]
     * @param  array $data [description]
     * @return array       ids of data array
     */
    private function getIdsFromArray($data)
    {
        $m=[];
        foreach ($data as $el){
            $m[] = $el->id;
        }
       return $m;
    }
    /*
    *  Add / remove fields from collection for import ref
    *
    *
    *   @param collection  
    */

    private function setimport_ref(Collection $collection)
    {
        $collection->map(function ($item)
        {
            $item->import_ref = $item->id;
            $item->user_id = auth()->user()->id;
          
            return array_except($item,['id','address_id','contactphone','email','firstname','lastname','fullname','title']);
        });
       
        return $collection;
    }

    /*
    @function updateImportTable
    @return boolean
    insert matched id into import table
     */
    private function updateImportTable($data)
    {

        foreach ($data as $el){
          
            $import = $this->import->whereId($el->id)->update(['address_id' => $el->import_ref]);
            
        }
        
        return true;
    }
}
