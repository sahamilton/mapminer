<?php
namespace App;
trait Geocode
{
   public function getGeoCode($geoCode){

        if(is_array($geoCode)){
           
                $data['lat'] = $geoCode[0]['latitude'];
                $data['lng'] = $geoCode[0]['longitude'];
                $data['geostatus']=TRUE; 

            }elseif(is_object($geoCode)){
               
                $data['lat'] = $geoCode->first()->getLatitude();
                $data['lng'] = $geoCode->first()->getLongitude();
                $data['geostatus']=TRUE; 


            }else{
              
                $data['lat'] = null;
                $data['lng'] = null;
                $data['geostatus']=FALSE; 

            }

          return $data;
    }
   
}