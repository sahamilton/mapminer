<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Construction extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        return [
            'id' => $this->resource['id'],
            'title' => $this->resource['siteaddresspartial'],
            'address'=> $this->resource['siteaddresspartial'],
            'city' => $this->resource['city'],
            'zipcode' =>$this->resource['zip'],
            'state' => $this->resource['state'],
            'lat' => $this->resource['geo_latitude'],
            'lng' => $this->resource['geo_longitude'],
        ];
    }
}
