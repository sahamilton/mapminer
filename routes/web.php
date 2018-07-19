<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------

| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
	Route::get('/', ['as'=>'welcome',function () {

		    return view('welcome');

	}]);
Route::get('/testerror', function () {
    throw new Exception('Example exception!');
});
/*
	
	Route::get('/error',function(){
		Bugsnag::notifyError('ErrorType', 'Test Error');
	});
*/

Route::auth();

Route::get('/home', 'HomeController@index');

Route::group(['middleware' => 'auth'], function () {
   	
	#About
		Route::get('about',['as'=>'about','uses'=>'AdminAboutController@getInfo']);

   	#AccountTypes
		Route::resource('accounttype','AccounttypesController',	['only' => ['index', 'show']]);

	#Branches
		Route::get('/branches/{state}/state/', ['as'=>'branches.statelist','uses'=>'BranchesController@state']);
		Route::post('/branches/state', ['as'=>'branches.state','uses'=>'BranchesController@state']);
		Route::get('/branches/{state}/statemap', ['as'=>'branches.showstatemap','uses'=>'BranchesController@statemap']);
		Route::post('/branches/statemap', ['as'=>'branches.statemap','uses'=>'BranchesController@statemap']);
		Route::get('/branch/{branchId}/map', ['as'=>'branch.map','uses'=>'BranchesController@map']);
		Route::get('/branches/map', ['as'=>'branches.map', 'uses'=>'BranchesController@mapall']);
		Route::get('branches/{branchId}/shownearby',['as' => 'shownearby.branch', 'uses' => 'BranchesController@showNearbyBranches']);
		Route::get('branches/{state}/showstate', ['as' => 'showstate.branch','uses' => 'BranchesController@getStateBranches']);
		Route::get('branches/{branchId}/nearby',['as' => 'nearby.branch', 'uses' => 'BranchesController@getNearbyBranches']);
		Route::get('branches/{branchId}/locations',['as' => 'branch.locations', 'uses' => 'BranchesController@getLocationsServed']);
		Route::get('branches/{branchId}/showlist',['as' => 'showlist.locations', 'uses' => 'LocationsController@listNearbyLocations']);
		Route::get('branches/{branchId}/salesteam',['as' => 'showlist.salesteam', 'uses' => 'BranchesController@showSalesTeam']);
		Route::get('branches/managed/{mgrId}',['as'=>'managed.branch', 'uses'=>'BranchesController@getMyBranches']);
		Route::get('branches/managedmap/{mgrId}',['as'=>'managed.branchmap', 'uses'=>'BranchesController@mapMyBranches']);
		Route::resource('branches','BranchesController',['only' => ['index', 'show']]);

	#Comments
		Route::resource('comment','CommentsController');

	#Companies
		Route::post('company/filter',['as'=>'company.filter','uses'=>'CompaniesController@filter']);
		Route::get('/company/{companyId}/state/{state?}', ['as'=>'company.state','uses'=>'CompaniesController@stateselect']);
		Route::post('/company/stateselect', ['as'=>'company.stateselect','uses'=>'CompaniesController@stateselect']);

		Route::get('/company/{companyId}/statemap/{state}', ['as'=>'company.statemap','uses'=>'CompaniesController@statemap']);
		Route::get('/company/vertical/{vertical}', ['as'=>'company.vertical','uses'=>'CompaniesController@vertical']);
		Route::get('/company/{companyId}/segment/{segment}', ['as'=>'company.segment','uses'=>'CompaniesController@show']);
		
		Route::resource('company', 'CompaniesController',['only' => ['index', 'show']]);


	# Contacts
		Route::get('contacts/{id}/vcard',['as'=>'contacts.vcard','uses'=>'LocationContactController@vcard']);
		Route::resource('contacts','LocationContactController');

   	# Documents
		Route::resource('docs','DocumentsController',['only' => ['index', 'show']]);

	#Geocoding

		Route::post('findme',['as'=>'findme','uses'=>'GeoCodingController@findMe']);
		Route::get('findme',['as'=>'findme','uses'=>'MapsController@findme']);

	#Locations

		Route::get('location/{id}/branches', ['as' => 'assign.location', 'uses' => 'LocationsController@getClosestBranch']);
		Route::get('locations/{id}/vcard',['as'=>'locations.vcard','uses'=>'LocationsController@vcard']);
		Route::get('location/{locationId}/branchmap', ['as' => 'nearby.location', 'uses' => 'LocationsController@getClosestBranchMap']);
		Route::get('location/shownearby', ['as' => 'shownearby.location', 'uses' => 'LocationsController@showNearbyLocations']);
		Route::get('location/nearby', ['as' => 'nearby/location', 'uses' => 'LocationsController@mapNearbyLocations']);
		Route::post('location/contact',['as'=>'location.addcontact','uses'=>'LocationContactController@store']);

		Route::resource('locations','LocationsController',['only' => ['show']]);

	#Managers
		Route::get('manage/account',['as'=>'managers.view','uses'=>'ManagersController@manager']);
		Route::post('manage/account',['as'=>'managers.changeview','uses'=>'ManagersController@selectaccounts']);
		Route::get('locationnotes/{companyID}',['as'=>'locationnotes.show','uses'=>'ManagersController@showManagerNotes']);

	#Maps
		Route::get('api/mylocalbranches/{distance}/{latLng}/{limit?}', ['as' => 'map.mybranches', 'uses' => 'MapsController@findLocalBranches']);
		Route::get('api/myAccountsList/{distance}/{latLng}', ['as' => 'list.myaccounts', 'uses' => 'MapsController@findLocalAccounts']);
		Route::get('api/mylocalaccounts/{distance}/{latLng}/{companyId?}', ['as' => 'map.mylocations', 'uses' => 'MapsController@findLocalAccounts']);
		Route::get('api/mybranchList/{distance}/{latLng}', ['as' => 'list.mybranches', 'uses' => 'MapsController@findLocalBranches']);
		Route::get('api/people/map', ['as'=>'salesmap', 'uses'=>'PersonsController@getMapLocations']);
		Route::post('api/note/post',['as'=>'postNewNote','uses'=>'NotesController@store']);
		Route::get('api/note/get',['as'=>'addNewNote','uses'=>'NotesController@store']);
		Route::get('api/geo',['as'=>'geo','uses'=>'GeoCodingController@index']);

	#News
		//Route::resource('news', 'NewsController',  ['only' => ['index', 'show')));
		Route::get('currentnews',['as'=>'currentnews','uses'=>'NewsController@currentNews']);
		//Route::get('news', ['as'=>'news.index', 'uses'=>'NewsController@index']);
		Route::get('news/{slug}', ['as'=>'news.show', 'uses'=>'NewsController@show']);

	#Notes
		Route::get('mynotes',['as'=>'mynotes','uses'=>'NotesController@mynotes']);
		Route::get('exportlocationnotes/{companyID}', ['as'=>'exportlocationnotes','uses'=>'ManagersController@exportManagerNotes']);
		Route::resource('notes','NotesController');

	#People

		Route::get('person/{personId}/showmap', ['as'=>'showmap.person', 'uses'=>'PersonsController@showmap']);
		Route::get('people/map', ['as'=>'person.map', 'uses'=>'PersonsController@map']);
		Route::get('geocode/people',['as'=>'person.geocode','uses'=>'PersonsController@geoCodePersons']);
		Route::get('person/{vertical}/vertical',['as'=>'person.vertical','uses'=>'PersonsController@vertical']);
		Route::resource('person','PersonsController',['only' => ['index', 'show']]);

	# Projects
		Route::get('api/mylocalprojects/{distance}/{latLng}', ['as' => 'map.myprojects', 'uses' => 'ProjectsController@findNearbyProjects']);
		Route::get('projects/{id}/claim',['as'=>'projects.claim','uses'=>'ProjectsController@claimProject']);
		Route::post('project/{id}/close',['as'=>'projects.close','uses'=>'ProjectsController@closeproject']);
		Route::get('projects/myprojects',['as'=>'projects.myprojects','uses'=>'ProjectsController@myProjects']);
		Route::get('projects/download',['as'=>'projects.export','uses'=>'ProjectsController@exportMyProjects']);
		Route::post('project/{id}/transfer',['as'=>'projects.transfer','uses'=>'ProjectsController@transfer']);
		Route::post('projects/contact',['as'=>'projects.addcontact','uses'=>'ProjectsController@addCompanyContact']);
		Route::post('projects/firm',['as'=>'projects.addcontactfirm','uses'=>'ProjectsController@addProjectCompany']);
		Route::resource('projects', 'ProjectsController',['only' => ['index', 'show']]);

		Route::resource('projectcompany', 'ProjectCompanyController',['only' => ['show']]);


	#Regions
		Route::resource('region','RegionsController',['only' => ['index', 'show']]);

	#ServiceLines
		Route::get('serviceline/{id}/{type?}',['as'=>'serviceline.accounts','uses'=>'ServicelinesController@show']);
		Route::resource('serviceline','ServicelinesController',	['only' => ['index', 'show']]);

	#Sales Campaigns
		Route::get('campaigns',['as'=>'salescampaigns','uses'=>'SalesActivityController@mycampaigns']);
		Route::resource('salesactivity','SalesActivityController',
			['only' => ['show']]);

	# Sales organization
		Route::get('salesorg/{person?}',['as'=>'salesorg','uses'=>'SalesOrgController@getSalesBranches']);
		Route::get('salesorg/{person}/list',['as'=>'salesorg.list','uses'=>'SalesOrgController@getSalesOrgList']);
		Route::get('salesorg/coverage',['as'=>'salescoverage','uses'=>'SalesOrgController@salesCoverageMap']);
		Route::post('salesorg/find',['as'=>'lead.find','uses'=>'LeadsController@find']);
		Route::get('branch/{branchId}/salesteam',array('as' => 'branch.salesteam', 'uses' => 'BranchesController@showSalesTeam'));


	# Sales leads
		/*Route::get('prospect/{id}/accept',['as'=>'saleslead.accept','uses'=>'SalesLeadsController@accept']);
		Route::get('prospect/{id}/decline',['as'=>'saleslead.decline','uses'=>'SalesLeadsController@decline']);
		Route::get('prospects/{pid}/showrep',['as'=>'salesleads.showrep','uses'=>'SalesLeadsController@showrep']);
		Route::get('prospects/download',['as'=>'salesleads.download','uses'=>'SalesLeadsController@download']);
		Route::get('prospects/{id}/showrepdetail/{pid}',['as'=>'salesleads.showrepdetail','uses'=>'SalesLeadsController@showrepdetail']);
		Route::get('leadrank',['as'=>'api.leadrank','uses'=>'SalesLeadsController@rank']);
		Route::post('prospect/{id}/close',['as'=>'saleslead.close','uses'=>'SalesLeadsController@close']);
		Route::get('prospect/{pid}/leads',['as'=>'saleslead.mapleads','uses'=>'SalesLeadsController@mapleads']);
		Route::resource('salesleads','SalesLeadsController');*/

	# Sales Notes
		Route::get('salesnotes/{companyId}',['as'=>'salesnotes','uses'=>'SalesNotesController@show']);
		Route::get('salesnotes/print/{companyId}',['as'=>'salesnotes.print','uses'=>'SalesNotesController@printSalesNotes']);
		Route::resource('salesnotes','SalesNotesController');

	# Sales Resources
		Route::get('resources',['as'=>'resources.view','uses'=>'WatchController@getCompaniesWatched']);


	# Watch List
		Route::get('watch',['as'=>'watch.index', 'uses'=>'WatchController@index']);
		Route::get('watch/export',['as'=>'watch.export', 'uses'=>'WatchController@export']);
		Route::get('watch/add/{locationId}',['as'=>'watch.add', 'uses'=>'WatchController@create']);
		Route::get('watch/delete/{locationID}',['as'=>'watch.delete', 'uses'=>'WatchController@destroy']);
		Route::get('watch/map',['as'=>'watch.map','uses'=>'WatchController@showwatchmap']);
		Route::get('cowatch/export',['as'=>'company.watchexport', 'uses'=>'WatchController@companywatchexport']);
	#	New Leads


		Route::get('/newleads/{pid}',['as'=>'salesrep.newleads','uses'=>'LeadsController@salesLeads']);
		Route::get('/newleads/show/{id}/',['as'=>'salesrep.newleads.show','uses'=>'LeadsController@salesLeadsDetail']);
		Route::get('/newleads/{pid}/map',['as'=>'salesrep.newleads.map','uses'=>'LeadsController@salesLeadsMap']);
		Route::get('api/newleads/{pid}/map',['as'=>'salesrep.newleads.mapdata','uses'=>'LeadsController@getMapData']);
		Route::get('/branch/leads',['as'=>'branchmanager.newleads','uses'=>'LeadsController@getAssociatedBranches']);
		Route::get('newleadrank',['as'=>'api.newlead.rank','uses'=>'LeadsController@rank']);
		Route::get('/newleads/branch/{bid}/map',['as'=>'newleads.branch.map','uses'=>'LeadsController@branchLeadsMap']);
		Route::get('api/newleads/branch/{id}/map',['as'=>'newleads.branch.mapdata','uses'=>'LeadsController@getBranchMapData']);
		Route::get('newlead/{pid}/export',['as'=>'newleads.export','uses'=>'LeadsController@exportLeads']);
		Route::post('newlead/{id}/close',['as'=>'templead.close','uses'=>'LeadsController@close']);
	## Webleads
		/*Route::get('/myleads', ['as'=>'my.webleads','uses'=>'WebLeadsController@saleslist']);
		Route::get('/webleads/{lead}/salesshow',['as'=>'webleads.salesshow','uses'=>'WebLeadsController@salesshow']);
		Route::get('/webleads/map',['as'=>'webleads.map','uses'=>'WebLeadsController@salesLeadsMap']);
		Route::get('/webleads/mapdata',['as'=>'api.webleads.map','uses'=>'WebLeadsController@getMapData']);
		Route::post('/weblead/{lead}/close',['as'=>'weblead.close','uses'=>'WebLeadsController@close']);*/
	#AJAX Links
	#// Move these to api routes
		Route::get('api/company/{companyId}/statemap/{state}', ['as'=>'company.statemap','uses'=>'LocationsController@getStateLocations']);

		Route::get('api/news/nonews','NewsController@noNews');
		Route::get('api/news/setnews','NewsController@setNews');

		Route::get('api/branch/map', ['as'=>'branch/map', 'uses'=>'BranchesController@getAllbranchmap']);
		Route::get('api/branch/statemap/{state?}', ['as'=>'branch.statemap', 'uses'=>'BranchesController@makeStateMap']);
		Route::get('api/location/{locationId}/branchnearby',['as'=>'shownearby.branchlocation','uses' => 'MapsController@getLocationsPosition']);

		Route::get('api/watchmap',['as'=>'api.watchmap','uses'=>'WatchController@watchmap']);
		Route::match(['get','post'],'api/advancedsearch',['as'=>'setSearch','uses'=>'SearchFiltersController@setSessionSearch']);
		Route::get('documents/select',['as'=>'documents.select','uses'=>'DocumentsController@select']);
		Route::post('documents/select',['as'=>'documents.select','uses'=>'DocumentsController@getDocuments']);
		Route::get('rank',['as'=>'api.rank','uses'=>'DocumentsController@rank']);
    	Route::get('watchedby/{id}',['as'=>'watchedby','uses'=>'DocumentsController@watchedby']);
    	Route::get('documents/{id}',['as'=>'documents.show','uses'=>'DocumentsController@show']);
    	# Search Settings
    	Route::get('/salesteam/find', 'SearchController@searchSalesteam');

    	/*Route::get('search',function(){
    		return response()->view('search.search');
    	});

*/
    	# Training
		Route::get('mytraining',['as'=>'mytraining','uses'=>'TrainingController@mytraining']);

    	#User settings
		Route::get('/user/settings',['as'=>'profile','uses'=>'UsersController@settings']);

		Route::get('user/update',['as'=>'update.profile','uses'=>'UsersController@updateprofile']);
		Route::post('user/update',['as'=>'update.profile','uses'=>'UsersController@saveprofile']);
		// legacy login address
		Route::get('user/login',function(){
			if(auth()->check()){

				return redirect()->route('welcome');
			}
			redirect()->intended('login');
		});
});

