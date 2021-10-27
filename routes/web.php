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

// registration
Route::get('/registration/{token}', [\App\Http\Controllers\Administrators\UserController::class, 'registration_view'])->name('registration')->middleware('guest');
Route::post('/registration/accept', [\App\Http\Controllers\Registration\RegisterController::class, 'create'])->name('accept')->middleware('guest');

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

    // submissions
    Route::get('/submissions', [\App\Http\Controllers\Submissions\SubmissionController::class, 'index'])->name('submissions.index');
    Route::post('/submissions/select', [\App\Http\Controllers\Submissions\SubmissionController::class, 'select'])->name('submissions.select');
    Route::get('/submissions/create/{slug}', [\App\Http\Controllers\Submissions\SubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/submssions/store/{id}', [\App\Http\Controllers\Submissions\SubmissionController::class, 'store'])->name('submissions.store');
    Route::get('/submssions/edit/{id}', [\App\Http\Controllers\Submissions\SubmissionController::class, 'edit'])->name('submissions.edit');
    Route::post('/submssions/update/{id}', [\App\Http\Controllers\Submissions\SubmissionController::class, 'update'])->name('submissions.update');
    Route::post('/submssions/destroy/{id}', [\App\Http\Controllers\Submissions\SubmissionController::class, 'destroy'])->name('submissions.destroy');

    // reports
    Route::get('/reports', [\App\Http\Controllers\Reports\ReportController::class, 'index'])->name('reports.index');

    // get dropdown options
    Route::get('/dropdowns/options/{id}', [\App\Http\Controllers\FormBuilder\DropdownController::class, 'options'])->name('dropdown.options');


    // maintenances
    Route::get('/maintenances', [\App\Http\Controllers\Maintenance\MaintenanceController::class, 'index'])->name('maintenances.index');

    // dropdowns
    Route::get('/dropdowns/options/{id}', [\App\Http\Controllers\Maintenance\DropdownController::class, 'options'])->name('dropdowns.options');
    Route::post('/dropdowns/options/add/{id}', [\App\Http\Controllers\Maintenance\DropdownController::class, 'addOptions'])->name('dropdowns.options.add');
    Route::post('/dropdowns/options/arrange', [\App\Http\Controllers\Maintenance\DropdownController::class, 'arrangeOptions']);
    Route::get('/dropdowns/options/activate/{id}', [\App\Http\Controllers\Maintenance\DropdownController::class, 'activate']);
    Route::get('/dropdowns/options/inactivate/{id}', [\App\Http\Controllers\Maintenance\DropdownController::class, 'inactivate']);
    Route::resource('dropdowns', \App\Http\Controllers\Maintenance\DropdownController::class);

    //researchForms
    Route::get('/research-forms/activate/{id}', [\App\Http\Controllers\Maintenance\ResearchFormController::class, 'activate']);
    Route::get('/research-forms/inactivate/{id}', [\App\Http\Controllers\Maintenance\ResearchFormController::class, 'inactivate']);
    Route::resource('research-forms', \App\Http\Controllers\Maintenance\ResearchFormController::class);    

    //researchFields
    Route::get('/research-fields/activate/{id}', [\App\Http\Controllers\Maintenance\ResearchFieldController::class, 'activate']);
    Route::get('/research-fields/inactivate/{id}', [\App\Http\Controllers\Maintenance\ResearchFieldController::class, 'inactivate']);
    Route::post('/research-fields/arrange', [\App\Http\Controllers\Maintenance\ResearchFieldController::class, 'arrange']);
    Route::resource('research-forms.research-fields', \App\Http\Controllers\Maintenance\ResearchFieldController::class);

    //researchSubmissions
    Route::get('/research/complete/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'complete'])->name('research.complete');
    Route::get('/research/publication/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'publication'])->name('research.publication');
    Route::get('/research/presentation/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'presentation'])->name('research.presentation');
    Route::get('/research/copyright/{research_code}', [\App\Http\Controllers\Research\ResearchController::class, 'copyright'])->name('research.copyright');
    Route::get('/research/remove-document/{filename}', [\App\Http\Controllers\Research\ResearchController::class, 'removeDoc'])->name('research.removedoc');
    

    Route::resource('research', \App\Http\Controllers\Research\ResearchController::class);
    Route::resource('research.completed', \App\Http\Controllers\Research\CompletedController::class);
    Route::resource('research.publication', \App\Http\Controllers\Research\PublicationController::class);
    Route::resource('research.presentation', \App\Http\Controllers\Research\PresentationController::class);
    Route::resource('research.citation', \App\Http\Controllers\Research\CitationController::class);
    Route::resource('research.utilization', \App\Http\Controllers\Research\UtilizationController::class);
    Route::resource('research.copyrighted', \App\Http\Controllers\Research\CopyrightedController::class);

    //users
    Route::get('/users/invite', [\App\Http\Controllers\Administrators\UserController::class, 'invite'])->name('users.invite');
    Route::post('/users/invite/send', [\App\Http\Controllers\Administrators\UserController::class, 'send'])->name('users.sendinvite');
    Route::resource('users', \App\Http\Controllers\UserController::class);

    Route::get('/departments/options/{id}', [\App\Http\Controllers\Maintenances\DepartmentController::class, 'options']);
     

    // HAP routes
    Route::group(['middleware' => 'role:hap', 'prefix' => 'hap', 'as' => 'hap.'], function(){
    });

    // faculty/professor routes
    Route::group(['middleware' => 'role:professor', 'prefix' => 'professor', 'as' => 'professor.'], function(){
    });
    
    // admin routes
    Route::group(['middleware' => 'role:administrator', 'prefix' => 'admin', 'as' => 'admin.'], function(){

        // forms
        Route::post('/forms/save-arrange', [\App\Http\Controllers\FormBuilder\FormController::class, 'arrange'])->name('forms.arrange');
        Route::resource('forms', \App\Http\Controllers\FormBuilder\FormController::class);
        
        // form's fields
        Route::get('/forms/fields/info/{id}',[\App\Http\Controllers\FormBuilder\FieldController::class, 'getInfo']);
        Route::post('/forms/fields/save-arrange/{id}', [\App\Http\Controllers\FormBuilder\FieldController::class, 'arrange'])->name('fields.arrange');
        Route::get('/forms/fields/preview/{id}', [\App\Http\Controllers\FormBuilder\FieldController::class, 'preview'])->name('fields.preview');
        Route::resource('forms.fields', \App\Http\Controllers\FormBuilder\FieldController::class);

        //users
        Route::get('/users/invite', [\App\Http\Controllers\Administrators\UserController::class, 'invite'])->name('users.invite');
        Route::post('/users/invite/send', [\App\Http\Controllers\Administrators\UserController::class, 'send'])->name('users.sendinvite');
        Route::resource('users', \App\Http\Controllers\Administrators\UserController::class);

        //maintenances
        Route::resource('/maintenances/colleges', \App\Http\Controllers\Maintenances\CollegeController::class);
        //Route::get('/maintenances/colleges/{college}/delete', [\App\Http\Controllers\Maintenances\CollegeController::class, 'delete']);
        
        Route::resource('/maintenances/departments', \App\Http\Controllers\Maintenances\DepartmentController::class);
        //Route::get('/maintenances/departments/{department}/delete', [\App\Http\Controllers\Maintenances\DepartmentController::class, 'delete']);
    
        
    });
});