<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => 'auth'], function () {
	Route::get('api//user', function (Request $request) {
	    return $request->user();
	});

	Route::get('api/company/{companyId}/statemap/{state}', array('as'=>'company.statemap','uses'=>'LocationsController@getStateLocations'));

	Route::get('api/news/nonews','NewsController@noNews');
	Route::get('api/news/setnews','NewsController@setNews'); 

	Route::get('api/branch/map', array('as'=>'branch/map', 'uses'=>'BranchesController@getAllbranchmap'));
	Route::get('api/branch/statemap/{state?}', array('as'=>'branch/statemap', 'uses'=>'BranchesController@getStateBranches'));
	Route::get('api/location/{locationId}/branchnearby',['as'=>'shownearby.branchlocation','uses' => 'MapsController@getLocationsPosition']);

	#Maps		
		Route::get('api/mylocalbranches/{distance}/{latLng}', array('as' => 'map.mybranches', 'uses' => 'MapsController@findLocalBranches'));
		
		Route::get('api/myAccountsList/{distance}/{latLng}', array('as' => 'list.myaccounts', 'uses' => 'MapsController@findLocalAccounts'));

		Route::get('api/mylocalaccounts/{distance}/{latLng}/{companyId?}', array('as' => 'map.mylocations', 'uses' => 'MapsController@findLocalAccounts'));
		
		Route::get('api/mybranchList/{distance}/{latLng}', array('as' => 'list.mybranches', 'uses' => 'MapsController@findLocalBranches'));

		Route::get('api/people/map', array('as'=>'salesmap', 'uses'=>'PersonsController@getMapLocations'));

		Route::post('api/note/post',array('as'=>'postNewNote','uses'=>'NotesController@store'));
		Route::get('api/note/get',array('as'=>'addNewNote','uses'=>'NotesController@store'));

		Route::get('api/geo',array('as'=>'geo','uses'=>'GeoCodingController@index'));

		Route::get('api/watchupdate',array('as'=>'api.watchupdate', 'uses'=>'WatchController@watchupdate'));

		Route::get('api/watchmap',array('as'=>'api.watchmap','uses'=>'WatchController@watchmap'));

		Route::post('api/advancedsearch',array('as'=>'setSearch','uses'=>'SearchFiltersController@setSessionSearch'));
});