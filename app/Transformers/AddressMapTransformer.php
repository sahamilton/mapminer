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
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * return array
     */
    public function transform(Address $address)
    {
        $address->leadtype = $this->_getType($address);
        return $address;
    }
    private function _getType(Address $address) :string
    {
        
        if($address->open_opportunities_count > 0) {
            return "opportunity";
        } elseif ($address->assigned_to_branch_count > 0){
             return "branchlead";
        } elseif (isset($address->isCustomer)){
            return "customer";
        } elseif ($address->assigned_to_branch_count === 0){
            return "lead";
        } else {
            return "lead";
        }
    }
}
