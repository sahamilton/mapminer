<?php


use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------

| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', ['as'=>'welcome','uses'=>'HomeController@index']);

    
        
//Route::get('testinbound', ['as'=>'testinbound', 'uses'=>'InboundMailController@inbound']);
//Route::get('testemail', ['as'=>'testemail', 'uses'=>'InboundMailController@testemail']);
    
    /*
    
    Route::get('/error',function(){
    Bugsnag::notifyError('ErrorType', 'Test Error');
    */
    // Routes for branch assignment verification
Route::get('/correction/{token}/{cid?}', ['as'=>'branchassociation.confirm', 'uses'=>'BranchManagementController@confirm']);
Route::get('/confirmation/{token}/{cid?}', ['as'=>'branchassociation.correct', 'uses'=>'BranchManagementController@correct']);

Route::auth();

Route::get('/home', ['as'=>'home', 'uses'=>'HomeController@index']);

Route::group(
    ['middleware' => 'auth'], function () {
        Route::get('/company/find', 'SearchController@searchCompanies');
        Route::get('/myleads/find', 'SearchController@searchMyLeads');
        //     About
        Route::get('about', ['as'=>'about', 'uses'=>'AdminAboutController@getInfo']);
        
           //     Activities
        //Route::get('branch/{branch}/activity', ['as'=>'branch.activity', 'uses'=>'ActivityController@getBranchActivtiesByType']);
        Route::get('activity/{activity}/complete', ['as'=>'activity.complete', 'uses'=>'ActivityController@complete']);
        Route::get('ical/{user}', ['as'=>'ical', 'uses'=>'iCalController@create']);

        Route::get('activities/export', ['as'=>'activities.export','uses'=>'ActivityController@export']);
        Route::get('activity/branch/{branch}', ['as'=>'branch.activity', 'uses'=>'ActivityController@branch']);
        //Route::get('followup', ['as'=>'followup', 'uses'=>'ActivityController@future']);
        Route::resource('activity', 'ActivityController');
        //Route::get('activity', [App\Http\Controllers\ActivityController::class, 'index'])->name('activity.index');
       //Route::get('activity', [App\Http\Livewire\ActivitiesTable::class])->name('activity.index');
        
        //     Address
        Route::post('address/merge', ['as'=>'addresses.merge', 'uses'=>'AddressController@mergeAddress']);
        Route::get('address/{address}/duplicates', ['as'=>'address.duplicates', 'uses'=>'AddressController@duplicates']);
        Route::post('address/{address}/rating', ['as'=>'address.rating', 'uses'=>'AddressController@rating']);
        Route::post('address/{address}/claim', ['as'=>'lead.claim', 'uses'=>'MyLeadsController@claim']);
        Route::resource('address', 'AddressController');
        
        //     Avatar
        Route::post('change/avatar', ['as'=>'change.avatar', 'uses'=>'AvatarController@store']);
        
        //   Branch Leads
        Route::get('branchleads/import', ['as'=>'branchleads.import', 'uses'=>'Imports\BranchLeadImportController@getFile']);
        //   Temporary branch lead assignment
        //Route::get('branchleads/assign', ['as'=>'branchlead.tempassign', 'uses'=>'BranchLeadController@assign']);
        Route::get('branchleads/stale', ['as'=>'branchleads.staleleads', 'uses'=>'BranchLeadController@staleLeads']);
        Route::resource('branchleads', 'BranchLeadController');
        //     Branches
        Route::get('/branches/{state}/state/', ['as'=>'branches.statelist', 'uses'=>'BranchesController@state']);
        Route::post('/branches/state', ['as'=>'branches.state', 'uses'=>'BranchesController@state']);
        Route::get('/branches/{state}/statemap', ['as'=>'branches.showstatemap', 'uses'=>'BranchesController@statemap']);
        Route::post('/branches/statemap', ['as'=>'branches.statemap', 'uses'=>'BranchesController@statemap']);
        Route::get('/branch/{branch}/map', ['as'=>'branch.map', 'uses'=>'BranchesController@map']);
        Route::get('/branches/map', ['as'=>'branches.map', 'uses'=>'BranchesController@mapall']);
        Route::get('branches/{branch}/shownearby', ['as' => 'shownearby.branch', 'uses' => 'BranchesController@showNearbyBranches']);
        //Route::get('branches/{state}/showstate', ['as' => 'showstate.branch', 'uses' => 'BranchesController@statemap']);
        Route::get('branches/{branch}/nearby', ['as' => 'nearby.branch', 'uses' => 'BranchesController@getNearbyBranches']);
        //Route::get('branches/{branch}/locations', ['as' => 'branch.locations', 'uses' => 'BranchesController@getLocationsServed']);
        Route::get('branches/{branch}/showlist', ['as' => 'showlist.locations', 'uses' => 'BranchesController@listNearbyLocations']);
        Route::get('branches/{branch}/salesteam', ['as' => 'showlist.salesteam', 'uses' => 'BranchesController@showSalesTeam']);
        Route::get('branches/managed/{person}', ['as'=>'managed.branch', 'uses'=>'BranchesController@getMyBranches']);
        Route::get('branches/managedmap/{mgrId}', ['as'=>'managed.branchmap', 'uses'=>'BranchesController@mapMyBranches']);
        Route::resource('branches', 'BranchesController', ['only' => ['index', 'show']]);
        //   Branch Activities
        Route::get('branch/{branch}/activities', ['as'=>'activity.branch', 'uses'=>'ActivityController@branchActivities']);
        Route::get('branch/{branch}/upcoming', ['as'=>'upcomingactivity.branch', 'uses'=>'ActivityController@branchUpcomingActivities']);
        Route::post('/branch/quickAdd', ['as'=>'quickadd', 'uses'=>'BranchesController@quickadd']);
        Route::post(
            'branch/activities', ['as'=>'activities.branch', 'uses'=>'ActivityController@branchActivities']
        );
        //     Branch Assignments
        Route::get(
            'branchassignment/{user}/change', ['as'=>'branchassignment.change', 'uses'=>'BranchManagementController@change']
        );
        Route::resource('branchassignments', 'BranchManagementController', ['only'=>['index', 'show', 'edit', 'update']]);

        //     Branch Dashboard
        Route::get('summary', ['as'=>'branch.summary', 'uses'=>'BranchDashboardController@summary']);
        Route::post('branches/period', ['as'=>'period.setperiod', 'uses'=>'BranchDashboardController@setPeriod']);
        Route::post('branches/dashboard', ['as'=>'branches.dashboard', 'uses'=>'BranchDashboardController@selectBranch']);
        Route::get('manager/{person}/dashboard', ['as'=>'manager.dashboard', 'uses'=>'MgrDashboardController@manager']);
        Route::post('manager/dashboard', ['as'=>'dashboard.select', 'uses'=>'DashboardController@select']);
        Route::get('dashboard', ['as'=>'dashboard', 'uses'=>'DashboardController@index']);
        Route::resource('branchdashboard', 'BranchDashboardController');
        
        Route::get('cal/{period}', ['as'=>'cal.month', 'uses'=>'CalendarController@getCalPeriod']);
        Route::resource('calendar', 'CalendarController')->except(['show']);
        
        //   Branch Requirements
         
        Route::get('requirements', ['as'=>'branch.requirements', 'uses'=>'BranchesController@requirements']);

        
        //   Manager Dashboard
        Route::get('dashboard/reset', ['as'=>'dashboard.reset', 'uses'=> 'DashboardController@reset']);
        Route::post('dashboard/setmanager', ['as'=>'dashboard.setManager', 'uses'=> 'DashboardController@setManager']);
        Route::get('mgrsummary', ['as'=>'newmgrsummary', 'uses'=>'MgrDashboardController@mgrSummary']);
        Route::get('namsummary', ['as'=>'namsummary', 'uses'=>'NamDashboardController@show']);
        Route::get('mydashboard', ['as'=>'mydashboard', 'uses'=>'DashboardController@reset']);
        Route::resource('mgrdashboard', 'MgrDashboardController');
        Route::post('namdashboard/select', ['as'=>'namdashboard.select', 'uses'=>'NAMDashboardController@select']);
        Route::resource('namdashboard', 'NAMDashboardController');
        //   Dashboard
        
        Route::get('newdashboard/{company}/company', ['as'=>'newdashboard.company', 'uses'=>'NewDashboardController@showCompany']);
        Route::get('newdashboard/{person}/manager', ['as'=>'newdashboard.manager', 'uses'=>'NewDashboardController@showManager']);
        Route::get('newdashboard/{person}/leads', ['as'=>'newdashboard.leads', 'uses'=>'NewDashboardController@showLeads']);
        Route::get('newdashboard/{company}/branch/{branch}', ['as'=>'newdashboard.branchdetail', 'uses'=>'NewDashboardController@showCompanyBranch']);
        Route::get('newdashboard', ['as'=>'newdashboard', 'uses'=>'NewDashboardController@index']);
        Route::post('dashboard/period', ['as'=>'newperiod.setperiod', 'uses'=>'NewDashboardController@setPeriod']);
        Route::resource('dashboard', 'DashboardController');
        // Branch Next Week View
        Route::resource('branchsummary', 'BranchSummaryController');
        //   Branch PipelineMyLea
        Route::get('branch/pipeline', ['as'=>'branches.pipeline', 'uses'=>"OpportunityController@pipeline"]);
           
        //   Branch Leads
        Route::get('location/list', ['as'=>'lead.list','uses'=>'MyLeadsController@list']);
        Route::get('branch/leads/{branch?}', ['as'=>'branch.leads', 'uses'=>'MyLeadsController@index']);
        Route::get('branch/{branch}/leads', ['as'=>'lead.branch', 'uses'=>'MyLeadsController@branchLeads']);
        Route::post('branch/lead', ['as'=>'leads.branch', 'uses'=>'MyLeadsController@branchLeads']);
        
        Route::resource('branchleadsimport', 'Imports\BranchLeadImportController');
        //     Comments
        Route::resource('comment', 'CommentsController');
        
        //     Companies
        Route::post('company/filter', ['as'=>'company.filter', 'uses'=>'CompaniesController@filter']);
        Route::get('/company/{company}/state/{state?}', ['as'=>'company.state', 'uses'=>'CompaniesController@stateselect']);
        Route::post('/company/stateselect', ['as'=>'company.stateselect', 'uses'=>'CompaniesController@stateselector']);
        
        Route::get('/company/{company}/statemap/{state}', ['as'=>'company.statemap', 'uses'=>'CompaniesController@statemap']);
        Route::get('/company/vertical/{vertical}', ['as'=>'company.vertical', 'uses'=>'CompaniesController@vertical']);
        Route::get('/company/{company}/segment/{segment}', ['as'=>'company.segment', 'uses'=>'CompaniesController@show']);
        Route::get('/company/{accounttype}', ['as'=>'company.type', 'uses'=>'CompaniesController@byType']);
        Route::get('/companies', ['as'=>'company.index', 'uses'=>'CompaniesController@index']);
        Route::get('/companies/{company}', ['as'=>'company.show', 'uses'=>'CompaniesController@show']);

        Route::get('/nearbycompanies', ['as'=>'nearbycompanies', 'uses'=>'CompaniesController@nearby']);
        //Route::resource('companies', 'CompaniesController', ['only' => ['index', 'show']])->name('company');
        
        
        //   Contacts
        Route::get('contacts/{id}/vcard', ['as'=>'contacts.vcard', 'uses'=>'LocationContactController@vcard']);
        Route::get('contacts/{contact}/primary', ['as'=>'contacts.primary', 'uses'=>'LocationContactController@makePrimaryContact']);
        Route::resource('contacts', 'LocationContactController');
        
        Route::post('contact/branch', ['as'=>'contact.branch', 'uses'=>'LocationContactController@branchcontacts']);
        Route::get('contacts/branch/{branch}', ['as'=>'contacts.branch', 'uses'=>'LocationContactController@branchcontacts']);
        Route::get('contacts/{branch}/export', ['as'=>'contacts.export', 'uses'=>'LocationContactController@export']);

        Route::resource('mycontacts', 'MyContactsController');
        Route::post('dataquality/details', ['as'=>'dataquality.details', 'uses'=>"DataQualityController@details"]);
        Route::post('dataquality/branch', ['as'=>'dataquality.branch', 'uses'=>'DataQualityController@branch']); 
        Route::resource('dataquality', 'DataQualityController');  
           //   Documents
        Route::resource('docs', 'DocumentsController', ['only' => ['index', 'show']]);
        
        //   Feedback
        
        Route::resource('feedback', 'FeedbackController', ['only'=>['index', 'show', 'store']]);
        
        //     Geocoding
        
        Route::post('findme', ['as'=>'findme', 'uses'=>'GeoCodingController@findMe']);
        Route::get('findme', ['as'=>'findme', 'uses'=>'MapsController@findme']);
        
        // Industries
        Route::resource('naic', 'NaicsController');
        
        //     Industry Focus
        Route::resource('/industryfocus', 'PersonIndustryController');
    
        //   Lead
        Route::get('leads',  ['as'=>'lead.assign', 'uses'=>'LeadsController@assign']);
        Route::post('lead/{address}/reassign', ['as'=>'lead.reassign', 'uses'=>'MyLeadsController@reassign']);
        //     Locations
        Route::get('search/leads', ['as'=>'search.leads', 'uses'=>'SearchController@leads']);
        Route::get('location/{address}/branches', ['as' => 'assign.location', 'uses' => 'LocationsController@getClosestBranch']);
        Route::get('locations/{address}/vcard', ['as'=>'locations.vcard', 'uses'=>'LocationsController@vcard']);
        Route::get('location/{locationId}/branchmap', ['as' => 'nearby.location', 'uses' => 'LocationsController@getClosestBranchMap']);
        Route::get('location/shownearby', ['as' => 'shownearby.location', 'uses' => 'LocationsController@showNearbyLocations']);
        Route::get('location/nearby', ['as' => 'nearby/location', 'uses' => 'LocationsController@mapNearbyLocations']);
        Route::post('location/contact', ['as'=>'location.addcontact', 'uses'=>'LocationContactController@store']);
        Route::get('customer/{address}', ['as' => 'mark.customer', 'uses' => 'LocationsController@markAsCustomer']);
        Route::post('location/{address}/transferrequest', ['as'=>'lead.transferrequest','uses' => 'LocationsController@transferrequest' ]);
        Route::resource('locations', 'LocationsController', ['only' => ['show']]);
        
        //     Managers
        Route::get('manage/account', ['as'=>'managers.view', 'uses'=>'ManagersController@manager']);
        Route::post('manage/account', ['as'=>'managers.changeview', 'uses'=>'ManagersController@selectaccounts']);
        Route::get('locationnotes/{company}', ['as'=>'locationnotes.show', 'uses'=>'ManagersController@showManagerNotes']);
        
        //     Maps
        Route::get('api/mylocalbranches/{distance}/{latLng}/{limit?}', ['as' => 'map.mybranches', 'uses' => 'MapsController@findLocalBranches']);
        Route::get('api/mylocalpeople/{distance}/{latLng}/{limit?}', ['as' => 'map.mypeople', 'uses' => 'MapsController@findLocalPeople']);
        Route::get('api/myAccountsList/{distance}/{latLng}', ['as' => 'list.myaccounts', 'uses' => 'MapsController@findLocalAccounts']);
        Route::get('api/mylocalaccounts/{distance}/{latLng}/{companyId?}', ['as' => 'map.mylocations', 'uses' => 'MapsController@findLocalAccounts']);
        Route::get('api/mybranchList/{distance}/{latLng}', ['as' => 'list.mybranches', 'uses' => 'MapsController@findLocalBranches']);
        Route::get('api/people/map', ['as'=>'salesmap', 'uses'=>'PersonsController@getMapLocations']);
        Route::post('api/note/post', ['as'=>'postNewNote', 'uses'=>'NotesController@store']);
        Route::get('api/note/get', ['as'=>'addNewNote', 'uses'=>'NotesController@store']);
        Route::get('api/geo', ['as'=>'geo', 'uses'=>'GeoCodingController@index']);
        Route::get('api/myleads/{person}/{distance}/{latLng}/{limit?}', ['as'=>'myleadsmap', 'uses'=>'MyLeadsController@findNearbyLeads']);
        Route::get('api/address/{distance}/{latLng}', ['as'=>'addressmap', 'uses'=>'AddressController@findLocations']);
        
        // Nearby
        // 
        
        Route::resource('nearby', 'NearbyController');


        //     News
        Route::resource('news', 'NewsController',  ['' => ['index', 'show']]);
        Route::get('currentnews', ['as'=>'currentnews', 'uses'=>'NewsController@currentNews']);
        
        //     Notes
        Route::get('mynotes', ['as'=>'mynotes', 'uses'=>'NotesController@mynotes']);
        Route::get('exportlocationnotes/{company}', ['as'=>'exportlocationnotes', 'uses'=>'ManagersController@exportManagerNotes']);
        Route::resource('notes', 'NotesController');
        
        //     Opportunity
        Route::post('/branchlead/{address}', ['as'=>'branch.lead.add', 'uses'=>'OpportunityController@addToBranchLeads']);
        Route::post('/opportunities/{opportunity}/close/', ['as'=>'opportunity.close', 'uses'=>'OpportunityController@close']);
        Route::post('/opportunities/branch/', ['as'=>'opportunity.branch', 'uses'=>'OpportunityController@getBranchOpportunities']);
        Route::post('/opportunities/manager/', ['as'=>'opportunity.manager', 'uses'=>'OpportunityController@managerOpportunities']);
        Route::get('/opportunities/branch/{branch}', ['as'=>'opportunities.branch', 'uses'=>'OpportunityController@branchOpportunities']);
        Route::delete('opportunity/{opportunity}/destroy', ['as'=>'opportunity.remove', 'uses'=>'OpportunityController@destroy']);
        Route::get('/opportunity/chart', ['as'=>'oppoprtunity.chart', 'uses'=>'OpportunityController@chart']);
        Route::get('api/opportunity/{distance}/{latLng}', ['as'=>'opportunitymap', 'uses'=>'OpportunityController@findOpportunities']);
        
        Route::resource('opportunity', 'OpportunityController');
        //     Orders
        Route::resource('orders', 'OrdersController');
        
        //     People
        
        Route::get('person/{person}/showmap', ['as'=>'showmap.person', 'uses'=>'PersonsController@showmap']);
        Route::get('people/map', ['as'=>'person.map', 'uses'=>'PersonsController@map']);
        Route::get('geocode/people', ['as'=>'person.geocode', 'uses'=>'PersonsController@geoCodePersons']);
        Route::get('person/{vertical}/vertical', ['as'=>'person.vertical', 'uses'=>'PersonsController@vertical']);
        Route::resource('person', 'PersonsController', ['only' => ['index', 'show']]);
        Route::get('adduser/{oracle}', ['as'=>'oracle.useradd','uses'=>'OracleController@addUser']);
        //   Projects
        Route::get('api/mylocalprojects/{distance}/{latLng}', ['as' => 'map.myprojects', 'uses' => 'ProjectsController@findNearbyProjects']);
        Route::get('projects/{id}/claim', ['as'=>'projects.claim', 'uses'=>'ProjectsController@claimProject']);
        Route::post('project/{id}/close', ['as'=>'projects.close', 'uses'=>'ProjectsController@closeproject']);
        Route::get('projects/myprojects', ['as'=>'projects.myprojects', 'uses'=>'ProjectsController@myProjects']);
        Route::get('projects/download', ['as'=>'projects.export', 'uses'=>'ProjectsController@exportMyProjects']);
        Route::post('project/{id}/transfer', ['as'=>'projects.transfer', 'uses'=>'ProjectsController@transfer']);
        Route::post('projects/contact', ['as'=>'projects.addcontact', 'uses'=>'ProjectsController@addCompanyContact']);
        Route::post('projects/firm', ['as'=>'projects.addcontactfirm', 'uses'=>'ProjectsController@addProjectCompany']);
        Route::resource('projects', 'ProjectsController', ['only' => ['index', 'show']]);
        
        Route::resource('projectcompany', 'ProjectCompanyController', ['only' => ['show']]);
        
        
        //     Regions
        //Route::resource('region', 'RegionsController', ['only' => ['index', 'show']]);
        
        //     ServiceLines
        Route::get('serviceline/{serviceline}/{type?}', ['as'=>'serviceline.accounts', 'uses'=>'ServicelinesController@show']);
        Route::resource('serviceline', 'ServicelinesController', ['only' => ['index', 'show']]);
        
        //     Branch Sales Campaigns
        Route::get('branchcampaigns/{campaign}/{branch}', ['as'=>'branchcampaign.show', 'uses'=>'BranchCampaignController@show']);

        Route::post('branchcampaigns/add', ['as'=>'branchcampaign.add', 'uses'=>'BranchCampaignController@store']);
        Route::post('branchcampaigns/remove', ['as'=>'branchcampaign.delete', 'uses'=>'BranchCampaignController@delete']);

        Route::post('branchcampaigns/change', ['as'=>'branchcampaign.change', 'uses'=>'BranchCampaignController@change']);     

        Route::post('branchcampaigns/{campaign}/setmgr', ['as'=>'branchcampaign.manager','uses'=>'BranchCampaignController@setManager']);
        Route::get('campaigns/{campaign}', ['as'=>'campaigns.track', 'uses'=>'CampaignTrackingController@show']);
        Route::resource('branchcampaigns', 'BranchCampaignController', ['except'=>['destroy']]);
        //Route::resource('salesactivity', 'SalesActivityController', ['only' => ['show']]);
        
        //   Sales organization
        Route::get('salesorg/coverage', ['as'=>'salescoverage', 'uses'=>'SalesOrgController@salesCoverageMap']);
        
        Route::post('salesorg/find', ['as'=>'salesorg.find', 'uses'=>'SalesOrgController@find']);
        // add salesorg reqource with show and index only
        Route::resource('salesorg', 'SalesOrgController', ['only'=>['index', 'show']]);

        
        
        
        Route::get('branch/{branchId}/salesteam', array('as' => 'branch.salesteam', 'uses' => 'BranchesController@showSalesTeam'));
        
        
        //   Sales leads
        Route::get('prospect/{id}/accept', ['as'=>'saleslead.accept', 'uses'=>'SalesLeadsController@accept']);
        Route::get('prospect/{id}/decline', ['as'=>'saleslead.decline', 'uses'=>'SalesLeadsController@decline']);
        Route::get('prospects/{pid}/showrep', ['as'=>'salesleads.showrep', 'uses'=>'SalesLeadsController@showrep']);
        Route::get('prospects/download', ['as'=>'salesleads.download', 'uses'=>'SalesLeadsController@download']);
        Route::get('prospects/{id}/showrepdetail/{pid}', ['as'=>'salesleads.showrepdetail', 'uses'=>'SalesLeadsController@showrepdetail']);
        Route::get('leadrank', ['as'=>'api.leadrank', 'uses'=>'SalesLeadsController@rank']);
        Route::post('prospect/{id}/close', ['as'=>'saleslead.close', 'uses'=>'SalesLeadsController@close']);
        Route::get('prospect/{pid}/leads', ['as'=>'saleslead.mapleads', 'uses'=>'SalesLeadsController@mapleads']);
        Route::resource('salesleads', 'SalesLeadsController');
        
        //   Sales Notes
        Route::get('salesnotes/{company}', ['as'=>'salesnotes.company', 'uses'=>'SalesNotesController@show']);
        Route::get('salesnotes/print/{company}', ['as'=>'salesnotes.print', 'uses'=>'SalesNotesController@printSalesNotes']);
        Route::resource('salesnotes', 'SalesNotesController')->only(['show']);
        
        //   Sales Resources
        Route::get('resources', ['as'=>'resources.view', 'uses'=>'WatchController@getCompaniesWatched']);
        //   Search
        //   
        //   Team
        //   
        //   
        Route::get('team/manage/{user?}', ['as'=>'team.manage', 'uses'=>'TeamController@index']);
        Route::get('team/{team}/export', ['as'=>'team.export', 'uses'=>'TeamActivityController@export']);
        Route::resource('team', 'TeamActivityController');
        
        
        //   Watch List
        Route::get('watch', ['as'=>'watch.index', 'uses'=>'WatchController@index']);
        Route::get('watch/export', ['as'=>'watch.export', 'uses'=>'WatchController@export']);
        Route::get('watch/add/{watch}', ['as'=>'watch.add', 'uses'=>'WatchController@create']);
        Route::get('watch/delete/{locationID}', ['as'=>'watch.delete', 'uses'=>'WatchController@destroy']);
        Route::get('watch/map', ['as'=>'watch.map', 'uses'=>'WatchController@showwatchmap']);
        Route::get('cowatch/export', ['as'=>'company.watchexport', 'uses'=>'WatchController@companywatchexport']);
        //     New Leads
        Route::resource('myleads', 'MyLeadsController');
        Route::post('branch/{address}/remove', ['as'=>'branch.lead.remove', 'uses'=>'OpportunityController@remove']);
        //Route::get('myclosedleads', ['as'=>'myclosedleads', 'uses'=>'MyLeadsController@closedleads']);
        Route::post('mylead/{id}/close', ['as'=>'mylead.close', 'uses'=>'MyLeadsController@close']);
        Route::resource('myleadsactivity', 'MyLeadsActivityController');
        Route::resource('myleadscontact', 'MyLeadsContactController');
        
        Route::post('reports/{report}/run', ['as'=>'reports.run', 'uses'=>'ReportsController@run']);

        Route::resource('reports', 'ReportsController', ['only' => ['index', 'show']]);
       
        //     AJAX Links
        //     // Move these to api routes
        Route::get('api/company/{companyId}/statemap/{state}', ['as'=>'company.statemap', 'uses'=>'LocationsController@getStateLocations']);
    
        Route::get('api/news/nonews', 'NewsController@noNews');
        Route::get('api/news/setnews', 'NewsController@setNews');

        Route::get('api/branch/map', ['as'=>'branch/map', 'uses'=>'BranchesController@getAllbranchmap']);
        Route::get('api/branch/statemap/{state?}', ['as'=>'branch.statemap', 'uses'=>'BranchesController@makeStateMap']);
        Route::get('api/location/{locationId}/branchnearby', ['as'=>'shownearby.branchlocation', 'uses' => 'MapsController@getLocationsPosition']);

        Route::get('api/watchmap', ['as'=>'api.watchmap', 'uses'=>'WatchController@watchmap']);
        Route::match(['get', 'post'], 'api/advancedsearch', ['as'=>'setSearch', 'uses'=>'SearchFiltersController@setSessionSearch']);
        Route::get('documents/select', ['as'=>'documents.select', 'uses'=>'DocumentsController@select']);
        Route::post('documents/select', ['as'=>'documents.select', 'uses'=>'DocumentsController@getDocuments']);
        Route::get('rank', ['as'=>'api.rank', 'uses'=>'DocumentsController@rank']);
        Route::get('watchedby/{id}', ['as'=>'watchedby', 'uses'=>'DocumentsController@watchedby']);
        Route::get('documents/{id}', ['as'=>'documents.show', 'uses'=>'DocumentsController@show']);
        //   Search Settings
        Route::get('/salesteam/find', 'SearchController@searchSalesteam');


        //   Training
        Route::resource('training', 'TrainingController', ['only' => ['index', 'show']]);
        //   Impersonate
        Route::impersonate();

        // User Tracking
        
        Route::get('usertracking', ['as'=>'usertracking.index', 'uses'=>'UserTrackingController@index']);
        //Route::post('usertracking/show', ['as'=>'usertracking.show', 'uses'=>'UserTrackingController@show']);
        Route::get('usertracking/{person}/show', ['as'=>'usertracking.show', 'uses'=>'UserTrackingController@show']);
        Route::get('usertracking/{model}/detail', ['as'=>'usertracking.detail', 'uses'=>'UserTrackingController@detail']);
        

        //     User (Profile) settings
        Route::post('profile/update', ['as'=>'update.profile', 'uses'=>'UsersController@updateProfile']);
        Route::get('profile', ['as'=>'myProfile', 'uses'=>'UsersController@profile']);
        Route::resource('user', 'UsersController', ['only' => ['show', 'update']]);;
        
        Route::get('resetpassword', ['as'=>'reset.password', 'uses'=>'Auth\\ResetPasswordController@showResetForm']);
        // legacy login address
        Route::get(
            'user/login', function () {
                if (auth()->check()) {
                    return redirect()->route('welcome');
                }
                redirect()->intended('login');
            
            }
        );

        Route::get('newmap', ['as'=>'newmaps', 'uses'=>'MapsController@new']);
        Route::get('mobile/{address}/show', ['as'=>'mobile.show', 'uses'=>'MobileController@show']);
        Route::get('mobile/{address}/check', ['as'=>'mobile.checkaddress','uses'=>'MobileController@check']);
        Route::get('mobile/searchaddress', ['as'=>'mobile.searchaddress', 'uses'=>'MobileController@searchaddress']);
        Route::match(['get', 'post'], 'searchleads', ['as'=>'searchleads', 'uses'=>'MyLeadsController@searchleads']);
    
        Route::post('mobile/search', ['as'=>'mobile.search', 'uses'=>'MobileController@search']);
        Route::post('mobile/select', ['as'=>'mobile.select', 'uses'=>'MobileController@select']);
        Route::resource('mobile', 'MobileController');


        Route::get('searchtest', ['as'=>'typeahead', 'uses'=>'SearchController@typeahead']);
    }
);
