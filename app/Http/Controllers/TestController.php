<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    
    public  $states =
            ["CA"=> array(
                  "BC" => "British Columbia",
                  "ON" => "Ontario",
                  "NF" => "Newfoundland",
                  "NS" => "Nova Scotia",
                  "PE" => "Prince Edward Island",
                  "NB" => "New Brunswick",
                  "QC" => "Quebec",
                  "MB" => "Manitoba",
                  "SK" => "Saskatchewan",
                  "AB" => "Alberta",
                  "NT" => "Northwest Territories",
                  "NU" => "Nunavut",
                  "YT" => "Yukon Territory"
                ),
            "US"=> array(
                'AL'=>'ALABAMA',
                'AK'=>'ALASKA',
                'AS'=>'AMERICAN SAMOA',
                'AZ'=>'ARIZONA',
                'AR'=>'ARKANSAS',
                'CA'=>'CALIFORNIA',
                'CO'=>'COLORADO',
                'CT'=>'CONNECTICUT',
                'DE'=>'DELAWARE',
                'DC'=>'DISTRICT OF COLUMBIA',
                'FM'=>'FEDERATED STATES OF MICRONESIA',
                'FL'=>'FLORIDA',
                'GA'=>'GEORGIA',
                'GU'=>'GUAM GU',
                'HI'=>'HAWAII',
                'ID'=>'IDAHO',
                'IL'=>'ILLINOIS',
                'IN'=>'INDIANA',
                'IA'=>'IOWA',
                'KS'=>'KANSAS',
                'KY'=>'KENTUCKY',
                'LA'=>'LOUISIANA',
                'ME'=>'MAINE',
                'MH'=>'MARSHALL ISLANDS',
                'MD'=>'MARYLAND',
                'MA'=>'MASSACHUSETTS',
                'MI'=>'MICHIGAN',
                'MN'=>'MINNESOTA',
                'MS'=>'MISSISSIPPI',
                'MO'=>'MISSOURI',
                'MT'=>'MONTANA',
                'NE'=>'NEBRASKA',
                'NV'=>'NEVADA',
                'NH'=>'NEW HAMPSHIRE',
                'NJ'=>'NEW JERSEY',
                'NM'=>'NEW MEXICO',
                'NY'=>'NEW YORK',
                'NC'=>'NORTH CAROLINA',
                'ND'=>'NORTH DAKOTA',
                'MP'=>'NORTHERN MARIANA ISLANDS',
                'OH'=>'OHIO',
                'OK'=>'OKLAHOMA',
                'OR'=>'OREGON',
                'PW'=>'PALAU',
                'PA'=>'PENNSYLVANIA',
                'PR'=>'PUERTO RICO',
                'RI'=>'RHODE ISLAND',
                'SC'=>'SOUTH CAROLINA',
                'SD'=>'SOUTH DAKOTA',
                'TN'=>'TENNESSEE',
                'TX'=>'TEXAS',
                'UT'=>'UTAH',
                'VT'=>'VERMONT',
                'VI'=>'VIRGIN ISLANDS',
                'VA'=>'VIRGINIA',
                'WA'=>'WASHINGTON',
                'WV'=>'WEST VIRGINIA',
                'WI'=>'WISCONSIN',
                'WY'=>'WYOMING'
            ),
            ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function form(){
        return response()->view('test.index');
    }

    public function select(Request $request){
        $cid = $request->get('country');
       return response()->response()->json($this->states[$cid]);
        //return response()->json($this->states[$cid],201);
       

    }

    public function send(Request $request){
        dd($request->all());
    }
}
