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
   
}