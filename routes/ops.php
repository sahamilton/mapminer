<?php
use Illuminate\Http\Request;   
/** 
 *  Sales Ops  Routes
 *  ------------------------------------------
 */

        //     Ops Main Page
        Route::get('/', ['as'=>'ops', 'uses'=>'Admin\AdminDashboardController@dashboard']);
        
        //   Activity types
        Route::resource('activitytype', 'ActivityTypeController');
           //     AccountTypes
        Route::resource('accounttype', 'AccounttypesController');
        //   Address
        Route::get('address/import', ['as'=>'address.importfile', 'uses'=>'AddressImportController@getfile']);
        
        //     Branches
        Route::get('branches/import', ['as'=>'branches.importfile', 'uses'=>'BranchesImportController@getFile']);
        Route::post('branches/change', ['as'=>'branches.change', 'uses'=>'BranchesImportController@update']);
        Route::post('branches/bulkimport', ['as'=>'branches.import', 'uses'=>'BranchesImportController@import']);
        Route::get('geocode', ['as'=>'branches.geocode', 'uses'=>'BranchesController@geoCodeBranches']);
        Route::get('branchmap', ['as'=>'branches.genmap', 'uses'=>'BranchesController@rebuildBranchMap']);
        Route::get('branches/export', ['as'=>'branches.export', 'uses'=>'BranchesController@export']);
        Route::get('branches/team/export', ['as'=>'branches.team.export', 'uses'=>'BranchesController@exportTeam']);
        Route::get('branch/{branch}/reassign', ['as'=>'branchReassign','uses'=>'BranchesController@reassignBranch']);
        Route::post('branch/{branch}/reassign', ['as'=>'branch.reassign','uses'=>'BranchesController@reassign']);
        Route::resource('branches', 'BranchesController', ['except'=>['index', 'show']]);
        
        //     Companies
        Route::get('companies/import', ['as'=>'companies.importfile', 'uses'=>'CompaniesImportController@getFile']);
        Route::post('companies/import', ['as'=>'companies.import', 'uses'=>'CompaniesImportController@import']);
        Route::get('companies/export', ['as'=>'companies.export', 'uses'=>'CompaniesExportController@index']);
        Route::post('companies/export', ['as'=>'companies.locationsexport', 'uses'=>'CompaniesExportController@export']);
        
        Route::get('companies/download', ['as'=>'allcompanies.export', 'uses'=>'CompaniesController@exportAccounts']);
        
        Route::get('company/{companyId}/export', ['as'=>'company.export', 'uses'=>'WatchController@companyexport']);
        
        //   Order Import
        Route::get('orderimport/flush', ['as'=>'orderimport.flush', 'uses'=>'OrderImportController@flush']);
        Route::get('orderimport/finalize', ['as'=>'orderimport.finalize', 'uses'=>'OrderImportController@finalize']);
        
        Route::resource('orderimport', 'CompaniesImportController');
        
        //Route::post('company/filter', ['as'=>'company.filter', 'uses'=>'CompaniesController@filter']);
        Route::resource('company', 'CompaniesController', ['except' => ['index', 'show']]);
        //   Customers
        
        Route::get('customers/export', ['as'=>'customers.export', 'uses'=>'CompaniesExportController@export']);
        Route::post('/importcustomers/mapfields', ['as'=>'companies.mapfields', 'uses'=>'CompaniesImportController@mapfields']);
        Route::resource('customers', 'CustomerController');
            //   Documents
        Route::resource('documents', 'DocumentsController');
    
        //   Emails
        Route::post('emails/selectrecipients', ['as'=>'emails.updatelist', 'uses'=>'EmailsController@addRecipients']);
        Route::get('emails/update', ['as'=>'emails.updaterecipients', 'uses'=>'EmailsController@changelist']);
        Route::get('emails/{id}/clone', ['as'=>'emails.clone', 'uses'=>'EmailsController@clone']);
        Route::get('emails/{id}/recipients', ['as'=>'emails.recipients', 'uses'=>'EmailsController@recipients']);
        Route::post('emails/send', ['as'=>'emails.send', 'uses'=>'EmailsController@sendEmail']);
        Route::resource('emails', 'EmailsController');
        //   Feedback
        Route::get('feedback/export', ['as'=>'feedback.export', 'uses'=>'FeedbackController@export']);
        Route::get('feedback{feedback}/close', ['as'=>'feedback.close', 'uses'=>'FeedbackController@close']);
        Route::get('feedback{feedback}/open', ['as'=>'feedback.open', 'uses'=>'FeedbackController@open']);
        Route::resource('feedback', 'FeedbackController', ['except'=>['index', 'show', 'store']]);
    
        //   Feedback Comments
        Route::resource('feedback_comment', 'FeedbackCommentsController');
        //   Imports
    
        Route::get('branch/teams', ['as'=>'branch_team.importfile', 'uses'=>'BranchTeamImportController@getFile']);
        Route::post('branch/teams', ['as'=>'branches.teamimport', 'uses'=>'BranchTeamImportController@import']);
        Route::get('imports', ['as'=>'imports.index', 'uses'=>'ImportController@index']);
        Route::post('/importleads/mapfields', ['as'=>'leads.mapfields', 'uses'=>'LeadImportController@mapfields']);
        Route::post('/importlocations/mapfields', ['as'=>'locations.mapfields', 'uses'=>'LocationsImportController@mapfields']);
        Route::post('/importprojects/mapfields', ['as'=>'projects.mapfields', 'uses'=>'ProjectsImportController@mapfields']);
        Route::post('/importprojectcompany/mapfields', ['as'=>'projectcompany.mapfields', 'uses'=>'ProjectsCompanyImportController@mapfields']);
        Route::post('/importbranches/mapfields', ['as'=>'branches.mapfields', 'uses'=>'BranchesImportController@mapfields']);
        Route::post('/importbranchteams/mapfields', ['as'=>'branchteam.mapfields', 'uses'=>'BranchTeamImportController@mapfields']);

        //     Locations
        Route::get('locations/import', ['as'=>'locations.importfile', 'uses'=>'LocationsImportController@getfile']);
        Route::post('locations/bulkimport', ['as'=>'locations.import', 'uses'=>'LocationsImportController@import']);
       
        //   LocationsPostImport

        Route::post('locations/adddelete', ['as'=>'locations.adddelete', 'uses'=>'LocationPostImportController@adddelete']);
        Route::resource('locations/postprocess', 'LocationPostImportController');

        Route::get('api/geocode', ['as'=>'api.geocode', 'uses'=>'LocationsController@bulkGeoCodeLocations']);
        Route::get('locations/{companyID}/create', ['as'=>'company.location.create', 'uses'=>'LocationsController@create']);
        Route::resource('locations', 'LocationsController', ['except'=>['show']]);

        //   Projects
        Route::get('projects/import', ['as'=>'projects.importfile', 'uses'=>'ProjectsImportController@getFile']);
        Route::get('projects/importcompany', ['as'=>'project_company.importfile', 'uses'=>'ProjectsCompanyImportController@getFile']);
        Route::post('projects/import', ['as'=>'projects.bulkimport', 'uses'=>'ProjectsImportController@import']);
        Route::post('projects/importcompany', ['as'=>'projects.companyimport', 'uses'=>'ProjectsCompanyImportController@import']);

        Route::get('projects/export', ['as'=>'projects.exportowned', 'uses'=>'ProjectsController@exportowned']);
        Route::get('projects/status', ['as'=>'projects.status', 'uses'=>'ProjectsController@statuses']);

        Route::get('projects/stats', ['as'=>'project.stats', 'uses'=>'ProjectsController@projectStats']);
        Route::get('projects/exportstats', ['as'=>'project.exportstats', 'uses'=>'ProjectsController@exportProjectStats']);
        Route::get('projects/{id}/owner', ['as'=>'project.owner', 'uses'=>'ProjectsController@ownedProjects']);
        Route::post('projects/{id}/release', ['as'=>'projects.release', 'uses'=>'ProjectsController@release']);


        //     Project Source
        Route::resource('projectsource', 'ProjectSourceController');

        

        //Leads Import       
        Route::get('leads/import/{id?}', ['as'=>'prospects.importfile', 'uses'=>'LeadImportController@getFile']);
        Route::get('leads/import/assigned/{id?}', ['as'=>'assigned_prospects.importfile', 'uses'=>'LeadAssignedImportController@getFile']);
        
        Route::post('leads/import', ['as'=>'leads.import', 'uses'=>'LeadImportController@import']);
        Route::get('leadsource/{leadsource}/addcompany', ['as'=>'leadsource.addcompany', 'uses'=>'LeadSourceController@selectCompaniesToAdd']);
        Route::post('leadsource/{leadsource}/addcompanylocations', ['as'=>'leadsource.addcompanylocations', 'uses'=>'LeadSourceController@addCompanyLocationsToLeadSource']);
        Route::get('leadsource/{leadsource}/results', ['as'=>'leadsource.results', 'uses'=>'LeadSourceController@leadSourceBranchResults']);
        
        Route::post('leadsource/{leadsource}/assign', ['as'=>'leads.geoassign', 'uses'=>'LeadsAssignController@geoAssignLeads']);
        Route::get('leads/{leadsource}/assign', ['as'=>'leads.leadassign', 'uses'=>'LeadsController@assignLeads']);
        
        Route::get('leads/{leadsource}/batchassign', ['as'=>'leads.assignbatch', 'uses'=>'LeadsAssignController@assignLeads']);
        
        Route::post('leads/assign', ['as'=>'webleads.assign', 'uses'=>'WebLeadsController@assignLeads']);
        
        
        //     //   Web leads
        
        
        Route::get('/leads/{address}/singleassign', ['as'=>'leads.singleassign', 'uses'=>'LeadsAssignController@singleleadassign']);
        Route::post('/leads/{address}/singleassign', ['as'=>'leads.postassign', 'uses'=>'LeadsAssignController@store']);
        Route::post('/leads/assign', ['as'=>'leads.assign', 'uses'=>'LeadsController@assignLeads']);
        Route::delete('/leads/{id}/unassign', ['as'=>'webleads.unassign', 'uses'=>'LeadsController@unAssignLeads']);
        
        
        //Route::get('webleads/{lead}', ['as'=>'webleads.show', 'uses'=>'WebLeadsController@show']);
        //Route::resource('webleads', 'WebLeadsImportController');
        
        
        Route::get('leadsource/{leadsource}/export', ['as'=>'leadsource.export', 'uses'=>'LeadSourceController@export']);
        
        Route::post('/webleads/import/form', ['as'=>'leads.webleadsinsert', 'uses'=>'WebleadsImportController@getLeadFormData']);
        Route::post('/webleads/import/create', ['as'=>'webleads.import.store', 'uses'=>'WebleadsImportController@store']);
        Route::post('lead/search', ['as'=>'leads.search', 'uses'=>'LeadsController@search']);
        Route::get('lead/search', ['as'=>'leads.search', 'uses'=>'LeadsController@searchAddress']);
        Route::get('address/{address}/assign', ['as'=>'leads.assignlead', 'uses'=>'LeadsAssignController@show']);
        /*Route::get('leads/{id}/person', ['as'=>'leads.person', 'uses'=>'LeadsController@getPersonsLeads']);
        Route::get('leads/{id}/person/{sid}/source', ['as'=>'leads.personsource', 'uses'=>'LeadsController@getPersonSourceLeads'])
        Route::get('lead/branch/{bid?}', ['as'=>'leads.branch', 'uses'=>'LeadsController@branches']);
        Route::resource('leads', 'LeadsController');*/

        
        //     Salesnotes
        Route::get('salesnotes/filedelete/{file}', ['as'=>'salesnotes.filedelete', 'uses'=>'SalesNotesController@filedelete']);
        Route::get('salesnotes/edit/{company}', ['as'=>'salesnotes.cocreate', 'uses'=>'SalesNotesController@edit']);
        //   OrderImports
        
        Route::resource('orderimport', 'OrderImportController');
        //   Prospect Source / LeadSource
        
        Route::get('leadsource/{leadsource}/announce', ['as'=>'leadsource.announce', 'uses'=>'LeadsEmailController@announceLeads']);
        Route::post('leadsource/{leadsource}/email', ['as'=>'sendleadsource.message', 'uses'=>'LeadsEmailController@email']);
        Route::get('leadsource/{leadsource}/assign', ['as'=>'leadsource.assign', 'uses'=>'LeadsAssignController@assignLeads']);
        Route::get('leadsource/{leadsource}/branch', ['as'=>'leadsource.branches', 'uses'=>'LeadSourceController@branches']);
        Route::get('leadsource/{leadsource}/unassigned', ['as'=>'leadsource.unassigned', 'uses'=>'LeadSourceController@unassigned']);
        Route::get('leadsource/{leadsource}/addleads', ['as'=>'leadsource.addleads', 'uses'=>'LeadSourceImportController@getFile']);
        
        Route::get('leadsource/{leadsource}/state/{state}', ['as'=>'leadsource.unassigned.state', 'uses'=>'LeadSourceController@unassignedstate']);
        Route::get('leadsource/flush', ['as'=>'leadsource.flush', 'uses'=>'LeadSourceController@flushManagerLeads']);
        Route::post('leadsource/mgrflush', ['as'=>'leadsource.mgrflush', 'uses'=>'LeadSourceController@flushManagerLeadsConfirm']);
        Route::post('leadsource/finalflush', ['as'=>'leadsource.finalflush', 'uses'=>'LeadSourceController@flushManagerLeadsFinal']);
        Route::get('leadsource/{leadsource}/flushleads', ['as'=>'leadsource.flushleads', 'uses'=>'LeadSourceController@flushLeads']);
        Route::resource('leadsource', 'LeadSourceController');
        
        //     Salesnotes
        Route::get('salesnotes/filedelete/{file}', ['as'=>'salesnotes.filedelete', 'uses'=>'SalesNotesController@filedelete']);
        
        
        //   Sales Activity / Campaigns

        Route::get('salesactivity/{vertical}/vertical', ['as'=>'salesactivity.vertical', 'uses'=>'SalesActivityController@index']);
        Route::post('salesactivity/updateteam', ['as'=>'salesactivity.modifyteam', 'uses'=>'SalesActivityController@updateteam']);
        Route::resource('salesactivity', 'Admin\SalesActivityManagementController');

        Route::get('campaigndocs/{id}', ['as'=>'salesdocuments.index', 'uses'=>'SalesActivityController@campaignDocuments']);
        Route::get('campaign/{id}/announce', ['as'=>'campaign.announce', 'uses'=>'CampaignEmailController@announceCampaign']);
        Route::post('campaign/{id}/message', ['as'=>'sendcampaign.message', 'uses'=>'CampaignEmailController@email']);

        Route::get('salesteam', ['as'=>'teamupdate', 'uses'=>'SalesActivityController@changeTeam']);


        //     CompanyService
        
        Route::get('/company/{id}/service/{state?}', ['as'=>'company.service', 'uses'=>'CompaniesServiceController@getServiceDetails']);

        Route::get('/company/{id}/teamservice/{state?}', ['as'=>'company.teamservice', 'uses'=>'CompaniesServiceController@getServiceTeamDetails']);
        Route::post('/company/service', ['as'=>'company.service.select', 'uses'=>'CompaniesServiceController@selectServiceDetails']);
        Route::get('company/{id}/serviceexport/{state?}', ['as'=>'company.service.export', 'uses'=>'CompaniesServiceController@exportServiceDetails']);
        Route::get('company/{id}/serviceteamexport/{state?}', ['as'=>'company.teamservice.export', 'uses'=>'CompaniesServiceController@exportServiceTeamDetails']);

        //     Watchlists
        Route::get('watchlist/{userid}', ['as'=>'watch.mywatchexport', 'uses'=>'WatchController@export']);

        //     //   Search
        Route::get('user/find', 'SearchController@searchUsers');

        
        Route::get('/person/{person}/find', ['as'=>'person.details', 'uses'=>'PersonSearchController@find']);

        Route::resource('newleads', 'LeadSourceController');
  