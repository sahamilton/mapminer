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
                $data['address'] =  $geoCode->first()->getStreetNumber()." " . $geoCode->first()->getStreetName();
                $data['street'] = $data['address'];
                $data['city'] =  $geoCode->first()->getLocality();
                $data['zip'] = $geoCode->first()->getPostalCode();

                if(count($geoCode->first()->getadminLevels())>0){
                    //dd('it does');
                    $data['state'] = $geoCode->first()
                                    ->getadminLevels()
                                    ->first()
                                    ->getCode();
                }else{
                   $data['state']=null;
                   //dd('it doesnt');
                }

               $data['fulladdress'] = $data['address'] .' ' . $data['city']. ' ' . $data['state'] .' ' . $data['zip'];
              

            }else{
              
                $data['lat'] = null;
                $data['lng'] = null;
                $data['geostatus']=FALSE; 

            }

          return $data;
    }


    public function scopeNearby($query,$location,$radius=100,$limit=null){

    $geocode = Geolocation::fromDegrees($location->lat,$location->lng);
    
    $bounding = $geocode->boundingCoordinates($radius,'mi');
   
    $sub = $this->selectSub('id','lat','lng')
                ->whereBetween('lat',[$bounding['min']->degLat,$bounding['max']->degLat])
                ->whereBetween('lng',[$bounding['min']->degLon,$bounding['max']->degLon]);

    $query = $query
        ->select()//pick the columns you want here.
        ->selectRaw("{$this->haversine($location)} AS distance")
        ->mergeBindings($sub->getQuery())
        ->whereRaw("{$this->haversine($location)} < $radius ")
        ->orderBy('distance','ASC')
        ->inRandomOrder();
        if($limit){
            $query = $query->limit($limit);
        }
      
        return $query;
    }
   
   /**  

   **/

    public function locationsNearbyBranches(Company $company,$radius=25,$limit=null){
        //add pagination
        $query ="select 
                locs.id,
                locs.businessname,
                locs.street as locstreet, 
                locs.city as loccity, 
                locs.state as locstate,
                locs.zip as loczip,
                branch.branchname,
                branch.id as branch_id,
                branch.city,
                branch.state,
                branch.phone as branch_phone,
                branchfilter.branchdistance,
                people.id as pid,
                people.depth as depth,
                concat_ws(' ',people.firstname,people.lastname) as repname,
                peepsfilter.peepsdistance,
                people.phone
            from locations locs, 
                branches branch,
                persons people,
                (select 
                        blocs.id as blocid,
                        branches.id as branchid,
                        branches.city ,
                        3956 * acos(cos(radians(blocs.lat)) 
                        * cos(radians(branches.lat)) 
                        * cos(radians(branches.lng) 
                        - radians(blocs.lng)) 
                        + sin(radians(blocs.lat)) 
                        * sin(radians(branches.lat))) as branchdistance

                    from locations blocs
                    left join branches on (
                        3956 * acos(cos(radians(blocs.lat)) 
                        * cos(radians(branches.lat)) 
                        * cos(radians(branches.lng) 
                        - radians(blocs.lng)) 
                        + sin(radians(blocs.lat)) 
                        * sin(radians(branches.lat))) 
                        < branches.radius
                        ) 

                    where blocs.company_id = " . $company->id . "
                    order by blocid,branchdistance) branchfilter,

                (select 
                        plocs.id as plocid,
                        peeps.id as peepid,
                        3956 * acos(cos(radians(plocs.lat)) 
                        * cos(radians(peeps.lat)) 
                        * cos(radians(peeps.lng) 
                        - radians(plocs.lng)) 
                        + sin(radians(plocs.lat)) 
                        * sin(radians(peeps.lat))) as peepsdistance 
                    from locations plocs
                    left join 
                    (select persons.* from persons,role_user,roles
                        where persons.user_id = role_user.user_id 
                        and role_user.role_id = roles.id 
                        and roles.name = 'Sales') peeps
                    on (
                        3956 * acos(cos(radians(plocs.lat)) 
                        * cos(radians(peeps.lat)) 
                        * cos(radians(peeps.lng) 
                        - radians(plocs.lng)) 
                        + sin(radians(plocs.lat)) 
                        * sin(radians(peeps.lat))) 
                        < 100
                    ) 
                    where plocs.company_id = ". $company->id ."
                    order by plocid,peepsdistance) peepsfilter
            where peepsfilter.plocid = branchfilter.blocid
            and locs.id = branchfilter.blocid
            and people.id = peepsfilter.peepid
            and branch.id = branchfilter.branchid
            order by locs.id";

          return \DB::select($query);

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
    
    */
    public function distanceBetween($lat1, $lon1, $lat2, $lon2, $unit=null) {
        
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

    public function getMyPosition(){
        $location = new Location;
            //$limited=$this->limit;
            //we need to test to see if geo is filled
        if ($geo = session()->get('geo') && isset($geo['lat']))
            {
            
                $location->lat = $geo['lat'];
                $location->lng = $geo['lng'];

            }elseif($position = auth()->user()->position()){
                
                $position = explode(",",auth()->user()->position());
                $location->lat =  $position[0];
                $location->lng =  $position[1];

            }else{
                //default to Tacoma
                $location->lat =  '47.25';
                $location->lng =  '-122.44';
            }
        return $location;
    }

    public function distanceFromMe($collection){
        
        $myPosition = $this->getMyPosition();
        return $collection->map(function ($item) use($myPosition){
           $item->distance = $this->distanceBetween($myPosition->lat,$myPosition->lng,$item->lat,$item->lng);
           return $item;
        });
    }

    public function geoCodeAddress(string $address){

        $geoCode = app('geocoder')->geocode($address)->get();
        return $this->getGeoCode($geoCode);
    }
}