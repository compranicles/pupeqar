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

// homepage
Route::get('/', function () {
    return view('auth.login');
})->name('home')->middleware('guest');

// dashboard and homepage display
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});

// upload and remove documents/images
Route::post('upload', [\App\Http\Controllers\UploadController::class, 'store']);
Route::delete('remove', [\App\Http\Controllers\UploadController::class, 'destroy']);

// document/image access routes
Route::get('image/{filename}', [\App\Http\Controllers\StorageFileController::class, 'getDocumentFile'])->name('document.display');
Route::get('download/{filename}', [\App\Http\Controllers\StorageFileController::class, 'downloadFile'])->name('document.download');
Route::get('document-view/{filename}', [\App\Http\Controllers\StorageFileController::class, 'viewFile'])->name('document.view');

// auth checker
Route::group(['middleware' => 'auth'], function() {
    //announcements
    Route::get('announcements/view/{id}', [\App\Http\Controllers\AnnouncementController::class, 'showMessage']);
    Route::get('/announcements/hide/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'hide'])->name('announcements.hide');
    Route::get('/announcements/activate/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'activate'])->name('announcements.activate');
    Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class);

    // maintenances
    Route::get('/maintenances', [\App\Http\Controllers\Maintenances\MaintenanceController::class, 'index'])->name('maintenances.index');

    // Colleges and Departments
    Route::get('/maintenances/colleges/name/{id}', [\App\Http\Controllers\Maintenances\CollegeController::class, 'getCollegeName'])->name('college.name');
    Route::resource('/maintenances/colleges', \App\Http\Controllers\Maintenances\CollegeController::class);
    //Route::get('/maintenances/colleges/{college}/delete', [\App\Http\Controllers\Maintenances\CollegeController::class, 'delete']);
    Route::get('/departments/options/{id}', [\App\Http\Controllers\Maintenances\DepartmentController::class, 'options']);
    Route::get('/maintenances/departments/name/{id}', [\App\Http\Controllers\Maintenances\DepartmentController::class, 'getDepartmentName'])->name('department.name');
    Route::resource('/maintenances/departments', \App\Http\Controllers\Maintenances\DepartmentController::class);
    //Route::get('/maintenances/departments/{department}/delete', [\App\Http\Controllers\Maintenances\DepartmentController::class, 'delete']);

    //Currency
    Route::get('/maintenances/currencies/name/{id}', [\App\Http\Controllers\Maintenances\CurrencyController::class, 'getCurrencyName'])->name('currency.name');
    Route::get('/maintenances/currencies/list', [\App\Http\Controllers\Maintenances\CurrencyController::class, 'list'])->name('currencies.list');
    Route::resource('/maintenances/currencies', \App\Http\Controllers\Maintenances\CurrencyController::class);

    // dropdowns
    Route::get('/dropdowns/options/{id}', [\App\Http\Controllers\Maintenances\DropdownController::class, 'options'])->name('dropdowns.options');
    Route::post('/dropdowns/options/add/{id}', [\App\Http\Controllers\Maintenances\DropdownController::class, 'addOptions'])->name('dropdowns.options.add');
    Route::post('/dropdowns/options/arrange', [\App\Http\Controllers\Maintenances\DropdownController::class, 'arrangeOptions']);
    Route::get('/dropdowns/options/activate/{id}', [\App\Http\Controllers\Maintenances\DropdownController::class, 'activate']);
    Route::get('/dropdowns/options/inactivate/{id}', [\App\Http\Controllers\Maintenances\DropdownController::class, 'inactivate']);
    Route::get('/dropdowns/option/name/{id}', [\App\Http\Controllers\Maintenances\DropdownController::class, 'getOptionName'])->name('dropdowns.option.name');
    Route::resource('dropdowns', \App\Http\Controllers\Maintenances\DropdownController::class);

    //reportsManagement
    Route::get('/reports/tables/{table}', [\App\Http\Controllers\Maintenances\ReportCategoryController::class, 'getTableColumns']);
    Route::get('/report-columns/activate/{id}', [\App\Http\Controllers\Maintenances\ReportColumnController::class, 'activate']);
    Route::get('/report-columns/inactivate/{id}', [\App\Http\Controllers\Maintenances\ReportColumnController::class, 'inactivate']);
    Route::post('/report-columns/arrange', [\App\Http\Controllers\Maintenances\ReportColumnController::class, 'arrange']);
    Route::resource('report-types', \App\Http\Controllers\Maintenances\ReportTypeController::class);
    Route::resource('report-categories', \App\Http\Controllers\Maintenances\ReportCategoryController::class);
    Route::resource('report-categories.report-columns', \App\Http\Controllers\Maintenances\ReportColumnController::class);

    //inventionForms
    Route::get('/invention-forms/activate/{id}', [\App\Http\Controllers\Maintenances\InventionFormController::class, 'activate']);
    Route::get('/invention-forms/inactivate/{id}', [\App\Http\Controllers\Maintenances\InventionFormController::class, 'inactivate']);
    Route::resource('invention-forms', \App\Http\Controllers\Maintenances\InventionFormController::class);

    //inventionFields
    Route::get('/invention-fields/activate/{id}', [\App\Http\Controllers\Maintenances\InventionFieldController::class, 'activate']);
    Route::get('/invention-fields/inactivate/{id}', [\App\Http\Controllers\Maintenances\InventionFieldController::class, 'inactivate']);
    Route::post('/invention-fields/arrange', [\App\Http\Controllers\Maintenances\InventionFieldController::class, 'arrange']);
    Route::resource('invention-forms.invention-fields', \App\Http\Controllers\Maintenances\InventionFieldController::class);

    //researchForms
    Route::get('/research-forms/activate/{id}', [\App\Http\Controllers\Maintenances\ResearchFormController::class, 'activate']);
    Route::get('/research-forms/inactivate/{id}', [\App\Http\Controllers\Maintenances\ResearchFormController::class, 'inactivate']);
    Route::resource('research-forms', \App\Http\Controllers\Maintenances\ResearchFormController::class);    

    //researchFields
    Route::get('/research-fields/activate/{id}', [\App\Http\Controllers\Maintenances\ResearchFieldController::class, 'activate']);
    Route::get('/research-fields/inactivate/{id}', [\App\Http\Controllers\Maintenances\ResearchFieldController::class, 'inactivate']);
    Route::post('/research-fields/arrange', [\App\Http\Controllers\Maintenances\ResearchFieldController::class, 'arrange']);
    Route::resource('research-forms.research-fields', \App\Http\Controllers\Maintenances\ResearchFieldController::class);

    //extensionProgramForms
    Route::get('/extension-program-forms/activate/{id}', [\App\Http\Controllers\Maintenances\ExtensionProgramFormController::class, 'activate']);
    Route::get('/extension-program-forms/inactivate/{id}', [\App\Http\Controllers\Maintenances\ExtensionProgramFormController::class, 'inactivate']);
    Route::resource('extension-program-forms', \App\Http\Controllers\Maintenances\ExtensionProgramFormController::class);    

    //extensionProgramFields
    Route::get('/extension-program-fields/activate/{id}', [\App\Http\Controllers\Maintenances\ExtensionProgramFieldController::class, 'activate']);
    Route::get('/extension-program-fields/inactivate/{id}', [\App\Http\Controllers\Maintenances\ExtensionProgramFieldController::class, 'inactivate']);
    Route::post('/extension-program-fields/arrange', [\App\Http\Controllers\Maintenances\ExtensionProgramFieldController::class, 'arrange']);
    Route::resource('extension-program-forms.extension-program-fields', \App\Http\Controllers\Maintenances\ExtensionProgramFieldController::class);

    //academicForms
    Route::get('/academic-module-forms/activate/{id}', [\App\Http\Controllers\Maintenances\AcademicModuleFormController::class, 'activate']);
    Route::get('/academic-module-forms/inactivate/{id}', [\App\Http\Controllers\Maintenances\AcademicModuleFormController::class, 'inactivate']);
    Route::resource('academic-module-forms', \App\Http\Controllers\Maintenances\AcademicModuleFormController::class);

    //academicFields
    Route::get('/academic-module-fields/activate/{id}', [\App\Http\Controllers\Maintenances\AcademicModuleFieldController::class, 'activate']);
    Route::get('/academic-module-fields/inactivate/{id}', [\App\Http\Controllers\Maintenances\AcademicModuleFieldController::class, 'inactivate']);
    Route::post('/academic-module-fields/arrange', [\App\Http\Controllers\Maintenances\AcademicModuleFieldController::class, 'arrange']);
    Route::resource('academic-module-forms.academic-module-fields', \App\Http\Controllers\Maintenances\AcademicModuleFieldController::class);

    //ipcrForms
    Route::get('/ipcr-forms/activate/{id}', [\App\Http\Controllers\Maintenances\IPCRFormController::class, 'activate']);
    Route::get('/ipcr-forms/inactivate/{id}', [\App\Http\Controllers\Maintenances\IPCRFormController::class, 'inactivate']);
    Route::resource('ipcr-forms', \App\Http\Controllers\Maintenances\IPCRFormController::class);

    //ipcrFields
    Route::get('/ipcr-fields/activate/{id}', [\App\Http\Controllers\Maintenances\IPCRFieldController::class, 'activate']);
    Route::get('/ipcr-fields/inactivate/{id}', [\App\Http\Controllers\Maintenances\IPCRFieldController::class, 'inactivate']);
    Route::post('/ipcr-fields/arrange', [\App\Http\Controllers\Maintenances\IPCRFieldController::class, 'arrange']);
    Route::resource('ipcr-forms.ipcr-fields', \App\Http\Controllers\Maintenances\IPCRFieldController::class);

    //researchSubmissions
    Route::get('/research/complete/{id}', [\App\Http\Controllers\Research\ResearchController::class, 'complete'])->name('research.complete');
    Route::get('/research/publication/{id}', [\App\Http\Controllers\Research\ResearchController::class, 'publication'])->name('research.publication');
    Route::get('/research/presentation/{id}', [\App\Http\Controllers\Research\ResearchController::class, 'presentation'])->name('research.presentation');
    Route::get('/research/copyright/{id}', [\App\Http\Controllers\Research\ResearchController::class, 'copyright'])->name('research.copyright');
    Route::get('/research/add-document/{research_code}/{research_category_id}',  [\App\Http\Controllers\Research\ResearchController::class, 'addDocument'])->name('research.adddoc');
    Route::post('/research/save-document/{research_code}/{research_category_id}',  [\App\Http\Controllers\Research\ResearchController::class, 'saveDocument'])->name('research.savedoc');
    Route::get('/research/remove-document/{filename}', [\App\Http\Controllers\Research\ResearchController::class, 'removeDoc'])->name('research.removedoc');
    
    Route::post('/research/with-code', [\App\Http\Controllers\Research\ResearchController::class, 'useResearchCode'])->name('research.code');
    Route::get('/research/with-code/create/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'addResearch'])->name('research.code.create');
    Route::post('/research/with-code/save/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'saveResearch'])->name('research.code.save');
    Route::get('/research/retrieve/{research_code}',  [\App\Http\Controllers\Research\ResearchController::class, 'retrieve'])->name('research.retrieve');
    Route::post('/research/edit-non-lead/{research}',  [\App\Http\Controllers\Research\ResearchController::class, 'updateNonLead'])->name('research.update-non-lead');
    Route::get('/research/manage-researchers/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'manageResearchers'])->name('research.manage-researchers');
    Route::post('/research/manage-researchers/save-role/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'saveResearchRole'])->name('research.save-role');
    Route::post('/research/manage-researchers/remove-researcher/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'removeResearcher'])->name('research.remove-researcher');
    Route::get('/research/manage-researchers/remove-self/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'removeSelf'])->name('research.remove-self');
    Route::post('/research/manage-researchers/return-researcher/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'returnResearcher'])->name('research.return-researcher');

    //FACULTY: research

    // Route::get('/research/edit-non-lead/{id}',  [\App\Http\Controllers\Research\ResearchController::class, 'updateNonLead'])->name('research.update-non-lead');

    /************************************RESEARCH FORMS*********************************** */
    //research
    Route::resource('research', \App\Http\Controllers\Research\ResearchController::class);
    Route::resource('research.completed', \App\Http\Controllers\Research\CompletedController::class);
    Route::resource('research.publication', \App\Http\Controllers\Research\PublicationController::class);
    Route::resource('research.presentation', \App\Http\Controllers\Research\PresentationController::class);
    Route::resource('research.citation', \App\Http\Controllers\Research\CitationController::class);
    Route::resource('research.utilization', \App\Http\Controllers\Research\UtilizationController::class);
    Route::resource('research.copyrighted', \App\Http\Controllers\Research\CopyrightedController::class);
    
    /************************************INVENTION, INNOVATION, CREATIVE WORKS FORMS*********************************** */

    //FACULTY:invention
    Route::get('/invention-innovation-creative/remove-document/{filename}', [\App\Http\Controllers\Inventions\InventionController::class, 'removeDoc'])->name('iicw.removedoc');
    Route::resource('invention-innovation-creative', \App\Http\Controllers\Inventions\InventionController::class);

    /************************************EXTENSION PROGRAMS AND EXPERT SERVICES*********************************** */

    //FACULTY: extension-programs
    Route::get('/extension-programs/expert-service-as-consultant/remove-document/{filename}', [\App\Http\Controllers\ExtensionPrograms\ExpertServices\ConsultantController::class, 'removeDoc'])->name('esconsultant.removedoc');
    Route::resource('/extension-programs/expert-service-as-consultant', \App\Http\Controllers\ExtensionPrograms\ExpertServices\ConsultantController::class);
    
    Route::get('/extension-programs/expert-service-in-conference/remove-document/{filename}', [\App\Http\Controllers\ExtensionPrograms\ExpertServices\ConferenceController::class, 'removeDoc'])->name('esconference.removedoc');
    Route::resource('/extension-programs/expert-service-in-conference', \App\Http\Controllers\ExtensionPrograms\ExpertServices\ConferenceController::class);
    
    Route::get('/extension-programs/expert-service-in-academic/remove-document/{filename}', [\App\Http\Controllers\ExtensionPrograms\ExpertServices\AcademicController::class, 'removeDoc'])->name('esacademic.removedoc');
    Route::resource('/extension-programs/expert-service-in-academic', \App\Http\Controllers\ExtensionPrograms\ExpertServices\AcademicController::class);
    
    Route::get('/extension-programs/extension-service/remove-document/{filename}', [\App\Http\Controllers\ExtensionPrograms\ExtensionServiceController::class, 'removeDoc'])->name('extension-service.removedoc');
    Route::resource('/extension-programs/extension-service', \App\Http\Controllers\ExtensionPrograms\ExtensionServiceController::class);

    /************************************ACADEMIC DEVELOPMENT FORMS*********************************** */

    //FACULTY: academic-development
    Route::get('/academic-development/rtmmi/remove-document/{filename}', [\App\Http\Controllers\AcademicDevelopment\ReferenceController::class, 'removeDoc'])->name('rtmmi.removedoc');
    Route::resource('/academic-development/rtmmi', \App\Http\Controllers\AcademicDevelopment\ReferenceController::class);
    Route::get('/academic-development/syllabus/remove-document/{filename}', [\App\Http\Controllers\AcademicDevelopment\SyllabusController::class, 'removeDoc'])->name('syllabus.removedoc');
    Route::resource('/academic-development/syllabus', \App\Http\Controllers\AcademicDevelopment\SyllabusController::class);

    //academics
    // Route::resource('academics', \App\Http\Controllers\Academics\AcademicController::class);

    //partnership, linkages, network
    Route::get('/partnership/remove-document/{filename}', [\App\Http\Controllers\ExtensionPrograms\PartnershipController::class, 'removeDoc'])->name('partnership.removedoc');
    Route::resource('partnership', \App\Http\Controllers\ExtensionPrograms\PartnershipController::class);

    //inter-country mobility
    Route::get('/mobility/remove-document/{filename}', [\App\Http\Controllers\ExtensionPrograms\MobilityController::class, 'removeDoc'])->name('mobility.removedoc');
    Route::resource('mobility', \App\Http\Controllers\ExtensionPrograms\MobilityController::class);

    //Outreach Programs
    Route::get('/outreach-program/remove-document/{filename}', [\App\Http\Controllers\ExtensionPrograms\OutreachProgramController::class, 'removeDoc'])->name('outreach-program.removedoc');
    Route::resource('outreach-program', \App\Http\Controllers\ExtensionPrograms\OutreachProgramController::class);

    //StudentAwards and Recognition
    Route::get('/student-award/remove-document/{filename}', [\App\Http\Controllers\AcademicDevelopment\StudentAwardController::class, 'removeDoc'])->name('student-award.removedoc');
    Route::resource('student-award', \App\Http\Controllers\AcademicDevelopment\StudentAwardController::class);

    //Student Seminar and Trainings
    Route::get('/student-training/remove-document/{filename}', [\App\Http\Controllers\AcademicDevelopment\StudentTrainingController::class, 'removeDoc'])->name('student-training.removedoc');
    Route::resource('student-training', \App\Http\Controllers\AcademicDevelopment\StudentTrainingController::class);

    //Viable Demonstration Projects
    Route::get('/viable-project/remove-document/{filename}', [\App\Http\Controllers\AcademicDevelopment\ViableProjectController::class, 'removeDoc'])->name('viable-project.removedoc');
    Route::resource('viable-project', \App\Http\Controllers\AcademicDevelopment\ViableProjectController::class);

    //Awards and Recognition Received by the College and Department
    Route::get('/college-department-award/remove-document/{filename}', [\App\Http\Controllers\AcademicDevelopment\CollegeDepartmentAwardController::class, 'removeDoc'])->name('college-department-award.removedoc');
    Route::resource('college-department-award', \App\Http\Controllers\AcademicDevelopment\CollegeDepartmentAwardController::class);

    //Technical Extension Programs/ Projects/ Activities
    Route::get('/technical-extension/remove-document/{filename}', [\App\Http\Controllers\AcademicDevelopment\TechnicalExtensionController::class, 'removeDoc'])->name('technical-extension.removedoc');
    Route::resource('technical-extension', \App\Http\Controllers\AcademicDevelopment\TechnicalExtensionController::class);

    /************************************IPCR*********************************** */
    Route::get('/request/remove-document/{filename}', [\App\Http\Controllers\IPCR\RequestController::class, 'removeDoc'])->name('request.removedoc');
    Route::resource('ipcr/request', \App\Http\Controllers\IPCR\RequestController::class);

    /***************************FILTERS*************************************** */
    Route::get('/research/filterByYear/{year_or_quarter}/{status}', [\App\Http\Controllers\Research\ResearchController::class, 'researchYearFilter'])->name('research.filterByYear');
    Route::get('/invention-innovation-creative/{year_or_quarter}/{filter}', [\App\Http\Controllers\Inventions\InventionController::class, 'inventionYearFilter'])->name('invention.filterByYear');
    Route::get('/academic-development/syllabus/{year}/{filter}', [\App\Http\Controllers\AcademicDevelopment\SyllabusController::class, 'syllabusYearFilter'])->name('syllabus.filterByYear');


    // Reports API
    Route::get('/reports/tables/data/{id}', [\App\Http\Controllers\Reports\ReportController::class, 'getColumnDataPerReportCategory']);
    Route::get('/reports/tables/data/{id}/{code}', [\App\Http\Controllers\Reports\ReportController::class, 'getTableDataPerColumnCategory']);
    Route::get('/reports/tables/data/documents/{id}/{code}', [\App\Http\Controllers\Reports\ReportController::class, 'getDocuments']);
    Route::get('/reports/data/{id}', [\App\Http\Controllers\Reports\ReportController::class, 'getReportData']);
    Route::get('/reports/docs/{id}', [\App\Http\Controllers\Reports\ReportController::class, 'getDocumentsUsingId']);
    Route::get('/reports/reject-details/{id}', [\App\Http\Controllers\Reports\ReportController::class, 'getRejectDetails']);
    Route::get('/reports/manage/{report_id}/{report_category_id}', [\App\Http\Controllers\Reports\ReportController::class, 'viewReportOrigin'])->name('report.manage');

    // //faculty Reports
    // Route::get('/submissions/faculty/add-document/{id}/{research_category_id}',  [\App\Http\Controllers\Reports\FacultyController::class, 'addDocument'])->name('faculty.adddoc');
    // Route::post('/submissions/faculty/save-document/{id}/{research_category_id}',  [\App\Http\Controllers\Reports\FacultyController::class, 'saveDocument'])->name('faculty.savedoc');
    // Route::resource('/submissions/faculty', \App\Http\Controllers\Reports\FacultyController::class);

    //chairperson Reports
    Route::get('/submissions/chairperson/accept/{id}', [\App\Http\Controllers\Reports\ChairpersonController::class, 'accept'])->name('chairperson.accept');
    Route::get('/submissions/chairperson/reject-create/{id}', [\App\Http\Controllers\Reports\ChairpersonController::class, 'rejectCreate'])->name('chairperson.reject-create');
    Route::post('/submissions/chairperson/reject/{id}', [\App\Http\Controllers\Reports\ChairpersonController::class, 'reject'])->name('chairperson.reject');
    Route::get('/submissions/chairperson/relay/{id}', [\App\Http\Controllers\Reports\ChairpersonController::class, 'relay'])->name('chairperson.relay');
    Route::get('/submissions/chairperson/undo/{id}', [\App\Http\Controllers\Reports\ChairpersonController::class, 'undo'])->name('chairperson.undo');
    Route::post('/submissions/chairperson/accept-selected', [\App\Http\Controllers\Reports\ChairpersonController::class, 'acceptSelected'])->name('chairperson.accept-select');
    Route::post('/submissions/chairperson/deny-selected', [\App\Http\Controllers\Reports\ChairpersonController::class, 'denySelected'])->name('chairperson.deny-select');
    Route::post('/submissions/chairperson/reject-selected', [\App\Http\Controllers\Reports\ChairpersonController::class, 'rejectSelected'])->name('chairperson.reject-selected');
    Route::resource('/submissions/chairperson', \App\Http\Controllers\Reports\ChairpersonController::class);

    //dean reports
    Route::get('/submissions/dean/accept/{id}', [\App\Http\Controllers\Reports\DeanController::class, 'accept'])->name('dean.accept');
    Route::get('/submissions/dean/reject-create/{id}', [\App\Http\Controllers\Reports\DeanController::class, 'rejectCreate'])->name('dean.reject-create');
    Route::post('/submissions/dean/reject/{id}', [\App\Http\Controllers\Reports\DeanController::class, 'reject'])->name('dean.reject');
    Route::get('/submissions/dean/relay/{id}', [\App\Http\Controllers\Reports\DeanController::class, 'relay'])->name('dean.relay');
    Route::get('/submissions/dean/undo/{id}', [\App\Http\Controllers\Reports\DeanController::class, 'undo'])->name('dean.undo');
    Route::post('/submissions/dean/accept-selected', [\App\Http\Controllers\Reports\DeanController::class, 'acceptSelected'])->name('dean.accept-select');
    Route::post('/submissions/dean/deny-selected', [\App\Http\Controllers\Reports\DeanController::class, 'denySelected'])->name('dean.deny-select');
    Route::post('/submissions/dean/reject-selected', [\App\Http\Controllers\Reports\DeanController::class, 'rejectSelected'])->name('dean.reject-selected');
    Route::resource('/submissions/dean', \App\Http\Controllers\Reports\DeanController::class);

    //sector reports
    Route::get('/submissions/sector/accept/{id}', [\App\Http\Controllers\Reports\SectorController::class, 'accept'])->name('sector.accept');
    Route::get('/submissions/sector/reject-create/{id}', [\App\Http\Controllers\Reports\SectorController::class, 'rejectCreate'])->name('sector.reject-create');
    Route::post('/submissions/sector/reject/{id}', [\App\Http\Controllers\Reports\SectorController::class, 'reject'])->name('sector.reject');
    Route::get('/submissions/sector/relay/{id}', [\App\Http\Controllers\Reports\SectorController::class, 'relay'])->name('sector.relay');
    Route::get('/submissions/sector/undo/{id}', [\App\Http\Controllers\Reports\SectorController::class, 'undo'])->name('sector.undo');
    Route::post('/submissions/sector/accept-selected', [\App\Http\Controllers\Reports\SectorController::class, 'acceptSelected'])->name('sector.accept-select');
    Route::post('/submissions/sector/deny-selected', [\App\Http\Controllers\Reports\SectorController::class, 'denySelected'])->name('sector.deny-select');
    Route::post('/submissions/sector/reject-selected', [\App\Http\Controllers\Reports\SectorController::class, 'rejectSelected'])->name('sector.reject-selected');
    Route::resource('/submissions/sector', \App\Http\Controllers\Reports\SectorController::class);
    
    //ipqmso reports
    Route::get('/submissions/ipqmso/accept/{id}', [\App\Http\Controllers\Reports\IpqmsoController::class, 'accept'])->name('ipqmso.accept');
    Route::get('/submissions/ipqmso/reject-create/{id}', [\App\Http\Controllers\Reports\IpqmsoController::class, 'rejectCreate'])->name('ipqmso.reject-create');
    Route::post('/submissions/ipqmso/reject/{id}', [\App\Http\Controllers\Reports\IpqmsoController::class, 'reject'])->name('ipqmso.reject');
    Route::post('/submissions/ipqmso/undo/{id}', [\App\Http\Controllers\Reports\IpqmsoController::class, 'reject'])->name('ipqmso.undo');
    Route::post('/submissions/ipqmso/accept-selected', [\App\Http\Controllers\Reports\IpqmsoController::class, 'acceptSelected'])->name('ipqmso.accept-select');
    Route::post('/submissions/ipqmso/deny-selected', [\App\Http\Controllers\Reports\IpqmsoController::class, 'denySelected'])->name('ipqmso.deny-select');
    Route::post('/submissions/ipqmso/reject-selected', [\App\Http\Controllers\Reports\IpqmsoController::class, 'rejectSelected'])->name('ipqmso.reject-selected');
    Route::resource('/submissions/ipqmso', \App\Http\Controllers\Reports\IpqmsoController::class);

    //view all reports
    Route::get('/submissions/view/all', [\App\Http\Controllers\Reports\AllController::class, 'index'])->name('reports.all');

    Route::get('/test', [\App\Http\Controllers\Test\TestController::class, 'index'])->name('test.index');
    /**********************************SUBMISSIONS************************************* */
    Route::resource('/submissions/to-finalize', \App\Http\Controllers\Submissions\SubmissionController::class);
    Route::get('/submissions/denied', [\App\Http\Controllers\Submissions\DeniedController::class, 'index'])->name('submissions.denied.index');
    Route::get('/submissions/approved', [\App\Http\Controllers\Submissions\AcceptedController::class, 'index'])->name('submissions.accepted.index');
    Route::get('/submissions/my-accomplishments', [\App\Http\Controllers\Submissions\MySubmissionController::class, 'index'])->name('submissions.myaccomp.index');
    Route::get('/submissions/department-accomplishments/{id}', [\App\Http\Controllers\Submissions\DepartmentSubmissionController::class, 'index'])->name('submissions.departmentaccomp.index');
    Route::get('/submissions/college-accomplishments/{id}', [\App\Http\Controllers\Submissions\CollegeSubmissionController::class, 'index'])->name('submissions.collegeaccomp.index');
    Route::get('/submissions/sector-accomplishments', [\App\Http\Controllers\Submissions\SectorSubmissionController::class, 'index'])->name('submissions.sectoraccomp.index');
    Route::get('/submissions/ipqmso-accomplishments', [\App\Http\Controllers\Submissions\IpqmsoSubmissionController::class, 'index'])->name('submissions.ipqmsoaccomp.index');
    Route::get('/submissions/faculty/add-document/{id}/{research_category_id}',  [\App\Http\Controllers\Submissions\SubmissionController::class, 'addDocument'])->name('submissions.faculty.adddoc');
    Route::post('/submissions/faculty/save-document/{id}/{research_category_id}',  [\App\Http\Controllers\Submissions\SubmissionController::class, 'saveDocument'])->name('submissions.faculty.savedoc');

    Route::get('/submissions/college/{collegeId}', [\App\Http\Controllers\Submissions\SubmissionController::class, 'getCollege'])->name('submissions.getCollege');
    // admin routes
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function(){

        // forms
        // Route::post('/forms/save-arrange', [\App\Http\Controllers\FormBuilder\FormController::class, 'arrange'])->name('forms.arrange');
        // Route::resource('forms', \App\Http\Controllers\FormBuilder\FormController::class);
        // form's fields
        // Route::get('/forms/fields/info/{id}',[\App\Http\Controllers\FormBuilder\FieldController::class, 'getInfo']);
        // Route::post('/forms/fields/save-arrange/{id}', [\App\Http\Controllers\FormBuilder\FieldController::class, 'arrange'])->name('fields.arrange');
        // Route::get('/forms/fields/preview/{id}', [\App\Http\Controllers\FormBuilder\FieldController::class, 'preview'])->name('fields.preview');
        // Route::resource('forms.fields', \App\Http\Controllers\FormBuilder\FieldController::class);

        //maintenances
        Route::resource('/maintenances/colleges', \App\Http\Controllers\Maintenances\CollegeController::class);
        //Route::get('/maintenances/colleges/{college}/delete', [\App\Http\Controllers\Maintenances\CollegeController::class, 'delete']);
        
        Route::resource('/maintenances/departments', \App\Http\Controllers\Maintenances\DepartmentController::class);
        //Route::get('/maintenances/departments/{department}/delete', [\App\Http\Controllers\Maintenances\DepartmentController::class, 'delete']);
    
        //authentication management
        //roles
        Route::resource('/authentication/roles', \App\Http\Controllers\Authentication\RoleController::class);
        //permissions
        Route::resource('/authentication/permissions', \App\Http\Controllers\Authentication\PermissionController::class);
        //users
        Route::resource('/authentication/users', \App\Http\Controllers\UserController::class);
    });
});