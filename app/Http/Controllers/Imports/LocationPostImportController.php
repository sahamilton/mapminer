<?php

namespace App\Http\Controllers\Imports;

use App\Address;
use App\Company;
use App\Contact;
use App\LocationPostImport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LocationPostImportController extends ImportController
{
    public $company;
    public $contact;
    public $import;
    public $address;
    public $contactFields = [
        "fullname",
        "firstname",
        "lastname",
        "title" ,
        "email",
        "contactphone",
    ];

    /**
     * [__construct description].
     *
     * @param LocationPostImport $import  [description]
     * @param Company            $company [description]
     * @param Address            $address [description]
     */
    public function __construct(
        LocationPostImport $import,
        Company $company,
        Address $address,
        Contact $contact
    ) {
        $this->company = $company;
        $this->import = $import;
        $this->address = $address;
        $this->contact = $contact;
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

            $this->_addNewLocations($data);
            // import the contacts
            $message = 'Imported '.$data['add']->count().' locations. Matched '.count($data['matched']).' existing locations';

            return redirect()->route('company.show', $this->company->id)->withMessage($message);
        } else {
            $data = $this->import->dunsMatchAddress();

            $this->_addNewLocations($data);

            $message = 'Imported '.$data['add']->count().' locations. Matched '.$data['matched']->count().' existing locations';

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

        if (count($data['delete']) > 0) {
            $this->_deleteLocations($data['delete']);
        }
        if (count($data['add']) > 0) {
            $data = $this->_addNewLocations($data);
            $this->_copyAddressIdToImport($data);
        }

        if (count($data['matched']) > 0) {
            $this->_updateLocations($data);
        }

        /// copy all contact information to contacts
        $this->import->truncate();

        return redirect()->route('company.show', $data['company']->id)->withSuccess('Locations imported and updated');
    }

    /**
     * [_addNewLocations description].
     *
     * @param [type] $data [description]
     *
     * @return [<description>]
     */
    private function _addNewLocations($data)
    {
        /*User::chunk(100, function ($users) {
          foreach ($users as $user) {
            $some_value = ($user->some_field > 0) ? 1 : 0;
            // might be more logic here
            $user->update(['some_other_field' => $some_value]);
          }
        });*/

        $m = $this->_getIdsFromArray($data['add']);
        
        $this->import->whereIn('id', $m)
            ->chunk(
                100, function ($inserts) {
                    $inserts->each(
                        function ($insert) {
                            $branch_id = $this->_getBranchId($insert);
                            
                            $item = $this->_setImportRef($insert);
                            
                            $address = \DB::table('addresses')->insertGetId($item->toArray());
                            $contact = $this->_getContactData($insert, $address);
                            $this->_updateImportTable($item, $address);
                            if ($branch_id) {
                                $this->_assignToBranch($address, $branch_id);
                            }
                        
                        }
                    );
                }
            );

        return $data;
    }

    private function _assignToBranch(int $address_id, int $branch_id )
    {
        $this->address->findOrFail($address_id)->assignedToBranch()->attach($branch_id);
    }
    private function _getBranchId($insert)
    {
        if (isset($insert->branch_id)) {
            return $insert->branch_id;
        }
        return false;
    }
    private function _getContactData($insert, $address_id)
    {
        
        $result = false;
        foreach ($this->contact->fillable as $field) {
            if ($field != 'user_id' && null !== $insert->getOriginal($field)) {
                $result = true;
                $data[$field] = $insert->getOriginal($field);
            }
        }
        if ($result) {
            
            $data['user_id'] = auth()->user()->id;
            $data['address_id'] = $address_id;
            $data['comments'] = 'Imported from lead source '. $insert->lead_source_id;
            $contact = Contact::create($data);
            
        }
        
    }
    /**
     * [_copyAddressIdToImport description].
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
     * [_updateLocations description].
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
     * [_getMatchedAddresses description].
     *
     * @param [type] $data [description]
     *
     * @return [type]       [description]
     */
    private function _getMatchedAddresses($data)
    {
        $match = $this->_getIdsFromArray($data['matched']);

        return $this->import->whereNotNull('address_id')
            ->whereIn('id', $match)
            ->get();
    }

    /**
     * [_deleteLocations description].
     *
     * @param [type] $data [description]
     *
     * @return [type]       [description]
     */
    private function _deleteLocations($data)
    {
        $m = $this->_getIdsFromArray($data);

        return  $this->address->whereIn('id', $m)->delete();
    }

    /**
     * [_getIdsFromArray description].
     * @param  array $data [description]
     * @return array       ids of data array
     */
    private function _getIdsFromArray($data)
    {
        $m = [];
        foreach ($data as $el) {
            $m[] = $el->id;
        }

        return $m;
    }

    /**
     * [_setImportRef description].
     *
     * @param Collection $collection [description]
     *
     * @return [type]                 [description]
     */
    private function _setImportRef($item)
    {
        $item->import_ref = $item->id;
        $item->user_id = auth()->user()->id;
        $item->created_at = Carbon::now();

        return Arr::except($item, ['id', 'address_id', 'contactphone', 'email', 'firstname', 'lastname', 'fullname', 'title','branch_id']);
    }

    /**
     * [_updateImportTable description].
     *
     * @param [type] $data [description]
     *
     * @return [type]       [description]
     */
    private function _updateImportTable($item, $address_id)
    {
        $item->update(['import_ref'=>$address_id]);
    }
}
