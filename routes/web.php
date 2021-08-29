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
Route::get('document-view/{filename}', [\App\Http\Controllers\StorageFileController::class, 'viewFile'])->name('document.view');

Route::group(['middleware' => 'auth'], function() {
    Route::get('announcement/{id}', [\App\Http\Controllers\AnnouncementController::class, 'showMessage']);
    Route::group(['middleware' => 'role:hap', 'prefix' => 'hap', 'as' => 'hap.'], function(){
        // Route::resource('submissions', \App\Http\Controllers\Hap\SubmissionController::class);
    });
    Route::group(['middleware' => 'role:professor', 'prefix' => 'professor', 'as' => 'professor.'], function(){
        Route::get('/submissions', [\App\Http\Controllers\Professors\SubmissionController::class, 'index'])->name('submissions.index');
        Route::post('/submissions/select', [\App\Http\Controllers\Professors\SubmissionController::class, 'formselect'])->name('submissions.select');
        Route::post('ongoingadvanced/deletefileonedit/{ongoingadvanced}', [\App\Http\Controllers\Submissions\OngoingAdvancedController::class, 'removeFileInEdit'])->name('ongoingadvanced.file.delete');
        Route::post('facultyaward/deletefileonedit/{facultyaward}', [\App\Http\Controllers\Submissions\FacultyAwardController::class, 'removeFileInEdit'])->name('facultyaward.file.delete');
        Route::post('officership/deletefileonedit/{officership}', [\App\Http\Controllers\Submissions\OfficershipController::class, 'removeFileInEdit'])->name('officership.file.delete');
        Route::post('attendanceconference/deletefileonedit/{attendanceconference}', [\App\Http\Controllers\Submissions\AttendanceConferenceController::class, 'removeFileInEdit'])->name('attendanceconference.file.delete');
        Route::post('attendancetraining/deletefileonedit/{attendancetraining}', [\App\Http\Controllers\Submissions\AttendanceTrainingController::class, 'removeFileInEdit'])->name('attendancetraining.file.delete');
        Route::post('research/deletefileonedit/{research}', [\App\Http\Controllers\Submissions\ResearchController::class, 'removeFileInEdit'])->name('research.file.delete');
        Route::post('researchpublication/deletefileonedit/{researchpublication}', [\App\Http\Controllers\Submissions\ResearchPublicationController::class, 'removeFileInEdit'])->name('researchpublication.file.delete');
        Route::post('researchpresentation/deletefileonedit/{researchpresentation}', [\App\Http\Controllers\Submissions\ResearchPresentationController::class, 'removeFileInEdit'])->name('researchpresentation.file.delete');
        Route::post('researchcitation/deletefileonedit/{researchcitation}', [\App\Http\Controllers\Submissions\ResearchCitationController::class, 'removeFileInEdit'])->name('researchcitation.file.delete');
        Route::post('researchutilization/deletefileonedit/{researchutilization}', [\App\Http\Controllers\Submissions\ResearchUtilizationController::class, 'removeFileInEdit'])->name('researchutilization.file.delete');
        Route::post('researchcopyright/deletefileonedit/{researchcopyright}', [\App\Http\Controllers\Submissions\ResearchCopyrightController::class, 'removeFileInEdit'])->name('researchcopyright.file.delete');
        Route::post('invention/deletefileonedit/{invention}', [\App\Http\Controllers\Submissions\InventionController::class, 'removeFileInEdit'])->name('invention.file.delete');
        Route::post('expertconsultant/deletefileonedit/{expertconsultant}', [\App\Http\Controllers\Submissions\ExpertConsultantController::class, 'removeFileInEdit'])->name('expertconsultant.file.delete');
        Route::post('expertconference/deletefileonedit/{expertconference}', [\App\Http\Controllers\Submissions\ExpertConferenceController::class, 'removeFileInEdit'])->name('expertconference.file.delete');
        Route::post('expertjournal/deletefileonedit/{expertjournal}', [\App\Http\Controllers\Submissions\ExpertJournalController::class, 'removeFileInEdit'])->name('expertjournal.file.delete');
        Route::resource('ongoingadvanced', \App\Http\Controllers\Submissions\OngoingAdvancedController::class)->names([
            'index' => 'submissions.ongoingadvanced',
            'create' => 'submissions.ongoingadvanced.create',
            'store' => 'submissions.ongoingadvanced.store',
            'show' => 'submissions.ongoingadvanced.show',
            'edit' => 'submissions.ongoingadvanced.edit',
            'update' => 'submissions.ongoingadvanced.update',
            'destroy' => 'submissions.ongoingadvanced.destroy'
        ]);
        Route::resource('facultyaward', \App\Http\Controllers\Submissions\FacultyAwardController::class)->names([
            'index' => 'submissions.facultyaward',
            'create' => 'submissions.facultyaward.create',
            'store' => 'submissions.facultyaward.store',
            'show' => 'submissions.facultyaward.show',
            'edit' => 'submissions.facultyaward.edit',
            'update' => 'submissions.facultyaward.update',
            'destroy' => 'submissions.facultyaward.destroy'
        ]);
        Route::resource('officership', \App\Http\Controllers\Submissions\OfficershipController::class)->names([
            'index' => 'submissions.officership',
            'create' => 'submissions.officership.create',
            'store' => 'submissions.officership.store',
            'show' => 'submissions.officership.show',
            'edit' => 'submissions.officership.edit',
            'update' => 'submissions.officership.update',
            'destroy' => 'submissions.officership.destroy'
        ]);
        Route::resource('attendanceconference', \App\Http\Controllers\Submissions\AttendanceConferenceController::class)->names([
            'index' => 'submissions.attendanceconference',
            'create' => 'submissions.attendanceconference.create',
            'store' => 'submissions.attendanceconference.store',
            'show' => 'submissions.attendanceconference.show',
            'edit' => 'submissions.attendanceconference.edit',
            'update' => 'submissions.attendanceconference.update',
            'destroy' => 'submissions.attendanceconference.destroy'
        ]);
        Route::resource('attendancetraining', \App\Http\Controllers\Submissions\AttendanceTrainingController::class)->names([
            'index' => 'submissions.attendancetraining',
            'create' => 'submissions.attendancetraining.create',
            'store' => 'submissions.attendancetraining.store',
            'show' => 'submissions.attendancetraining.show',
            'edit' => 'submissions.attendancetraining.edit',
            'update' => 'submissions.attendancetraining.update',
            'destroy' => 'submissions.attendancetraining.destroy'
        ]);
        Route::resource('research', \App\Http\Controllers\Submissions\ResearchController::class)->names([
            'index' => 'submissions.research',
            'create' => 'submissions.research.create',
            'store' => 'submissions.research.store',
            'show' => 'submissions.research.show',
            'edit' => 'submissions.research.edit',
            'update' => 'submissions.research.update',
            'destroy' => 'submissions.research.destroy'
        ]);
        Route::resource('researchpublication', \App\Http\Controllers\Submissions\ResearchPublicationController::class)->names([
            'index' => 'submissions.researchpublication',
            'create' => 'submissions.researchpublication.create',
            'store' => 'submissions.researchpublication.store',
            'show' => 'submissions.researchpublication.show',
            'edit' => 'submissions.researchpublication.edit',
            'update' => 'submissions.researchpublication.update',
            'destroy' => 'submissions.researchpublication.destroy'
        ]);
        Route::resource('researchpresentation', \App\Http\Controllers\Submissions\ResearchPresentationController::class)->names([
            'index' => 'submissions.researchpresentation',
            'create' => 'submissions.researchpresentation.create',
            'store' => 'submissions.researchpresentation.store',
            'show' => 'submissions.researchpresentation.show',
            'edit' => 'submissions.researchpresentation.edit',
            'update' => 'submissions.researchpresentation.update',
            'destroy' => 'submissions.researchpresentation.destroy'
        ]);
        Route::resource('researchcitation', \App\Http\Controllers\Submissions\ResearchCitationController::class)->names([
            'index' => 'submissions.researchcitation',
            'create' => 'submissions.researchcitation.create',
            'store' => 'submissions.researchcitation.store',
            'show' => 'submissions.researchcitation.show',
            'edit' => 'submissions.researchcitation.edit',
            'update' => 'submissions.researchcitation.update',
            'destroy' => 'submissions.researchcitation.destroy'
        ]);
        Route::resource('researchutilization', \App\Http\Controllers\Submissions\ResearchUtilizationController::class)->names([
            'index' => 'submissions.researchutilization',
            'create' => 'submissions.researchutilization.create',
            'store' => 'submissions.researchutilization.store',
            'show' => 'submissions.researchutilization.show',
            'edit' => 'submissions.researchutilization.edit',
            'update' => 'submissions.researchutilization.update',
            'destroy' => 'submissions.researchutilization.destroy'
        ]);
        Route::resource('researchcopyright', \App\Http\Controllers\Submissions\ResearchCopyrightController::class)->names([
            'index' => 'submissions.researchcopyright',
            'create' => 'submissions.researchcopyright.create',
            'store' => 'submissions.researchcopyright.store',
            'show' => 'submissions.researchcopyright.show',
            'edit' => 'submissions.researchcopyright.edit',
            'update' => 'submissions.researchcopyright.update',
            'destroy' => 'submissions.researchcopyright.destroy'
        ]);
        Route::resource('invention', \App\Http\Controllers\Submissions\InventionController::class)->names([
            'index' => 'submissions.invention',
            'create' => 'submissions.invention.create',
            'store' => 'submissions.invention.store',
            'show' => 'submissions.invention.show',
            'edit' => 'submissions.invention.edit',
            'update' => 'submissions.invention.update',
            'destroy' => 'submissions.invention.destroy'
        ]);
        Route::resource('expertconsultant', \App\Http\Controllers\Submissions\ExpertConsultantController::class)->names([
            'index' => 'submissions.expertconsultant',
            'create' => 'submissions.expertconsultant.create',
            'store' => 'submissions.expertconsultant.store',
            'show' => 'submissions.expertconsultant.show',
            'edit' => 'submissions.expertconsultant.edit',
            'update' => 'submissions.expertconsultant.update',
            'destroy' => 'submissions.expertconsultant.destroy'
        ]);
        Route::resource('expertconference', \App\Http\Controllers\Submissions\ExpertConferenceController::class)->names([
            'index' => 'submissions.expertconference',
            'create' => 'submissions.expertconference.create',
            'store' => 'submissions.expertconference.store',
            'show' => 'submissions.expertconference.show',
            'edit' => 'submissions.expertconference.edit',
            'update' => 'submissions.expertconference.update',
            'destroy' => 'submissions.expertconference.destroy'
        ]);
        Route::resource('expertjournal', \App\Http\Controllers\Submissions\ExpertJournalController::class)->names([
            'index' => 'submissions.expertjournal',
            'create' => 'submissions.expertjournal.create',
            'store' => 'submissions.expertjournal.store',
            'show' => 'submissions.expertjournal.show',
            'edit' => 'submissions.expertjournal.edit',
            'update' => 'submissions.expertjournal.update',
            'destroy' => 'submissions.expertjournal.destroy'
        ]);
        
        // Route::get('search', [\App\Http\Controllers\Professors\EventController::class, 'search'])->name('events.search');
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