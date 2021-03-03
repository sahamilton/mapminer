<?php

namespace App\Http\Controllers;

use App\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * [_getLocationLatLng description].
     *
     * @param [type] $latlng [description]
     *
     * @return [type]         [description]
     */
    protected function getLocationLatLng($latlng)
    {
        $position = explode(':', $latlng);
        $location = new Modell;
        $location->lat = $position[0];
        $location->lng = $position[1];

        return $location;
    }
}
