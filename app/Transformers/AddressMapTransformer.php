<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Address;
class AddressMapTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * var array
     */
    protected  $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * var array
     */
    protected  $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * return array
     */
    public function transform(Address $address)
    {
        $type = $this->_getType($address);
        return [

            'id'=>$address->id,
            'account'=>$address->company ? $address->company->companyname : '',
            'name'=>$address->businessname,
            'type'=>$type,
            'lat'=>$address->lat,
            'lng'=>$address->lng,
            'address'=>$address->fullAddress(),
            'locationsweb'=>route('address.show', $address->id),
            'distance' => $address->distance,

        ];
    }
    private function _getType(Address $address) :string
    {
        
        if ($address->openOpportunities->count() > 0) {
            $type = "opportunity";
        } elseif ($address->isCustomer) {
            $type =  "customer";
        } elseif ($address->assignedToBranch->count() > 0) {
             $type =  "branchlead";
        } elseif (! $address->assignedToBranch) {
            $type =  "lead";
        } else {
            $type =  "lead";
        }
        return $type;
    }
}
