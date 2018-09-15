<?php

namespace App\Http\Controllers;

use App\Construction;
use Illuminate\Http\Request;
use GuzzleHttp;


class ConstructionController 
{
    public $construct;
    protected $url = 'https://api.constructionmonitor.com/v1/';
    public function __construct(Construction $construct){
        $this->construct = $construct;
    }
    public function index()
    {
        $projects = array();
        return response()->view('construct.index',compact('projects'));
    }

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
    public function search(Request $request){
 
        $data = $request->except('_token');
        $geoCode = app('geocoder')->geocode($data['address'])->get();
        $data['location'] =$this->construct->getGeoCode($geoCode);

        \Session::put('geo', $data['location']);
        if($request->get('view')=='list'){
            $projects = $this->getProjectData($data);
            $data['view']='list';
            return response()->view('construct.index',compact('projects','data'));
        }else{
            $data['lat']=$data['location']['lat'];
            $data['lng']=$data['location']['lng'];
            $data['latlng']= $data['lat'].":".$data['lng'];
            $data['datalocation']=route('construction.api',['distance'=>$data['distance'],'latlng'=>$data['latlng']]);
            $data['zoomLevel']=12;
            $data['view']='map';
            return response()->view('construct.map',compact('data'));
        }
        
    }

    public function map($distance,$latlng){
        $data['distance']= $distance;
        $location = explode(":",$latlng);
        $data['location']['lat']=$location[0];
        $data['location']['lng']=$location[1];
        $projects = $this->getProjectData($data);

        return response()->view('construct.xml',compact('projects'));


    }
    public function show($id)
    {

        $client = new GuzzleHttp\Client();
        $res = $client->request('get', $this->url .'permits/'.$id, [
            'auth' => ['hamilton@elaconsultinggroup.com','e7f32326edc8136cf60d34a3cc0674ae'
            ]
        ]);

        
        $collection = collect(json_decode($res->getBody(), true));
        $project = $collection['hits']['hits'][0]['_source'];
       
        
        dd($project);
        return response()->view('construct.show',compact('project'));
    }

    private function getProjectData($data){
        $client = new GuzzleHttp\Client();
       
        $res = $client->request('post', $this->url .'permits/', [
            'auth' => ['hamilton@elaconsultinggroup.com','e7f32326edc8136cf60d34a3cc0674ae'
            ], ['decode_content' => 'gzip'],'form_params'=>$this->getRequestBody($data)


        ]);
 
        $collection = collect(json_decode($res->getBody(), true));

       return $collection['hits']['hits'];
    }
}
