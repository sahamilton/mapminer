<?php

namespace App\Http\Controllers;

use App\Address;
use App\Branch;
use App\Http\Requests\LeadAddressFormRequest;
use App\Person;
use App\Serviceline;
use Excel;
use Illuminate\Http\Request;

class SalesOrgController extends BaseController
{
    public $distance = 20;
    public $limit = 5;
    public $branch;
    public $person;
    public $salesroles = [4, 5, 6, 7, 8];

    /**
     * [__construct description].
     *
     * @param Branch  $branch  [description]
     * @param Person  $person  [description]
     * @param Address $address [description]
     */
    public function __construct(
        Branch $branch, Person $person, Address $address
    ) {
        $this->person = $person;
        $this->branch = $branch;
        $this->address = $address;

        //$this->person->rebuild();
    }

    /**
     * [index description].
     *
     * @return [type] [description]
     */
    public function index()
    {
        $salesperson = $this->_loadSalesOrgRelations($this->_getSalesLeaders());

        return response()->view('salesorg.salesmanagerlist', compact('salesperson'));
    }

    /**
     * [show description].
     *
     * @param Request $request     [description]
     * @param Person  $salesperson [description]
     *
     * @return [type]               [description]
     */
    public function show(Request $request, Person $salesperson)
    {
      
        
        if ($salesperson->isLeaf()) {
            $salesorg = $this->_loadSalesOrgRelations($salesperson);

            return response()->view('salesorg.map', compact('salesorg'));
        } else {
            $salesteam = $this->_loadSalesOrgRelations($salesperson);
            
            if (request()->has('view') && request('view')=='list') {
                return response()->view('salesorg.salesmanagerlist', compact('salesperson'));
            }

            return response()->view('salesorg.managermap', compact('salesteam'));
        }
    }

    /**
     * [getSalesOrgList description].
     *
     * @param [type] $salesperson [description]
     *
     * @return [type]              [description]
     */
    public function getSalesOrgList($salesperson)
    {
        // this could be combined with getSAlesBranches and
        // refactored to function show

        $salesperson->load('userdetails.roles', 'directReports', 'directReports.userdetails', 'directReports.userdetails.roles', 'reportsTo.userdetails.roles');

        return response()->view('salesorg.salesmanagerlist', compact('salesperson'));
    }

    /**
     * [getSalesBranches description].
     *
     * @param [type] $salesPerson [description]
     *
     * @return [type]              [description]
     */
    public function getSalesBranches($salesPerson = null)
    {

        // if not id then find root salesorg id

        if (! $salesPerson) {
            $salesperson = $this->_getSalesLeaders();
        } else {
            $salesperson = Person::whereId($salesPerson->id)->first();
        }

        // if leaf

        if ($salesperson->isLeaf()) {
            $salesorg = $salesperson->load('userdetails.roles', 'reportsTo', 'reportsTo.userdetails.roles', 'branchesServiced');

            return response()->view('salesorg.map', compact('salesorg'));
        } else {
            $salesteam = $this->_loadSalesOrgRelations($salesperson);

            return response()->view('salesorg.managermap', compact('salesteam'));
        }
    }

    /**
     * [salesCoverageMap description].
     *
     * @return [type] [description]
     */
    public function salesCoverageMap()
    {
        $this->salesCoverageData();

        return response()->make('salesorg.coveragemap');
    }

    /**
     * [_getServicelines description].
     *
     * @param Serviceline $servicelines [description]
     *
     * @return [type]                    [description]
     */
    private function _getServicelines(Serviceline $servicelines)
    {
        $userServiceLines = [];
        foreach ($servicelines as $serviceline) {
            $userServiceLines[] = $serviceline->id;
        }

        return $userServiceLines;
    }

    /**
     * [_getSalesLeaders description].
     *
     * @return [type] [description]
     */
    private function _getSalesLeaders()
    {

        //Note this is hard coded to the first person with the EVP role
        return $this->person->getPersonsWithRole([14])->first();
    }

    /**
     * [noManager description].
     *
     * @return [type] [description]
     */
    public function noManager()
    {
        $people = $this->person->whereNull('reports_to')
            ->with('userdetails', 'userdetails.roles')
            ->get();
        $title = 'Users with no manager';

        return response()->view('admin.users.nomanager', compact('people', 'title'));
    }

    /**
     * [noManagerExport description].
     *
     * @return [type] [description]
     */
    public function noManagerExport()
    {
        Excel::download(
            'NoManagers'.time(), function ($excel) {
                $excel->sheet(
                    'No Managers', function ($sheet) {
                        $people = $this->person->whereNull('reports_to')
                            ->with('userdetails', 'userdetails.roles')
                            ->get();
                        $sheet->loadView('admin.users.nmexport', compact('people'));
                    }
                );
            }
        )->download('csv');
    }

    /**
     * [_loadSalesOrgRelations description].
     *
     * @param Person $salesperson [description]
     *
     * @return [type]              [description]
     */
    private function _loadSalesOrgRelations(Person $salesperson)
    {
        return $salesperson->load('userdetails.roles', 'directReports', 'directReports.userdetails', 'directReports.userdetails.roles', 'reportsTo.userdetails.roles');
    }

    /**
     * [find description].
     *
     * @param LeadAddressFormRequest $request [description]
     *
     * @return [type]                          [description]
     */
    public function find(Request $request)
    {
        $geoCode = app('geocoder')->geocode(request('address'))->get();

        if (! $geoCode or count($geoCode) == 0) {
            return redirect()->back()->withInput()->with('error', 'Unable to Geocode address:'.request('address'));
        } else {
            request()->merge($this->person->getGeoCode($geoCode));
        }
        $data = request()->all();

        if (! request()->has('number')) {
            $data['number'] = 5;
        }

        session()->put('geo', $data);
        $address = new Address($data);

        if ($request->type == 'branch') {
            $branches = $this->branch->nearby($address, $data['distance'], $data['number'])->get();

            return response()->view('salesorg.nearbybranches', compact('data', 'branches'));
        } else {
            $people = $this->person->nearby($address, $data['distance'], $data['number'])->get();

            return response()->view('salesorg.nearbypeople', compact('data', 'people'));
        }
    }
}
