<?php
use Illuminate\Http\Request;       
   /**
     *  Admin Routes
     *  ------------------------------------------
     */
        
        Route::resource('audits', 'AuditController');
      
        Route::get('accounttype/locations', ['as'=>'accounttype.locations', 'uses'=>'AccounttypesController@locations']);

        Route::get('branchassignments/select', ['as'=>'branchassignment.check', 'uses'=>'Admin\BranchManagementController@select']);
        Route::post('branchassignments/email', ['as'=>'branchteam.email', 'uses'=>'Admin\BranchManagementController@confirm']);
        Route::post('branchassignments/send', ['as'=>'branchassignments.send', 'uses'=>'Admin\BranchManagementController@emailAssignments']);
        Route::get('branch/manage', ['as'=>'branch.management', 'uses'=>'Admin\BranchManagementController@index']);
        Route::get('branch/manage/export/{type?}', ['as'=>'branches.manage.export', 'uses'=>'Admin\BranchManagementController@export']);
        Route::get('branch/check', ['as'=>'branch.check', 'uses'=>'BranchAssignmentController@checkBranchReporting']);
        
        
        //   Campaigns (email)
        Route::get('campaigns/{campaign}/track', ['as'=>'campaigns.track', 'uses'=>'CampaignTrackingController@show']);
        Route::get('campaigns/{campaign}/launch', ['as'=>'campaigns.launch', 'uses'=>'CampaignController@launch']);
        Route::get('campaigns/{campaign}/{branch}/test', ['as'=>'branchcampaign.test', 'uses'=>'CampaignController@branchTest']);
        Route::post('campaigns/stats', ['as'=>'campaigns.stats', 'uses'=>'CampaignController@campaignStats']);
        Route::get('campaigns/reports/{campaign?}', ['as'=>'campaigns.select', 'uses'=>'CampaignController@selectReport']);
        Route::get('campaigns/{campaign}/export', ['as'=>'campaigns.export', 'uses'=>'CampaignTrackingController@export']);
        Route::get('campaigns/{campaign}/companyexport', ['as'=>'campaigns.company.export', 'uses'=>'CampaignTrackingController@exportCompany']);
        Route::get('campaigns/{campaign}/manager/{person}', ['as'=>'campaigns.manager', 'uses'=>'CampaignController@select']);
        Route::post('campaigns/{campaign}/report', ['as'=>'campaigns.report', 'uses'=>'CampaignController@export']);
        Route::post('campaigns/{campaign}/company', ['as'=>'campaigns.companyreport', 'uses'=>'CampaignTrackingController@company']);
        Route::get('campaigns/{campaign}/company', ['as'=>'campaigns.company', 'uses'=>'CampaignTrackingController@summaryByCompany']);
        Route::get('campaigns/{campaign}/company/{company}', ['as'=>'campaigns.company.detail', 'uses'=>'CampaignTrackingController@detailByCompany']);
        Route::get('campaigns/populate', ['as'=>'campaigns.populate', 'uses'=>'CampaignController@populateAddressCampaign']);

        Route::resource('campaigns', 'CampaignController');

        Route::resource('campaigndocuments', 'CampaignDocumentsController');

        //   Construction
        Route::resource('/construction', 'ConstructionController');

        Route::post('/construction/search', ['as'=>'construction.search', 'uses'=>'ConstructionController@search']);

        Route::get('/construction/company/{id}', ['as'=>'construction.company', 'uses'=>'ConstructionController@company']);

        Route::get('/construction/api/{distance}/{latLng}', ['as'=>'construction.api', 'uses'=>'ConstructionController@map']);

        //   Database Backups
        Route::resource('database', 'DatabaseBackupManagerController');

        

        //   User Management

        Route::get('cleanse', ['as'=>'users.cleanse', 'uses'=>'Admin\AdminUsersController@cleanse']);
        
        Route::get('users/import', ['as'=>'users.importfile', 'uses'=>'Imports\UsersImportController@getFile']);
        Route::get('usersimport', ['as'=>'usersimport.index', 'uses'=>'Imports\UsersImportController@index']);

        Route::post('users/bulkimport', ['as'=>'admin.users.bulkimport', 'uses'=>'Imports\UsersImportController@import']);
        Route::post('users/import', ['as'=>'users.mapfields', 'uses'=>'Imports\UsersImportController@mapfields']);
        
        Route::get('users/deleted', ['as'=>'deleted.users', 'uses'=>'Admin\AdminUsersController@deleted']);
        Route::get('users/{id}/restore', ['as'=>'users.restore', 'uses'=>'Admin\AdminUsersController@restore']);

        Route::delete('users/{id}/purge', ['as'=>'users.permdestroy', 'uses'=>'Admin\AdminUsersController@permdeleted']);

        Route::post('users/purge', ['as'=>'users.bulkPermDestroy', 'uses'=>'Admin\AdminUsersController@bulkPermDelete']);



        Route::post('user/usererrors', ['as'=>'fixusercreateerrors', 'uses'=>'Imports\UsersImportController@fixUserErrors']);
        Route::post('user/importcleanse/delete', ['as'=>'user.importdelete', 'uses'=>'Imports\UserImportCleanseController@bulkdestroy']);
        Route::post('user/importcleanse/insert', ['as'=>'user.importinsert', 'uses'=>'Imports\UserImportCleanseController@createNewUsers']);
        Route::get('user/importfinal', ['as'=>'user.importfinal', 'uses'=>'Imports\UserImportCleanseController@importAllUsers']);
        Route::get('user/importflush', ['as'=>'importcleanse.flush', 'uses'=>'Imports\UserImportCleanseController@flush']);

        Route::get('user/bulkdelete', ['as'=>'bulkdelete', 'uses'=>'Admin\AdminUsersController@bulkdelete']);
        Route::post('user/bulkdelete', ['as'=>'users.bulkdelete', 'uses'=>'Admin\AdminUsersController@confirmDelete']);
        Route::post('user/massdelete', ['as'=>'users.massdelete', 'uses'=>'Admin\AdminUsersController@massDelete']);
        Route::resource('user/importcleanse', 'Imports\UsersImportController');
         // Oracle
        

        
        Route::get('oracle/import', ['as'=>'oracle.importfile', 'uses'=>'Imports\OracleImportController@getfile']);
        Route::post('oracle/bulkimport', ['as'=>'oracle.import', 'uses'=>'Imports\OracleImportController@import']);
        Route::post('/oracle/mapfields', ['as'=>'oracle.mapfields', 'uses'=>'Imports\OracleImportController@mapfields']);

        Route::get('oracle/list', ['as'=>'oracle.list', 'uses'=>'OracleController@showOracle']);
        Route::get('oracle/verify', ['as'=>'oracle.verify', 'uses'=>'OracleController@verify']);
        Route::get('oracle/manager', ['as'=>'oracle.manager', 'uses'=>'OracleController@matchManager']);
        Route::get('oracle/unmatched', ['as'=>'oracle.unmatched', 'uses'=>'OracleController@unmatched']);
        Route::resource('oracle', 'OracleController');
        //Route::get('users/sync/delete', ['as'=>'users.sync.delete', 'uses'=>'Admin\UserSyncController@delete']);
        
        //Route::post('users/sync/confirm', ['as'=>'users.sync.confirm', 'uses'=>'Admin\UserSyncController@confirm']);

        //Route::get('users/sync/reconfirm', ['as'=>'users.sync.reconfirm', 'uses'=>'Admin\UserSyncController@reconfirm']);

        //Route::post('users/sync/purge', ['as'=>'users.sync.purge', 'uses'=>'Admin\UserSyncController@purge']);
        
        //Route::get('users/newusers', ['as'=>'import.newusers', 'uses'=>'Imports\UsersImportController@newUsers']);
        
        //Route::post('users/createnewusers', ['as'=>'import.createnewusers', 'uses'=>'Imports\UsersImportController@createNewUsers']);

        Route::get('users/serviceline/{serviceline}', ['as'=>'serviceline.user', 'uses'=>'Admin\AdminUsersController@index']);
        Route::get('users/nomanager', ['as'=>'nomanager', 'uses'=>'SalesOrgController@noManager']);
       
        Route::get('users/export', ['as'=>'users.export', 'uses'=>'UsersController@export']);
        Route::resource('users', 'Admin\AdminUsersController');

        Route::post('lastlogged', ['as'=>'lastlogged', 'uses'=>'Admin\AdminUsersController@lastlogged']);
        //   Reports
        //Route::post('reports/{report}/run', ['as'=>'reports.run', 'uses'=>'ReportsController@run']);
        
        Route::post('reports/{report}/send', ['as'=>'reports.send', 'uses'=>'ReportsController@send']);
        Route::get('reports/review/{filename?}', ['as'=>'reports.review', 'uses'=>'ReportsController@review']);
        Route::post('reports/{report}/addrecipient', ['as'=>'reports.addrecipient', 'uses'=>'ReportsController@addRecipient']);
        Route::post('reports/{report}/removerecipient', ['as'=>'reports.removerecipient', 'uses'=>'ReportsController@removeRecipient']);
        Route::resource('reports', 'ReportsController', ['except'=>['index','show']]);

        
        
        
        //   User Role Management

        Route::resource('roles', 'Admin\AdminRolesController');
            //    Permissions
        Route::get('setapitoken', ['as'=>'setapi.token', 'uses'=>'UsersController@resetApiToken']);
        Route::resource('permissions', 'Admin\AdminPermissionsController');

        //     Howtofields
        Route::resource('howtofields', 'HowtofieldsController');



        //     People
        Route::get('person/import', ['as'=>'person.bulkimport', 'uses'=>'PersonsController@import']);
        Route::post('person/import', ['as'=>'person.import', 'uses'=>'PersonsController@processimport']);
        Route::get('person/export', ['as'=>'person.export', 'uses'=>'PersonsController@export']);
        
        //     ServiceLines
        Route::resource('serviceline', 'ServicelinesController');


        //   Lead Status

        Route::resource('leadstatus', 'LeadStatusController');

         //     Regions
        Route::resource('region', 'RegionsController');


        //   Sales Process

        Route::resource('process', 'SalesProcessController');

        //   Training

        Route::resource('training', 'TrainingController')->except(['index', 'show']);
        ;

        //   Admin Dashboard
        Route::get('watching/{user}', ['as'=>'watch.watching', 'uses'=>'WatchController@watching']);
        Route::get('userlogin/{view?}', ['as'=>'admin.showlogins', 'uses'=>'Admin\AdminDashboardController@logins']);
        Route::get('userlogin/download/{view?}', ['as'=>'admin.downloadlogins', 'uses'=>'Admin\AdminDashboardController@downloadlogins']);
        Route::get('/', ['as'=>'dashboard', 'uses'=>'Admin\AdminDashboardController@dashboard']);

        Route::post('dashboard/show', ['as'=>'dashboard.show', 'uses'=>'NewDashboardController@show']);

        //     Comments
        Route::get('comment/download', ['as'=>'comment.download', 'uses'=>'CommentsController@download']);

        //     News
        Route::get('news/{id}/audience', ['as'=>'news.audience', 'uses'=>'NewsController@audience']);
        Route::resource('news', 'NewsController', ['except' => ['index', 'show']]);


        //     Notes
        Route::get('notes/{companyid}/co', ['as'=>'notes.company', 'uses'=>'NotesController@companynotes']);
        Route::get('locationnotes', ['as'=>'locations.notes', 'uses'=>'NotesController@index']);

        //     Search Filters
        Route::get('htfimport', ['as'=>'htfimport', 'uses'=>'HowtofieldsController@import']);
        Route::get('searchfilters/analysis/{id?}', ['as'=>'vertical.analysis', 'uses'=>'SearchFiltersController@filterAnalysis']);
        Route::get('searchfilters/export/{id?}', ['as'=>'vertical.export', 'uses'=>'SearchFiltersController@export']);
        Route::get('searchfilters/promote/{filterid}', ['as'=>'admin.searchfilter.promote', 'uses'=>'SearchFiltersController@promote']);
        Route::get('searchfilters/demote/{filterid}', ['as'=>'admin.searchfilter.demote', 'uses'=>'SearchFiltersController@demote']);
        Route::get('filterform', 'SearchFiltersController@filterForm');

        Route::get('api/searchfilters/getAccounts', ['as'=>'getAccountSegments', 'uses'=>'SearchFiltersController@getAccountSegments']);
        Route::post('api/searchfilters/postAccounts', ['as'=>'postAccountSegments', 'uses'=>'SearchFiltersController@getAccountSegments']);
        Route::resource('searchfilters', 'SearchFiltersController');
        //   Jobs
        Route::resource('jobs', 'FailedJobsController');
        Route::resource('testjob', 'TestJobController');


        //   Tracking
        Route::resource('track', 'TrackController');

        //   Versions
        Route::resource('versions', 'GitController');

        Route::get('authtest', ['as'=>'test', 'uses'=>'TestController@test']);

