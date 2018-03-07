<?php
namespace App;
trait Geocode
{
   /**


   **/
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


    public function scopeNearby($query,$location,$radius=100){

    $geocode = Geolocation::fromDegrees($location->lat,$location->lng);
    
    $bounding = $geocode->boundingCoordinates($radius,'mi');
  
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
   
   /**  

   **/

    public function locationsNearbyBranches(Company $company,$radius=25,$limit=null){
        //add pagination
        $query ="select 
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
                3956 * acos(cos(radians(locations.lat)) 
                     * cos(radians(branches.lat)) 
                     * cos(radians(branches.lng) 
                     - radians(locations.lng)) 
                     + sin(radians(locations.lat)) 
                     * sin(radians(branches.lat))) as branchdistance
            from locations
            left join branches on (
                3956 * acos(cos(radians(locations.lat)) 
                     * cos(radians(branches.lat)) 
                     * cos(radians(branches.lng) 
                     - radians(locations.lng)) 
                     + sin(radians(locations.lat)) 
                     * sin(radians(branches.lat))) 
                     < branches.radius
                 )  
            where locations.company_id = ?
            order by locid,branchdistance";
          return \DB::select($query,[$company->id]);

    }

    private function haversine($location){
        return "(3956 * acos(cos(radians($location->lat)) 
                     * cos(radians($this->table.lat)) 
                     * cos(radians($this->table.lng) 
                     - radians($location->lng)) 
                     + sin(radians($location->lat)) 
                     * sin(radians($this->table.lat))))";
    }
    
    /*
    Not sure if this is used
    */
    public function distanceBetween($lat1, $lon1, $lat2, $lon2, $unit) {
        
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}