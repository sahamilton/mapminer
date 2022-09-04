<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Construction;
use GuzzleHttp;
use Illuminate\Http\Request;

class ConstructionController extends BaseController
{
    public $construction;
    public $branch;

    /**
     * [__construct description].
     *
     * @param Construction $construction [description]
     * @param Branch       $branch       [description]
     */
    public function __construct(Construction $construction, Branch $branch)
    {
        $this->construction = $construction;
        $this->branch = $branch;
        parent::__construct($construction);
    }

    /**
     * [index description].
     *
     * @return [type] [description]
     */
    public function index()
    {
        $projects = [];

        return response()->view('construct.index', compact('projects'));
    }

    /**
     * [search description].
     *
     * @param Request $request [description]
     *
     * @return [type]           [description]
     */
    public function search(Request $request)
    {
        $data = request()->except('_token');

        $geoCode = app('geocoder')->geocode($data['address'])->get();
        $data['location'] = $this->construction->getGeoCode($geoCode);
        session()->put('geo', $data);

        if ($data['view'] == 'list') {
            $projects = $this->construction->getProjectData($data);

            return response()->view('construct.index', compact('projects', 'data'));
        } else {
            $data = $this->construction->getMapData($data);

            return response()->view('construct.map', compact('data'));
        }
    }

    /**
     * [map description].
     *
     * @param [type] $distance [description]
     * @param [type] $latlng   [description]
     *
     * @return xml          [description]
     */
    public function map($distance, $latlng)
    {
        $data = $this->construction->getMapParameters($distance, $latlng);
        $projects = $this->construction->getProjectData($data);

        return response()->view('construct.xml', compact('projects'));
    }

    /**
     * [show description].
     *
     * @param [type] $id [description]
     *
     * @return [type]     [description]
     */
    public function show($id)
    {
        $project = $this->construction->getProject($id);

        $construction = $this->construction->makeConstruction($project);
        if (! isset($project['location'])) {
            $project['location']['lat'] = $construction->lat;
            $project['location']['lon'] = $construction->lng;
        }

        $branches = $this->branch->nearby($construction);

        return response()->view('construct.show', compact('project', 'branches'));
    }

    /**
     * [company description].
     *
     * @param [type] $id [description]
     *
     * @return [type]     [description]
     */
    public function company($id)
    {
        $company = $this->construction->getCompany($id);

        return response()->view('construct.companyshow', compact('company'));
    }
}
