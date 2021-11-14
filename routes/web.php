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
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    $announcements = \App\Models\Announcement::where('status', 1)->latest()->take(5)->get();
    return view('dashboard', compact('announcements'));
})->name('dashboard');

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
    Route::resource('/maintenances/colleges', \App\Http\Controllers\Maintenances\CollegeController::class);
    //Route::get('/maintenances/colleges/{college}/delete', [\App\Http\Controllers\Maintenances\CollegeController::class, 'delete']);
    Route::get('/departments/options/{id}', [\App\Http\Controllers\Maintenances\DepartmentController::class, 'options']);
    Route::resource('/maintenances/departments', \App\Http\Controllers\Maintenances\DepartmentController::class);
    //Route::get('/maintenances/departments/{department}/delete', [\App\Http\Controllers\Maintenances\DepartmentController::class, 'delete']);

    //Currency
    Route::get('/maintenances/currencies/list', [\App\Http\Controllers\Maintenances\CurrencyController::class, 'list'])->name('currencies.list');
    Route::resource('/maintenances/currencies', \App\Http\Controllers\Maintenances\CurrencyController::class);

    // dropdowns
    Route::get('/dropdowns/options/{id}', [\App\Http\Controllers\Maintenances\DropdownController::class, 'options'])->name('dropdowns.options');
    Route::post('/dropdowns/options/add/{id}', [\App\Http\Controllers\Maintenances\DropdownController::class, 'addOptions'])->name('dropdowns.options.add');
    Route::post('/dropdowns/options/arrange', [\App\Http\Controllers\Maintenances\DropdownController::class, 'arrangeOptions']);
    Route::get('/dropdowns/options/activate/{id}', [\App\Http\Controllers\Maintenances\DropdownController::class, 'activate']);
    Route::get('/dropdowns/options/inactivate/{id}', [\App\Http\Controllers\Maintenances\DropdownController::class, 'inactivate']);
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

    Route::resource('research', \App\Http\Controllers\Research\ResearchController::class);
    Route::resource('research.completed', \App\Http\Controllers\Research\CompletedController::class);
    Route::resource('research.publication', \App\Http\Controllers\Research\PublicationController::class);
    Route::resource('research.presentation', \App\Http\Controllers\Research\PresentationController::class);
    Route::resource('research.citation', \App\Http\Controllers\Research\CitationController::class);
    Route::resource('research.utilization', \App\Http\Controllers\Research\UtilizationController::class);
    Route::resource('research.copyrighted', \App\Http\Controllers\Research\CopyrightedController::class);

    
    //invention
    Route::resource('inventions', \App\Http\Controllers\Inventions\InventionController::class);
    
    //extensions
    Route::resource('extensions', \App\Http\Controllers\Extensions\ExtensionController::class);
    
    //academics
    Route::resource('academics', \App\Http\Controllers\Academics\AcademicController::class);

    // Reports
    Route::get('/reports/tables/data/{id}', [\App\Http\Controllers\Reports\ReportController::class, 'getColumnDataPerReportCategory']);
    Route::get('/reports/tables/data/{id}/{code}', [\App\Http\Controllers\Reports\ReportController::class, 'getTableDataPerColumnCategory']);
    Route::get('/reports/tables/data/documents/{id}/{code}', [\App\Http\Controllers\Reports\ReportController::class, 'getDocuments']);

    //faculty Reports
    Route::resource('/reports/faculty', \App\Http\Controllers\Reports\FacultyController::class);

    //chairperson Reports
    Route::resource('/reports/chairperson', \App\Http\Controllers\Reports\ChairpersonController::class);
    
    // admin routes
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function(){

        // forms
        Route::post('/forms/save-arrange', [\App\Http\Controllers\FormBuilder\FormController::class, 'arrange'])->name('forms.arrange');
        Route::resource('forms', \App\Http\Controllers\FormBuilder\FormController::class);
        // form's fields
        Route::get('/forms/fields/info/{id}',[\App\Http\Controllers\FormBuilder\FieldController::class, 'getInfo']);
        Route::post('/forms/fields/save-arrange/{id}', [\App\Http\Controllers\FormBuilder\FieldController::class, 'arrange'])->name('fields.arrange');
        Route::get('/forms/fields/preview/{id}', [\App\Http\Controllers\FormBuilder\FieldController::class, 'preview'])->name('fields.preview');
        Route::resource('forms.fields', \App\Http\Controllers\FormBuilder\FieldController::class);

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