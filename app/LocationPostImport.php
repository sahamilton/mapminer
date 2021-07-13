<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationPostImport extends Model
{
    public $table = 'addresses_import';
    public $fillable = ['address_id'];

    /**
     * [getAddressMatchData description].
     *
     * @param [type] $request [description]
     *
     * @return [type]          [description]
     */
    public function getAddressMatchData($request)
    {
        $company_id = request('additionaldata')['company_id'];

        return returnAddressMatchData($company_id);
    }

    public function dunsMatchAddress()
    {
        $data = [];
        $duns = $this->pluck('duns')->toArray();
        $data['matched'] = Address::whereIn('duns', $duns)->get();
        $matched = $data['matched']->pluck('duns')->toArray();

        $data['add'] = $this->whereNotIn('duns', $matched)->get();

        return $data;
    }

    /**
     * [returnAddressMatchData description].
     *
     * @param Company $company [description]
     *
     * @return [type]           [description]
     */
    public function returnAddressMatchData(Company $company)
    {
        /* ST_Distance_Sphere(
                point(lng, lat),
                point(". $longitude . ", " . $latitude .")
            )  < ".$close_in_metres );*/
        $data['company'] = $company;
        $this->distance = 'ST_Distance_Sphere(point(addresses.lng,addresses.lat),point(addresses_import.lng,addresses_import.lat))';

        $data['matched'] = $this->_geoMatchAddresses($company->id);
        $this->_updateImportTable($data['matched']);
        $data['add'] = $this->_geoAddAddresses($data['matched']);
        $data['delete'] = $this->_geoDeleteAddress($company->id);
        return $data;
    }

    /**
     * [_geoMatchAddresses description].
     *
     * @param [type] $company_id [description]
     *
     * @return [type]             [description]
     */
    private function _geoMatchAddresses($company_id)
    {
        $query = 'select addresses_import.id  as id, 
        addresses.id as import_ref
         from addresses,addresses_import where addresses.company_id = addresses_import.company_id 
        and addresses_import.company_id = '.$company_id.
        ' and '.$this->distance.' < 50';

        return  \DB::select($query);
        // update import table with existing id
    }

    /**
     * [_geoAddAddresses description].
     *
     * @param [type] $data [description]
     *
     * @return [type]       [description]
     */
    private function _geoAddAddresses($data)
    {
        $match = [];
        foreach ($data as $el) {
            $match[] = $el->id;
        }

        return \DB::table('addresses_import')->whereNotIn('id', $match)->get();
    }

    /**
     * [_geoDeleteAddress description].
     *
     * @param [type] $company_id [description]
     *
     * @return [type]             [description]
     */
    private function _geoDeleteAddress($company_id)
    {
        $query = 'select addresses.*  FROM addresses left join addresses_import  on '.$this->distance.' < 10  where addresses.company_id = '.$company_id.' and addresses_import.id is null';

        return \DB::select($query);
    }

    /**
     * [_updateImportTable description].
     *
     * @param [type] $data [description]
     *
     * @return [type]       [description]
     */
    private function _updateImportTable($data)
    {
        foreach ($data as $el) {
            \DB::table('addresses_import')
                ->where('id', $el->id)
                ->update(['import_ref' => $el->import_ref]);
        }

        return true;
    }
}
