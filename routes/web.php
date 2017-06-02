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
	Route::get('/error',function(){
		Bugsnag::notifyError('ErrorType', 'Test Error');
	});


Route::auth();

Route::get('/home', 'HomeController@index');

Route::group(['middleware' => 'auth'], function () {
   
	#User settings
		Route::get('/user/settings',['as'=>'profile','uses'=>'UsersController@settings']);

	#Companies
		Route::resource('company', 'CompaniesController',['only' => ['index', 'show']]);
		Route::get('/company/{companyId}/state/{state?}', ['as'=>'company.state','uses'=>'CompaniesController@state']);
		Route::post('/company/stateselect', ['as'=>'company.stateselect','uses'=>'CompaniesController@stateselect']);	
		Route::get('/company/{companyId}/statemap/{state}', ['as'=>'company.statemap','uses'=>'CompaniesController@statemap']);
		
		Route::get('/company/vertical/{vertical}', ['as'=>'company.vertical','uses'=>'CompaniesController@vertical']);
		Route::get('/company/{companyId}/segment/{segment}', ['as'=>'company.segment','uses'=>'CompaniesController@segment']);
		Route::post('company/filter',['as'=>'company.filter','uses'=>'CompaniesController@filter']);
	#Locations
		Route::resource('location','LocationsController',['only' => ['index', 'show']]);
		Route::get('location/{locationId}/branches', ['as' => 'assign.location', 'uses' => 'LocationsController@getClosestBranch']);
		
		Route::get('location/{locationId}/branchmap', ['as' => 'nearby.location', 'uses' => 'LocationsController@getClosestBranchMap']);
		Route::get('location/shownearby', ['as' => 'shownearby.location', 'uses' => 'LocationsController@showNearbyLocations']);
		Route::get('location/nearby', ['as' => 'nearby/location', 'uses' => 'LocationsController@mapNearbyLocations']);
	
	#AccountTypes
		Route::resource('accounttype','AccounttypesController',	['only' => ['index', 'show']]);
		
	#ServiceLines
		Route::get('serviceline/{id}/{type?}',['as'=>'serviceline.accounts','uses'=>'ServicelinesController@show']);
		Route::resource('serviceline','ServicelinesController',	['only' => ['index', 'show']]);
	
	
	#News
		//Route::resource('news', 'NewsController',  ['only' => ['index', 'show')));
		Route::get('news', ['as'=>'news.index', 'uses'=>'NewsController@index']);
		Route::get('news/{slug}', ['as'=>'news.show', 'uses'=>'NewsController@show']);		
			
		
	#Branches
		
		Route::post('/branches/state', ['as'=>'branches.state','uses'=>'BranchesController@state']);
		Route::post('/branches/statemap', ['as'=>'branches.statemap','uses'=>'BranchesController@statemap']);
		Route::get('/branches/state/{state?}', 'BranchesController@state');
		Route::get('/branches/statemap/{state?}', 'BranchesController@statemap');
		Route::get('/branches/map', ['as'=>'branches.map', 'uses'=>'BranchesController@mapall']);
		Route::resource('branches','BranchesController',['only' => ['index', 'show']]);
		
		Route::get('/branch/{branchId}/map', 'BranchesController@map');
		/*Route::get('api/location/branchnearby/{locationId}',function(){
			dd('wtf');
		});*/
		
		Route::get('branches/{branchId}/shownearby',['as' => 'shownearby.branch', 'uses' => 'BranchesController@showNearbyBranches']);
		Route::get('branches/{state}/showstate', ['as' => 'showstate.branch','uses' => 'BranchesController@getStateBranches']);
		Route::get('branches/{branchId}/nearby',['as' => 'nearby.branch', 'uses' => 'BranchesController@getNearbyBranches']);
		Route::get('branches/{branchId}/locations',['as' => 'branch.locations', 'uses' => 'BranchesController@getLocationsServed']);
		Route::get('branches/{branchId}/showlist',['as' => 'showlist.locations', 'uses' => 'LocationsController@listNearbyLocations']);
		Route::get('branches/{branchId}/salesteam',['as' => 'showlist.salesteam', 'uses' => 'BranchesController@showSalesTeam']);
		Route::get('branches/managed/{mgrId}',['as'=>'managed.branch', 'uses'=>'BranchesController@getMyBranches']);
	
	#Regions
		Route::resource('region','RegionsController',['only' => ['index', 'show']]);
	
	
		
	#People
		
		Route::get('person/{personId}/showmap', ['as'=>'showmap.person', 'uses'=>'PersonsController@showmap']);
		Route::get('people/map', ['as'=>'person.map', 'uses'=>'PersonsController@map']);
		
		Route::get('geocode/people',['as'=>'person.geocode','uses'=>'PersonsController@geoCodePersons']);
		Route::resource('person','PersonsController',['only' => ['index', 'show']]);
	#Comments
		Route::resource('comment','CommentsController');
	
	# Sales organization
		Route::get('salesorg/{person?}',['as'=>'salesorg','uses'=>'SalesOrgController@getSalesBranches']);
		Route::get('salesorg/{person}/list',['as'=>'salesorg.list','uses'=>'SalesOrgController@getSalesOrgList']);
		Route::get('salesorg/coverage',['as'=>'salescoverage','uses'=>'SalesOrgController@salesCoverageMap']);
		Route::get('branch/{branchId}/salesteam',array('as' => 'branch.salesteam', 'uses' => 'BranchesController@showSalesTeam'));

	# Sales organization 
		Route::get('saleslead/{id}/accept',['as'=>'saleslead.accept','uses'=>'SalesLeadsController@accept']);
		Route::resource('salesleads','SalesLeadsController');
	#Notes
		
		Route::get('notes/{noteId}/delete',['as' => 'delete/note', 'uses' => 'NotesController@destroy']);
		Route::get('mynotes',['as'=>'mynotes','uses'=>'NotesController@mynotes']);
		
		Route::get('exportlocationnotes/{companyID}', ['as'=>'exportlocationnotes','uses'=>'PersonsController@exportManagerNotes']);
		Route::resource('notes','NotesController');	
	#Geocoding
		
		Route::post('findme',['as'=>'findme','uses'=>'GeoCodingController@findMe']);
		Route::get('findme',['as'=>'findme','uses'=>'MapsController@findme']);
		
	# Sales Notes
		Route::get('salesnotes/{companyId}',['as'=>'salesnotes','uses'=>'SalesNotesController@show']);
		Route::get('salesnotes/print/{companyId}',['as'=>'salesnotes/print','uses'=>'SalesNotesController@printSalesNotes']);

	
	
	# Watch List	
		Route::get('watch',['as'=>'watch', 'uses'=>'WatchController@index']);
		
		Route::get('watch/add/{locationId}',['as'=>'watch.add', 'uses'=>'WatchController@create']);
		Route::get('watch/delete/{locationID}',['as'=>'watch.delete', 'uses'=>'WatchController@destroy']);
		Route::get('watchexport',['as'=>'watchexport', 'uses'=>'WatchController@export']);
		
		Route::get('watchmap',['as'=>'watchmap','uses'=>'WatchController@showwatchmap']);
		Route::get('watchexport',['as'=>'company.watchexport', 'uses'=>'PersonsController@companywatchexport']);

	# Sales organization
		Route::get('salesorg/{person?}',['as'=>'salesorg','uses'=>'SalesOrgController@getSalesBranches']);
		Route::get('salesorg/coverage',['as'=>'salescoverage','uses'=>'SalesOrgController@salesCoverageMap']);
		
	##Managers
		Route::get('manage/account',['as'=>'managers.view','uses'=>'PersonsController@manager']);
		Route::post('manage/account',['as'=>'managers.view','uses'=>'PersonsController@selectaccounts']);
		Route::get('locationnotes/{companyID}',['as'=>'locationnotes.show','uses'=>'PersonsController@showManagerNotes']);
	## Sales Resources
		Route::get('resources',['as'=>'resources.view','uses'=>'WatchController@getCompaniesWatched']);
		#Sales Campaigns
		
		Route::get('campaigns',['as'=>'salescampaigns','uses'=>'SalesActivityController@mycampaigns']);
		Route::resource('salesactivity','SalesActivityController',
			['only' => ['show']]);
	
	#AJAX Links
		Route::get('api/company/{companyId}/statemap/{state}', ['as'=>'company.statemap','uses'=>'LocationsController@getStateLocations']);

		Route::get('api/news/nonews','NewsController@noNews');
		Route::get('api/news/setnews','NewsController@setNews'); 

		Route::get('api/branch/map', ['as'=>'branch/map', 'uses'=>'BranchesController@getAllbranchmap']);
		Route::get('api/branch/statemap/{state?}', ['as'=>'branch/statemap', 'uses'=>'BranchesController@getStateBranches']);
		Route::get('api/location/{locationId}/branchnearby',['as'=>'shownearby.branchlocation','uses' => 'MapsController@getLocationsPosition']);

	#Maps		
		Route::get('api/mylocalbranches/{distance}/{latLng}', ['as' => 'map.mybranches', 'uses' => 'MapsController@findLocalBranches']);
		
		Route::get('api/myAccountsList/{distance}/{latLng}', ['as' => 'list.myaccounts', 'uses' => 'MapsController@findLocalAccounts']);

		Route::get('api/mylocalaccounts/{distance}/{latLng}/{companyId?}', ['as' => 'map.mylocations', 'uses' => 'MapsController@findLocalAccounts']);
		
		Route::get('api/mybranchList/{distance}/{latLng}', ['as' => 'list.mybranches', 'uses' => 'MapsController@findLocalBranches']);

		Route::get('api/people/map', ['as'=>'salesmap', 'uses'=>'PersonsController@getMapLocations']);

		Route::post('api/note/post',['as'=>'postNewNote','uses'=>'NotesController@store']);
		Route::get('api/note/get',['as'=>'addNewNote','uses'=>'NotesController@store']);

		Route::get('api/geo',['as'=>'geo','uses'=>'GeoCodingController@index']);

		Route::get('api/watchupdate',['as'=>'api.watchupdate', 'uses'=>'WatchController@watchupdate']);

		Route::get('api/watchmap',['as'=>'api.watchmap','uses'=>'WatchController@watchmap']);

		Route::post('api/advancedsearch',['as'=>'setSearch','uses'=>'SearchFiltersController@setSessionSearch']);	
		Route::get('documents/select',['as'=>'documents.select','uses'=>'DocumentsController@select']);
		Route::post('documents/select',['as'=>'documents.select','uses'=>'DocumentsController@getDocuments']);
		Route::get('/rank',['as'=>'api.rank','uses'=>'DocumentsController@rank']);
    	Route::get('watchedby/{id}',['as'=>'watchedby','uses'=>'DocumentsController@watchedby']);
    	Route::get('documents/{id}',['as'=>'documents.show','uses'=>'DocumentsController@show']);
});

