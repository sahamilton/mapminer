<?php
namespace App;
trait Geocode
{
   public function getGeoCode($geoCode){
        
        if(is_array($geoCode) && count($geoCode)>0){
           
                $data['lat'] = $geoCode[0]['latitude'];
                $data['lng'] = $geoCode[0]['longitude'];
                $data['geostatus']=TRUE; 

            }elseif(is_object($geoCode) && count($geoCode)>0){

                $data['lat'] = $geoCode->first()->getCoordinates()->getLatitude();
                $data['lng'] = $geoCode->first()->getCoordinates()->getLongitude();
                $data['geostatus']=TRUE;
                $data['city'] =  $geoCode->first()->getLocality();
                $adminLevels = $geoCode->first()->getadminLevels();
                $data['state'] =  $adminLevels->first()->getCode();


            }else{
              
                $data['lat'] = null;
                $data['lng'] = null;
                $data['geostatus']=FALSE; 

            }

          return $data;
    }



   public function scopeBoundedNearby($query, $location, $radius = 250) {
    
     $haversine = "(3956 * acos(cos(radians($location->lat)) 
                     * cos(radians($this->table.lat)) 
                     * cos(radians($this->table.lng) 
                     - radians($location->lng)) 
                     + sin(radians($location->lat)) 
                     * sin(radians($this->table.lat))))";
    return $query
        ->select() //pick the columns you want here.
        ->selectRaw("{$haversine} AS distance")
        ->whereRaw("{$haversine} < ?", [$radius])
        ->orderBy('distance','ASC');
     
        
    }




    public function scopeNearby($query,$location,$radius=100){

    $geocode = Geolocation::fromDegrees($location->lat,$location->lng);
    
    $bounding = $geocode->boundingCoordinates($radius,'mi');
  
   /* $haversine = "(3956 * acos(cos(radians($location->lat)) 
                     * cos(radians($this->table.lat)) 
                     * cos(radians($this->table.lng) 
                     - radians($location->lng)) 
                     + sin(radians($location->lat)) 
                     * sin(radians($this->table.lat))))";*/

    $sub = $this->selectSub('id','lat','lng')
                ->whereBetween('lat',[$bounding['min']->degLat,$bounding['max']->degLat])
                ->whereBetween('lng',[$bounding['min']->degLon,$bounding['max']->degLon]);

    return $query
        ->select()//pick the columns you want here.
        ->selectRaw("{$this->haversine($location)} AS distance")
        ->mergeBindings($sub->getQuery())
        ->whereRaw("{$this->haversine($location)} < $radius ")
        ->orderBy('distance','ASC');
    }

    public function locationsNearbyBranches(Company $company,$radius=25){

     return \DB::select('select 
                locations.id as locid,
                locations.businessname,
                locations.street as locstreet, 
                locations.city as loccity, 
                locations.state as locstate,
                locations.zip as loczip,
                branches.id as branchid, 
                branchname,
                branches.city as branchcity,
                branches.state as branchstate,
            (3956 * acos(cos(radians(locations.lat)) 
                                 * cos(radians(branches.lat)) 
                                 * cos(radians(branches.lng) 
                                 - radians(locations.lng)) 
                                 + sin(radians(locations.lat)) 
                                 * sin(radians(branches.lat)))) as branchdistance
            from locations
            left join branches on 
                
                               3956 * acos(cos(radians(locations.lat)) 
                                 * cos(radians(branches.lat)) 
                                 * cos(radians(branches.lng) 
                                 - radians(locations.lng)) 
                                 + sin(radians(locations.lat)) 
                                 * sin(radians(branches.lat)))
                                 < 25
                               
                              
            where locations.company_id = ?
            order by locid, branchdistance',[$company->id]);

}



    public function scopeAllNearby($query,$company,$radius){
      /*return $query->select()
      ->selectRaw('
            (3956 * acos(cos(radians(locations.lat)) 
                                 * cos(radians(branches.lat)) 
                                 * cos(radians(branches.lng) 
                                 - radians(locations.lng)) 
                                 + sin(radians(locations.lat)) 
                                 * sin(radians(branches.lat)))) as branchdistance')
       ->from('locations')
        ->join('branches on 
                               (3956 * acos(cos(radians(locations.lat)) 
                                 * cos(radians(branches.lat)) 
                                 * cos(radians(branches.lng) 
                                 - radians(locations.lng)) 
                                 + sin(radians(locations.lat)) 
                                 * sin(radians(branches.lat)))) < $radius)')
        
        ->orderBy('locations.id')
        ->orderBy('branchdistance');*/





    }

    private function haversine($location){
        return "(3956 * acos(cos(radians($location->lat)) 
                     * cos(radians($this->table.lat)) 
                     * cos(radians($this->table.lng) 
                     - radians($location->lng)) 
                     + sin(radians($location->lat)) 
                     * sin(radians($this->table.lat))))";
    }
}