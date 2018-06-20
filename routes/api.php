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
Route::get('/user', function (Request $request) {
    return $request->user();
});
Route::get('watchupdate',['as'=>'api.watchupdate', 'uses'=>'WatchController@watchupdate'])->middleware('auth:api');
Route::get('leadrank',['as'=>'api.lead.rank', 'uses'=>'LeadsController@leadrank'])->middleware('auth:api');
Route::post('test/state',['as'=>'test.state','uses'=>'TestController@select'])->middleware('auth:api');
//Route::post('advancedsearch',['as'=>'setSearch','uses'=>'SearchFiltersController@setSessionSearch'])->middleware('auth:api');