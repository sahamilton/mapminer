<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyImport extends Imports
{
    public $requiredFields = [
            'businessname',
            'accounttypes_id',
            'street',
            'city',
            'state',
            'zip',
            'lat',
            'lng',
            'branch_id',
            'customer_id'
            ];
    public $fillable = ['address_id'];
    public $table = 'customerimport';
    
    /*public function getCompaniesToCreate(){
		return \DB::select(\DB::raw("SELECT distinct customerimport.companyname,customerimport.customer_id FROM `customerimport` left join companies on customerimport.customer_id = companies.customer_id where companies.customer_id is null"));
	}*/
}
