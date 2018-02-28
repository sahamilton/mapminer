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



   public function scopeOldNearby($query, $location, $radius = 250) {
    
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

    $R = 3956;  // earth's mean radius, miles
    //$R = 6371;  // earth's mean radius, km

    // first-cut bounding box (in degrees)
    $maxLat = $location->lat + rad2deg($radius/$R);
    $minLat = $location->lat - rad2deg($radius/$R);
    $maxLng = $location->lng + rad2deg(asin($radius/$R) / cos(deg2rad($location->lat)));
    $minLng = $location->lng - rad2deg(asin($radius/$R) / cos(deg2rad($location->lat)));

     $haversine = "(3956 * acos(cos(radians($location->lat)) 
                     * cos(radians($this->table.lat)) 
                     * cos(radians($this->table.lng) 
                     - radians($location->lng)) 
                     + sin(radians($location->lat)) 
                     * sin(radians($this->table.lat))))";

    $sub = $this->selectSub('id','lat','lng')
                ->whereBetween('lat',[$minLat,$maxLat])
                ->whereBetween('lng',[$minLng,$maxLng]);
    return $query
         //pick the columns you want here.
        ->selectRaw("{$haversine} AS distance")
        ->mergeBindings($sub->getQuery())
        ->whereRaw("{$haversine} < ?", [$radius])
        ->orderBy('distance','ASC');
        


    /*$sql = "Select id,lat,lng,
                   acos(sin($lat)
                   *sin(radians(lat)) 
                   +cos($lat)
                   *cos(radians(lat))
                   *cos(radians(lng)-$lng)) 
                   * $R As D
            From (
                Select id,lat,lng
                From $table
                Where lat Between $minLat And $maxLat
                  And Lon Between $minLon And $maxLon
            ) As FirstCut
            Where acos(sin($lat)
                *sin(radians(lat)) 
                + cos($lat)
                *cos(radians(lat))
                *cos(radians(lng)-$lng)) 
                * $R < $rad
            Order by D
            limit $limit";

    }
    return $sql;*/
    }
}