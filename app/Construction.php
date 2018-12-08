<?php

namespace App;

use GuzzleHttp;
class Construction extends Model
{
    use Geocode;
    protected $value = '250000';
	// setup map paramets for store locator 
    protected $fillable = ['lat','lng','id','address','city','state','zip'];
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

    public function getCompany($id){
    	$client = new GuzzleHttp\Client();
        $res = $client->request('post', config('services.cm.url') .'permits/', 
        	['auth' => [config('services.cm.user'),config('services.cm.secret')],
        	['decode_content' => 'gzip'],
            
            'form_params' => $this->getCompanyRequest($id),
         


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
            'must'=>'{"range":{"valuation" :{"gte":'.$this->value.'}}}',
            'sourceExclude'=>'person*,company*,cbsa,jurisdiction,county,area',
            'pageLimit'=>100];

     

    }


    private function getCompanyRequest($id){
        return ['filter'=>'{
                        "nested": {
                            "path": "companylinks",
                            "query": {
                                "match": {
                                    "companylinks.company.id": '.$id.'
                                }
                            }
                        }
                    }',
            'must'=>'{"range":{"valuation" :{"gte":'.$this->value.'}}}',
            'sourceExclude'=>'flatfile,cbsa,jurisdiction,county,area',
            'pageLimit'=>100];
     }

     public function makeConstruction($project){
        if(isset($project['location']))
           { 
                
                $data['lat'] = $project['location']['lat'];
                $data['lng'] = $project['location']['lon'];
                $data['id'] = $project['id'];
                
           }else{
                // try and geocode address
                $geoCode = app('geocoder')->geocode($project['siteaddress'])->get();
                $data =$this->getGeoCode($geoCode);
                $data['id']= $project['id'];
            

           }
           return new Construction($data);
     }
}
