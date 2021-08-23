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

Route::get('/', function () {
    return view('auth.login');
})->name('home')->middleware('guest');


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    $announcements = \App\Models\Announcement::where('status', 1)->latest()->take(5)->get();
    return view('dashboard', compact('announcements'));
})->name('dashboard');

Route::post('upload', [\App\Http\Controllers\UploadController::class, 'store']);
Route::delete('remove', [\App\Http\Controllers\UploadController::class, 'destroy']);
// Route::delete('removeimage', [\App\Http\Controllers\UploadController::class, 'destroyImage']);

Route::get('image/{filename}', [\App\Http\Controllers\StorageFileController::class, 'getDocumentFile'])->name('document.display');
Route::get('download/{filename}', [\App\Http\Controllers\StorageFileController::class, 'downloadFile'])->name('document.download');

Route::group(['middleware' => 'auth'], function() {
    Route::get('announcement/{id}', [\App\Http\Controllers\AnnouncementController::class, 'showMessage']);
    Route::group(['middleware' => 'role:hap', 'prefix' => 'hap', 'as' => 'hap.'], function(){
        // Route::resource('submissions', \App\Http\Controllers\Hap\SubmissionController::class);
    });
    Route::group(['middleware' => 'role:professor', 'prefix' => 'professor', 'as' => 'professor.'], function(){
        Route::get('/submissions', [\App\Http\Controllers\Professors\SubmissionController::class, 'index'])->name('submissions.index');
        Route::post('/submissions/select', [\App\Http\Controllers\Professors\SubmissionController::class, 'formselect'])->name('submissions.select');
        Route::resource('ongoingadvanced', \App\Http\Controllers\Submissions\OngoingAdvancedController::class)->names([
            'index' => 'submissions.ongoingadvanced',
            'create' => 'submissions.ongoingadvanced.create',
            'store' => 'submissions.ongoingadvanced.store',
            'show' => 'submissions.ongoingadvanced.show',
            'edit' => 'submissions.ongoingadvanced.edit',
            'update' => 'submissions.ongoingadvanced.update',
            'destroy' => 'submissions.ongoingadvanced.destroy'
        ]);
        // Route::get('search', [\App\Http\Controllers\Professors\EventController::class, 'search'])->name('events.search');
        // Route::post('submissions/delete/{event}', [\App\Http\Controllers\Professors\SubmissionController::class, 'deleteFile'])->name('file.delete');
        // Route::resource('events', \App\Http\Controllers\Professors\EventController::class);
        // Route::resource('events.submissions', \App\Http\Controllers\Professors\SubmissionController::class);
    });
    Route::group(['middleware' => 'role:administrator', 'prefix' => 'admin', 'as' => 'admin.'], function(){
        Route::view('/maintenances', 'admin.maintenances.index')->name('maintenances.index');
        Route::get('/users/invite', [\App\Http\Controllers\Administrators\UserController::class, 'invite'])->name('users.invite');
        Route::post('/users/invite/send', [\App\Http\Controllers\Administrators\UserController::class, 'send'])->name('users.sendinvite');
        Route::resource('departments', \App\Http\Controllers\Maintenances\DepartmentController::class)->names([
            'index' => 'maintenances.department',
            'create' => 'maintenances.department.create',
            'store' => 'maintenances.department.store',
            'edit' => 'maintenances.department.edit',
            'update' => 'maintenances.department.update',
            'destroy' => 'maintenances.department.destroy'
        ]);
        Route::resource('accreditation-levels', \App\Http\Controllers\Maintenances\AccreLevelController::class)->names([
            'index' => 'maintenances.accrelevel',
            'create' => 'maintenances.accrelevel.create',
            'store' => 'maintenances.accrelevel.store',
            'edit' => 'maintenances.accrelevel.edit',
            'update' => 'maintenances.accrelevel.update',
            'destroy' => 'maintenances.accrelevel.destroy'
        ]);
        Route::resource('support-types', \App\Http\Controllers\Maintenances\SupportTypeController::class)->names([
            'index' => 'maintenances.support',
            'create' => 'maintenances.support.create',
            'store' => 'maintenances.support.store',
            'edit' => 'maintenances.support.edit',
            'update' => 'maintenances.support.update',
            'destroy' => 'maintenances.support.destroy'
        ]);
        Route::resource('study-status', \App\Http\Controllers\Maintenances\StudyStatusController::class)->names([
            'index' => 'maintenances.studystatus',
            'create' => 'maintenances.studystatus.create',
            'store' => 'maintenances.studystatus.store',
            'edit' => 'maintenances.studystatus.edit',
            'update' => 'maintenances.studystatus.update',
            'destroy' => 'maintenances.studystatus.destroy'
        ]);
        Route::resource('faculty-awards', \App\Http\Controllers\Maintenances\FacultyAwardController::class)->names([
            'index' => 'maintenances.facultyaward',
            'create' => 'maintenances.facultyaward.create',
            'store' => 'maintenances.facultyaward.store',
            'edit' => 'maintenances.facultyaward.edit',
            'update' => 'maintenances.facultyaward.update',
            'destroy' => 'maintenances.facultyaward.destroy'
        ]);
        Route::resource('levels', \App\Http\Controllers\Maintenances\LevelController::class)->names([
            'index' => 'maintenances.level',
            'create' => 'maintenances.level.create',
            'store' => 'maintenances.level.store',
            'edit' => 'maintenances.level.edit',
            'update' => 'maintenances.level.update',
            'destroy' => 'maintenances.level.destroy'
        ]);
        Route::resource('faculty-officerships', \App\Http\Controllers\Maintenances\FacultyOfficerController::class)->names([
            'index' => 'maintenances.facultyofficer',
            'create' => 'maintenances.facultyofficer.create',
            'store' => 'maintenances.facultyofficer.store',
            'edit' => 'maintenances.facultyofficer.edit',
            'update' => 'maintenances.facultyofficer.update',
            'destroy' => 'maintenances.facultyofficer.destroy'
        ]);
        Route::resource('development-classifications', \App\Http\Controllers\Maintenances\DevelopClassController::class)->names([
            'index' => 'maintenances.developclass',
            'create' => 'maintenances.developclass.create',
            'store' => 'maintenances.developclass.store',
            'edit' => 'maintenances.developclass.edit',
            'update' => 'maintenances.developclass.update',
            'destroy' => 'maintenances.developclass.destroy'
        ]);
        Route::resource('development-natures', \App\Http\Controllers\Maintenances\DevelopNatureController::class)->names([
            'index' => 'maintenances.developnature',
            'create' => 'maintenances.developnature.create',
            'store' => 'maintenances.developnature.store',
            'edit' => 'maintenances.developnature.edit',
            'update' => 'maintenances.developnature.update',
            'destroy' => 'maintenances.developnature.destroy'
        ]);
        Route::resource('funding-types', \App\Http\Controllers\Maintenances\FundingTypeController::class)->names([
            'index' => 'maintenances.fundingtype',
            'create' => 'maintenances.fundingtype.create',
            'store' => 'maintenances.fundingtype.store',
            'edit' => 'maintenances.fundingtype.edit',
            'update' => 'maintenances.fundingtype.update',
            'destroy' => 'maintenances.fundingtype.destroy'
        ]);
        Route::resource('training-classifications', \App\Http\Controllers\Maintenances\TrainingClassController::class)->names([
            'index' => 'maintenances.trainclass',
            'create' => 'maintenances.trainclass.create',
            'store' => 'maintenances.trainclass.store',
            'edit' => 'maintenances.trainclass.edit',
            'update' => 'maintenances.trainclass.update',
            'destroy' => 'maintenances.trainclass.destroy'
        ]);
        Route::resource('research-classifications', \App\Http\Controllers\Maintenances\ResearchClassController::class)->names([
            'index' => 'maintenances.researchclass',
            'create' => 'maintenances.researchclass.create',
            'store' => 'maintenances.researchclass.store',
            'edit' => 'maintenances.researchclass.edit',
            'update' => 'maintenances.researchclass.update',
            'destroy' => 'maintenances.researchclass.destroy'
        ]);
        Route::resource('research-categories', \App\Http\Controllers\Maintenances\ResearchCategoryController::class)->names([
            'index' => 'maintenances.researchcategory',
            'create' => 'maintenances.researchcategory.create',
            'store' => 'maintenances.researchcategory.store',
            'edit' => 'maintenances.researchcategory.edit',
            'update' => 'maintenances.researchcategory.update',
            'destroy' => 'maintenances.researchcategory.destroy'
        ]);
        Route::resource('research-agendas', \App\Http\Controllers\Maintenances\ResearchAgendaController::class)->names([
            'index' => 'maintenances.researchagenda',
            'create' => 'maintenances.researchagenda.create',
            'store' => 'maintenances.researchagenda.store',
            'edit' => 'maintenances.researchagenda.edit',
            'update' => 'maintenances.researchagenda.update',
            'destroy' => 'maintenances.researchagenda.destroy'
        ]);
        Route::resource('research-involvements', \App\Http\Controllers\Maintenances\ResearchInvolveController::class)->names([
            'index' => 'maintenances.researchinvolve',
            'create' => 'maintenances.researchinvolve.create',
            'store' => 'maintenances.researchinvolve.store',
            'edit' => 'maintenances.researchinvolve.edit',
            'update' => 'maintenances.researchinvolve.update',
            'destroy' => 'maintenances.researchinvolve.destroy'
        ]);
        Route::resource('research-types', \App\Http\Controllers\Maintenances\ResearchTypeController::class)->names([
            'index' => 'maintenances.researchtype',
            'create' => 'maintenances.researchtype.create',
            'store' => 'maintenances.researchtype.store',
            'edit' => 'maintenances.researchtype.edit',
            'update' => 'maintenances.researchtype.update',
            'destroy' => 'maintenances.researchtype.destroy'
        ]);
        Route::resource('research-levels', \App\Http\Controllers\Maintenances\ResearchLevelController::class)->names([
            'index' => 'maintenances.researchlevel',
            'create' => 'maintenances.researchlevel.create',
            'store' => 'maintenances.researchlevel.store',
            'edit' => 'maintenances.researchlevel.edit',
            'update' => 'maintenances.researchlevel.update',
            'destroy' => 'maintenances.researchlevel.destroy'
        ]);
        Route::resource('index-platforms', \App\Http\Controllers\Maintenances\IndexPlatformController::class)->names([
            'index' => 'maintenances.indexplatform',
            'create' => 'maintenances.indexplatform.create',
            'store' => 'maintenances.indexplatform.store',
            'edit' => 'maintenances.indexplatform.edit',
            'update' => 'maintenances.indexplatform.update',
            'destroy' => 'maintenances.indexplatform.destroy'
        ]);
        Route::resource('invention-classifications', \App\Http\Controllers\Maintenances\InventionClassController::class)->names([
            'index' => 'maintenances.inventionclass',
            'create' => 'maintenances.inventionclass.create',
            'store' => 'maintenances.inventionclass.store',
            'edit' => 'maintenances.inventionclass.edit',
            'update' => 'maintenances.inventionclass.update',
            'destroy' => 'maintenances.inventionclass.destroy'
        ]);
        Route::resource('invention-status', \App\Http\Controllers\Maintenances\InventionStatusController::class)->names([
            'index' => 'maintenances.inventionstatus',
            'create' => 'maintenances.inventionstatus.create',
            'store' => 'maintenances.inventionstatus.store',
            'edit' => 'maintenances.inventionstatus.edit',
            'update' => 'maintenances.inventionstatus.update',
            'destroy' => 'maintenances.inventionstatus.destroy'
        ]);
        Route::resource('service-consultant', \App\Http\Controllers\Maintenances\ServiceConsultantController::class)->names([
            'index' => 'maintenances.serviceconsultant',
            'create' => 'maintenances.serviceconsultant.create',
            'store' => 'maintenances.serviceconsultant.store',
            'edit' => 'maintenances.serviceconsultant.edit',
            'update' => 'maintenances.serviceconsultant.update',
            'destroy' => 'maintenances.serviceconsultant.destroy'
        ]);
        Route::resource('service-conference', \App\Http\Controllers\Maintenances\ServiceConferenceController::class)->names([
            'index' => 'maintenances.serviceconference',
            'create' => 'maintenances.serviceconference.create',
            'store' => 'maintenances.serviceconference.store',
            'edit' => 'maintenances.serviceconference.edit',
            'update' => 'maintenances.serviceconference.update',
            'destroy' => 'maintenances.serviceconference.destroy'
        ]);
        Route::resource('service-journal', \App\Http\Controllers\Maintenances\ServiceJournalController::class)->names([
            'index' => 'maintenances.servicejournal',
            'create' => 'maintenances.servicejournal.create',
            'store' => 'maintenances.servicejournal.store',
            'edit' => 'maintenances.servicejournal.edit',
            'update' => 'maintenances.servicejournal.update',
            'destroy' => 'maintenances.servicejournal.destroy'
        ]);
        Route::resource('service-nature', \App\Http\Controllers\Maintenances\ServiceNatureController::class)->names([
            'index' => 'maintenances.servicenature',
            'create' => 'maintenances.servicenature.create',
            'store' => 'maintenances.servicenature.store',
            'edit' => 'maintenances.servicenature.edit',
            'update' => 'maintenances.servicenature.update',
            'destroy' => 'maintenances.servicenature.destroy'
        ]);
        Route::resource('extension-nature', \App\Http\Controllers\Maintenances\ExtensionNatureController::class)->names([
            'index' => 'maintenances.extensionnature',
            'create' => 'maintenances.extensionnature.create',
            'store' => 'maintenances.extensionnature.store',
            'edit' => 'maintenances.extensionnature.edit',
            'update' => 'maintenances.extensionnature.update',
            'destroy' => 'maintenances.extensionnature.destroy'
        ]);
        Route::resource('extension-classifications', \App\Http\Controllers\Maintenances\ExtensionClassController::class)->names([
            'index' => 'maintenances.extensionclass',
            'create' => 'maintenances.extensionclass.create',
            'store' => 'maintenances.extensionclass.store',
            'edit' => 'maintenances.extensionclass.edit',
            'update' => 'maintenances.extensionclass.update',
            'destroy' => 'maintenances.extensionclass.destroy'
        ]);
        Route::resource('extension-status', \App\Http\Controllers\Maintenances\ExtensionStatusController::class)->names([
            'index' => 'maintenances.extensionstatus',
            'create' => 'maintenances.extensionstatus.create',
            'store' => 'maintenances.extensionstatus.store',
            'edit' => 'maintenances.extensionstatus.edit',
            'update' => 'maintenances.extensionstatus.update',
            'destroy' => 'maintenances.extensionstatus.destroy'
        ]);
        Route::resource('partner-types', \App\Http\Controllers\Maintenances\PartnerTypeController::class)->names([
            'index' => 'maintenances.partnertype',
            'create' => 'maintenances.partnertype.create',
            'store' => 'maintenances.partnertype.store',
            'edit' => 'maintenances.partnertype.edit',
            'update' => 'maintenances.partnertype.update',
            'destroy' => 'maintenances.partnertype.destroy'
        ]);
        Route::resource('partner-types', \App\Http\Controllers\Maintenances\PartnerTypeController::class)->names([
            'index' => 'maintenances.partnertype',
            'create' => 'maintenances.partnertype.create',
            'store' => 'maintenances.partnertype.store',
            'edit' => 'maintenances.partnertype.edit',
            'update' => 'maintenances.partnertype.update',
            'destroy' => 'maintenances.partnertype.destroy'
        ]);
        Route::resource('collaboration-nature', \App\Http\Controllers\Maintenances\CollabNatureController::class)->names([
            'index' => 'maintenances.collabnature',
            'create' => 'maintenances.collabnature.create',
            'store' => 'maintenances.collabnature.store',
            'edit' => 'maintenances.collabnature.edit',
            'update' => 'maintenances.collabnature.update',
            'destroy' => 'maintenances.collabnature.destroy'
        ]);
        Route::resource('collaboration-deliverable', \App\Http\Controllers\Maintenances\CollabDeliverController::class)->names([
            'index' => 'maintenances.collabdeliver',
            'create' => 'maintenances.collabdeliver.create',
            'store' => 'maintenances.collabdeliver.store',
            'edit' => 'maintenances.collabdeliver.edit',
            'update' => 'maintenances.collabdeliver.update',
            'destroy' => 'maintenances.collabdeliver.destroy'
        ]);
        Route::resource('target-beneficiaries', \App\Http\Controllers\Maintenances\TargetBeneficiaryController::class)->names([
            'index' => 'maintenances.targetbeneficiary',
            'create' => 'maintenances.targetbeneficiary.create',
            'store' => 'maintenances.targetbeneficiary.store',
            'edit' => 'maintenances.targetbeneficiary.edit',
            'update' => 'maintenances.targetbeneficiary.update',
            'destroy' => 'maintenances.targetbeneficiary.destroy'
        ]);
        Route::resource('engagement-nature', \App\Http\Controllers\Maintenances\EngageNatureController::class)->names([
            'index' => 'maintenances.engagenature',
            'create' => 'maintenances.engagenature.create',
            'store' => 'maintenances.engagenature.store',
            'edit' => 'maintenances.engagenature.edit',
            'update' => 'maintenances.engagenature.update',
            'destroy' => 'maintenances.engagenature.destroy'
        ]);
        Route::resource('faculty-involvement', \App\Http\Controllers\Maintenances\FacultyInvolveController::class)->names([
            'index' => 'maintenances.facultyinvolve',
            'create' => 'maintenances.facultyinvolve.create',
            'store' => 'maintenances.facultyinvolve.store',
            'edit' => 'maintenances.facultyinvolve.edit',
            'update' => 'maintenances.facultyinvolve.update',
            'destroy' => 'maintenances.facultyinvolve.destroy'
        ]);
        Route::resource('users', \App\Http\Controllers\Administrators\UserController::class);
        // Route::get('/announcements/hide/{announcement}', [\App\Http\Controllers\Administrators\AnnouncementController::class, 'hide'])->name('announcements.hide');
        // Route::get('/announcements/activate/{announcement}', [\App\Http\Controllers\Administrators\AnnouncementController::class, 'activate'])->name('announcements.activate');
        // Route::get('/events/accept/{event}', [\App\Http\Controllers\Administrators\EventController::class, 'accept'])->name('events.accept');
        // Route::get('/events/reject/{event}', [\App\Http\Controllers\Administrators\EventController::class, 'reject'])->name('events.reject');
        // Route::get('/events/close/{event}', [\App\Http\Controllers\Administrators\EventController::class, 'close'])->name('events.close');
        // Route::resource('announcements', \App\Http\Controllers\Administrators\AnnouncementController::class);
        // Route::resource('events', \App\Http\Controllers\Administrators\EventController::class);
        // Route::resource('event_types', \App\Http\Controllers\Administrators\EventTypeController::class);
    });
});


Route::get('/registration/{token}', [\App\Http\Controllers\Administrators\UserController::class, 'registration_view'])->name('registration')->middleware('guest');
Route::post('/registration/accept', [\App\Http\Controllers\Registration\RegisterController::class, 'create'])->name('accept')->middleware('guest');