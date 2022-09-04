<?php

namespace App\Models;

use GuzzleHttp;

class Construction extends Model
{
    use Geocode;
    protected $value = '250000';
    // setup map paramets for store locator
    protected $fillable = ['lat', 'lng', 'id', 'address', 'city', 'state', 'zip', 'position'];

    /**
     * [getMapData description].
     *
     * @param [type] $data [description]
     *
     * @return [type]       [description]
     */
    public function getMapData($data)
    {
        $data['lat'] = $data['location']['lat'];
        $data['lng'] = $data['location']['lng'];
        $data['latlng'] = $data['lat'].':'.$data['lng'];
        $data['datalocation'] = route('construction.api', ['distance'=>$data['distance'], 'latlng'=>$data['latlng']]);
        $data['zoomLevel'] = 12;
        $data['view'] = 'map';

        return $data;
    }

    /**
     * [getMapParameters description].
     *
     * @param [type] $distance [description]
     * @param [type] $latlng   [description]
     *
     * @return [type]           [description]
     */
    public function getMapParameters($distance, $latlng)
    {
        $data['distance'] = $distance;
        $location = explode(':', $latlng);
        $data['location']['lat'] = $location[0];
        $data['location']['lng'] = $location[1];

        return $data;
    }

    /**
     * [getProjectData description].
     *
     * @param [type] $data [description]
     *
     * @return [type]       [description]
     */
    public function getProjectData($data)
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request(
            'post', config('services.cm.url').'permits/', [
            'auth' => [config('services.cm.user'), config('services.cm.secret')],
            [
                'decode_content' => 'gzip', ],
                'form_params'=>$this->_getRequestBody($data),
            ]
        );

        $collection = collect(json_decode($res->getBody(), true));

        return $collection['hits']['hits'];
    }

    // Call API for id resource

    /**
     * [getCompany description].
     *
     * @param [type] $id [description]
     *
     * @return [type]     [description]
     */
    public function getCompany($id)
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request(
            'post',
            config('services.cm.url').'permits/',
            ['auth' => [config('services.cm.user'), config('services.cm.secret')],
            ['decode_content' => 'gzip'],

            'form_params' => $this->_getCompanyRequest($id),

            ]
        );

        $collection = collect(json_decode($res->getBody(), true));

        return $collection['hits']['hits'];
    }

    // Call API for id resource

    /**
     * [getProject description].
     *
     * @param [type] $id [description]
     *
     * @return [type]     [description]
     */
    public function getProject($id)
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request(
            'get',
            config('services.cm.url').'permits/'.$id,
            ['auth' => [config('services.cm.user'), config('services.cm.secret')],
            ['decode_content' => 'gzip'],

            ]
        );

        $collection = collect(json_decode($res->getBody(), true));

        return $collection['hits']['hits'][0]['_source'];
    }

    /**
     * [_getRequestBody description].
     *
     * @param [type] $data [description]
     *
     * @return [type]       [description]
     */
    private function _getRequestBody($data)
    {
        return ['filter'=>'{
                "geo_distance" : {
                    "distance" : "'.$data['distance'].'mi",
                    "location" : {
                        "lat" : '.$data['location']['lat'].' ,
                        "lon" : '.$data['location']['lng'].' 
                    }
                }
            }',
            'must'=>'{"range":{"valuation" :{"gte":'.$this->value.'}}}',
            'sourceExclude'=>'person*,company*,cbsa,jurisdiction,county,area',
            'pageLimit'=>100, ];
    }

    /**
     * [_getCompanyRequest description].
     *
     * @param [type] $id [description]
     *
     * @return [type]     [description]
     */
    private function _getCompanyRequest($id)
    {
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
            'pageLimit'=>100, ];
    }

    /**
     * [makeConstruction description].
     *
     * @param [type] $project [description]
     *
     * @return [type]          [description]
     */
    public function makeConstruction($project)
    {
        if (isset($project['location'])) {
            $data['lat'] = $project['location']['lat'];
            $data['lng'] = $project['location']['lon'];
            $data['id'] = $project['id'];
        } else {
            // try and geocode address
            $geoCode = app('geocoder')->geocode($project['siteaddress'])->get();
            $data = $this->getGeoCode($geoCode);
            $data['id'] = $project['id'];
        }

        return new self($data);
    }
}
