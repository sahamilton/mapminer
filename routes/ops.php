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
        Route::get('address/import', ['as'=>'address.importfile', 'uses'=>'Imports\AddressImportController@getfile']);
        
        //     Branches
        Route::get('branches/import', ['as'=>'branches.importfile', 'uses'=>'Imports\BranchesImportController@getFile']);
        Route::post('branches/change', ['as'=>'branches.change', 'uses'=>'Imports\BranchesImportController@update']);
        Route::post('branches/bulkimport', ['as'=>'branches.import', 'uses'=>'Imports\BranchesImportController@import']);
        Route::get('geocode', ['as'=>'branches.geocode', 'uses'=>'BranchesController@geoCodeBranches']);
        Route::get('branchmap', ['as'=>'branches.genmap', 'uses'=>'BranchesController@rebuildBranchMap']);
        Route::get('branches/export', ['as'=>'branches.export', 'uses'=>'BranchesController@export']);
        Route::get('branch/{branch}/reassign', ['as'=>'branchReassign','uses'=>'BranchesController@reassignBranch']);
        Route::post('branch/{branch}/reassign', ['as'=>'branch.reassign','uses'=>'BranchesController@reassign']);
        Route::resource('branches', 'BranchesController', ['except'=>['index', 'show']]);
        
        //     Companies
        Route::get('companies/import', ['as'=>'companies.importfile', 'uses'=>'Imports\CompaniesImportController@getFile']);
        Route::post('companies/import', ['as'=>'companies.import', 'uses'=>'Imports\CompaniesImportController@import']);
        Route::get('companies/export', ['as'=>'companies.export', 'uses'=>'Exports\CompaniesExportController@index']);
        Route::post('companies/export', ['as'=>'companies.locationsexport', 'uses'=>'Exports\CompaniesExportController@export']);
        
        Route::get('companies/download', ['as'=>'allcompanies.export', 'uses'=>'CompaniesController@exportAccounts']);
        
        Route::get('company/{companyId}/export', ['as'=>'company.export', 'uses'=>'WatchController@companyexport']);
       
        // Contacts
        // 
        Route::get('contacts/import', ['as'=>'contacts.importfile', 'uses'=>'Imports\ContactsImportController@getFile']);
        Route::post('contacts/import', ['as'=>'contacts.import', 'uses'=>'Imports\ContactsImportController@import']);
        Route::post('/contacts/mapfields', ['as'=>'contacts.mapfields', 'uses'=>'Imports\ContactsImportController@mapfields']);
        Route::get('contacts/postimport', ['as'=>'contacts.postimport', 'uses'=>'Imports\ContactsImportController@postImport']);
        Route::get('contacts/createcompany', ['as'=>'contacts.createcompany', 'uses'=>'Imports\ContactsImportController@createMissingCompanies']);
        Route::get('contacts/createleads', ['as'=>'contacts.createleads', 'uses'=>'Imports\ContactsImportController@createLeads']);
        Route::post('contacts/importcontacts', ['as'=>'contacts.importcontacts', 'uses'=>'Imports\ContactsImportController@importContacts']);

        //   Order Import
        Route::get('orderimport/flush', ['as'=>'orderimport.flush', 'uses'=>'Imports\OrderImportController@flush']);
        Route::get('orderimport/finalize', ['as'=>'orderimport.finalize', 'uses'=>'Imports\OrderImportController@finalize']);
        
        Route::resource('orderimport', 'Imports\CompaniesImportController');
        
        //Route::post('company/filter', ['as'=>'company.filter', 'uses'=>'CompaniesController@filter']);
        Route::resource('company', 'CompaniesController', ['except' => ['index', 'show']]);
        //   Customers
        
        Route::get('customers/export', ['as'=>'customers.export', 'uses'=>'Exports\CompaniesExportController@export']);
        Route::post('/importcustomers/mapfields', ['as'=>'companies.mapfields', 'uses'=>'Imports\CompaniesImportController@mapfields']);
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
    
        Route::get('branch/teams', ['as'=>'branch_team.importfile', 'uses'=>'Imports\BranchTeamImportController@getFile']);
        Route::post('branch/teams', ['as'=>'branches.teamimport', 'uses'=>'Imports\BranchTeamImportController@import']);
        Route::get('imports', ['as'=>'imports.index', 'uses'=>'Imports\ImportController@index']);
        Route::post('/importleads/mapfields', ['as'=>'leads.mapfields', 'uses'=>'Imports\LeadImportController@mapfields']);
        Route::post('/importlocations/mapfields', ['as'=>'locations.mapfields', 'uses'=>'Imports\LocationsImportController@mapfields']);
        Route::post('/importprojects/mapfields', ['as'=>'projects.mapfields', 'uses'=>'Imports\ProjectsImportController@mapfields']);
        Route::post('/importprojectcompany/mapfields', ['as'=>'projectcompany.mapfields', 'uses'=>'Imports\ProjectsCompanyImportController@mapfields']);
        Route::post('/importbranches/mapfields', ['as'=>'branches.mapfields', 'uses'=>'Imports\BranchesImportController@mapfields']);
        Route::post('/importbranchteams/mapfields', ['as'=>'branchteam.mapfields', 'uses'=>'Imports\BranchTeamImportController@mapfields']);


        //     Locations
        Route::get('locations/import', ['as'=>'locations.importfile', 'uses'=>'Imports\LocationsImportController@getfile']);
        Route::post('locations/bulkimport', ['as'=>'locations.import', 'uses'=>'Imports\LocationsImportController@import']);
       
        //   LocationsPostImport

        Route::post('locations/adddelete', ['as'=>'locations.adddelete', 'uses'=>'Imports\LocationPostImportController@adddelete']);
        Route::resource('locations/postprocess', 'Imports\LocationPostImportController');

        Route::get('api/geocode', ['as'=>'api.geocode', 'uses'=>'LocationsController@bulkGeoCodeLocations']);
        Route::get('locations/{companyID}/create', ['as'=>'company.location.create', 'uses'=>'LocationsController@create']);
        Route::resource('locations', 'LocationsController', ['except'=>['show']]);
        
        
        //   Projects
        Route::get('projects/import', ['as'=>'projects.importfile', 'uses'=>'Imports\ProjectsImportController@getFile']);
        Route::get('projects/importcompany', ['as'=>'project_company.importfile', 'uses'=>'Imports\ProjectsCompanyImportController@getFile']);
        Route::post('projects/import', ['as'=>'projects.bulkimport', 'uses'=>'Imports\ProjectsImportController@import']);
        Route::post('projects/importcompany', ['as'=>'projects.companyimport', 'uses'=>'Imports\ProjectsCompanyImportController@import']);

        Route::get('projects/export', ['as'=>'projects.exportowned', 'uses'=>'ProjectsController@exportowned']);
        Route::get('projects/status', ['as'=>'projects.status', 'uses'=>'ProjectsController@statuses']);

        Route::get('projects/stats', ['as'=>'project.stats', 'uses'=>'ProjectsController@projectStats']);
        Route::get('projects/exportstats', ['as'=>'project.exportstats', 'uses'=>'ProjectsController@exportProjectStats']);
        Route::get('projects/{id}/owner', ['as'=>'project.owner', 'uses'=>'ProjectsController@ownedProjects']);
        Route::post('projects/{id}/release', ['as'=>'projects.release', 'uses'=>'ProjectsController@release']);
        // Management Team
        Route::get('managers',['as'=>'managers.livewire', 'uses'=>'ManagersController@livewireManagers']);

        //     Project Source
        Route::resource('projectsource', 'ProjectSourceController');

        // Export Persons data
        Route::post('exports/store', ['as'=>'export.store', 'uses'=>'Exports\ExportController@store']);

        //Leads Import       
        Route::get('leads/import/{id?}', ['as'=>'prospects.importfile', 'uses'=>'Imports\LeadImportController@getFile']);
        Route::get('leads/import/assigned/{id?}', ['as'=>'assigned_prospects.importfile', 'uses'=>'Imports\LeadAssignedImportController@getFile']);
        
        Route::post('leads/import', ['as'=>'leads.import', 'uses'=>'Imports\LeadImportController@import']);
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
        //Route::resource('webleads', 'Imports\WebLeadsImportController');
        
        
        Route::get('leadsource/{leadsource}/export', ['as'=>'leadsource.export', 'uses'=>'LeadSourceController@export']);
        
        Route::post('/webleads/import/form', ['as'=>'leads.webleadsinsert', 'uses'=>'Imports\WebLeadsImportController@getLeadFormData']);
        Route::post('/webleads/import/create', ['as'=>'webleads.import.store', 'uses'=>'Imports\WebLeadsImportController@store']);
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
        Route::resource('salesnotes', 'SalesNotesController')->except(['show']);
        //   OrderImports
        
        Route::resource('orderimport', 'Imports\OrderImportController');
        //   Prospect Source / LeadSource
        
        Route::get('leadsource/{leadsource}/announce', ['as'=>'leadsource.announce', 'uses'=>'LeadsEmailController@announceLeads']);
        Route::post('leadsource/{leadsource}/email', ['as'=>'sendleadsource.message', 'uses'=>'LeadsEmailController@email']);
        Route::get('leadsource/{leadsource}/assign', ['as'=>'leadsource.assign', 'uses'=>'LeadsAssignController@assignLeads']);
        Route::get('leadsource/{leadsource}/branch', ['as'=>'leadsource.branches', 'uses'=>'LeadSourceController@branches']);
        Route::get('leadsource/{leadsource}/unassigned', ['as'=>'leadsource.unassigned', 'uses'=>'LeadSourceController@unassigned']);
        Route::get('leadsource/{leadsource}/addleads', ['as'=>'leadsource.addleads', 'uses'=>'Imports\LeadSourceImportController@getFile']);
        
        Route::get('leadsource/{leadsource}/state/{state}', ['as'=>'leadsource.unassigned.state', 'uses'=>'LeadSourceController@unassignedstate']);
        Route::get('leadsource/flush', ['as'=>'leadsource.flush', 'uses'=>'LeadSourceController@flushManagerLeads']);
        Route::post('leadsource/mgrflush', ['as'=>'leadsource.mgrflush', 'uses'=>'LeadSourceController@flushManagerLeadsConfirm']);
        Route::post('leadsource/finalflush', ['as'=>'leadsource.finalflush', 'uses'=>'LeadSourceController@flushManagerLeadsFinal']);
        Route::get('leadsource/{leadsource}/flushleads', ['as'=>'leadsource.flushleads', 'uses'=>'LeadSourceController@flushLeads']);
        Route::resource('leadsource', 'LeadSourceController');
        

        // Persons Data
        Route::get('persondata', ['as'=>'persons.data.export', 'uses'=>'Exports\ExportController@index']);
        Route::post('persondata/export', ['as'=>'exports.store', 'uses'=>'Exports\ExportController@store']);
        
        //   Sales Activity / Campaigns

        Route::get('salesactivity/{vertical}/vertical', ['as'=>'salesactivity.vertical', 'uses'=>'SalesActivityController@index']);
        Route::post('salesactivity/updateteam', ['as'=>'salesactivity.modifyteam', 'uses'=>'SalesActivityController@updateteam']);
        Route::resource('salesactivity', 'Admin\SalesActivityManagementController');

        Route::get('campaigndocs/{campaign}', ['as'=>'salesdocuments.index', 'uses'=>'SalesActivityController@campaignDocuments']);
        Route::get('campaign/{campaign}/announce', ['as'=>'campaign.announce', 'uses'=>'CampaignEmailController@announceCampaign']);
        Route::post('campaign/{campaign}/message', ['as'=>'sendcampaign.message', 'uses'=>'CampaignEmailController@email']);

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
        Route::get('/person/{person}/welcome', ['as'=>'person.welcome', 'uses'=>'PersonSearchController@welcome']);

        Route::resource('newleads', 'LeadSourceController');
  