/** ------------------------------------------
 *  Admin / Sales  Routes
 *  ------------------------------------------
 */
Route::group(['prefix' => 'ops', 'middleware' =>'ops'], function()
{
	#Ops Main Page
		Route::get('/',['as'=>'ops','uses'=>'Admin\AdminDashboardController@dashboard']);
	
	#Branches
		Route::get('branches/import', ['as'=>'branches.importfile', 'uses'=>'BranchesImportController@getFile']);
		Route::post('branches/change',['as'=>'branches.change','uses'=>'BranchesImportController@update']);
		Route::post('branches/bulkimport', ['as'=>'branches.import', 'uses'=>'BranchesImportController@import']);
		Route::get('geocode', ['as'=>'branches.geocode', 'uses'=>'BranchesController@geoCodeBranches']);
		Route::get('branchmap', ['as'=>'branches.genmap', 'uses'=>'BranchesController@rebuildBranchMap']);
		Route::get('branches/export', ['as'=>'branches.export', 'uses'=>'BranchesController@export']);
		Route::resource('branches','BranchesController',['except'=>['index','show']]);

	#Companies

		Route::get('companies/export', ['as'=>'companies.export', 'uses'=>'CompaniesController@export']);
		Route::post('companies/export', ['as'=>'companies.locationsexport', 'uses'=>'CompaniesController@locationsExport']);
		Route::get('companies/download', ['as'=>'companies.download','uses'=>'CompaniesController@exportAccounts']);
		Route::get('company/{companyId}/export',['as'=>'company.export','uses'=>'WatchController@companyexport']);
		//Route::post('company/filter',['as'=>'company.filter','uses'=>'CompaniesController@filter']);
		Route::resource('company','CompaniesController',['except' => ['index', 'show']]);

    # Documents
    	Route::resource('documents','DocumentsController');

    # Emails
    	Route::post('emails/selectrecipients',['as'=>'emails.updatelist','uses'=>'EmailsController@addRecipients']);
    	Route::get('emails/update',['as'=>'emails.updaterecipients','uses'=>'EmailsController@changelist']);
    	Route::get('emails/{id}/clone',['as'=>'emails.clone','uses'=>'EmailsController@clone']);
    	Route::get('emails/{id}/recipients',['as'=>'emails.recipients','uses'=>'EmailsController@recipients']);
    	Route::post('emails/send',['as'=>'emails.send','uses'=>'EmailsController@sendEmail']);
    	Route::resource('emails','EmailsController');

    # Imports
   		Route::get('branch/teams',['as'=>'branch_team.importfile','uses'=>'BranchTeamImportController@getFile']);
   		Route::post('branch/teams',['as'=>'branches.teamimport','uses'=>'BranchTeamImportController@import']);
    	Route::get('imports',['as'=>'imports.index','uses'=>'ImportController@index']);
    	Route::post('/importleads/mapfields',['as'=>'leads.mapfields','uses'=>'LeadImportController@mapfields']);
    	Route::post('/importlocations/mapfields',['as'=>'locations.mapfields','uses'=>'LocationsImportController@mapfields']);
    	Route::post('/importprojects/mapfields',['as'=>'projects.mapfields','uses'=>'ProjectsImportController@mapfields']);
    	Route::post('/importprojectcompany/mapfields',['as'=>'projectcompany.mapfields','uses'=>'ProjectsCompanyImportController@mapfields']);
    	Route::post('/importbranches/mapfields',['as'=>'branches.mapfields','uses'=>'BranchesImportController@mapfields']);
    	Route::post('/importbranchteams/mapfields',['as'=>'branchteam.mapfields','uses'=>'BranchTeamImportController@mapfields']);

	#Locations
		Route::get('locations/import', ['as'=>'locations.importfile', 'uses'=>'LocationsImportController@getfile']);
		Route::post('locations/bulkimport', ['as'=>'locations.import', 'uses'=>'LocationsImportController@import']);


		Route::get('api/geocode',['as'=>'api.geocode','uses'=>'LocationsController@bulkGeoCodeLocations']);
		Route::get('locations/{companyID}/create',['as'=>'company.location.create','uses'=>'LocationsController@create']);
		Route::resource('locations','LocationsController',['except'=>['show']]);

	# Projects
		Route::get('projects/import',['as'=>'projects.importfile','uses'=>'ProjectsImportController@getFile']);
		Route::get('projects/importcompany',['as'=>'project_company.importfile','uses'=>'ProjectsCompanyImportController@getFile']);
		Route::post('projects/import',['as'=>'projects.bulkimport','uses'=>'ProjectsImportController@import']);
		Route::post('projects/importcompany',['as'=>'projects.companyimport','uses'=>'ProjectsCompanyImportController@import']);

		Route::get('projects/export',['as'=>'projects.exportowned','uses'=>'ProjectsController@exportowned']);
		Route::get('projects/status',['as'=>'projects.status','uses'=>'ProjectsController@statuses']);

		Route::get('projects/stats',['as'=>'project.stats','uses'=>'ProjectsController@projectStats']);
		Route::get('projects/exportstats',['as'=>'project.exportstats','uses'=>'ProjectsController@exportProjectStats']);
		Route::get('projects/{id}/owner',['as'=>'project.owner','uses'=>'ProjectsController@ownedProjects']);
		Route::post('projects/{id}/release',['as'=>'projects.release','uses'=>'ProjectsController@release']);


	#Project Source
		Route::resource('projectsource','ProjectSourceController');

	#Prospects / Leads
	/*	Route::get('leads/address',['as'=>'lead.address','uses'=>'LeadsController@address']);
		Route::get('leads/{vertical}/vertical',['as'=>'lead.vertical','uses'=>'LeadsController@index']);
		*/	
		Route::get('leads/import/{id?}',['as'=>'prospects.importfile','uses'=>'LeadImportController@getFile']);
		Route::get('leads/import/assigned/{id?}',['as'=>'assigned_prospects.importfile','uses'=>'LeadAssignedImportController@getFile']);

		Route::post('leads/import',['as'=>'leads.import','uses'=>'LeadImportController@import']);
	/*	
		Route::get('leads/assign/{sid}/source',['as'=>'leads.geoassign','uses'=>'LeadsAssignController@geoAssignLeads']);
		Route::get('leads/{id}/assign',['as'=>'leads.leadassign','uses'=>'LeadsController@assignLeads']);*/
		//Route::post('leads/batchassign',['as'=>'leads.assignbatch','uses'=>'LeadsAssignController@assignLead']);
		//Route::post('leads/assign',['as'=>'leads.assign','uses'=>'LeadsController@postAssignLeads']);
		
		
	## Web leads
		
		Route::post('/webleads/import/form',['as'=>'leads.webleadsinsert','uses'=>'WebleadsImportController@getLeadFormData']);
		
		Route::post('/webleads/import/create',['as'=>'webleads.import.store','uses'=>'WebleadsImportController@store']);

		Route::post('/leads/assign',['as'=>'leads.assign','uses'=>'LeadsController@assignLeads']);
		Route::delete('/leads/{id}/unassign',['as'=>'webleads.unassign','uses'=>'LeadsController@unAssignLeads']);
		
		
		//Route::get('webleads/{lead}',['as'=>'webleads.show','uses'=>'WebLeadsController@show']);
		//Route::resource('webleads','WebLeadsImportController');

		Route::get('leads/{id}/person',['as'=>'leads.person','uses'=>'LeadsController@getPersonsLeads']);
		Route::get('leads/{id}/person/{sid}/source',['as'=>'leads.personsource','uses'=>'LeadsController@getPersonSourceLeads']);
		Route::get('leadsource/{id}/export',['as'=>'leadsource.export','uses'=>'LeadSourceController@export']);
		Route::post('lead/search',['as'=>'leads.search','uses'=>'LeadsController@search']);
		Route::get('lead/search',['as'=>'leads.search','uses'=>'LeadsController@searchAddress']);
		Route::get('lead/branch/{bid?}',['as'=>'leads.branch','uses'=>'LeadsController@branches']);
		Route::resource('leads','LeadsController');

	# Prospect Source / LeadSource

		Route::get('leadsource/{id}/announce',['as'=>'leadsource.announce','uses'=>'LeadsEmailController@announceLeads']);
		Route::post('leadsource/{id}/email',['as'=>'sendleadsource.message','uses'=>'LeadsEmailController@email']);
		Route::get('leadsource/{id}/assign',['as'=>'leadsource.assign','uses'=>'LeadSourceController@assignLeads']);
		Route::get('leadsource/{id}/branch',['as'=>'leadsource.branches','uses'=>'LeadSourceController@branches']);
		Route::get('leadsource/{id}/unassigned',['as'=>'leadsource.unassigned','uses'=>'LeadSourceController@unassigned']);
		Route::get('leadsource/{id}/addleads',['as'=>'leadsource.addleads','uses'=>'LeadImportController@getFile']);


		Route::get('leadsource/{id}/flush',['as'=>'leadsource.flushleads','uses'=>'LeadSourceController@flushLeads']);
		Route::resource('leadsource','LeadSourceController');

	#Salesnotes
		Route::get('salesnotes/filedelete/{file}', ['as'=>'salesnotes.filedelete', 'uses'=>'SalesNotesController@filedelete']);
		Route::get('salesnotes/create/{companyId}',['as'=>'salesnotes.cocreate','uses'=>'SalesNotesController@createSalesNotes']);


	# Sales Activity

		Route::get('salesactivity/{vertical}/vertical',['as'=>'salesactivity.vertical','uses'=>'SalesActivityController@index']);
		Route::post('salesactivity/updateteam',['as'=>'salesactivity.modifyteam','uses'=>'SalesActivityController@updateteam']);
		Route::resource('salesactivity','SalesActivityController',['except' => ['show']]);

		Route::get('campaigndocs/{id}',['as'=>'salesdocuments.index','uses'=>'SalesActivityController@campaignDocuments']);
		Route::get('campaign/{id}/announce',['as'=>'campaign.announce','uses'=>'CampaignEmailController@announceCampaign']);
		Route::post('campaign/{id}/message',['as'=>'sendcampaign.message','uses'=>'CampaignEmailController@email']);

		Route::get('salesteam',['as'=>'teamupdate','uses'=>'SalesActivityController@changeTeam']);

	#CompanyService
		
		Route::get('/company/{id}/newservice/{state?}',['as'=>'company.service','uses'=>'CompaniesServiceController@getServiceDetails']);
		Route::post('/company/service',['as'=>'company.service.select','uses'=>'CompaniesServiceController@selectServiceDetails']);
		Route::get('company/{id}/serviceexport/{state?}',['as'=>'company.service.export','uses'=>'CompaniesServiceController@exportServiceDetails']);


	#Watchlists
		Route::get('watchlist/{userid}', ['as'=>'watch.mywatchexport', 'uses'=>'WatchController@export']);

	## Search
		Route::get('/user/find', 'SearchController@searchUsers');
		
		Route::get('/person/{person}/find',['as'=>'person.details','uses'=>'PersonSearchController@find']);

	#NewLeads
	   // Route::get('newleads/team',['as'=>'templeads.team','uses'=>'TempleadController@salesteam']);
	    Route::get('/newleads/{pid}/branchmgr',['as'=>'branchmgr.newleads','uses'=>'LeadsController@getAssociatedBranches']);
	   Route::get('/newleads/branch',['as'=>'templeads.branch','uses'=>'LeadsController@branches']);
	    //Route::get('/newleads/{id}/branch/',['as'=>'leads.branchid','uses'=>'LeadsController@branchLeads']);
		Route::resource('newleads','LeadSourceController');
		
});
/** ------------------------------------------
 *  Admin Routes
 *  ------------------------------------------
 */


Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function()
{
	# Branch managemnet
		Route::get('branch/manage',['as'=>'branch.management','uses'=>'Admin\BranchManagementController@index']);
		Route::get('branch/check',['as'=>'branch.check','uses'=>'Admin\AdminUsersController@checkBranchAssignments']);
    # User Management

		Route::get('cleanse',['as'=>'users.cleanse','uses'=>'Admin\AdminUsersController@cleanse']);
		Route::get('users/import',['as'=>'users.importfile', 'uses'=>'UsersImportController@getFile']);
		Route::post('users/bulkimport',['as'=>'admin.users.bulkimport', 'uses'=>'UsersImportController@import']);
		Route::post('users/import',['as'=>'users.mapfields','uses'=>'UsersImportController@mapfields']);
		Route::post('user/importerrors',['as'=>'fixuserinputerrors','uses'=>'UsersImportController@fixerrors']);
		Route::get('users/serviceline/{servicelineId}', ['as'=>'serviceline.user','uses'=>'Admin\AdminUsersController@index']);
		Route::get('users/nomanager', ['as'=>'nomanager','uses'=>'SalesOrgController@noManager']);
		Route::get('users/nomanager/export', ['as'=>'nomanager.export','uses'=>'SalesOrgController@noManagerExport']);
		Route::resource('users', 'Admin\AdminUsersController');


	# User Role Management

		Route::resource('roles','Admin\AdminRolesController');
	    #  Permissions

		Route::resource('permissions','Admin\AdminPermissionsController');

	#Howtofields
		Route::resource('howtofields','HowtofieldsController');


	#People
		Route::get('person/import',['as'=>'person.bulkimport', 'uses'=>'PersonsController@import']);
		Route::post('person/import',['as'=>'person.import', 'uses'=>'PersonsController@processimport']);
		Route::get('person/export', ['as'=>'person.export', 'uses'=>'PersonsController@export']);


	#ServiceLines
		Route::resource('serviceline','ServicelinesController');


	# Lead Status

	 	Route::resource('leadstatus','LeadStatusController');



	# Sales Process

		Route::resource('process','SalesProcessController');

	# Training
		Route::get('training/{id}/view',['as'=>'training.view','uses'=>'TrainingController@view']);
		Route::resource('training','TrainingController');

	# Admin Dashboard
		Route::get('watching/{userid}', ['as'=>'watch.watching', 'uses'=>'WatchController@watching']);
		Route::get('userlogin/{view?}',['as'=>'admin.showlogins', 'uses'=>'Admin\AdminDashboardController@logins']);
		Route::get('userlogin/download/{view?}',['as'=>'admin.downloadlogins', 'uses'=>'Admin\AdminDashboardController@downloadlogins']);
		Route::get('/', ['as'=>'dashboard','uses'=>'Admin\AdminDashboardController@dashboard']);

	#Comments
		Route::get('comment/download', ['as'=>'comment.download', 'uses'=>'CommentsController@download']);

	#News
		Route::get('news/{id}/audience',['as'=>'news.audience','uses'=>'NewsController@audience']);
		Route::resource('news', 'NewsController');


	#Notes
		Route::get('notes/{companyid}/co',['as'=>'notes.company','uses'=>'NotesController@companynotes']);
		Route::get('locationnotes',['as'=>'locations.notes', 'uses'=>'NotesController@index']);

	#Search Filters

		Route::get('searchfilters/analysis/{id?}',['as'=>'vertical.analysis','uses'=>'SearchFiltersController@filterAnalysis']);
		Route::get('searchfilters/export/{id?}',['as'=>'vertical.export','uses'=>'SearchFiltersController@export']);
		Route::get('searchfilters/promote/{filterid}',['as'=>'admin.searchfilter.promote','uses'=>'SearchFiltersController@promote']);
		Route::get('searchfilters/demote/{filterid}',['as'=>'admin.searchfilter.demote','uses'=>'SearchFiltersController@demote']);
		Route::get('filterform','SearchFiltersController@filterForm');

		Route::get('api/searchfilters/getAccounts',['as'=>'getAccountSegments','uses'=>'SearchFiltersController@getAccountSegments']);
		Route::post('api/searchfilters/postAccounts',['as'=>'postAccountSegments','uses'=>'SearchFiltersController@getAccountSegments']);
		Route::resource('searchfilters','SearchFiltersController');

		

	# Seeder for relationships with servicelines
		Route::get('seeder',['as'=>'seeder','uses'=>'CompaniesController@seeder']);
		Route::get('apiseeder',['as'=>'apiseeder','uses'=>'UsersController@seeder']);

	# Versions
	 	Route::resource('versions','GitController');

	 	Route::get('/leads/unassigned',['as'=>'unassigned.leads','uses'=>'LeadsController@unassignedleads']);
	 	//Route::get('branch/{bid}/people',['as'=>'test.branch.people', 'uses'=>'WebLeadsController@getSalesPeopleofBranch']);
	 	Route::get('authtest',['as'=>'test','uses'=>'TestController@test']);
	 	Route::get('testmorph',['as'=>'testmorph','uses'=>'BranchesController@testmorph']);
});
