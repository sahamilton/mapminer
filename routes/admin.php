<?php
use Illuminate\Http\Request;       
   /**
     *  Admin Routes
     *  ------------------------------------------
     */
Route::group(
    ['prefix' => 'admin', 'middleware' =>'admin'], function () {
        Route::get('branchassignments/select', ['as'=>'branchassignment.check', 'uses'=>'Admin\BranchManagementController@select']);
        Route::post('branchassignments/email', ['as'=>'branchteam.email', 'uses'=>'Admin\BranchManagementController@confirm']);
        Route::post('branchassignments/send', ['as'=>'branchassignments.send', 'uses'=>'Admin\BranchManagementController@emailAssignments']);
        Route::get('branch/manage', ['as'=>'branch.management', 'uses'=>'Admin\BranchManagementController@index']);
        Route::get('branch/check', ['as'=>'branch.check', 'uses'=>'Admin\AdminUsersController@checkBranchAssignments']);
        

        // Route::get('branch/{branch}/purge', ['as'=>'branch.purge','uses'=>'BranchesController@delete']);
        
        //   Campaigns (email)
        Route::get('campaigns/{campaign}/track', ['as'=>'campaigns.track', 'uses'=>'CampaignTrackingController@show']);
        Route::get('campaigns/{campaign}/launch', ['as'=>'campaigns.launch', 'uses'=>'CampaignController@launch']);
        Route::get('campaigns/{campaign}/{branch}/test', ['as'=>'branchcampaign.test', 'uses'=>'CampaignController@branchTest']);
        Route::post('campaigns/stats', ['as'=>'campaigns.stats', 'uses'=>'CampaignController@campaignStats']);
        Route::get('campaigns/reports/{campaign?}', ['as'=>'campaigns.select', 'uses'=>'CampaignController@selectReport']);
        Route::get('campaigns/{campaign}/export', ['as'=>'campaigns.export', 'uses'=>'CampaignTrackingController@export']);
        Route::get('campaigns/{campaign}/companyexport', ['as'=>'campaigns.company.export', 'uses'=>'CampaignTrackingController@exportCompany']);
        Route::post('campaigns/{campaign}/report', ['as'=>'campaigns.report', 'uses'=>'CampaignController@export']);
        Route::post('campaigns/{campaign}/company', ['as'=>'campaigns.companyreport', 'uses'=>'CampaignTrackingController@company']);
        Route::get('campaigns/{campaign}/company', ['as'=>'campaigns.company', 'uses'=>'CampaignTrackingController@summaryByCompany']);
        Route::get('campaigns/{campaign}/company/{company}', ['as'=>'campaigns.company.detail', 'uses'=>'CampaignTrackingController@detailByCompany']);

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
        Route::get('users/import', ['as'=>'users.importfile', 'uses'=>'UsersImportController@getFile']);
        Route::get('usersimport', ['as'=>'usersimport.index', 'uses'=>'UsersImportController@index']);

        Route::post('users/bulkimport', ['as'=>'admin.users.bulkimport', 'uses'=>'UsersImportController@import']);
        Route::post('users/import', ['as'=>'users.mapfields', 'uses'=>'UsersImportController@mapfields']);
        Route::get('users/deleted', ['as'=>'deleted.users', 'uses'=>'Admin\AdminUsersController@deleted']);
        Route::get('users/{id}/restore', ['as'=>'users.restore', 'uses'=>'Admin\AdminUsersController@restore']);

        Route::delete('users/{id}/purge', ['as'=>'users.permdestroy', 'uses'=>'Admin\AdminUsersController@permdeleted']);

        Route::post('user/usererrors', ['as'=>'fixusercreateerrors', 'uses'=>'UsersImportController@fixUserErrors']);
        Route::post('user/importcleanse/delete', ['as'=>'user.importdelete', 'uses'=>'UserImportCleanseController@bulkdestroy']);
        Route::post('user/importcleanse/insert', ['as'=>'user.importinsert', 'uses'=>'UserImportCleanseController@createNewUsers']);
        Route::get('user/importfinal', ['as'=>'user.importfinal', 'uses'=>'UserImportCleanseController@importAllUsers']);
        Route::get('user/importflush', ['as'=>'importcleanse.flush', 'uses'=>'UserImportCleanseController@flush']);

        Route::get('user/bulkdelete', ['as'=>'bulkdelete', 'uses'=>'Admin\AdminUsersController@bulkdelete']);
        Route::post('user/bulkdelete', ['as'=>'users.bulkdelete', 'uses'=>'Admin\AdminUsersController@confirmDelete']);
        Route::post('user/massdelete', ['as'=>'users.massdelete', 'uses'=>'Admin\AdminUsersController@massDelete']);
        Route::resource('user/importcleanse', 'UsersImportController');



        Route::get('users/newusers', ['as'=>'import.newusers', 'uses'=>'UsersImportController@newUsers']);
        Route::post('users/createnewusers', ['as'=>'import.createnewusers', 'uses'=>'UsersImportController@createNewUsers']);

        Route::get('users/serviceline/{serviceline}', ['as'=>'serviceline.user', 'uses'=>'Admin\AdminUsersController@index']);
        Route::get('users/nomanager', ['as'=>'nomanager', 'uses'=>'SalesOrgController@noManager']);
        Route::get('users/nomanager/export', ['as'=>'nomanager.export', 'uses'=>'SalesOrgController@noManagerExport']);

        Route::resource('users', 'Admin\AdminUsersController');

        Route::post('lastlogged', ['as'=>'lastlogged', 'uses'=>'Admin\AdminUsersController@lastlogged']);
        //   Reports
        //Route::post('reports/{report}/run', ['as'=>'reports.run', 'uses'=>'ReportsController@run']);

        Route::post('reports/{report}/send', ['as'=>'reports.send', 'uses'=>'ReportsController@send']);
        Route::get('reports/review', ['as'=>'reports.review', 'uses'=>'ReportsController@review']);
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

        //     Comments
        Route::get('comment/download', ['as'=>'comment.download', 'uses'=>'CommentsController@download']);

        //     News
        Route::get('news/{id}/audience', ['as'=>'news.audience', 'uses'=>'NewsController@audience']);
        Route::resource('news', 'NewsController', ['except' => ['index', 'show']]);


        //     Notes
        Route::get('notes/{companyid}/co', ['as'=>'notes.company', 'uses'=>'NotesController@companynotes']);
        Route::get('locationnotes', ['as'=>'locations.notes', 'uses'=>'NotesController@index']);

        //     Search Filters

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
        Route::get(
            'testjob', function () {
                //$companies = App\Company::whereIn('id', [532])->get();
                $period['from'] = \Carbon\Carbon::now()->subDay()->startOfDay();
                $period['to'] = \Carbon\Carbon::now()->subDay()->endOfDay();

                //App\Jobs\AccountActivities::dispatch($companies, $period);
                //App\Jobs\ActivityOpportunity::dispatch($period);
                //$opportunity = App\Opportunity::has('branch')->first();
                //App\Jobs\WonOpportunity::dispatch($opportunity);
                // App\Jobs\Top50WeeklyReport::dispatch();
                //App\Jobs\BranchLogins::dispatch($period);
                App\Jobs\DailyBranch::dispatch($period);
                 //App\Jobs\AccountActivities::dispatch($company, $period);
                //App\Jobs\BranchCampaign::dispatch();
                //App\Jobs\BranchOpportunities::dispatch($period);
                 //App\Jobs\RebuildPeople::dispatch();
                //App\Jobs\BranchLogins::dispatch($period);
                 /*$filesInFolder = \File::files(storage_path('backups'));
                 foreach ($filesInFolder as $file){
                 if(pathinfo($file)['extension'] == 'sql'){
                 $filename = pathinfo($file)['filename'];
                 App\Jobs\ZipBackUp::withChain([new App\Jobs\UploadToDropbox($filename)])
                        ->dispatch($filename)->onQueue('mapminer');
                 }*/
                 
                 /*}
                 
                 $period['from'] = now();
                 $period['to'] = now()->addWeek();
                 App\Jobs\WeeklyActivityReminder::dispatch($period);*/
                 //App\Jobs\WeeklyOpportunitiesReminder::dispatch();
                 /*$period['from'] = \Carbon\Carbon::now()->subWeek()->startOfWeek();
                 $period['to'] = \Carbon\Carbon::now();/
                App\Jobs\BranchStats::dispatch($period);*
                //App\Jobs\ActivityOpportunity::dispatch($period);
                //App\Jobs\ActivityOpportunityReport::dispatch();
                
                //App\Jobs\ZipBackup::dispatch('MMProd20190123');
                //App\Jobs\UploadToDropbox::dispatch('MMProd20190123');
                //Mail::queue(new App\Mail\ConfirmBackup('MMProd20190123'));
                */
            }
        );


        //   Tracking
        Route::resource('track', 'TrackController');

        //   Versions
        Route::resource('versions', 'GitController');

        Route::get('authtest', ['as'=>'test', 'uses'=>'TestController@test']);
    }
);
