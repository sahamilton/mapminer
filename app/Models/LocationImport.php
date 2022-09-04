<?php

namespace App\Models;

use App\Company;
use Illuminate\Database\Eloquent\Model;

class LocationImport extends Imports
{
    public $table = 'addresses';
    public $dontCreateTemp= true;
    public $requiredFields = ['businessname','street','city','state','zip','lat','lng'];
    public $fillable = ['address_id'];
    /**
     * [setDontCreateTemp description]
     * 
     * @param [type] $state [description]
     */
    public function setDontCreateTemp($state)
    {
        $this->dontCreateTemp = $state;
    }
    
}