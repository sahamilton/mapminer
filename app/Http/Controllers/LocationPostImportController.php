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

   
    
    private function addNewLocations($data)
    {
       
       /* insert into table
        update import_ref
        if contacts
            add contacts
            delete from import table*/
     
    
    $company = $this->company->findOrFail($data['company_id']);

        $insert = $this->import->whereIn('id',$data['add'])->get();
      
       // $insert = \DB::table($this->temptable);
        $insert = $this->setimport_ref($insert);
      
        \DB::table('addresses')->insert($insert->toArray());
        $this->copyAddresIdToImport($data);

        $this->copyContacts($data);
    }
    private function copyAddresIdToImport($data)
    {
        $locations = $this->address
            ->where('company_id','=',$data['company_id'])
            ->whereNotNull('import_ref')
            ->pluck('id','import_ref')->toArray();
       
        foreach ($locations as $id=>$ref)
        {
          
            $loc = $this->import->findOrFail($id);
           
            $loc->update(['address_id'=>$ref]);

        }
        

    }
    
    private function copyContacts($data)
    {

    }
   
    private function updateLocations($data)
    {
        
        
        dd($data);
        /*update table
        if contacts
            add contacts
        delete from import table*/
    }

    private function deleteLocations($data)
    {
       
       return  \DB::table('addresses')->whereIn('id',$data)->delete();
    }

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(request()->has('delete')){
            $this->deleteLocations(request()->all());
       }
        
        $this->addNewLocations(request()->all());
        $this->updateLocations(request()->all());

        return redirect()->route('locations.process');
    }

}
