<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Model;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * [_getLocationLatLng description]
     * 
     * @param [type] $latlng [description]
     * 
     * @return [type]         [description]
     */
    protected function getLocationLatLng($latlng)
    {
        $position =explode(":", $latlng);
        $location = new Modell;
        $location->lat = $position[0];
        $location->lng = $position[1];
        return $location;
    }
    
}
