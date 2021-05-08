<?php

namespace App\Transfomers;

use App\Company;
use Illuminate\Database\Eloquent\Model;
use League\Fractal;

class CompanyActivityTransformer extends  Fractal\TransformerAbstract
{
   
    /**
     * [transform description]
     * 
     * @param Company $company [description]
     * 
     * @return [type]           [description]
     */
    public function transform(Company $company)
    {
   
        return [
            'id'      => (int) $company->id,
            'name'   => $company->companyname,
            'data'=>[],

        ];
    }
}
