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

Route::get(
    '/user', function (Request $request) {
        return $request->user();
    }
);*/

Route::get('howtofields', ['as'=>'howtofields.reorder', 'uses'=>'HowtofieldsController@reorder']);
Route::get('watchupdate', ['as'=>'api.watchupdate', 'uses'=>'WatchController@watchupdate'])->middleware('auth:api');
Route::get('leadrank', ['as'=>'api.lead.rank', 'uses'=>'LeadsController@leadrank'])->middleware('auth:api');
Route::post('test/state', ['as'=>'test.state','uses'=>'TestController@select'])->middleware('auth:api');
Route::get('/opportunity/toggle', ['as'=>'opportunity.toggle','uses'=>'OpportunityController@toggle']);
Route::post('branch/people', ['as'=>'api.branch.people', 'uses'=>'WebLeadsController@getSalesPeopleofBranch'])->middleware('auth:api');
Route::post('inbound', ['as'=>'inbound.email','uses'=>'InboundMailController@inbound']);
Route::post(
    'project/{id}', 
    ['as'=>'api.project.update',
    'uses'=>'ProjectsController@updateField']
)
->middleware('auth:api');
Route::post(
    'activity/{activity}', 
    ['as'=>'api.note.edit',
    'uses'=>'ActivityController@updateNote']
)
    ->middleware('auth:api');

//Route::post('advancedsearch',['as'=>'setSearch','uses'=>'SearchFiltersController@setSessionSearch'])->middleware('auth:api');
