<?php
use \Carbon\Carbon;
use App\User;
use App\Mail\SendWeeklyActivityReminder;
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


	Route::get('testinbound',['as'=>'testinbound','uses'=>'InboundMailController@inbound']);
	Route::get('testemail',['as'=>'testemail','uses'=>'InboundMailController@testemail']);

/*
	
	Route::get('/error',function(){
		Bugsnag::notifyError('ErrorType', 'Test Error');
*/
	// Routes for branch assignment verification
Route::get('/correction/{token}/{cid?}',['as'=>'branchassociation.confirm','uses'=>'BranchManagementController@confirm']);
Route::get('/confirmation/{token}/{cid?}',['as'=>'branchassociation.correct','uses'=>'BranchManagementController@correct']);

Route::auth();

Route::get('/home', ['as'=>'home','uses'=>'HomeController@index']);

Route::group(['middleware' => 'auth'], function () {
   	Route::get('/company/find', 'SearchController@searchCompanies');
	#About
		Route::get('about',['as'=>'about','uses'=>'AdminAboutController@getInfo']);

   	#Activities/
		Route::get('branch/{branch}/activity/{activitytype?}',['as'=>'branch.activity','uses'=>'ActivityController@getBranchActivtiesByType']);
		Route::get('activity/{activity}/complete',['as'=>'activity.complete','uses'=>'ActivityController@complete']);
		Route::get('followup',['as'=>'followup','uses'=>'ActivityController@future']);
		Route::resource('activity','ActivityController');
		
   	#AccountTypes
		Route::resource('accounttype','AccounttypesController',	['only' => ['index', 'show']]);
	#Address
		Route::post('address/{address}/rating',['as'=>'address.rating','uses'=>'AddressController@rating']);
		Route::resource('address','AddressController');
		
	#Avatar
		Route::post('change/avatar',['as'=>'change.avatar','uses'=>'AvatarController@store']);
	
	# Branch Leads
		Route::get('branchleads.import',['as'=>'branchleads.import','uses'=>'BranchLeadImportController@getFile']);
		# Temporary branch lead assignment
		//Route::get('branchleads/assign',['as'=>'branchlead.tempassign','uses'=>'BranchLeadController@assign']);
		Route::resource('branchleads','BranchLeadController');	
	#Branches
		Route::get('/branches/{state}/state/', ['as'=>'branches.statelist','uses'=>'BranchesController@state']);
		Route::post('/branches/state', ['as'=>'branches.state','uses'=>'BranchesController@state']);
		Route::get('/branches/{state}/statemap', ['as'=>'branches.showstatemap','uses'=>'BranchesController@statemap']);
		Route::post('/branches/statemap', ['as'=>'branches.statemap','uses'=>'BranchesController@statemap']);
		Route::get('/branch/{branch}/map', ['as'=>'branch.map','uses'=>'BranchesController@map']);
		Route::get('/branches/map', ['as'=>'branches.map', 'uses'=>'BranchesController@mapall']);
		Route::get('branches/{branch}/shownearby',['as' => 'shownearby.branch', 'uses' => 'BranchesController@showNearbyBranches']);
		//Route::get('branches/{state}/showstate', ['as' => 'showstate.branch','uses' => 'BranchesController@getStateBranches']);
		Route::get('branches/{branch}/nearby',['as' => 'nearby.branch', 'uses' => 'BranchesController@getNearbyBranches']);
		//Route::get('branches/{branch}/locations',['as' => 'branch.locations', 'uses' => 'BranchesController@getLocationsServed']);
		Route::get('branches/{branch}/showlist',['as' => 'showlist.locations', 'uses' => 'BranchesController@listNearbyLocations']);
		Route::get('branches/{branch}/salesteam',['as' => 'showlist.salesteam', 'uses' => 'BranchesController@showSalesTeam']);
		Route::get('branches/managed/{mgrId}',['as'=>'managed.branch', 'uses'=>'BranchesController@getMyBranches']);
		Route::get('branches/managedmap/{mgrId}',['as'=>'managed.branchmap', 'uses'=>'BranchesController@mapMyBranches']);
		Route::resource('branches','BranchesController',['only' => ['index', 'show']]);
	# Branch Activities
		Route::get('branch/{branch}/activities',['as'=>'activity.branch','uses'=>'ActivityController@branchActivities']);
		Route::get('branch/{branch}/upcoming',['as'=>'upcomingactivity.branch','uses'=>'ActivityController@branchUpcomingActivities']);
		Route::post('branch/activities',['as'=>'activities.branch','uses'=>'ActivityController@branchActivities']);
	#Branch Assignments
		Route::get('branchassignment/{user}/change',['as'=>'branchassignment.change','uses'=>'BranchManagementController@change']);
		Route::resource('branchassignments','BranchManagementController',['only'=>['index','show','edit','update']]);
	#Branch Dashboard
		Route::post('branches/period',['as'=>'period.setperiod','uses'=>'BranchDashboardController@setPeriod']);
		Route::post('branches/dashboard',['as'=>'branches.dashboard','uses'=>'BranchDashboardController@selectBranch']);
		Route::get('manager/{person}/dashboard',['as'=>'manager.dashboard','uses'=>'MgrDashboardController@manager']);
		Route::post('manager/dashboard',['as'=>'dashboard.select','uses'=>'DashboardController@select']);
	    Route::resource('branchdashboard','BranchDashboardController');
	# Manager Dashboard
		Route::resource('mgrdashboard','MgrDashboardController');
	# Dashboard
		Route::resource('dashboard','DashboardController');

	# Branch Pipeline
	    Route::get('branch/pipeline',['as'=>'branches.pipeline','uses'=>"OpportunityController@pipeline"]);
	   
	# Branch Leads
		Route::get('branch/leads',['as'=>'branch.leads','uses'=>'MyLeadsController@index']);
		Route::get('branch/{branch}/leads',['as'=>'lead.branch','uses'=>'MyLeadsController@branchLeads']);
		Route::post('branch/lead',['as'=>'leads.branch','uses'=>'MyLeadsController@branchLeads']);
		
		Route::resource('branchleadsimport','BranchLeadImportController');
	#Comments
		Route::resource('comment','CommentsController');

	#Companies
		Route::post('company/filter',['as'=>'company.filter','uses'=>'CompaniesController@filter']);
		Route::get('/company/{company}/state/{state?}', ['as'=>'company.state','uses'=>'CompaniesController@stateselect']);
		Route::post('/company/stateselect', ['as'=>'company.stateselect','uses'=>'CompaniesController@stateselector']);

		Route::get('/company/{company}/statemap/{state}', ['as'=>'company.statemap','uses'=>'CompaniesController@statemap']);
		Route::get('/company/vertical/{vertical}', ['as'=>'company.vertical','uses'=>'CompaniesController@vertical']);
		Route::get('/company/{company}/segment/{segment}', ['as'=>'company.segment','uses'=>'CompaniesController@show']);
		
		Route::resource('company', 'CompaniesController',['only' => ['index', 'show']]);


	# Contacts
		Route::get('contacts/{id}/vcard',['as'=>'contacts.vcard','uses'=>'LocationContactController@vcard']);
		Route::resource('contacts','LocationContactController');
		Route::post('contact/branch',['as'=>'contact.branch','uses'=>'LocationContactController@branchcontacts']);
		Route::get('contacts/branch/{branch}',['as'=>'contacts.branch','uses'=>'LocationContactController@branchcontacts']);
		Route::resource('mycontacts','MyContactsController');

	   	
   	# Documents
		Route::resource('docs','DocumentsController',['only' => ['index', 'show']]);

	# Feedback

		Route::resource('feedback','FeedbackController',['only'=>['index','show','store']]);

	#Geocoding

		Route::post('findme',['as'=>'findme','uses'=>'GeoCodingController@findMe']);
		Route::get('findme',['as'=>'findme','uses'=>'MapsController@findme']);

	# Incoming
			
	#Industry Focus
    	Route::resource('/industryfocus','PersonIndustryController');

    # Lead

    	Route::post('lead/{address}/reassign',['as'=>'lead.reassign','uses'=>'MyLeadsController@reassign']);
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
		Route::get('api/mylocalpeople/{distance}/{latLng}/{limit?}', ['as' => 'map.mypeople', 'uses' => 'MapsController@findLocalPeople']);
		Route::get('api/myAccountsList/{distance}/{latLng}', ['as' => 'list.myaccounts', 'uses' => 'MapsController@findLocalAccounts']);
		Route::get('api/mylocalaccounts/{distance}/{latLng}/{companyId?}', ['as' => 'map.mylocations', 'uses' => 'MapsController@findLocalAccounts']);
		Route::get('api/mybranchList/{distance}/{latLng}', ['as' => 'list.mybranches', 'uses' => 'MapsController@findLocalBranches']);
		Route::get('api/people/map', ['as'=>'salesmap', 'uses'=>'PersonsController@getMapLocations']);
		Route::post('api/note/post',['as'=>'postNewNote','uses'=>'NotesController@store']);
		Route::get('api/note/get',['as'=>'addNewNote','uses'=>'NotesController@store']);
		Route::get('api/geo',['as'=>'geo','uses'=>'GeoCodingController@index']);
		Route::get('api/myleads/{distance}/{latLng}/{limit?}',['as'=>'myleadsmap','uses'=>'MapsController@finds']);
		Route::get('api/address/{distance}/{latLng}',['as'=>'addressmap','uses'=>'AddressController@findLocations']);

	#News
		Route::resource('news', 'NewsController',  ['' => ['index', 'show']]);
		Route::get('currentnews',['as'=>'currentnews','uses'=>'NewsController@currentNews']);
		//Route::get('news', ['as'=>'news.index', 'uses'=>'NewsController@index']);
		//Route::get('news/{slug}', ['as'=>'news.show', 'uses'=>'NewsController@show']);

	#Notes
		Route::get('mynotes',['as'=>'mynotes','uses'=>'NotesController@mynotes']);
		Route::get('exportlocationnotes/{company}', ['as'=>'exportlocationnotes','uses'=>'ManagersController@exportManagerNotes']);
		Route::resource('notes','NotesController');

	#Opportunity
		Route::post('/branchlead/{address}',['as'=>'branch.lead.add','uses'=>'OpportunityController@addToBranchLeads']);
		Route::post('/opportunities/{opportunity}/close/',['as'=>'opportunity.close','uses'=>'OpportunityController@close']);
		Route::post('/opportunities/branch/',['as'=>'opportunity.branch','uses'=>'OpportunityController@branchOpportunities']);
		Route::get('/opportunities/branch/{branch}',['as'=>'opportunities.branch','uses'=>'OpportunityController@branchOpportunities']);
		Route::delete('opportunity/{opportunity}/destroy',['as'=>'opportunity.remove','uses'=>'OpportunityController@destroy']);
		Route::get('/opportunity/chart',['as'=>'oppoprtunity.chart','uses'=>'OpportunityController@chart']);

		Route::resource('opportunity','OpportunityController');
	#Orders
		Route::resource('orders','OrdersController');

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
	//	Route::resource('region','RegionsController',['only' => ['index', 'show']]);

	#ServiceLines
		Route::get('serviceline/{id}/{type?}',['as'=>'serviceline.accounts','uses'=>'ServicelinesController@show']);
		Route::resource('serviceline','ServicelinesController',	['only' => ['index', 'show']]);

	#Sales Campaigns
		Route::get('campaigns',['as'=>'salescampaigns','uses'=>'SalesActivityController@mycampaigns']);
		Route::resource('salesactivity','SalesActivityController',
			['only' => ['show']]);

	# Sales organization
		Route::get('salesorg/coverage',['as'=>'salescoverage','uses'=>'SalesOrgController@salesCoverageMap']);
		
		Route::post('salesorg/find',['as'=>'salesorg.find','uses'=>'SalesOrgController@find']);
		// add salesorg reqource with show and index only
		Route::resource('salesorg','SalesOrgController',['only'=>['index','show']]);
		//Route::get('salesorg/{person?}',['as'=>'salesorg','uses'=>'SalesOrgController@getSalesBranches']);
		//Route::get('salesorg/{person}/list',['as'=>'salesorg.list','uses'=>'SalesOrgController@getSalesOrgList']);


		
		Route::get('branch/{branchId}/salesteam',array('as' => 'branch.salesteam', 'uses' => 'BranchesController@showSalesTeam'));


	# Sales leads
		Route::get('prospect/{id}/accept',['as'=>'saleslead.accept','uses'=>'SalesLeadsController@accept']);
		Route::get('prospect/{id}/decline',['as'=>'saleslead.decline','uses'=>'SalesLeadsController@decline']);
		Route::get('prospects/{pid}/showrep',['as'=>'salesleads.showrep','uses'=>'SalesLeadsController@showrep']);
		Route::get('prospects/download',['as'=>'salesleads.download','uses'=>'SalesLeadsController@download']);
		Route::get('prospects/{id}/showrepdetail/{pid}',['as'=>'salesleads.showrepdetail','uses'=>'SalesLeadsController@showrepdetail']);
		Route::get('leadrank',['as'=>'api.leadrank','uses'=>'SalesLeadsController@rank']);
		Route::post('prospect/{id}/close',['as'=>'saleslead.close','uses'=>'SalesLeadsController@close']);
		Route::get('prospect/{pid}/leads',['as'=>'saleslead.mapleads','uses'=>'SalesLeadsController@mapleads']);
		Route::resource('salesleads','SalesLeadsController');

	# Sales Notes
		Route::get('salesnotes/{company}',['as'=>'salesnotes','uses'=>'SalesNotesController@show']);
		Route::get('salesnotes/print/{company}',['as'=>'salesnotes.print','uses'=>'SalesNotesController@printSalesNotes']);
		Route::resource('salesnotes','SalesNotesController');

	# Sales Resources
		Route::get('resources',['as'=>'resources.view','uses'=>'WatchController@getCompaniesWatched']);
	# Search
	# 
	# Team
		Route::get('team/{team}/export',['as'=>'team.export','uses'=>'TeamActivityController@export']);
		Route::resource('team','TeamActivityController');
		

	# Watch List
		Route::get('watch',['as'=>'watch.index', 'uses'=>'WatchController@index']);
		Route::get('watch/export',['as'=>'watch.export', 'uses'=>'WatchController@export']);
		Route::get('watch/add/{watch}',['as'=>'watch.add', 'uses'=>'WatchController@create']);
		Route::get('watch/delete/{locationID}',['as'=>'watch.delete', 'uses'=>'WatchController@destroy']);
		Route::get('watch/map',['as'=>'watch.map','uses'=>'WatchController@showwatchmap']);
		Route::get('cowatch/export',['as'=>'company.watchexport', 'uses'=>'WatchController@companywatchexport']);
	#	New Leads
		Route::resource('myleads','MyLeadsController');
		Route::post('branch/{address}/remove',['as'=>'branch.lead.remove','uses'=>'OpportunityController@remove']);
		Route::get('myclosedleads',['as'=>'myclosedleads','uses'=>'MyLeadsController@closedleads']);
		Route::post('mylead/{id}/close',['as'=>'mylead.close','uses'=>'MyLeadsController@close']);
		Route::resource('myleadsactivity','MyLeadsActivityController');
		Route::resource('myleadscontact','MyLeadsContactController');

		/*
		Route::get('/newleads/{pid}',['as'=>'salesrep.newleads','uses'=>'LeadsController@salesLeads']);
		Route::get('/newleads/show/{id}/',['as'=>'salesrep.newleads.show','uses'=>'LeadsController@salesLeadsDetail']);
		Route::get('/newleads/{pid}/map',['as'=>'salesrep.newleads.map','uses'=>'LeadsController@salesLeadsMap']);
		Route::get('api/newleads/{pid}/map',['as'=>'salesrep.newleads.mapdata','uses'=>'LeadsController@getMapData']);
		Route::get('/branch/leads',['as'=>'branchmanager.newleads','uses'=>'LeadsController@getAssociatedBranches']);
		Route::get('newleadrank',['as'=>'api.newlead.rank','uses'=>'LeadsController@rank']);
		Route::get('/newleads/branch/{bid}/map',['as'=>'newleads.branch.map','uses'=>'LeadsController@branchLeadsMap']);
		Route::get('api/newleads/branch/{id}/map',['as'=>'newleads.branch.mapdata','uses'=>'LeadsController@getBranchMapData']);
		Route::get('newlead/{pid}/export',['as'=>'newleads.export','uses'=>'LeadsController@exportLeads']);
		Route::post('lead/{id}/claim',['as'=>'lead.claim','uses'=>'LeadsController@claim']);
		Route::post('lead/{id}/close',['as'=>'lead.close','uses'=>'LeadsController@close']);
		*/
        ## Webleads
        /*Route::get('/myleads', ['as'=>'my.webleads','uses'=>'WebLeadsController@saleslist']);
		Route::get('/webleads/{lead}/salesshow',['as'=>'webleads.salesshow','uses'=>'WebLeadsController@salesshow']);
		Route::get('/webleads/map',['as'=>'webleads.map','uses'=>'WebLeadsController@salesLeadsMap']);
		Route::get('/webleads/mapdata',['as'=>'api.webleads.map','uses'=>'WebLeadsController@getMapData']);
		Route::post('/weblead/{lead}/close',['as'=>'weblead.close','uses'=>'WebLeadsController@close']);*/
        #AJAX Links
        #// Move these to api routes
        Route::get('api/company/{companyId}/statemap/{state}', ['as'=>'company.statemap','uses'=>'LocationsController@getStateLocations']);

        Route::get('api/news/nonews', 'NewsController@noNews');
        Route::get('api/news/setnews', 'NewsController@setNews');

        Route::get('api/branch/map', ['as'=>'branch/map', 'uses'=>'BranchesController@getAllbranchmap']);
        Route::get('api/branch/statemap/{state?}', ['as'=>'branch.statemap', 'uses'=>'BranchesController@makeStateMap']);
        Route::get('api/location/{locationId}/branchnearby', ['as'=>'shownearby.branchlocation','uses' => 'MapsController@getLocationsPosition']);

        Route::get('api/watchmap', ['as'=>'api.watchmap','uses'=>'WatchController@watchmap']);
        Route::match(['get','post'], 'api/advancedsearch', ['as'=>'setSearch','uses'=>'SearchFiltersController@setSessionSearch']);
        Route::get('documents/select', ['as'=>'documents.select','uses'=>'DocumentsController@select']);
        Route::post('documents/select', ['as'=>'documents.select','uses'=>'DocumentsController@getDocuments']);
        Route::get('rank', ['as'=>'api.rank','uses'=>'DocumentsController@rank']);
        Route::get('watchedby/{id}', ['as'=>'watchedby','uses'=>'DocumentsController@watchedby']);
        Route::get('documents/{id}', ['as'=>'documents.show','uses'=>'DocumentsController@show']);
        # Search Settings
        Route::get('/salesteam/find', 'SearchController@searchSalesteam');

        /*Route::get('search',function(){
    		return response()->view('search.search');
    	});

        */
        # Training
        Route::resource('training', 'TrainingController')->only(['index', 'show']);
        # Impersonate
        Route::impersonate();
        #User (Profile) settings
        Route::resource('user', 'UsersController')->only(['show','edit','update']);
        
        // legacy login address
        Route::get('user/login', function () {
            if (auth()->check()) {
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
	
	# Activity types
	    Route::resource('activitytype','ActivityTypeController');

	# Address
		Route::get('address/import', ['as'=>'address.importfile', 'uses'=>'AddressImportController@getfile']);

	#Branches
		Route::get('branches/import', ['as'=>'branches.importfile', 'uses'=>'BranchesImportController@getFile']);
		Route::post('branches/change',['as'=>'branches.change','uses'=>'BranchesImportController@update']);
		Route::post('branches/bulkimport', ['as'=>'branches.import', 'uses'=>'BranchesImportController@import']);
		Route::get('geocode', ['as'=>'branches.geocode', 'uses'=>'BranchesController@geoCodeBranches']);
		Route::get('branchmap', ['as'=>'branches.genmap', 'uses'=>'BranchesController@rebuildBranchMap']);
		Route::get('branches/export', ['as'=>'branches.export', 'uses'=>'BranchesController@export']);
		Route::get('branches/team/export', ['as'=>'branches.team.export', 'uses'=>'BranchesController@exportTeam']);
		Route::resource('branches','BranchesController',['except'=>['index','show']]);
	
	#Companies
		Route::get('companies/import', ['as'=>'companies.importfile', 'uses'=>'CompaniesImportController@getFile']);
		Route::post('companies/import', ['as'=>'companies.import', 'uses'=>'CompaniesImportController@import']);
		Route::get('companies/export', ['as'=>'companies.export', 'uses'=>'CompaniesExportController@index']);
		Route::post('companies/export', ['as'=>'companies.locationsexport', 'uses'=>'CompaniesExportController@export']);

		Route::get('companies/download', ['as'=>'allcompanies.export','uses'=>'CompaniesController@exportAccounts']);
		
		Route::get('company/{companyId}/export',['as'=>'company.export','uses'=>'WatchController@companyexport']);
		
	# Order Import
		Route::get('orderimport/flush',['as'=>'orderimport.flush','uses'=>'OrderImportController@flush']);
		Route::get('orderimport/finalize',['as'=>'orderimport.finalize','uses'=>'OrderImportController@finalize']);

		Route::resource('orderimport','CompaniesImportController');

		//Route::post('company/filter',['as'=>'company.filter','uses'=>'CompaniesController@filter']);
		Route::resource('company','CompaniesController',['except' => ['index', 'show']]);
	# Customers
		
		Route::get('customers/export', ['as'=>'customers.export', 'uses'=>'CompaniesExportController@export']);
		Route::post('/importcustomers/mapfields',['as'=>'companies.mapfields','uses'=>'CompaniesImportController@mapfields']);
		Route::resource('customers','CustomerController');
    # Documents
    	Route::resource('documents','DocumentsController');

    # Emails
    	Route::post('emails/selectrecipients',['as'=>'emails.updatelist','uses'=>'EmailsController@addRecipients']);
    	Route::get('emails/update',['as'=>'emails.updaterecipients','uses'=>'EmailsController@changelist']);
    	Route::get('emails/{id}/clone',['as'=>'emails.clone','uses'=>'EmailsController@clone']);
    	Route::get('emails/{id}/recipients',['as'=>'emails.recipients','uses'=>'EmailsController@recipients']);
    	Route::post('emails/send',['as'=>'emails.send','uses'=>'EmailsController@sendEmail']);
    	Route::resource('emails','EmailsController');
    # Feedback
    	Route::get('feedback/export',['as'=>'feedback.export','uses'=>'FeedbackController@export']);
    	Route::get('feedback{feedback}/close',['as'=>'feedback.close','uses'=>'FeedbackController@close']);
    	Route::get('feedback{feedback}/open',['as'=>'feedback.open','uses'=>'FeedbackController@open']);
    	Route::resource('feedback','FeedbackController',['except'=>['index','show','store']]);

    # Feedback Comments
    	Route::resource('feedback_comment','FeedbackCommentsController');
    # Imports
    
        Route::get('branch/teams', ['as'=>'branch_team.importfile','uses'=>'BranchTeamImportController@getFile']);
        Route::post('branch/teams', ['as'=>'branches.teamimport','uses'=>'BranchTeamImportController@import']);
        Route::get('imports', ['as'=>'imports.index','uses'=>'ImportController@index']);
        Route::post('/importleads/mapfields', ['as'=>'leads.mapfields','uses'=>'LeadImportController@mapfields']);
        Route::post('/importlocations/mapfields', ['as'=>'locations.mapfields','uses'=>'LocationsImportController@mapfields']);
        Route::post('/importprojects/mapfields', ['as'=>'projects.mapfields','uses'=>'ProjectsImportController@mapfields']);
        Route::post('/importprojectcompany/mapfields', ['as'=>'projectcompany.mapfields','uses'=>'ProjectsCompanyImportController@mapfields']);
        Route::post('/importbranches/mapfields', ['as'=>'branches.mapfields','uses'=>'BranchesImportController@mapfields']);
        Route::post('/importbranchteams/mapfields', ['as'=>'branchteam.mapfields','uses'=>'BranchTeamImportController@mapfields']);

        #Locations
        Route::get('locations/import', ['as'=>'locations.importfile', 'uses'=>'LocationsImportController@getfile']);
        Route::post('locations/bulkimport', ['as'=>'locations.import', 'uses'=>'LocationsImportController@import']);
       
        # LocationsPostImport

        Route::post('locations/adddelete',['as'=>'locations.adddelete','uses'=>'LocationPostImportController@adddelete']);
        Route::resource('locations/postprocess','LocationPostImportController');

        Route::get('api/geocode', ['as'=>'api.geocode','uses'=>'LocationsController@bulkGeoCodeLocations']);
        Route::get('locations/{companyID}/create', ['as'=>'company.location.create','uses'=>'LocationsController@create']);
        Route::resource('locations', 'LocationsController', ['except'=>['show']]);

        # Projects
        Route::get('projects/import', ['as'=>'projects.importfile','uses'=>'ProjectsImportController@getFile']);
        Route::get('projects/importcompany', ['as'=>'project_company.importfile','uses'=>'ProjectsCompanyImportController@getFile']);
        Route::post('projects/import', ['as'=>'projects.bulkimport','uses'=>'ProjectsImportController@import']);
        Route::post('projects/importcompany', ['as'=>'projects.companyimport','uses'=>'ProjectsCompanyImportController@import']);

        Route::get('projects/export', ['as'=>'projects.exportowned','uses'=>'ProjectsController@exportowned']);
        Route::get('projects/status', ['as'=>'projects.status','uses'=>'ProjectsController@statuses']);

        Route::get('projects/stats', ['as'=>'project.stats','uses'=>'ProjectsController@projectStats']);
        Route::get('projects/exportstats', ['as'=>'project.exportstats','uses'=>'ProjectsController@exportProjectStats']);
        Route::get('projects/{id}/owner', ['as'=>'project.owner','uses'=>'ProjectsController@ownedProjects']);
        Route::post('projects/{id}/release', ['as'=>'projects.release','uses'=>'ProjectsController@release']);


        #Project Source
        Route::resource('projectsource', 'ProjectSourceController');

        #Prospects / Leads
        /*	Route::get('leads/address',['as'=>'lead.address','uses'=>'LeadsController@address']);
		Route::get('leads/{vertical}/vertical',['as'=>'lead.vertical','uses'=>'LeadsController@index']);
		*/	
		Route::get('leads/import/{id?}',['as'=>'prospects.importfile','uses'=>'LeadImportController@getFile']);
		Route::get('leads/import/assigned/{id?}',['as'=>'assigned_prospects.importfile','uses'=>'LeadAssignedImportController@getFile']);

		Route::post('leads/import',['as'=>'leads.import','uses'=>'LeadImportController@import']);
		Route::get('leadsource/{leadsource}/addcompany',['as'=>'leadsource.addcompany','uses'=>'LeadSourceController@selectCompaniesToAdd']);
		Route::post('leadsource/{leadsource}/addcompanylocations',['as'=>'leadsource.addcompanylocations','uses'=>'LeadSourceController@addCompanyLocationsToLeadSource']);
		Route::get('leadsource/{leadsource}/results',['as'=>'leadsource.results','uses'=>'LeadSourceController@leadSourceBranchResults']);
		
		Route::post('leadsource/{leadsource}/assign',['as'=>'leads.geoassign','uses'=>'LeadsAssignController@geoAssignLeads']);
		Route::get('leads/{leadsource}/assign',['as'=>'leads.leadassign','uses'=>'LeadsController@assignLeads']);
		
		Route::get('leads/{leadsource}/batchassign',['as'=>'leads.assignbatch','uses'=>'LeadsAssignController@assignLeads']);
		
		Route::post('leads/assign',['as'=>'webleads.assign','uses'=>'WebLeadsController@assignLeads']);
		
		
	## Web leads
		
		
		Route::get('/leads/{address}/singleassign',['as'=>'leads.singleassign','uses'=>'LeadsAssignController@singleleadassign']);
		Route::post('/leads/{address}/singleassign',['as'=>'leads.postassign','uses'=>'LeadsAssignController@store']);
		Route::post('/leads/assign',['as'=>'leads.assign','uses'=>'LeadsController@assignLeads']);
		Route::delete('/leads/{id}/unassign',['as'=>'webleads.unassign','uses'=>'LeadsController@unAssignLeads']);
		
		
		//Route::get('webleads/{lead}',['as'=>'webleads.show','uses'=>'WebLeadsController@show']);
		//Route::resource('webleads','WebLeadsImportController');

		
		Route::get('leadsource/{leasource}/export',['as'=>'leadsource.export','uses'=>'LeadSourceController@export']);
		
		Route::post('/webleads/import/form',['as'=>'leads.webleadsinsert','uses'=>'WebleadsImportController@getLeadFormData']);
		Route::post('/webleads/import/create',['as'=>'webleads.import.store','uses'=>'WebleadsImportController@store']);
		Route::post('lead/search',['as'=>'leads.search','uses'=>'LeadsController@search']);
		Route::get('lead/search',['as'=>'leads.search','uses'=>'LeadsController@searchAddress']);
		Route::get('address/{address}/assign',['as'=>'leads.assignlead','uses'=>'LeadsAssignController@show']);
		/*Route::get('leads/{id}/person',['as'=>'leads.person','uses'=>'LeadsController@getPersonsLeads']);
		Route::get('leads/{id}/person/{sid}/source',['as'=>'leads.personsource','uses'=>'LeadsController@getPersonSourceLeads'])
		Route::get('lead/branch/{bid?}',['as'=>'leads.branch','uses'=>'LeadsController@branches']);
		Route::resource('leads','LeadsController');*/
		#Salesnotes
		Route::get('salesnotes/filedelete/{file}', ['as'=>'salesnotes.filedelete', 'uses'=>'SalesNotesController@filedelete']);
		Route::get('salesnotes/create/{companyId}',['as'=>'salesnotes.cocreate','uses'=>'SalesNotesController@createSalesNotes']);
        # OrderImports

        Route::resource('orderimport', 'OrderImportController');
        # Prospect Source / LeadSource

		Route::get('leadsource/{leadsource}/announce',['as'=>'leadsource.announce','uses'=>'LeadsEmailController@announceLeads']);
		Route::post('leadsource/{leadsource}/email',['as'=>'sendleadsource.message','uses'=>'LeadsEmailController@email']);
		Route::get('leadsource/{leadsource}/assign',['as'=>'leadsource.assign','uses'=>'LeadsAssignController@assignLeads']);
		Route::get('leadsource/{leadsource}/branch',['as'=>'leadsource.branches','uses'=>'LeadSourceController@branches']);
		Route::get('leadsource/{leadsource}/unassigned',['as'=>'leadsource.unassigned','uses'=>'LeadSourceController@unassigned']);
		Route::get('leadsource/{leadsource}/addleads',['as'=>'leadsource.addleads','uses'=>'LeadImportController@getFile']);

		Route::get('leadsource/{leadsource}/state/{state}',['as'=>'leadsource.unassigned.state','uses'=>'LeadSourceController@unassignedstate']);
		Route::get('leadsource/{leadsource}/flush',['as'=>'leadsource.flushleads','uses'=>'LeadSourceController@flushLeads']);
		Route::resource('leadsource','LeadSourceController');

	#Salesnotes
		Route::get('salesnotes/filedelete/{file}', ['as'=>'salesnotes.filedelete', 'uses'=>'SalesNotesController@filedelete']);
		Route::get('salesnotes/create/{company}',['as'=>'salesnotes.cocreate','uses'=>'SalesNotesController@createSalesNotes']);

        # Sales Activity

        Route::get('salesactivity/{vertical}/vertical', ['as'=>'salesactivity.vertical','uses'=>'SalesActivityController@index']);
        Route::post('salesactivity/updateteam', ['as'=>'salesactivity.modifyteam','uses'=>'SalesActivityController@updateteam']);
        Route::resource('salesactivity', 'SalesActivityController', ['except' => ['show']]);

        Route::get('campaigndocs/{id}', ['as'=>'salesdocuments.index','uses'=>'SalesActivityController@campaignDocuments']);
        Route::get('campaign/{id}/announce', ['as'=>'campaign.announce','uses'=>'CampaignEmailController@announceCampaign']);
        Route::post('campaign/{id}/message', ['as'=>'sendcampaign.message','uses'=>'CampaignEmailController@email']);

        Route::get('salesteam', ['as'=>'teamupdate','uses'=>'SalesActivityController@changeTeam']);


        #CompanyService
        
        Route::get('/company/{id}/service/{state?}', ['as'=>'company.service','uses'=>'CompaniesServiceController@getServiceDetails']);

        Route::get('/company/{id}/teamservice/{state?}', ['as'=>'company.teamservice','uses'=>'CompaniesServiceController@getServiceTeamDetails']);
        Route::post('/company/service', ['as'=>'company.service.select','uses'=>'CompaniesServiceController@selectServiceDetails']);
        Route::get('company/{id}/serviceexport/{state?}', ['as'=>'company.service.export','uses'=>'CompaniesServiceController@exportServiceDetails']);
        Route::get('company/{id}/serviceteamexport/{state?}', ['as'=>'company.teamservice.export','uses'=>'CompaniesServiceController@exportServiceTeamDetails']);

        #Watchlists
        Route::get('watchlist/{userid}', ['as'=>'watch.mywatchexport', 'uses'=>'WatchController@export']);

        ## Search
        Route::get('/user/find', 'SearchController@searchUsers');

        
        Route::get('/person/{person}/find', ['as'=>'person.details','uses'=>'PersonSearchController@find']);

        #NewLeads
           // Route::get('newleads/team',['as'=>'templeads.team','uses'=>'TempleadController@salesteam']);
           // Route::get('/newleads/{pid}/branchmgr',['as'=>'branchmgr.newleads','uses'=>'LeadsController@getAssociatedBranches']);
           //Route::get('/newleads/branch',['as'=>'templeads.branch','uses'=>'LeadsController@branches']);
        //Route::get('/newleads/{id}/branch/',['as'=>'leads.branchid','uses'=>'LeadsController@branchLeads']);
        Route::resource('newleads', 'LeadSourceController');
    });
/** ------------------------------------------
 *  Admin Routes
 *  ------------------------------------------
 */


    Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {

        # Branch management

        Route::get('branchassignments/select', ['as'=>'branchassignment.check','uses'=>'Admin\BranchManagementController@select']);
        Route::post('branchassignments/email', ['as'=>'branchteam.email','uses'=>'Admin\BranchManagementController@confirm']);
        Route::post('branchassignments/send', ['as'=>'branchassignments.send','uses'=>'Admin\BranchManagementController@emailAssignments']);
        Route::get('branch/manage', ['as'=>'branch.management','uses'=>'Admin\BranchManagementController@index']);
        Route::get('branch/check', ['as'=>'branch.check','uses'=>'Admin\AdminUsersController@checkBranchAssignments']);
    
        # Campaigns (email)
        Route::resource('campaigns', 'CampaignController');

        # Construction
        Route::resource('/construction', 'ConstructionController');
        
        Route::post('/construction/search', ['as'=>'construction.search','uses'=>'ConstructionController@search']);

        Route::get('/construction/company/{id}', ['as'=>'construction.company','uses'=>'ConstructionController@company']);

        Route::get('/construction/api/{distance}/{latLng}', ['as'=>'construction.api','uses'=>'ConstructionController@map']);
    
        # Database Backups
        Route::resource('database','DatabaseBackupManagerController');
        
        # Reports
        Route::resource('reports','ReportsController');

        # User Management

        Route::get('cleanse', ['as'=>'users.cleanse','uses'=>'Admin\AdminUsersController@cleanse']);
        Route::get('users/import', ['as'=>'users.importfile', 'uses'=>'UsersImportController@getFile']);
        Route::get('usersimport', ['as'=>'usersimport.index','uses'=>'UsersImportController@index']);

        Route::post('users/bulkimport', ['as'=>'admin.users.bulkimport', 'uses'=>'UsersImportController@import']);
        Route::post('users/import', ['as'=>'users.mapfields','uses'=>'UsersImportController@mapfields']);
        
        
        Route::post('user/usererrors', ['as'=>'fixusercreateerrors','uses'=>'UsersImportController@fixUserErrors']);
        Route::post('user/importcleanse/delete', ['as'=>'user.importdelete','uses'=>'UserImportCleanseController@bulkdestroy']);
        Route::post('user/importcleanse/insert', ['as'=>'user.importinsert','uses'=>'UserImportCleanseController@createNewUsers']);
        Route::get('user/importfinal', ['as'=>'user.importfinal','uses'=>'UserImportCleanseController@importAllUsers']);
        Route::get('user/importflush', ['as'=>'importcleanse.flush','uses'=>'UserImportCleanseController@flush']);
        Route::resource('user/importcleanse', 'UserImportCleanseController');
        


        Route::get('users/newusers', ['as'=>'import.newusers','uses'=>'UsersImportController@newUsers']);
        Route::post('users/createnewusers', ['as'=>'import.createnewusers','uses'=>'UsersImportController@createNewUsers']);
        
        Route::get('users/serviceline/{serviceline}', ['as'=>'serviceline.user','uses'=>'Admin\AdminUsersController@index']);
        Route::get('users/nomanager', ['as'=>'nomanager','uses'=>'SalesOrgController@noManager']);
        Route::get('users/nomanager/export', ['as'=>'nomanager.export','uses'=>'SalesOrgController@noManagerExport']);

        Route::resource('users', 'Admin\AdminUsersController');


        # User Role Management

		Route::resource('roles','Admin\AdminRolesController');
	    #  Permissions
		Route::get('setapitoken',['as'=>'setapi.token','uses'=>'UsersController@resetApiToken']);
		Route::resource('permissions','Admin\AdminPermissionsController');

        #Howtofields
        Route::resource('howtofields', 'HowtofieldsController');



        #People
        Route::get('person/import', ['as'=>'person.bulkimport', 'uses'=>'PersonsController@import']);
        Route::post('person/import', ['as'=>'person.import', 'uses'=>'PersonsController@processimport']);
        Route::get('person/export', ['as'=>'person.export', 'uses'=>'PersonsController@export']);

        #ServiceLines
        Route::resource('serviceline', 'ServicelinesController');


        # Lead Status

        Route::resource('leadstatus', 'LeadStatusController');

         #Regions
        Route::resource('region', 'RegionsController');


        # Sales Process

        Route::resource('process', 'SalesProcessController');

        # Training
        
        Route::resource('training', 'TrainingController')->except(['index', 'show']);
        ;

        

        # Admin Dashboard
        Route::get('watching/{user}', ['as'=>'watch.watching', 'uses'=>'WatchController@watching']);
        Route::get('userlogin/{view?}', ['as'=>'admin.showlogins', 'uses'=>'Admin\AdminDashboardController@logins']);
        Route::get('userlogin/download/{view?}', ['as'=>'admin.downloadlogins', 'uses'=>'Admin\AdminDashboardController@downloadlogins']);
        Route::get('/', ['as'=>'dashboard','uses'=>'Admin\AdminDashboardController@dashboard']);

        #Comments
        Route::get('comment/download', ['as'=>'comment.download', 'uses'=>'CommentsController@download']);

        #News
        Route::get('news/{id}/audience', ['as'=>'news.audience','uses'=>'NewsController@audience']);
        Route::resource('news', 'NewsController', ['except' => ['index', 'show']]);


        #Notes
        Route::get('notes/{companyid}/co', ['as'=>'notes.company','uses'=>'NotesController@companynotes']);
        Route::get('locationnotes', ['as'=>'locations.notes', 'uses'=>'NotesController@index']);

        #Search Filters

        Route::get('searchfilters/analysis/{id?}', ['as'=>'vertical.analysis','uses'=>'SearchFiltersController@filterAnalysis']);
        Route::get('searchfilters/export/{id?}', ['as'=>'vertical.export','uses'=>'SearchFiltersController@export']);
        Route::get('searchfilters/promote/{filterid}', ['as'=>'admin.searchfilter.promote','uses'=>'SearchFiltersController@promote']);
        Route::get('searchfilters/demote/{filterid}', ['as'=>'admin.searchfilter.demote','uses'=>'SearchFiltersController@demote']);
        Route::get('filterform', 'SearchFiltersController@filterForm');

        Route::get('api/searchfilters/getAccounts', ['as'=>'getAccountSegments','uses'=>'SearchFiltersController@getAccountSegments']);
        Route::post('api/searchfilters/postAccounts', ['as'=>'postAccountSegments','uses'=>'SearchFiltersController@getAccountSegments']);
        Route::resource('searchfilters', 'SearchFiltersController');

	# Tracking
		Route::resource('track','TrackController');	

        # Seeder for relationships with servicelines
        //Route::get('seeder',['as'=>'seeder','uses'=>'CompaniesController@seeder']);
        //Route::get('apiseeder',['as'=>'apiseeder','uses'=>'UsersController@seeder']);


        # Versions
        Route::resource('versions', 'GitController');

        //Route::get('/leads/unassigned',['as'=>'unassigned.leads','uses'=>'LeadsController@unassignedleads']);
        //Route::get('branch/{bid}/people',['as'=>'test.branch.people', 'uses'=>'WebLeadsController@getSalesPeopleofBranch']);
        Route::get('authtest', ['as'=>'test','uses'=>'TestController@test']);
    });
