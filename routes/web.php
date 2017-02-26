<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::auth();  

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');



Route::get('/home', 'HomeController@index');

Route::group(['middleware' => 'auth'], function () {
   


	Route::get('role/{role}/purge',['as'=>'role.purge','uses'=>'RolesController@purge']);
	Route::resource('role','RolesController');

	Route::get('permission/{permission}/purge',['as'=>'permission.purge','uses'=>'PermissionsController@purge']);
	Route::resource('permission','PermissionsController');

	#Companies
		Route::resource('company', 'CompaniesController',array('only' => array('index', 'show')));
		Route::get('/company/{companyId}/state/{state?}', array('as'=>'company.state','uses'=>'CompaniesController@state'));
		Route::post('/company/stateselect', array('as'=>'company.stateselect','uses'=>'CompaniesController@stateselect'));	
		Route::get('/company/{companyId}/statemap/{state}', array('as'=>'company.statemap','uses'=>'CompaniesController@statemap'));
		
		Route::get('/company/vertical/{vertical}', array('as'=>'company.vertical','uses'=>'CompaniesController@vertical'));
		Route::get('/company/{companyId}/segment/{segment}', array('as'=>'company.segment','uses'=>'CompaniesController@segment'));
		Route::post('company/filter',array('as'=>'company.filter','uses'=>'CompaniesController@filter'));
	#Locations
		Route::resource('location','LocationsController',array('only' => array('index', 'show')));
		Route::get('location/{locationId}/branches', array('as' => 'assign.location', 'uses' => 'LocationsController@getClosestBranch'));
		
		Route::get('location/{locationId}/branchmap', array('as' => 'nearby.location', 'uses' => 'LocationsController@getClosestBranchMap'));
		Route::get('location/shownearby', array('as' => 'shownearby.location', 'uses' => 'LocationsController@showNearbyLocations'));
		Route::get('location/nearby', array('as' => 'nearby/location', 'uses' => 'LocationsController@mapNearbyLocations'));
	
	#AccountTypes
		Route::resource('accounttype','AccounttypesController',	array('only' => array('index', 'show')));
		
	#ServiceLines
		Route::get('serviceline/{id}/{type?}',array('as'=>'serviceline.accounts','uses'=>'ServicelinesController@show'));
		Route::resource('serviceline','ServicelinesController',	array('only' => array('index', 'show')));
	
	
	#News
		//Route::resource('news', 'NewsController',  array('only' => array('index', 'show')));
		Route::get('news', array('as'=>'news.index', 'uses'=>'NewsController@index'));
		Route::get('news/{slug}', array('as'=>'news.show', 'uses'=>'NewsController@show'));		
			
		
	#Branches
		
		Route::post('/branch/state', array('as'=>'branch.state','uses'=>'BranchesController@state'));
		Route::post('/branch/statemap', array('as'=>'branch.statemap','uses'=>'BranchesController@statemap'));
		Route::get('/branch/state/{state?}', 'BranchesController@state');
		Route::get('/branch/statemap/{state?}', 'BranchesController@statemap');
		Route::get('/branch/map', array('as'=>'branch.map', 'uses'=>'BranchesController@mapall'));
		Route::resource('branch','BranchesController',array('only' => array('index', 'show')));
		
		Route::get('/branch/{branchId}/map', 'BranchesController@map');
		/*Route::get('api/location/branchnearby/{locationId}',function(){
			dd('wtf');
		});*/
		
		Route::get('branch/{branchId}/shownearby',array('as' => 'shownearby/branch', 'uses' => 'BranchesController@showNearbyBranches'));
		//Route::get('branch/{state}/showstate', array('as' => 'showstate/branch','uses' => 'BranchesController@getStateBranches'));
		Route::get('branch/{branchId}/nearby',array('as' => 'nearby/branch', 'uses' => 'BranchesController@getNearbyBranches'));
		Route::get('branch/{branchId}/locations',array('as' => 'branch/locations', 'uses' => 'BranchesController@getLocationsServed'));
		Route::get('branch/{branchId}/showlist',array('as' => 'showlist/locations', 'uses' => 'LocationsController@listNearbyLocations'));
		Route::get('branch/{branchId}/salesteam',array('as' => 'showlist/salesteam', 'uses' => 'BranchesController@showSalesTeam'));
		Route::get('branch/managed/{mgrId}',array('as'=>'managed/branch', 'uses'=>'BranchesController@getMyBranches'));
	
	#Regions
		Route::resource('region','RegionsController',array('only' => array('index', 'show')));
	
	
		
	#People
		
		Route::get('person/{personId}/showmap', array('as'=>'showmap/person', 'uses'=>'PersonsController@showmap'));
		Route::get('people/map', array('as'=>'person.map', 'uses'=>'PersonsController@map'));
		
		Route::get('geocode/people',['as'=>'person.geocode','uses'=>'PersonsController@geoCodePersons']);
		Route::resource('person','PersonsController',array('only' => array('index', 'show')));
	#Comments
		Route::resource('comment','CommentsController');
	
	# Sales organization
		Route::get('salesorg/{person?}',['as'=>'salesorg','uses'=>'SalesOrgController@getSalesBranches']);
		Route::get('salesorg/{person}/list',['as'=>'salesorg.list','uses'=>'SalesOrgController@getSalesOrgList']);
		Route::get('salesorg/coverage',['as'=>'salescoverage','uses'=>'SalesOrgController@salesCoverageMap']);


	#Notes
		
		Route::get('notes/{noteId}/delete',array('as' => 'delete/note', 'uses' => 'NotesController@destroy'));
		Route::get('mynotes',array('as'=>'mynotes','uses'=>'NotesController@mynotes'));
		
		Route::get('exportlocationnotes/{companyID}', array('as'=>'exportlocationnotes','uses'=>'PersonsController@exportManagerNotes'));
		Route::resource('notes','NotesController');	
	#Geocoding
		
		Route::post('findme',array('as'=>'findme','uses'=>'GeoCodingController@findMe'));
		Route::get('findme',array('as'=>'findme','uses'=>'MapsController@findme'));
		
	# Sales Notes
		Route::get('salesnotes/{companyId}',array('as'=>'salesnotes','uses'=>'SalesNotesController@show'));
		Route::get('salesnotes/print/{companyId}',array('as'=>'salesnotes/print','uses'=>'SalesNotesController@printSalesNotes'));

	# Watch List	
		Route::get('watch',array('as'=>'watch', 'uses'=>'WatchController@index'));
		
		Route::get('watch/add/{locationId}',array('as'=>'watch.add', 'uses'=>'WatchController@create'));
		Route::get('watch/delete/{locationID}',array('as'=>'watch.delete', 'uses'=>'WatchController@destroy'));
		Route::get('watchexport',array('as'=>'watchexport', 'uses'=>'WatchController@export'));
		
		Route::get('watchmap',array('as'=>'watchmap','uses'=>'WatchController@showwatchmap'));
		Route::get('company/watchexport',array('as'=>'company.watchexport', 'uses'=>'PersonsController@companywatchexport'));

	# Sales organization
		Route::get('salesorg/{person?}',['as'=>'salesorg','uses'=>'SalesOrgController@getSalesBranches']);
		Route::get('salesorg/coverage',['as'=>'salescoverage','uses'=>'SalesOrgController@salesCoverageMap']);
		
	##Managers
		Route::get('manage/account',array('as'=>'managers.view','uses'=>'PersonsController@manager'));
		Route::post('manage/account',array('as'=>'managers.view','uses'=>'PersonsController@selectaccounts'));
		Route::get('locationnotes/{companyID}',array('as'=>'locationnotes.show','uses'=>'PersonsController@showManagerNotes'));
	## Sales Resources
		Route::get('resources',['as'=>'resources.view','uses'=>'WatchController@getCompaniesWatched']);

	#AJAX Links
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
