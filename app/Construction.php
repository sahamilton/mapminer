<?php

namespace App;

use GuzzleHttp;
class Construction extends Model
{
    use Geocode;

    protected $guarded = ['id'];


    public function getMapData($data){
            $data['lat']=$data['location']['lat'];
            $data['lng']=$data['location']['lng'];
            $data['latlng']= $data['lat'].":".$data['lng'];
            $data['datalocation']=route('construction.api',['distance'=>$data['distance'],'latlng'=>$data['latlng']]);
            $data['zoomLevel']=12;
            $data['view']='map';
            return $data;
    }

    public function getMapParameters($distance,$latlng){
    	$data['distance']= $distance;
        $location = explode(":",$latlng);
        $data['location']['lat']=$location[0];
        $data['location']['lng']=$location[1];
        return $data;
    }
    

    // Call API for nearby projects

    public function getProjectData($data){
        $client = new GuzzleHttp\Client();
        $res = $client->request('post', config('services.cm.url') .'permits/', [
            'auth' => [config('services.cm.user'),config('services.cm.secret')], 
            ['decode_content' => 'gzip'],
            'form_params'=>$this->getRequestBody($data)


        ]);
 
        $collection = collect(json_decode($res->getBody(), true));

       return $collection['hits']['hits'];
    }

    // Call API for id resource

    public function getProject($id){
    	$client = new GuzzleHttp\Client();
        $res = $client->request('get', config('services.cm.url') .'permits/'.$id, 
        	['auth' => [config('services.cm.user'),config('services.cm.secret')],
        	['decode_content' => 'gzip'],
        ]);

        $collection = collect(json_decode($res->getBody(), true));
        return $collection['hits']['hits'][0]['_source'];
    }
    /*
		Construct the API query


    */
    private function getRequestBody($data){

      return ['filter'=>'{
                "geo_distance" : {
                    "distance" : "'.$data['distance'].'mi",
                    "location" : {
                        "lat" : '.$data['location']['lat'] .' ,
                        "lon" : '.$data['location']['lng'] .' 
                    }
                }
            }',
                'must'=>'{"range":{"valuation" :{"gte":250000}}}',
                'sourceExclude'=>'person*,company*,cbsa,jurisdiction,county,area',
                'pageLimit'=>100];

     

    }
}
