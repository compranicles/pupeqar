<?php

use Illuminate\Support\Facades\Route;

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

// GUEST HOMEPAGE
Route::get('/', function () {
    return view('auth.login');
})->name('home')->middleware('guest');

/* DASHBOARD AND HOMEPAGE DISPLAY */
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});

/* HRIS REGISTER AND VERIFICATION */
Route::get('register/hris', [\App\Http\Controllers\HRISRegistration\RegistrationController::class, 'index'])->name('register.hris');
Route::post('register/verify', [\App\Http\Controllers\HRISRegistration\RegistrationController::class, 'verify'])->name('register.verify');
Route::get('register/create/{key}', [\App\Http\Controllers\HRISRegistration\RegistrationController::class, 'create'])->name('register.create');
Route::post('register/save', [\App\Http\Controllers\HRISRegistration\RegistrationController::class, 'save'])->name('register.save');

/* AUTH CHECKER */
Route::group(['middleware' => 'auth'], function() {

    /* UPLOAD AND REMOVE DOCUMENTS/IMAGES */
    Route::post('upload', [\App\Http\Controllers\UploadController::class, 'store']);
    Route::delete('remove', [\App\Http\Controllers\UploadController::class, 'destroy']);

    /* DOCUMENT/IMAGE ACCESS ROUTES */
    Route::get('image/{filename}', [\App\Http\Controllers\StorageFileController::class, 'getDocumentFile'])->name('document.display');
    Route::get('download/{filename}', [\App\Http\Controllers\StorageFileController::class, 'downloadFile'])->name('document.download');
    Route::get('document-view/{filename}', [\App\Http\Controllers\StorageFileController::class, 'viewFile'])->name('document.view');
            
    /* NOTIFICATIONS */
    Route::get('/get-notifications', [\App\Http\Controllers\NotificationController::class, 'getByUser']);
    Route::get('/notifications/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead']);
    Route::get('/notifications/all', [\App\Http\Controllers\NotificationController::class, 'seeAll'])->name('notif.all');

    /* ACTIVITY LOG */
    Route::get('get-dashboard-list', [\App\Http\Controllers\ActivityLogController::class, 'getTen']);
    Route::get('get-dashboard-list-indi', [\App\Http\Controllers\ActivityLogController::class, 'getTenIndi']);
    Route::get('view-logs', [\App\Http\Controllers\ActivityLogController::class, 'getAll'])->name('logs.all');
    Route::get('view-logs-user', [\App\Http\Controllers\ActivityLogController::class, 'getAllIndi'])->name('logs.user');

    /* ANNOUNCEMENTS */
    Route::get('announcements/view/{id}', [\App\Http\Controllers\AnnouncementController::class, 'showMessage']);
    Route::get('/announcements/hide/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'hide'])->name('announcements.hide');
    Route::get('/announcements/activate/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'activate'])->name('announcements.activate');
    Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class);

    /* MAINTENANCES */
    Route::get('/maintenances', [\App\Http\Controllers\Maintenances\MaintenanceController::class, 'index'])->name('maintenances.index');

    // 1. Colleges
    Route::get('/maintenances/colleges/name/{id}', [\App\Http\Controllers\Maintenances\CollegeController::class, 'getCollegeName'])->name('college.name');
    Route::resource('/maintenances/colleges', \App\Http\Controllers\Maintenances\CollegeController::class);
    // 2. Departments
    Route::get('/departments/options/{id}', [\App\Http\Controllers\Maintenances\DepartmentController::class, 'options']);
    Route::get('/maintenances/departments/name/{id}', [\App\Http\Controllers\Maintenances\DepartmentController::class, 'getDepartmentName'])->name('department.name');
    Route::resource('/maintenances/departments', \App\Http\Controllers\Maintenances\DepartmentController::class);
    // 3. Sectors
    Route::get('/maintenances/sectors', [\App\Http\Controllers\Maintenances\SectorController::class , 'index'])->name('sectors.maintenance.index');
    Route::get('/maintenances/sectors/sync', [\App\Http\Controllers\Maintenances\SectorController::class, 'sync'])->name('sectors.maintenance.sync');
    //4. Type/Format of Reports (e.g. Academic and Admin)
    Route::get('/maintenances/generate/types', [\App\Http\Controllers\Maintenances\GenerateTypeController::class, 'index'])->name('maintenance.generate.type');
    Route::get('/maintenances/generate/type/{type}/manage', [\App\Http\Controllers\Maintenances\GenerateTypeController::class, 'view'])->name('maintenance.generate.view');
    Route::get('/maintenances/generate/type/{type_id}/manage/{table_id}', [\App\Http\Controllers\Maintenances\GenerateTypeController::class, 'edit'])->name('maintenance.generate.edit');
    Route::get('/maintenances/generate/type/{type_id}/manage/{table_id}/{column_id}/rename', [\App\Http\Controllers\Maintenances\GenerateTypeController::class, 'editColumn'])->name('maintenance.generate.edit-column');
    Route::post('/maintenances/generate/type/{type_id}/manage/{table_id}/save', [\App\Http\Controllers\Maintenances\GenerateTypeController::class, 'save'])->name('maintenance.generate.save');
    Route::post('/maintenances/generate/type/{type_id}/manage/{table_id}/{column_id}/rename/save', [\App\Http\Controllers\Maintenances\GenerateTypeController::class, 'saveColumn'])->name('maintenance.generate.save-column');
    Route::get('/maintenances/generate/{column_id}/activate', [\App\Http\Controllers\Maintenances\GenerateTypeController::class, 'activate']);
    Route::get('/maintenances/generate/{column_id}/inactivate', [\App\Http\Controllers\Maintenances\GenerateTypeController::class, 'inactivate']);
    Route::post('/maintenances/generate/arrange', [\App\Http\Controllers\Maintenances\GenerateTypeController::class, 'arrange']);
    // 5. Document Description in Accomplishment Forms
    Route::get('/document-upload/description/{report_category_id}', [\App\Http\Controllers\Maintenances\DocumentDescriptionController::class, 'getDescriptionsByReportCategory']);
    Route::resource('/maintenances/document-description', \App\Http\Controllers\Maintenances\DocumentDescriptionController::class);
    Route::get('/maintenances/description/isActive/{report_category_id}/{description_id}/{is_active}', [\App\Http\Controllers\Maintenances\DocumentDescriptionController::class, 'isActive']);
    // 6. Currency
    Route::get('/maintenances/currencies/name/{id}', [\App\Http\Controllers\Maintenances\CurrencyController::class, 'getCurrencyName'])->name('currency.name');
    Route::get('/maintenances/currencies/list', [\App\Http\Controllers\Maintenances\CurrencyController::class, 'list'])->name('currencies.list');
    Route::resource('/maintenances/currencies', \App\Http\Controllers\Maintenances\CurrencyController::class);
    // 5. Dropdowns in Accomplishment Forms
    Route::get('/dropdowns/options/{id}', [\App\Http\Controllers\Maintenances\DropdownController::class, 'options'])->name('dropdowns.options');
    Route::post('/dropdowns/options/add/{id}', [\App\Http\Controllers\Maintenances\DropdownController::class, 'addOptions'])->name('dropdowns.options.add');
    Route::post('/dropdowns/options/arrange', [\App\Http\Controllers\Maintenances\DropdownController::class, 'arrangeOptions']);
    Route::get('/dropdowns/options/activate/{id}', [\App\Http\Controllers\Maintenances\DropdownController::class, 'activate']);
    Route::get('/dropdowns/options/inactivate/{id}', [\App\Http\Controllers\Maintenances\DropdownController::class, 'inactivate']);
    Route::get('/dropdowns/option/name/{id}', [\App\Http\Controllers\Maintenances\DropdownController::class, 'getOptionName'])->name('dropdowns.option.name');
    Route::resource('dropdowns', \App\Http\Controllers\Maintenances\DropdownController::class);
    // 6. Report Content (in Submissions up to Report)
    Route::get('/reports/tables/{table}', [\App\Http\Controllers\Maintenances\ReportCategoryController::class, 'getTableColumns']);
    Route::get('/report-columns/activate/{id}', [\App\Http\Controllers\Maintenances\ReportColumnController::class, 'activate']);
    Route::get('/report-columns/inactivate/{id}', [\App\Http\Controllers\Maintenances\ReportColumnController::class, 'inactivate']);
    Route::post('/report-columns/arrange', [\App\Http\Controllers\Maintenances\ReportColumnController::class, 'arrange']);
    Route::resource('report-types', \App\Http\Controllers\Maintenances\ReportTypeController::class);
    Route::resource('report-categories', \App\Http\Controllers\Maintenances\ReportCategoryController::class);
    Route::resource('report-categories.report-columns', \App\Http\Controllers\Maintenances\ReportColumnController::class);
    // 7. Invention, Innovation, and Creative Work (IICW) Forms
    Route::get('/invention-forms/activate/{id}', [\App\Http\Controllers\Maintenances\InventionFormController::class, 'activate']);
    Route::get('/invention-forms/inactivate/{id}', [\App\Http\Controllers\Maintenances\InventionFormController::class, 'inactivate']);
    Route::resource('invention-forms', \App\Http\Controllers\Maintenances\InventionFormController::class);
    // 8. IICW Input Fields
    Route::get('/invention-fields/activate/{id}', [\App\Http\Controllers\Maintenances\InventionFieldController::class, 'activate']);
    Route::get('/invention-fields/inactivate/{id}', [\App\Http\Controllers\Maintenances\InventionFieldController::class, 'inactivate']);
    Route::post('/invention-fields/arrange', [\App\Http\Controllers\Maintenances\InventionFieldController::class, 'arrange']);
    Route::resource('invention-forms.invention-fields', \App\Http\Controllers\Maintenances\InventionFieldController::class);
    // 9. Research Forms
    Route::get('/research-forms/activate/{id}', [\App\Http\Controllers\Maintenances\ResearchFormController::class, 'activate']);
    Route::get('/research-forms/inactivate/{id}', [\App\Http\Controllers\Maintenances\ResearchFormController::class, 'inactivate']);
    Route::resource('research-forms', \App\Http\Controllers\Maintenances\ResearchFormController::class);    
    // 10. Research Fields
    Route::get('/research-fields/activate/{id}', [\App\Http\Controllers\Maintenances\ResearchFieldController::class, 'activate']);
    Route::get('/research-fields/inactivate/{id}', [\App\Http\Controllers\Maintenances\ResearchFieldController::class, 'inactivate']);
    Route::post('/research-fields/arrange', [\App\Http\Controllers\Maintenances\ResearchFieldController::class, 'arrange']);
    Route::resource('research-forms.research-fields', \App\Http\Controllers\Maintenances\ResearchFieldController::class);
    // 11. Extension Programs and Services Forms
    Route::get('/extension-program-forms/activate/{id}', [\App\Http\Controllers\Maintenances\ExtensionProgramFormController::class, 'activate']);
    Route::get('/extension-program-forms/inactivate/{id}', [\App\Http\Controllers\Maintenances\ExtensionProgramFormController::class, 'inactivate']);
    Route::resource('extension-program-forms', \App\Http\Controllers\Maintenances\ExtensionProgramFormController::class);    
    // 12. Extension Programs and Services Fields
    Route::get('/extension-program-fields/activate/{id}', [\App\Http\Controllers\Maintenances\ExtensionProgramFieldController::class, 'activate']);
    Route::get('/extension-program-fields/inactivate/{id}', [\App\Http\Controllers\Maintenances\ExtensionProgramFieldController::class, 'inactivate']);
    Route::post('/extension-program-fields/arrange', [\App\Http\Controllers\Maintenances\ExtensionProgramFieldController::class, 'arrange']);
    Route::resource('extension-program-forms.extension-program-fields', \App\Http\Controllers\Maintenances\ExtensionProgramFieldController::class);
    // 13. Academic Development Forms
    Route::get('/academic-module-forms/activate/{id}', [\App\Http\Controllers\Maintenances\AcademicModuleFormController::class, 'activate']);
    Route::get('/academic-module-forms/inactivate/{id}', [\App\Http\Controllers\Maintenances\AcademicModuleFormController::class, 'inactivate']);
    Route::resource('academic-module-forms', \App\Http\Controllers\Maintenances\AcademicModuleFormController::class);
    // 14. Academic Development Fields
    Route::get('/academic-module-fields/activate/{id}', [\App\Http\Controllers\Maintenances\AcademicModuleFieldController::class, 'activate']);
    Route::get('/academic-module-fields/inactivate/{id}', [\App\Http\Controllers\Maintenances\AcademicModuleFieldController::class, 'inactivate']);
    Route::post('/academic-module-fields/arrange', [\App\Http\Controllers\Maintenances\AcademicModuleFieldController::class, 'arrange']);
    Route::resource('academic-module-forms.academic-module-fields', \App\Http\Controllers\Maintenances\AcademicModuleFieldController::class);
    // 15. IPCR Forms
    Route::get('/ipcr-forms/activate/{id}', [\App\Http\Controllers\Maintenances\IPCRFormController::class, 'activate']);
    Route::get('/ipcr-forms/inactivate/{id}', [\App\Http\Controllers\Maintenances\IPCRFormController::class, 'inactivate']);
    Route::resource('ipcr-forms', \App\Http\Controllers\Maintenances\IPCRFormController::class);
    // 16. IPCR Fields
    Route::get('/ipcr-fields/activate/{id}', [\App\Http\Controllers\Maintenances\IPCRFieldController::class, 'activate']);
    Route::get('/ipcr-fields/inactivate/{id}', [\App\Http\Controllers\Maintenances\IPCRFieldController::class, 'inactivate']);
    Route::post('/ipcr-fields/arrange', [\App\Http\Controllers\Maintenances\IPCRFieldController::class, 'arrange']);
    Route::resource('ipcr-forms.ipcr-fields', \App\Http\Controllers\Maintenances\IPCRFieldController::class);
    // 17. HRIS Forms
    Route::get('/hris-forms/activate/{id}', [\App\Http\Controllers\Maintenances\HRISFormController::class, 'activate']);
    Route::get('/hris-forms/inactivate/{id}', [\App\Http\Controllers\Maintenances\HRISFormController::class, 'inactivate']);
    Route::resource('hris-forms', \App\Http\Controllers\Maintenances\HRISFormController::class);
    // 18. HRIS Fields
    Route::get('/hris-fields/activate/{id}', [\App\Http\Controllers\Maintenances\HRISFieldController::class, 'activate']);
    Route::get('/hris-fields/inactivate/{id}', [\App\Http\Controllers\Maintenances\HRISFieldController::class, 'inactivate']);
    Route::post('/hris-fields/arrange', [\App\Http\Controllers\Maintenances\HRISFieldController::class, 'arrange']);
    Route::resource('hris-forms.hris-fields', \App\Http\Controllers\Maintenances\HRISFieldController::class);

    /* RESEARCH ACCOMPLISHMENTS */
    Route::resource('research', \App\Http\Controllers\Research\ResearchController::class);
    Route::resource('research.completed', \App\Http\Controllers\Research\CompletedController::class);
    Route::resource('research.publication', \App\Http\Controllers\Research\PublicationController::class);
    Route::resource('research.presentation', \App\Http\Controllers\Research\PresentationController::class);
    Route::resource('research.citation', \App\Http\Controllers\Research\CitationController::class);
    Route::resource('research.utilization', \App\Http\Controllers\Research\UtilizationController::class);
    Route::resource('research.copyrighted', \App\Http\Controllers\Research\CopyrightedController::class);
    // --
    Route::get('/research/complete/{id}', [\App\Http\Controllers\Research\ResearchController::class, 'complete'])->name('research.complete');
    Route::get('/research/publication/{id}', [\App\Http\Controllers\Research\ResearchController::class, 'publication'])->name('research.publication');
    Route::get('/research/presentation/{id}', [\App\Http\Controllers\Research\ResearchController::class, 'presentation'])->name('research.presentation');
    Route::get('/research/copyright/{id}', [\App\Http\Controllers\Research\ResearchController::class, 'copyright'])->name('research.copyright');
    Route::get('/research/add-document/{research_code}/{research_category_id}',  [\App\Http\Controllers\Research\ResearchController::class, 'addDocument'])->name('research.adddoc');
    Route::post('/research/save-document/{research_code}/{research_category_id}',  [\App\Http\Controllers\Research\ResearchController::class, 'saveDocument'])->name('research.savedoc');
    Route::get('/research/remove-document/{filename}', [\App\Http\Controllers\Research\ResearchController::class, 'removeDoc'])->name('research.removedoc');
    // Use Research By Co-Researchers
    Route::post('/research/with-code', [\App\Http\Controllers\Research\ResearchController::class, 'useResearchCode'])->name('research.code');
    Route::get('/research/with-code/create/{research_id}', [\App\Http\Controllers\Research\ResearchController::class, 'addResearch'])->name('research.code.create');
    Route::post('/research/with-code/save/{research_id}', [\App\Http\Controllers\Research\ResearchController::class, 'saveResearch'])->name('research.code.save');
    Route::get('/research/retrieve/{research_code}',  [\App\Http\Controllers\Research\ResearchController::class, 'retrieve'])->name('research.retrieve');
    Route::post('/research/edit-non-lead/{research}',  [\App\Http\Controllers\Research\ResearchController::class, 'updateNonLead'])->name('research.update-non-lead');
    // Management of Co-Researchers By Lead Researcher
    Route::get('/research/manage-researchers/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'manageResearchers'])->name('research.manage-researchers');
    Route::post('/research/manage-researchers/save-role/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'saveResearchRole'])->name('research.save-role');
    Route::post('/research/manage-researchers/remove-researcher/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'removeResearcher'])->name('research.remove-researcher');
    Route::get('/research/manage-researchers/remove-self/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'removeSelf'])->name('research.remove-self');
    Route::post('/research/manage-researchers/return-researcher/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'returnResearcher'])->name('research.return-researcher');
    // Invite Co-Researcher/s in a Research
    Route::get('/research/{research_id}/invite', [\App\Http\Controllers\Research\InviteController::class, 'index'])->name('research.invite.index');
    Route::post('/research/{research_id}/invite/add', [\App\Http\Controllers\Research\InviteController::class, 'add'])->name('research.invite.add');
    Route::post('/research/{research_id}/invite/remove', [\App\Http\Controllers\Research\InviteController::class, 'remove'])->name('research.invite.remove');
    Route::get('/research/{research_id}/invite/cancel', [\App\Http\Controllers\Research\InviteController::class, 'cancel'])->name('research.invite.cancel');
    Route::get('/research/{research_id}/invite/confirm', [\App\Http\Controllers\Research\InviteController::class, 'confirm'])->name('research.invite.confirm');
    // Filter
    Route::get('/research/filterByYear/{year_or_quarter}/{status}', [\App\Http\Controllers\Research\ResearchController::class, 'researchYearFilter'])->name('research.filterByYear');

    /* INVENTION, INNOVATION, AND CREATIVE WORKS ACCOMPLISHMENTS*/
    Route::resource('invention-innovation-creative', \App\Http\Controllers\Inventions\InventionController::class);
    //Remove Documents
    Route::get('/invention-innovation-creative/remove-document/{filename}', [\App\Http\Controllers\Inventions\InventionController::class, 'removeDoc'])->name('iicw.removedoc');

    /* EXTENSION PROGRAMS AND EXPERT SERVICES */
    Route::resource('/extension-programs/expert-service-as-consultant', \App\Http\Controllers\ExtensionPrograms\ExpertServices\ConsultantController::class);
    Route::resource('/extension-programs/expert-service-in-conference', \App\Http\Controllers\ExtensionPrograms\ExpertServices\ConferenceController::class);
    Route::resource('/extension-programs/expert-service-in-academic', \App\Http\Controllers\ExtensionPrograms\ExpertServices\AcademicController::class);
    Route::resource('/extension-programs/extension-service', \App\Http\Controllers\ExtensionPrograms\ExtensionServiceController::class);
    Route::resource('outreach-program', \App\Http\Controllers\ExtensionPrograms\OutreachProgramController::class);
    Route::resource('student-award', \App\Http\Controllers\AcademicDevelopment\StudentAwardController::class);
    Route::resource('student-training', \App\Http\Controllers\AcademicDevelopment\StudentTrainingController::class);
    Route::resource('viable-project', \App\Http\Controllers\AcademicDevelopment\ViableProjectController::class);
    Route::resource('college-department-award', \App\Http\Controllers\AcademicDevelopment\CollegeDepartmentAwardController::class);
    Route::resource('technical-extension', \App\Http\Controllers\AcademicDevelopment\TechnicalExtensionController::class);
    // Remove Documents
    Route::get('/extension-programs/expert-service-as-consultant/remove-document/{filename}', [\App\Http\Controllers\ExtensionPrograms\ExpertServices\ConsultantController::class, 'removeDoc'])->name('esconsultant.removedoc');
    Route::get('/extension-programs/expert-service-in-conference/remove-document/{filename}', [\App\Http\Controllers\ExtensionPrograms\ExpertServices\ConferenceController::class, 'removeDoc'])->name('esconference.removedoc');
    Route::get('/extension-programs/expert-service-in-academic/remove-document/{filename}', [\App\Http\Controllers\ExtensionPrograms\ExpertServices\AcademicController::class, 'removeDoc'])->name('esacademic.removedoc');
    Route::get('/extension-programs/extension-service/remove-document/{filename}', [\App\Http\Controllers\ExtensionPrograms\ExtensionServiceController::class, 'removeDoc'])->name('extension-service.removedoc');
    Route::get('/outreach-program/remove-document/{filename}', [\App\Http\Controllers\ExtensionPrograms\OutreachProgramController::class, 'removeDoc'])->name('outreach-program.removedoc');
    Route::get('/student-award/remove-document/{filename}', [\App\Http\Controllers\AcademicDevelopment\StudentAwardController::class, 'removeDoc'])->name('student-award.removedoc');
    Route::get('/student-training/remove-document/{filename}', [\App\Http\Controllers\AcademicDevelopment\StudentTrainingController::class, 'removeDoc'])->name('student-training.removedoc');
    Route::get('/viable-project/remove-document/{filename}', [\App\Http\Controllers\AcademicDevelopment\ViableProjectController::class, 'removeDoc'])->name('viable-project.removedoc');
    Route::get('/college-department-award/remove-document/{filename}', [\App\Http\Controllers\AcademicDevelopment\CollegeDepartmentAwardController::class, 'removeDoc'])->name('college-department-award.removedoc');
    Route::get('/technical-extension/remove-document/{filename}', [\App\Http\Controllers\AcademicDevelopment\TechnicalExtensionController::class, 'removeDoc'])->name('technical-extension.removedoc');
    
    /* ACADEMIC DEVELOPMENT ACCOMPLISHMENTS */
    Route::resource('/academic-development/rtmmi', \App\Http\Controllers\AcademicDevelopment\ReferenceController::class);
    Route::resource('/academic-development/syllabus', \App\Http\Controllers\AcademicDevelopment\SyllabusController::class);
    // Remove Documents
    Route::get('/academic-development/rtmmi/remove-document/{filename}', [\App\Http\Controllers\AcademicDevelopment\ReferenceController::class, 'removeDoc'])->name('rtmmi.removedoc');
    Route::get('/academic-development/syllabus/remove-document/{filename}', [\App\Http\Controllers\AcademicDevelopment\SyllabusController::class, 'removeDoc'])->name('syllabus.removedoc');
    // Filter
    Route::get('/academic-development/syllabus/{year}/{filter}', [\App\Http\Controllers\AcademicDevelopment\SyllabusController::class, 'syllabusYearFilter'])->name('syllabus.filterByYear');

    /* PARTNERSHIP, LINKAGES, AND NETWORK ACCOMPLISHMENTS*/
    Route::resource('partnership', \App\Http\Controllers\ExtensionPrograms\PartnershipController::class);
    Route::get('/mobility/remove-document/{filename}', [\App\Http\Controllers\ExtensionPrograms\MobilityController::class, 'removeDoc'])->name('mobility.removedoc');
    //Remove Documents
    Route::get('/partnership/remove-document/{filename}', [\App\Http\Controllers\ExtensionPrograms\PartnershipController::class, 'removeDoc'])->name('partnership.removedoc');
    Route::resource('mobility', \App\Http\Controllers\ExtensionPrograms\MobilityController::class);

    /* REQUEST & QUERIES ACCOMPLISHMENTS */
    Route::resource('ipcr/request', \App\Http\Controllers\IPCR\RequestController::class);
    // Remove Documents
    Route::get('/request/remove-document/{filename}', [\App\Http\Controllers\IPCR\RequestController::class, 'removeDoc'])->name('request.removedoc');

    /* REPORTS API */
    Route::get('/reports/tables/data/{id}', [\App\Http\Controllers\Reports\ReportDataController::class, 'getColumnDataPerReportCategory']);
    Route::get('/reports/tables/data/{id}/{code}', [\App\Http\Controllers\Reports\ReportDataController::class, 'getTableDataPerColumnCategory']);
    Route::get('/reports/tables/data/documents/{id}/{code}', [\App\Http\Controllers\Reports\ReportDataController::class, 'getDocuments']);
    Route::get('/reports/report-category/{id}', [\App\Http\Controllers\Reports\ReportDataController::class, 'getReportCategory']);
    Route::get('/reports/data/{id}', [\App\Http\Controllers\Reports\ReportDataController::class, 'getReportData']);
    Route::get('/reports/docs/{id}', [\App\Http\Controllers\Reports\ReportDataController::class, 'getDocumentsUsingId']);
    Route::get('/reports/reject-details/{id}', [\App\Http\Controllers\Reports\ReportDataController::class, 'getRejectDetails']);
    Route::get('/reports/manage/{report_id}/{report_category_id}', [\App\Http\Controllers\Reports\ReportDataController::class, 'viewReportOrigin'])->name('report.manage');
    Route::post('/reports/generate/{id}', [\App\Http\Controllers\Reports\GenerateController::class, 'index'])->name('report.generate.index');

    /* REPORTS TO RECEIVE */
    Route::get('/reports/to-receive/researcher', [\App\Http\Controllers\Reports\ToReceive\ResearcherController::class, 'index'])->name('researcher.index');
    Route::get('/reports/to-receive/extensionist', [\App\Http\Controllers\Reports\ToReceive\ExtensionistController::class, 'index'])->name('extensionist.index');
    Route::resource('/reports/to-receive/chairperson', \App\Http\Controllers\Reports\ToReceive\ChairpersonController::class);
    Route::resource('/reports/to-receive/director', \App\Http\Controllers\Reports\ToReceive\DeanController::class);
    Route::resource('/reports/to-receive/sector', \App\Http\Controllers\Reports\ToReceive\SectorController::class);
    Route::resource('/reports/to-receive/ipqmso', \App\Http\Controllers\Reports\ToReceive\IpqmsoController::class);
    // Acted By Chairperson
    Route::get('/reports/chairperson/accept/{id}', [\App\Http\Controllers\Reports\ToReceive\ChairpersonController::class, 'accept'])->name('chairperson.accept');
    Route::get('/reports/chairperson/reject-create/{id}', [\App\Http\Controllers\Reports\ToReceive\ChairpersonController::class, 'rejectCreate'])->name('chairperson.reject-create');
    Route::post('/reports/chairperson/reject/{id}', [\App\Http\Controllers\Reports\ToReceive\ChairpersonController::class, 'reject'])->name('chairperson.reject');
    Route::get('/reports/chairperson/relay/{id}', [\App\Http\Controllers\Reports\ToReceive\ChairpersonController::class, 'relay'])->name('chairperson.relay');
    Route::get('/reports/chairperson/undo/{id}', [\App\Http\Controllers\Reports\ToReceive\ChairpersonController::class, 'undo'])->name('chairperson.undo');
    Route::post('/reports/chairperson/accept-selected', [\App\Http\Controllers\Reports\ToReceive\ChairpersonController::class, 'acceptSelected'])->name('chairperson.accept-select');
    Route::post('/reports/chairperson/deny-selected', [\App\Http\Controllers\Reports\ToReceive\ChairpersonController::class, 'denySelected'])->name('chairperson.deny-select');
    Route::post('/reports/chairperson/reject-selected', [\App\Http\Controllers\Reports\ToReceive\ChairpersonController::class, 'rejectSelected'])->name('chairperson.reject-selected');
    // Acted By Dean
    Route::get('/reports/director/accept/{id}', [\App\Http\Controllers\Reports\ToReceive\DeanController::class, 'accept'])->name('dean.accept');
    Route::get('/reports/director/reject-create/{id}', [\App\Http\Controllers\Reports\ToReceive\DeanController::class, 'rejectCreate'])->name('dean.reject-create');
    Route::post('/reports/director/reject/{id}', [\App\Http\Controllers\Reports\ToReceive\DeanController::class, 'reject'])->name('dean.reject');
    Route::get('/reports/director/relay/{id}', [\App\Http\Controllers\Reports\ToReceive\DeanController::class, 'relay'])->name('dean.relay');
    Route::get('/reports/director/undo/{id}', [\App\Http\Controllers\Reports\ToReceive\DeanController::class, 'undo'])->name('dean.undo');
    Route::post('/reports/director/accept-selected', [\App\Http\Controllers\Reports\ToReceive\DeanController::class, 'acceptSelected'])->name('dean.accept-select');
    Route::post('/reports/director/deny-selected', [\App\Http\Controllers\Reports\ToReceive\DeanController::class, 'denySelected'])->name('dean.deny-select');
    Route::post('/reports/director/reject-selected', [\App\Http\Controllers\Reports\ToReceive\DeanController::class, 'rejectSelected'])->name('dean.reject-selected');
    // Acted By Sector
    Route::get('/reports/sector/accept/{id}', [\App\Http\Controllers\Reports\ToReceive\SectorController::class, 'accept'])->name('sector.accept');
    Route::get('/reports/sector/reject-create/{id}', [\App\Http\Controllers\Reports\ToReceive\SectorController::class, 'rejectCreate'])->name('sector.reject-create');
    Route::post('/reports/sector/reject/{id}', [\App\Http\Controllers\Reports\ToReceive\SectorController::class, 'reject'])->name('sector.reject');
    Route::get('/reports/sector/relay/{id}', [\App\Http\Controllers\Reports\ToReceive\SectorController::class, 'relay'])->name('sector.relay');
    Route::get('/reports/sector/undo/{id}', [\App\Http\Controllers\Reports\ToReceive\SectorController::class, 'undo'])->name('sector.undo');
    Route::post('/reports/sector/accept-selected', [\App\Http\Controllers\Reports\ToReceive\SectorController::class, 'acceptSelected'])->name('sector.accept-select');
    Route::post('/reports/sector/deny-selected', [\App\Http\Controllers\Reports\ToReceive\SectorController::class, 'denySelected'])->name('sector.deny-select');
    Route::post('/reports/sector/reject-selected', [\App\Http\Controllers\Reports\ToReceive\SectorController::class, 'rejectSelected'])->name('sector.reject-selected');
    // Acted By IPQMSO
    Route::get('/reports/ipqmso/accept/{id}', [\App\Http\Controllers\Reports\ToReceive\IpqmsoController::class, 'accept'])->name('ipqmso.accept');
    Route::get('/reports/ipqmso/reject-create/{id}', [\App\Http\Controllers\Reports\ToReceive\IpqmsoController::class, 'rejectCreate'])->name('ipqmso.reject-create');
    Route::post('/reports/ipqmso/reject/{id}', [\App\Http\Controllers\Reports\ToReceive\IpqmsoController::class, 'reject'])->name('ipqmso.reject');
    Route::post('/reports/ipqmso/undo/{id}', [\App\Http\Controllers\Reports\ToReceive\IpqmsoController::class, 'reject'])->name('ipqmso.undo');
    Route::post('/reports/ipqmso/accept-selected', [\App\Http\Controllers\Reports\ToReceive\IpqmsoController::class, 'acceptSelected'])->name('ipqmso.accept-select');
    Route::post('/reports/ipqmso/deny-selected', [\App\Http\Controllers\Reports\ToReceive\IpqmsoController::class, 'denySelected'])->name('ipqmso.deny-select');
    Route::post('/reports/ipqmso/reject-selected', [\App\Http\Controllers\Reports\ToReceive\IpqmsoController::class, 'rejectSelected'])->name('ipqmso.reject-selected');
    //Acted By Extensionist
    Route::get('/reports/extensionist/accept/{id}', [\App\Http\Controllers\Reports\ToReceive\ExtensionistController::class, 'accept'])->name('extensionist.accept');
    Route::get('/reports/extensionist/reject-create/{id}', [\App\Http\Controllers\Reports\ToReceive\ExtensionistController::class, 'rejectCreate'])->name('extensionist.reject-create');
    Route::post('/reports/extensionist/reject/{id}', [\App\Http\Controllers\Reports\ToReceive\ExtensionistController::class, 'reject'])->name('extensionist.reject');
    Route::post('/reports/extensionist/accept-selected', [\App\Http\Controllers\Reports\ToReceive\ExtensionistController::class, 'acceptSelected'])->name('extensionist.accept-select');
    Route::post('/reports/extensionist/deny-selected', [\App\Http\Controllers\Reports\ToReceive\ExtensionistController::class, 'denySelected'])->name('extensionist.deny-select');
    Route::post('/reports/extensionist/reject-selected', [\App\Http\Controllers\Reports\ToReceive\ExtensionistController::class, 'rejectSelected'])->name('extensionist.reject-selected');
    // Acted By Researcher
    Route::get('/reports/researcher/accept/{id}', [\App\Http\Controllers\Reports\ToReceive\ResearcherController::class, 'accept'])->name('researcher.accept');
    Route::get('/reports/researcher/reject-create/{id}', [\App\Http\Controllers\Reports\ToReceive\ResearcherController::class, 'rejectCreate'])->name('researcher.reject-create');
    Route::post('/reports/researcher/reject/{id}', [\App\Http\Controllers\Reports\ToReceive\ResearcherController::class, 'reject'])->name('researcher.reject');
    Route::post('/reports/researcher/accept-selected', [\App\Http\Controllers\Reports\ToReceive\ResearcherController::class, 'acceptSelected'])->name('researcher.accept-select');
    Route::post('/reports/researcher/deny-selected', [\App\Http\Controllers\Reports\ToReceive\ResearcherController::class, 'denySelected'])->name('researcher.deny-select');
    Route::post('/reports/researcher/reject-selected', [\App\Http\Controllers\Reports\ToReceive\ResearcherController::class, 'rejectSelected'])->name('researcher.reject-selected');

    /* REPORTS TO CONSOLIDATE */
    Route::get('/reports/consolidate/my-accomplishments', [\App\Http\Controllers\Reports\Consolidate\MyAccomplishmentController::class, 'index'])->name('reports.consolidate.myaccomplishments');
    Route::get('/reports/consolidate/research/{id}', [\App\Http\Controllers\Reports\Consolidate\ResearchConsolidatedController::class, 'index'])->name('reports.consolidate.research');
    Route::get('/reports/consolidate/extension/{id}', [\App\Http\Controllers\Reports\Consolidate\ExtensionConsolidatedController::class, 'index'])->name('reports.consolidate.extension');
    Route::get('/reports/consolidate/department/{id}', [\App\Http\Controllers\Reports\Consolidate\DepartmentConsolidatedController::class, 'index'])->name('reports.consolidate.department');
    Route::get('/reports/consolidate/college/{id}', [\App\Http\Controllers\Reports\Consolidate\CollegeConsolidatedController::class, 'index'])->name('reports.consolidate.college');
    Route::get('/reports/consolidate/sector/{id}', [\App\Http\Controllers\Reports\Consolidate\SectorConsolidatedController::class, 'index'])->name('reports.consolidate.sector');
    Route::get('/reports/consolidate/all', [\App\Http\Controllers\Reports\Consolidate\IpqmsoConsolidatedController::class, 'index'])->name('reports.consolidate.ipqmso');
    Route::get('/reports/consolidate/my-accomplishments/reportYearFilter/{year}/{quarter}', [\App\Http\Controllers\Reports\Consolidate\MyAccomplishmentController::class, 'individualReportYearFilter'])->name('reports.consolidate.myaccomplishments.reportYearFilter');
    Route::get('/reports/consolidate/department/reportYearFilter/{dept}/{year}/{quarter}', [\App\Http\Controllers\Reports\Consolidate\DepartmentConsolidatedController::class, 'departmentReportYearFilter'])->name('reports.consolidate.department.reportYearFilter');
    Route::get('/reports/consolidate/college/reportYearFilter/{college}/{year}/{quarter}', [\App\Http\Controllers\Reports\Consolidate\CollegeConsolidatedController::class, 'collegeReportYearFilter'])->name('reports.consolidate.college.reportYearFilter');
    Route::get('/reports/consolidate/all/{year}/{quarter}', [\App\Http\Controllers\Reports\Consolidate\IpqmsoConsolidatedController::class, 'reportYearFilter'])->name('reports.consolidate.ipqmso.reportYearFilter');
    
    /* GENERATE/EXPORT REPORT */
    Route::post('/reports/export/individual-export/{source_type}/{report_format}/{source_generate}/{year_generate}/{quarter_generate}/{id}', [\App\Http\Controllers\Reports\GenerateController::class, 'individualExport'])->name('report.individual.export');

    /* FOR TESTING PURPOSES */
    Route::get('/test', [\App\Http\Controllers\Test\TestController::class, 'index'])->name('test.index');
    
    /* SUBMISSIONS */
    Route::resource('/submissions/to-finalize', \App\Http\Controllers\Submissions\SubmissionController::class);
    Route::get('/submissions/to-finalize/college/{collegeId}', [\App\Http\Controllers\Submissions\SubmissionController::class, 'getCollege'])->name('submissions.getCollege');
    Route::get('/submissions/denied', [\App\Http\Controllers\Submissions\DeniedController::class, 'index'])->name('submissions.denied.index');
    Route::get('/submissions/approved', [\App\Http\Controllers\Submissions\AcceptedController::class, 'index'])->name('submissions.accepted.index');

    // Route::get('/submissions/faculty/add-document/{id}/{research_category_id}',  [\App\Http\Controllers\Submissions\SubmissionController::class, 'addDocument'])->name('submissions.faculty.adddoc');
    // Route::post('/submissions/faculty/save-document/{id}/{research_category_id}',  [\App\Http\Controllers\Submissions\SubmissionController::class, 'saveDocument'])->name('submissions.faculty.savedoc');

    /* HRIS SUBMISSIONS */
    Route::get('/submissions/educational-background', [\App\Http\Controllers\HRISSubmissions\EducationController::class, 'index'])->name('submissions.educ.index');
    Route::get('/submissions/educational-background/{educID}/add/', [\App\Http\Controllers\HRISSubmissions\EducationController::class, 'add'])->name('submissions.educ.add');
    Route::post('/submissions/educational-background/{educID}/save/', [\App\Http\Controllers\HRISSubmissions\EducationController::class, 'save'])->name('submissions.educ.save');
    Route::get('/submissions/development/', [\App\Http\Controllers\HRISSubmissions\SeminarAndTrainingController::class, 'index'])->name('submissions.development.index');
    Route::get('/submissions/development/seminar/{id}/add/', [\App\Http\Controllers\HRISSubmissions\SeminarAndTrainingController::class, 'addSeminar'])->name('submissions.development.seminar.add');
    Route::post('/submissions/development/seminar/{id}/save/', [\App\Http\Controllers\HRISSubmissions\SeminarAndTrainingController::class, 'saveSeminar'])->name('submissions.development.seminar.save');
    Route::get('/submissions/development/training/{id}/add/', [\App\Http\Controllers\HRISSubmissions\SeminarAndTrainingController::class, 'addTraining'])->name('submissions.development.training.add');
    Route::post('/submissions/development/training/{id}/save/', [\App\Http\Controllers\HRISSubmissions\SeminarAndTrainingController::class, 'saveTraining'])->name('submissions.development.training.save');
    Route::get('/submissions/officership', [\App\Http\Controllers\HRISSubmissions\OfficershipController::class, 'index'])->name('submissions.officership.index');

    /* PROFILE (SYNCHRONIZED WITH HRIS) */
    Route::get('/profile/personal', [\App\Http\Controllers\User\ProfileController::class, 'personal'])->name('profile.personal');
    Route::get('/profile/employment', [\App\Http\Controllers\User\ProfileController::class, 'employment'])->name('profile.employment');
    Route::get('/profile/education', [\App\Http\Controllers\User\ProfileController::class, 'education'])->name('profile.education');
    Route::get('/profile/professional-study', [\App\Http\Controllers\User\ProfileController::class, 'professionalStudy'])->name('profile.professionalStudy');
    Route::get('/profile/teaching-discipline', [\App\Http\Controllers\User\ProfileController::class, 'teaching'])->name('profile.teaching');
    
    /* SUPER ADMIN PERMANENT TASKS */
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function(){
        // Maintenance
        Route::resource('/maintenances/colleges', \App\Http\Controllers\Maintenances\CollegeController::class);
        Route::resource('/maintenances/departments', \App\Http\Controllers\Maintenances\DepartmentController::class);
        // Authentication Management
        Route::resource('/authentication/users', \App\Http\Controllers\UserController::class);
        Route::resource('/authentication/roles', \App\Http\Controllers\Authentication\RoleController::class);
        Route::resource('/authentication/permissions', \App\Http\Controllers\Authentication\PermissionController::class);

        // forms
        // Route::post('/forms/save-arrange', [\App\Http\Controllers\FormBuilder\FormController::class, 'arrange'])->name('forms.arrange');
        // Route::resource('forms', \App\Http\Controllers\FormBuilder\FormController::class);
        // form's fields
        // Route::get('/forms/fields/info/{id}',[\App\Http\Controllers\FormBuilder\FieldController::class, 'getInfo']);
        // Route::post('/forms/fields/save-arrange/{id}', [\App\Http\Controllers\FormBuilder\FieldController::class, 'arrange'])->name('fields.arrange');
        // Route::get('/forms/fields/preview/{id}', [\App\Http\Controllers\FormBuilder\FieldController::class, 'preview'])->name('fields.preview');
        // Route::resource('forms.fields', \App\Http\Controllers\FormBuilder\FieldController::class);

    });
});