/** ------------------------------------------
 *  Admin Routes 
 *  ------------------------------------------
 */ 


Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function()
{

     
    # # User Management
		
		Route::get('cleanse',['as'=>'users.cleanse','uses'=>'Admin\AdminUsersController@cleanse']);
		Route::get('users/import',['as'=>'admin.users.import', 'uses'=>'Admin\AdminUsersController@import']);
		Route::post('users/bulkimport',['as'=>'admin.users.bulkimport', 'uses'=>'Admin\AdminUsersController@bulkImport']);
		Route::get('users/{user}/purge',['as'=>'users.purge','uses'=> 'Admin\AdminUsersController@destroy']);
		Route::get('users/serviceline/{servicelineId}', ['as'=>'serviceline.user','uses'=>'Admin\AdminUsersController@index']);

		Route::resource('users', 'Admin\AdminUsersController');  


	  # User Role Management
		Route::get('roles/{role}/purge',['as'=>'roles.purge','uses'=>'Admin\AdminRolesController@purge']);
		Route::resource('roles','Admin\AdminRolesController');
	    #  Permissions 
		Route::get('permissions/{permission}/purge',['as'=>'permissions.purge','uses'=>'Admin\AdminPermissionsController@purge']);
		Route::resource('permissions','Admin\AdminPermissionsController');
     

    # Documents
    	Route::resource('documents','DocumentsController');
    	Route::get('documents/{document}/purge',['as'=>'documents.purge','uses'=>'DocumentsController@destroy']);


	#Locations
		Route::resource('locations','LocationsController');
		Route::post('locations/bulkimport', ['as'=>'locations.import', 'uses'=>'LocationsController@bulkImport']);
		Route::get('locationnotes',['as'=>'locations.notes', 'uses'=>'LocationsController@locationnotes']);
		Route::get('api/geocode',['as'=>'api.geocode','uses'=>'LocationsController@bulkGeoCodeLocations']);
	
	#Companies
		Route::get('companies/export', ['as'=>'companies.export', 'uses'=>'CompaniesController@export']);
		Route::post('companies/export', ['as'=>'companies.locationsexport', 'uses'=>'CompaniesController@locationsExport']);
		Route::get('companies/download', ['as'=>'companies.download','uses'=>'CompaniesController@exportAccounts']);
		Route::get('company/{companyId}/export',['as'=>'company.export','uses'=>'WatchController@companyexport']);
		Route::post('company/filter',['as'=>'company.filter','uses'=>'CompaniesController@filter']);
		/* deprecated
			Used to assign locations to branches
		 Route::get('location/{locationId}/assign', ['as' => 'assign.location', 'uses' => 'LocationsController@getClosestBranch']);
		*/
	
	
	#Branches
		Route::get('branches/import', ['as'=>'branches.import', 'uses'=>'BranchesController@import']);
		Route::get('branches/export', ['as'=>'branches.export', 'uses'=>'BranchesController@export']);
		Route::post('branches/bulkimport', ['as'=>'admin.branches.bulkimport', 'uses'=>'BranchesController@branchImport']);
		Route::get('geocode', ['as'=>'admin.branches.geocode', 'uses'=>'BranchesController@geoCodeBranches']);
		Route::get('branchmap', ['as'=>'admin.branches.genmap', 'uses'=>'BranchesController@rebuildBranchMap']);
		Route::resource('branches','BranchesController',['except'=>['index','show']]);
		
		
	#Howtofields	
		Route::resource('howtofields','HowtofieldsController');
		Route::get('howtofields/{fieldId}/delete', ['as'=>'howtofield.delete', 'uses'=>'HowtofieldsController@destroy']);
	
	#People
		Route::get('person/import',['as'=>'person.bulkimport', 'uses'=>'PersonsController@import']);
		Route::post('person/import',['as'=>'person.import', 'uses'=>'PersonsController@processimport']);
		Route::get('person/export', ['as'=>'person.export', 'uses'=>'PersonsController@export']);

	
	#ServiceLines
	
		Route::get('serviceline/{servicelineId}/delete', ['as'=>'serviceline.delete', 'uses'=>'ServicelinesController@destroy']);
		Route::resource('serviceline','ServicelinesController');
	#Leads
		Route::get('lead/address',['as'=>'lead.address','uses'=>'LeadsController@address']);
		Route::post('lead/find',['as'=>'lead.find','uses'=>'LeadsController@find']);
		Route::get('leads/batch',['as'=>'batchimport','uses'=>'LeadsController@batchImport']);
		Route::post('leads/batch',['as'=>'leads.batch','uses'=>'LeadsController@leadImport']);
		Route::get('leads/{id}/purge',['as'=>'leads.purge','uses'=>'LeadsController@destroy']);
		Route::resource('leads','LeadsController');
	
	#LeadSource
		Route::get('leadsource/{id}/purge',['as'=>'leadsource.purge','uses'=>'LeadSourceController@destroy']);
		Route::resource('leadsource','LeadSourceController');

	# Lead Status
		Route::get('leadstatus/{id}/purge',['as'=>'leadstatus.purge','uses'=>'LeadStatusController@destroy']);
	 	Route::resource('leadstatus','LeadStatusController');	

	#Salesnotes
		
		Route::resource('salesnotes','SalesNotesController');
		//Route::post('salesnotes/create',['as'=>'salesnotes.postcreate','uses'=>'CompaniesController@createSalesNotes']);
		Route::get('salesnotes/create/{companyId}',['as'=>'salesnotes.create','uses'=>'SalesNotesController@createSalesNotes']);
		//Route::post('salesnotes/store/{companyID}',['as'=>'salesnotes.store','uses'=>'CompaniesController@storeSalesNotes']);
		
		
		Route::get('salesnotes/filedelete/{file}', ['as'=>'salesnotes.filedelete', 'uses'=>'SalesNotesController@filedelete']);
	
		# Sales Process
			Route::get('process/{process}/purge',['as'=>'process.purge','uses'=>'SalesProcessController@destroy']);
			Route::resource('process','SalesProcessController');

		# Sales Activity
			Route::get('salesactivity/{activity}/purge',['as'=>'salesactivity.purge','uses'=>'SalesActivityController@destroy']);
			Route::resource('salesactivity','SalesActivityController',['except' => ['show']]);

			Route::get('campaigndocs/{id}',['as'=>'salesdocuments.index','uses'=>'SalesActivityController@getSalesActivity']);

			Route::get('campaign/{id}/announce',['as'=>'campaign.announce','uses'=>'SalesActivityController@announce']);

			Route::post('campaign/{id}/message',['as'=>'sendcampaign.message','uses'=>'SalesActivityController@email']);
			



	#Watchlists
		Route::get('watchlist/{userid}', ['as'=>'watch.mywatchexport', 'uses'=>'WatchController@export']);
	
	# Admin Dashboard
		Route::get('watching/{userid}', ['as'=>'watch.watching', 'uses'=>'WatchController@watching']);
		Route::get('userlogin/{view?}',['as'=>'admin.showlogins', 'uses'=>'Admin\AdminDashboardController@logins']);
		Route::get('/', ['uses'=>'Admin\AdminDashboardController@dashboard']);
	
	#Comments
		Route::get('comment/download', ['as'=>'comment.download', 'uses'=>'CommentsController@download']);
	
	#News
		Route::resource('news', 'NewsController');
		Route::get('news',['uses'=>'NewsController@admin']);
		Route::get('news/{newsId}/delete', ['as'=>'admin.news.delete', 'uses'=>'NewsController@destroy']);
		Route::post('news/{newsId}', ['as'=>'admin.news.update', 'uses'=>'NewsController@update']);
	
	#Search Filters
		Route::resource('searchfilters','SearchFiltersController');
		Route::get('searchfilters/promote/{filterid}',['as'=>'admin.searchfilter.promote','uses'=>'SearchFiltersController@promote']);
		Route::get('searchfilters/demote/{filterid}',['as'=>'admin.searchfilter.demote','uses'=>'SearchFiltersController@demote']);
		Route::get('filterform','SearchFiltersController@filterForm');
		Route::get('searchfilters/{id}/delete',['as'=>'admin.searchfilter.delete','uses'=>'SearchFiltersController@destroy']);
		Route::get('api/searchfilters/getAccounts',['as'=>'getAccountSegments','uses'=>'SearchFiltersController@getAccountSegments']);
		Route::post('api/searchfilters/postAccounts',['as'=>'postAccountSegments','uses'=>'SearchFiltersController@getAccountSegments']);
		Route::get('about',function(){

			return response()->view('site.about');
		})->name('about');
	# Seeder for relationships with servicelines
		Route::get('seeder',['as'=>'seeder','uses'=>'CompaniesController@seeder']);
		Route::get('apiseeder',['as'=>'apiseeder','uses'=>'UsersController@seeder']);
	
});
