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
    /**
     * [__construct description]
     * 
     * @param LocationPostImport $import  [description]
     * @param Company            $company [description]
     * @param Address            $address [description]
     */
    public function __construct(
        LocationPostImport $import, 
        Company $company,
        Address $address
    ) {
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

        // what happens if no company?
        if ($this->company = $this->company->find($import->company_id)) {
            $data = $this->import->returnAddressMatchData($this->company);
            
        } else {
            
            $data = $this->import->dunsMatchAddress();            
            $this->_addNewLocations($data);
            $message = 'Imported ' . $data['add']->count(). ' locations. Matched ' . $data['matched']->count() . ' existing locations';
            return redirect()->route('leadsource.index')->withMessage($message);
            
        }
        
        

        
    }

   
    
    


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request 
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if ($company = $this->company->find(request('company_id'))) {
            $data = $this->import->returnAddressMatchData($company);
        }
        
        
        if (count($data['delete'])>0) {
            $this->_deleteLocations($data['delete']);
        }
        if (count($data['add'])>0) {
            $data = $this->_addNewLocations($data);
            $this->_copyAddressIdToImport($data);
        }
      
        if (count($data['matched'])>0) {
            $this->_updateLocations($data);
        }
        
        /// copy all contact information to contacts
        $this->import->truncate();
      
        return redirect()->route('company.show', $data['company']->id)->withSuccess('Locations imported and updated');
    }

    
    
    /**
     * [_addNewLocations description]
     * 
     * @param [type] $data [description]
     *
     * @return [<description>]
     */
    private function _addNewLocations($data)
    {
        $m = $this->_getIdsFromArray($data['add']);
        $insert = $this->import->whereIn('id', $m)->get();
        $insert = $this->_setImportRef($insert);
        if ($insert->count()>0) {
            \DB::table('addresses')->insert($insert->toArray());
        }
        return $data;

    }
    
    /**
     * [_copyAddressIdToImport description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _copyAddressIdToImport($data)
    {
    
        $locations = $this->address
            ->where('company_id', $data['company']->id)
            ->whereNotNull('import_ref')
            ->pluck('id', 'import_ref')->toArray();
      
        foreach ($locations as $id=>$ref) {
          
            $loc = $this->import->findOrFail($id);
           
            $loc->update(['address_id'=>$ref]);

        }
        

    }
    /**
     * [_updateLocations description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _updateLocations($data)
    {
        //get ids
        
        $this->_updateImportTable($data['matched']);
        $imports = $this->_getMatchedAddresses($data);
        foreach ($imports as $import) {
            
            $address = $this->address->findOrFail($import->address_id);
            $address->update($import->toArray());
        }
        return true;
    }
    /**
     * [_getMatchedAddresses description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getMatchedAddresses($data)
    {
        $match = $this->_getIdsFromArray($data['matched']);
       
        return $this->import->whereNotNull('address_id')->whereIn('id',$match)->get(); 
    
    }

    /**
    * Remove unmatched / new locations from addresses
    *   
    * @param array
    *
    *
    */
    private function _deleteLocations($data)
    {
       
       $m = $this->_getIdsFromArray($data);
       
       return  $this->address->whereIn('id',$m)->delete();
    }
    /**
     * [_getIdsFromArray description]
     * @param  array $data [description]
     * @return array       ids of data array
     */
    private function _getIdsFromArray($data)
    {
      
        $m=[];
        foreach ($data as $el) {
            $m[] = $el->id;
        }
        return $m;
    }
    /**
     * [_setImportRef description]
     * 
     * @param Collection $collection [description]
     * 
     * @return [type]                 [description]
     */
    private function _setImportRef(Collection $collection)
    {
        $collection->map(
            function ($item) {
                $item->import_ref = $item->id;
                $item->user_id = auth()->user()->id;
          
                return array_except($item, ['id','address_id','contactphone','email','firstname','lastname','fullname','title']);
            }
        );
       
        return $collection;
    }

    /**
     * [_updateImportTable description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _updateImportTable($data)
    {

        foreach ($data as $el) {
          
            $import = $this->import->whereId($el->id)->update(['address_id' => $el->import_ref]);
            
        }
        
        return true;
    }
}
