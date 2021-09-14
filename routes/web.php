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

    Route::get('/submissions', [\App\Http\Controllers\Professors\SubmissionController::class, 'index'])->name('professor.submissions.index');
    Route::post('/submissions/select', [\App\Http\Controllers\Professors\SubmissionController::class, 'formselect'])->name('professor.submissions.select');
    Route::post('ongoingadvanced/deletefileonedit/{ongoingadvanced}', [\App\Http\Controllers\Submissions\OngoingAdvancedController::class, 'removeFileInEdit'])->name('professor.ongoingadvanced.file.delete');
    Route::post('facultyaward/deletefileonedit/{facultyaward}', [\App\Http\Controllers\Submissions\FacultyAwardController::class, 'removeFileInEdit'])->name('professor.facultyaward.file.delete');
    Route::post('officership/deletefileonedit/{officership}', [\App\Http\Controllers\Submissions\OfficershipController::class, 'removeFileInEdit'])->name('professor.officership.file.delete');
    Route::post('attendanceconference/deletefileonedit/{attendanceconference}', [\App\Http\Controllers\Submissions\AttendanceConferenceController::class, 'removeFileInEdit'])->name('professor.attendanceconference.file.delete');
    Route::post('attendancetraining/deletefileonedit/{attendancetraining}', [\App\Http\Controllers\Submissions\AttendanceTrainingController::class, 'removeFileInEdit'])->name('professor.attendancetraining.file.delete');
    Route::post('research/deletefileonedit/{research}', [\App\Http\Controllers\Submissions\ResearchController::class, 'removeFileInEdit'])->name('professor.research.file.delete');
    Route::post('researchpublication/deletefileonedit/{researchpublication}', [\App\Http\Controllers\Submissions\ResearchPublicationController::class, 'removeFileInEdit'])->name('professor.researchpublication.file.delete');
    Route::post('researchpresentation/deletefileonedit/{researchpresentation}', [\App\Http\Controllers\Submissions\ResearchPresentationController::class, 'removeFileInEdit'])->name('professor.researchpresentation.file.delete');
    Route::post('researchcitation/deletefileonedit/{researchcitation}', [\App\Http\Controllers\Submissions\ResearchCitationController::class, 'removeFileInEdit'])->name('professor.researchcitation.file.delete');
    Route::post('researchutilization/deletefileonedit/{researchutilization}', [\App\Http\Controllers\Submissions\ResearchUtilizationController::class, 'removeFileInEdit'])->name('professor.researchutilization.file.delete');
    Route::post('researchcopyright/deletefileonedit/{researchcopyright}', [\App\Http\Controllers\Submissions\ResearchCopyrightController::class, 'removeFileInEdit'])->name('professor.researchcopyright.file.delete');
    Route::post('invention/deletefileonedit/{invention}', [\App\Http\Controllers\Submissions\InventionController::class, 'removeFileInEdit'])->name('professor.invention.file.delete');
    Route::post('expertconsultant/deletefileonedit/{expertconsultant}', [\App\Http\Controllers\Submissions\ExpertConsultantController::class, 'removeFileInEdit'])->name('professor.expertconsultant.file.delete');
    Route::post('expertconference/deletefileonedit/{expertconference}', [\App\Http\Controllers\Submissions\ExpertConferenceController::class, 'removeFileInEdit'])->name('professor.expertconference.file.delete');
    Route::post('expertjournal/deletefileonedit/{expertjournal}', [\App\Http\Controllers\Submissions\ExpertJournalController::class, 'removeFileInEdit'])->name('professor.expertjournal.file.delete');
    Route::post('extensionprogram/deletefileonedit/{extensionprogram}', [\App\Http\Controllers\Submissions\ExtensionProgramController::class, 'removeFileInEdit'])->name('professor.extensionprogram.file.delete');
    Route::post('facultyintercountry/deletefileonedit/{facultyintercountry}', [\App\Http\Controllers\Submissions\FacultyInterCountryController::class, 'removeFileInEdit'])->name('professor.facultyintercountry.file.delete');
    Route::post('partnership/deletefileonedit/{partnership}', [\App\Http\Controllers\Submissions\PartnershipController::class, 'removeFileInEdit'])->name('professor.partnership.file.delete');
    Route::post('material/deletefileonedit/{material}', [\App\Http\Controllers\Submissions\MaterialController::class, 'removeFileInEdit'])->name('professor.material.file.delete');
    Route::post('syllabus/deletefileonedit/{syllabu}', [\App\Http\Controllers\Submissions\SyllabusController::class, 'removeFileInEdit'])->name('professor.syllabus.file.delete');
    Route::post('specialtask/deletefileonedit/{specialtask}', [\App\Http\Controllers\Submissions\SpecialTaskController::class, 'removeFileInEdit'])->name('professor.specialtask.file.delete');
    Route::post('specialtaskefficiency/deletefileonedit/{specialtaskefficiency}', [\App\Http\Controllers\Submissions\SpecialTaskEfficiencyController::class, 'removeFileInEdit'])->name('professor.specialtaskefficiency.file.delete');
    Route::post('specialtasktimeliness/deletefileonedit/{specialtasktimeliness}', [\App\Http\Controllers\Submissions\SpecialTaskTimelinessController::class, 'removeFileInEdit'])->name('professor.specialtasktimeliness.file.delete');
    Route::post('attendancefunction/deletefileonedit/{attendancefunction}', [\App\Http\Controllers\Submissions\AttendanceFunctionController::class, 'removeFileInEdit'])->name('professor.attendancefunction.file.delete');
    Route::post('viableproject/deletefileonedit/{viableproject}', [\App\Http\Controllers\Submissions\ViableProjectController::class, 'removeFileInEdit'])->name('professor.viableproject.file.delete');
    Route::post('branchaward/deletefileonedit/{branchaward}', [\App\Http\Controllers\Submissions\BranchAwardController::class, 'removeFileInEdit'])->name('professor.branchaward.file.delete');
    Route::resource('ongoingadvanced', \App\Http\Controllers\Submissions\OngoingAdvancedController::class)->names([
        'index' => 'professor.submissions.ongoingadvanced',
        'create' => 'professor.submissions.ongoingadvanced.create',
        'store' => 'professor.submissions.ongoingadvanced.store',
        'show' => 'professor.submissions.ongoingadvanced.show',
        'edit' => 'professor.submissions.ongoingadvanced.edit',
        'update' => 'professor.submissions.ongoingadvanced.update',
        'destroy' => 'professor.submissions.ongoingadvanced.destroy'
    ]);
    Route::resource('facultyaward', \App\Http\Controllers\Submissions\FacultyAwardController::class)->names([
        'index' => 'professor.submissions.facultyaward',
        'create' => 'professor.submissions.facultyaward.create',
        'store' => 'professor.submissions.facultyaward.store',
        'show' => 'professor.submissions.facultyaward.show',
        'edit' => 'professor.submissions.facultyaward.edit',
        'update' => 'professor.submissions.facultyaward.update',
        'destroy' => 'professor.submissions.facultyaward.destroy'
    ]);
    Route::resource('officership', \App\Http\Controllers\Submissions\OfficershipController::class)->names([
        'index' => 'professor.submissions.officership',
        'create' => 'professor.submissions.officership.create',
        'store' => 'professor.submissions.officership.store',
        'show' => 'professor.submissions.officership.show',
        'edit' => 'professor.submissions.officership.edit',
        'update' => 'professor.submissions.officership.update',
        'destroy' => 'professor.submissions.officership.destroy'
    ]);
    Route::resource('attendanceconference', \App\Http\Controllers\Submissions\AttendanceConferenceController::class)->names([
        'index' => 'professor.submissions.attendanceconference',
        'create' => 'professor.submissions.attendanceconference.create',
        'store' => 'professor.submissions.attendanceconference.store',
        'show' => 'professor.submissions.attendanceconference.show',
        'edit' => 'professor.submissions.attendanceconference.edit',
        'update' => 'professor.submissions.attendanceconference.update',
        'destroy' => 'professor.submissions.attendanceconference.destroy'
    ]);
    Route::resource('attendancetraining', \App\Http\Controllers\Submissions\AttendanceTrainingController::class)->names([
        'index' => 'professor.submissions.attendancetraining',
        'create' => 'professor.submissions.attendancetraining.create',
        'store' => 'professor.submissions.attendancetraining.store',
        'show' => 'professor.submissions.attendancetraining.show',
        'edit' => 'professor.submissions.attendancetraining.edit',
        'update' => 'professor.submissions.attendancetraining.update',
        'destroy' => 'professor.submissions.attendancetraining.destroy'
    ]);
    Route::resource('research', \App\Http\Controllers\Submissions\ResearchController::class)->names([
        'index' => 'professor.submissions.research',
        'create' => 'professor.submissions.research.create',
        'store' => 'professor.submissions.research.store',
        'show' => 'professor.submissions.research.show',
        'edit' => 'professor.submissions.research.edit',
        'update' => 'professor.submissions.research.update',
        'destroy' => 'professor.submissions.research.destroy'
    ]);
    Route::resource('researchpublication', \App\Http\Controllers\Submissions\ResearchPublicationController::class)->names([
        'index' => 'professor.submissions.researchpublication',
        'create' => 'professor.submissions.researchpublication.create',
        'store' => 'professor.submissions.researchpublication.store',
        'show' => 'professor.submissions.researchpublication.show',
        'edit' => 'professor.submissions.researchpublication.edit',
        'update' => 'professor.submissions.researchpublication.update',
        'destroy' => 'professor.submissions.researchpublication.destroy'
    ]);
    Route::resource('researchpresentation', \App\Http\Controllers\Submissions\ResearchPresentationController::class)->names([
        'index' => 'professor.submissions.researchpresentation',
        'create' => 'professor.submissions.researchpresentation.create',
        'store' => 'professor.submissions.researchpresentation.store',
        'show' => 'professor.submissions.researchpresentation.show',
        'edit' => 'professor.submissions.researchpresentation.edit',
        'update' => 'professor.submissions.researchpresentation.update',
        'destroy' => 'professor.submissions.researchpresentation.destroy'
    ]);
    Route::resource('researchcitation', \App\Http\Controllers\Submissions\ResearchCitationController::class)->names([
        'index' => 'professor.submissions.researchcitation',
        'create' => 'professor.submissions.researchcitation.create',
        'store' => 'professor.submissions.researchcitation.store',
        'show' => 'professor.submissions.researchcitation.show',
        'edit' => 'professor.submissions.researchcitation.edit',
        'update' => 'professor.submissions.researchcitation.update',
        'destroy' => 'professor.submissions.researchcitation.destroy'
    ]);
    Route::resource('researchutilization', \App\Http\Controllers\Submissions\ResearchUtilizationController::class)->names([
        'index' => 'professor.submissions.researchutilization',
        'create' => 'professor.submissions.researchutilization.create',
        'store' => 'professor.submissions.researchutilization.store',
        'show' => 'professor.submissions.researchutilization.show',
        'edit' => 'professor.submissions.researchutilization.edit',
        'update' => 'professor.submissions.researchutilization.update',
        'destroy' => 'professor.submissions.researchutilization.destroy'
    ]);
    Route::resource('researchcopyright', \App\Http\Controllers\Submissions\ResearchCopyrightController::class)->names([
        'index' => 'professor.submissions.researchcopyright',
        'create' => 'professor.submissions.researchcopyright.create',
        'store' => 'professor.submissions.researchcopyright.store',
        'show' => 'professor.submissions.researchcopyright.show',
        'edit' => 'professor.submissions.researchcopyright.edit',
        'update' => 'professor.submissions.researchcopyright.update',
        'destroy' => 'professor.submissions.researchcopyright.destroy'
    ]);
    Route::resource('invention', \App\Http\Controllers\Submissions\InventionController::class)->names([
        'index' => 'professor.submissions.invention',
        'create' => 'professor.submissions.invention.create',
        'store' => 'professor.submissions.invention.store',
        'show' => 'professor.submissions.invention.show',
        'edit' => 'professor.submissions.invention.edit',
        'update' => 'professor.submissions.invention.update',
        'destroy' => 'professor.submissions.invention.destroy'
    ]);
    Route::resource('expertconsultant', \App\Http\Controllers\Submissions\ExpertConsultantController::class)->names([
        'index' => 'professor.submissions.expertconsultant',
        'create' => 'professor.submissions.expertconsultant.create',
        'store' => 'professor.submissions.expertconsultant.store',
        'show' => 'professor.submissions.expertconsultant.show',
        'edit' => 'professor.submissions.expertconsultant.edit',
        'update' => 'professor.submissions.expertconsultant.update',
        'destroy' => 'professor.submissions.expertconsultant.destroy'
    ]);
    Route::resource('expertconference', \App\Http\Controllers\Submissions\ExpertConferenceController::class)->names([
        'index' => 'professor.submissions.expertconference',
        'create' => 'professor.submissions.expertconference.create',
        'store' => 'professor.submissions.expertconference.store',
        'show' => 'professor.submissions.expertconference.show',
        'edit' => 'professor.submissions.expertconference.edit',
        'update' => 'professor.submissions.expertconference.update',
        'destroy' => 'professor.submissions.expertconference.destroy'
    ]);
    Route::resource('expertjournal', \App\Http\Controllers\Submissions\ExpertJournalController::class)->names([
        'index' => 'professor.submissions.expertjournal',
        'create' => 'professor.submissions.expertjournal.create',
        'store' => 'professor.submissions.expertjournal.store',
        'show' => 'professor.submissions.expertjournal.show',
        'edit' => 'professor.submissions.expertjournal.edit',
        'update' => 'professor.submissions.expertjournal.update',
        'destroy' => 'professor.submissions.expertjournal.destroy'
    ]);
    Route::resource('extensionprogram', \App\Http\Controllers\Submissions\ExtensionProgramController::class)->names([
        'index' => 'professor.submissions.extensionprogram',
        'create' => 'professor.submissions.extensionprogram.create',
        'store' => 'professor.submissions.extensionprogram.store',
        'show' => 'professor.submissions.extensionprogram.show',
        'edit' => 'professor.submissions.extensionprogram.edit',
        'update' => 'professor.submissions.extensionprogram.update',
        'destroy' => 'professor.submissions.extensionprogram.destroy'
    ]);
    Route::resource('partnership', \App\Http\Controllers\Submissions\PartnershipController::class)->names([
        'index' => 'professor.submissions.partnership',
        'create' => 'professor.submissions.partnership.create',
        'store' => 'professor.submissions.partnership.store',
        'show' => 'professor.submissions.partnership.show',
        'edit' => 'professor.submissions.partnership.edit',
        'update' => 'professor.submissions.partnership.update',
        'destroy' => 'professor.submissions.partnership.destroy'
    ]);
    Route::resource('facultyintercountry', \App\Http\Controllers\Submissions\FacultyInterCountryController::class)->names([
        'index' => 'professor.submissions.facultyintercountry',
        'create' => 'professor.submissions.facultyintercountry.create',
        'store' => 'professor.submissions.facultyintercountry.store',
        'show' => 'professor.submissions.facultyintercountry.show',
        'edit' => 'professor.submissions.facultyintercountry.edit',
        'update' => 'professor.submissions.facultyintercountry.update',
        'destroy' => 'professor.submissions.facultyintercountry.destroy'
    ]);
    Route::resource('material', \App\Http\Controllers\Submissions\MaterialController::class)->names([
        'index' => 'professor.submissions.material',
        'create' => 'professor.submissions.material.create',
        'store' => 'professor.submissions.material.store',
        'show' => 'professor.submissions.material.show',
        'edit' => 'professor.submissions.material.edit',
        'update' => 'professor.submissions.material.update',
        'destroy' => 'professor.submissions.material.destroy'
    ]);
    Route::resource('syllabus', \App\Http\Controllers\Submissions\SyllabusController::class)->names([
        'index' => 'professor.submissions.syllabus',
        'create' => 'professor.submissions.syllabus.create',
        'store' => 'professor.submissions.syllabus.store',
        'show' => 'professor.submissions.syllabus.show',
        'edit' => 'professor.submissions.syllabus.edit',
        'update' => 'professor.submissions.syllabus.update',
        'destroy' => 'professor.submissions.syllabus.destroy'
    ]);
    Route::resource('specialtask', \App\Http\Controllers\Submissions\SpecialTaskController::class)->names([
        'index' => 'professor.submissions.specialtask',
        'create' => 'professor.submissions.specialtask.create',
        'store' => 'professor.submissions.specialtask.store',
        'show' => 'professor.submissions.specialtask.show',
        'edit' => 'professor.submissions.specialtask.edit',
        'update' => 'professor.submissions.specialtask.update',
        'destroy' => 'professor.submissions.specialtask.destroy'
    ]);
    Route::resource('specialtaskefficiency', \App\Http\Controllers\Submissions\SpecialTaskEfficiencyController::class)->names([
        'index' => 'professor.submissions.specialtaskefficiency',
        'create' => 'professor.submissions.specialtaskefficiency.create',
        'store' => 'professor.submissions.specialtaskefficiency.store',
        'show' => 'professor.submissions.specialtaskefficiency.show',
        'edit' => 'professor.submissions.specialtaskefficiency.edit',
        'update' => 'professor.submissions.specialtaskefficiency.update',
        'destroy' => 'professor.submissions.specialtaskefficiency.destroy'
    ]);
    Route::resource('specialtasktimeliness', \App\Http\Controllers\Submissions\SpecialTaskTimelinessController::class)->names([
        'index' => 'professor.submissions.specialtasktimeliness',
        'create' => 'professor.submissions.specialtasktimeliness.create',
        'store' => 'professor.submissions.specialtasktimeliness.store',
        'show' => 'professor.submissions.specialtasktimeliness.show',
        'edit' => 'professor.submissions.specialtasktimeliness.edit',
        'update' => 'professor.submissions.specialtasktimeliness.update',
        'destroy' => 'professor.submissions.specialtasktimeliness.destroy'
    ]);
    Route::resource('attendancefunction', \App\Http\Controllers\Submissions\AttendanceFunctionController::class)->names([
        'index' => 'professor.submissions.attendancefunction',
        'create' => 'professor.submissions.attendancefunction.create',
        'store' => 'professor.submissions.attendancefunction.store',
        'show' => 'professor.submissions.attendancefunction.show',
        'edit' => 'professor.submissions.attendancefunction.edit',
        'update' => 'professor.submissions.attendancefunction.update',
        'destroy' => 'professor.submissions.attendancefunction.destroy'
    ]);
    Route::resource('viableproject', \App\Http\Controllers\Submissions\ViableProjectController::class)->names([
        'index' => 'professor.submissions.viableproject',
        'create' => 'professor.submissions.viableproject.create',
        'store' => 'professor.submissions.viableproject.store',
        'show' => 'professor.submissions.viableproject.show',
        'edit' => 'professor.submissions.viableproject.edit',
        'update' => 'professor.submissions.viableproject.update',
        'destroy' => 'professor.submissions.viableproject.destroy'
    ]);
    Route::resource('branchaward', \App\Http\Controllers\Submissions\BranchAwardController::class)->names([
        'index' => 'professor.submissions.branchaward',
        'create' => 'professor.submissions.branchaward.create',
        'store' => 'professor.submissions.branchaward.store',
        'show' => 'professor.submissions.branchaward.show',
        'edit' => 'professor.submissions.branchaward.edit',
        'update' => 'professor.submissions.branchaward.update',
        'destroy' => 'professor.submissions.branchaward.destroy'
    ]);


    Route::group(['middleware' => 'role:hap', 'prefix' => 'hap', 'as' => 'hap.'], function(){
        Route::get('/review', [\App\Http\Controllers\Hap\ReviewController::class, 'index'])->name('review.index');
        Route::post('/review/accept', [\App\Http\Controllers\Hap\ReviewController::class, 'accept'])->name('review.accept');
        Route::post('/review/reject', [\App\Http\Controllers\Hap\ReviewController::class, 'reject'])->name('review.reject');



        Route::post('ongoingadvanced/deletefileonedit/{ongoingadvanced}', [\App\Http\Controllers\Hap\Reviews\OngoingAdvancedController::class, 'removeFileInEdit'])->name('ongoingadvanced.file.delete');
        Route::post('facultyaward/deletefileonedit/{facultyaward}', [\App\Http\Controllers\Hap\Reviews\FacultyAwardController::class, 'removeFileInEdit'])->name('facultyaward.file.delete');
        Route::post('officership/deletefileonedit/{officership}', [\App\Http\Controllers\Hap\Reviews\OfficershipController::class, 'removeFileInEdit'])->name('officership.file.delete');
        Route::post('attendanceconference/deletefileonedit/{attendanceconference}', [\App\Http\Controllers\Hap\Reviews\AttendanceConferenceController::class, 'removeFileInEdit'])->name('attendanceconference.file.delete');
        Route::post('attendancetraining/deletefileonedit/{attendancetraining}', [\App\Http\Controllers\Hap\Reviews\AttendanceTrainingController::class, 'removeFileInEdit'])->name('attendancetraining.file.delete');
        Route::post('research/deletefileonedit/{research}', [\App\Http\Controllers\Hap\Reviews\ResearchController::class, 'removeFileInEdit'])->name('research.file.delete');
        Route::post('researchpublication/deletefileonedit/{researchpublication}', [\App\Http\Controllers\Hap\Reviews\ResearchPublicationController::class, 'removeFileInEdit'])->name('researchpublication.file.delete');
        Route::post('researchpresentation/deletefileonedit/{researchpresentation}', [\App\Http\Controllers\Hap\Reviews\ResearchPresentationController::class, 'removeFileInEdit'])->name('researchpresentation.file.delete');
        Route::post('researchcitation/deletefileonedit/{researchcitation}', [\App\Http\Controllers\Hap\Reviews\ResearchCitationController::class, 'removeFileInEdit'])->name('researchcitation.file.delete');
        Route::post('researchutilization/deletefileonedit/{researchutilization}', [\App\Http\Controllers\Hap\Reviews\ResearchUtilizationController::class, 'removeFileInEdit'])->name('researchutilization.file.delete');
        Route::post('researchcopyright/deletefileonedit/{researchcopyright}', [\App\Http\Controllers\Hap\Reviews\ResearchCopyrightController::class, 'removeFileInEdit'])->name('researchcopyright.file.delete');
        Route::post('invention/deletefileonedit/{invention}', [\App\Http\Controllers\Hap\Reviews\InventionController::class, 'removeFileInEdit'])->name('invention.file.delete');
        Route::post('expertconsultant/deletefileonedit/{expertconsultant}', [\App\Http\Controllers\Hap\Reviews\ExpertConsultantController::class, 'removeFileInEdit'])->name('expertconsultant.file.delete');
        Route::post('expertconference/deletefileonedit/{expertconference}', [\App\Http\Controllers\Hap\Reviews\ExpertConferenceController::class, 'removeFileInEdit'])->name('expertconference.file.delete');
        Route::post('expertjournal/deletefileonedit/{expertjournal}', [\App\Http\Controllers\Hap\Reviews\ExpertJournalController::class, 'removeFileInEdit'])->name('expertjournal.file.delete');
        Route::post('extensionprogram/deletefileonedit/{extensionprogram}', [\App\Http\Controllers\Hap\Reviews\ExtensionProgramController::class, 'removeFileInEdit'])->name('extensionprogram.file.delete');
        Route::post('partnership/deletefileonedit/{partnership}', [\App\Http\Controllers\Hap\Reviews\PartnershipController::class, 'removeFileInEdit'])->name('partnership.file.delete');
        Route::post('facultyintercountry/deletefileonedit/{facultyintercountry}', [\App\Http\Controllers\Hap\Reviews\FacultyInterCountryController::class, 'removeFileInEdit'])->name('facultyintercountry.file.delete');
        Route::post('material/deletefileonedit/{material}', [\App\Http\Controllers\Hap\Reviews\MaterialController::class, 'removeFileInEdit'])->name('material.file.delete');
        Route::post('syllabus/deletefileonedit/{syllabu}', [\App\Http\Controllers\Hap\Reviews\SyllabusController::class, 'removeFileInEdit'])->name('syllabus.file.delete');
        Route::post('specialtask/deletefileonedit/{specialtask}', [\App\Http\Controllers\Hap\Reviews\SpecialTaskController::class, 'removeFileInEdit'])->name('specialtask.file.delete');
        Route::post('specialtaskefficiency/deletefileonedit/{specialtaskefficiency}', [\App\Http\Controllers\Hap\Reviews\SpecialTaskEfficiencyController::class, 'removeFileInEdit'])->name('specialtaskefficiency.file.delete');
        Route::post('specialtasktimeliness/deletefileonedit/{specialtasktimeliness}', [\App\Http\Controllers\Hap\Reviews\SpecialTaskTimelinessController::class, 'removeFileInEdit'])->name('specialtasktimeliness.file.delete');
        Route::post('attendancefunction/deletefileonedit/{attendancefunction}', [\App\Http\Controllers\Hap\Reviews\AttendanceFunctionController::class, 'removeFileInEdit'])->name('attendancefunction.file.delete');
        Route::post('viableproject/deletefileonedit/{viableproject}', [\App\Http\Controllers\Hap\Reviews\ViableProjectController::class, 'removeFileInEdit'])->name('viableproject.file.delete');
        Route::post('branchaward/deletefileonedit/{branchaward}', [\App\Http\Controllers\Hap\Reviews\BranchAwardController::class, 'removeFileInEdit'])->name('branchaward.file.delete');
        

        Route::resource('ongoingadvanced', \App\Http\Controllers\Hap\Reviews\OngoingAdvancedController::class)->names([
            'show' => 'review.ongoingadvanced.show',
            'edit' => 'review.ongoingadvanced.edit',
            'update' => 'review.ongoingadvanced.update',
            'destroy' => 'review.ongoingadvanced.destroy'
        ]);
        Route::resource('facultyaward', \App\Http\Controllers\Hap\Reviews\FacultyAwardController::class)->names([
            'show' => 'review.facultyaward.show',
            'edit' => 'review.facultyaward.edit',
            'update' => 'review.facultyaward.update',
            'destroy' => 'review.facultyaward.destroy'
        ]);
        Route::resource('officership', \App\Http\Controllers\Hap\Reviews\OfficershipController::class)->names([
            'show' => 'review.officership.show',
            'edit' => 'review.officership.edit',
            'update' => 'review.officership.update',
            'destroy' => 'review.officership.destroy'
        ]);
        Route::resource('attendanceconference', \App\Http\Controllers\Hap\Reviews\AttendanceConferenceController::class)->names([
            'show' => 'review.attendanceconference.show',
            'edit' => 'review.attendanceconference.edit',
            'update' => 'review.attendanceconference.update',
            'destroy' => 'review.attendanceconference.destroy'
        ]);
        Route::resource('attendancetraining', \App\Http\Controllers\Hap\Reviews\AttendanceTrainingController::class)->names([
            'show' => 'review.attendancetraining.show',
            'edit' => 'review.attendancetraining.edit',
            'update' => 'review.attendancetraining.update',
            'destroy' => 'review.attendancetraining.destroy'
        ]);
        Route::resource('research', \App\Http\Controllers\Hap\Reviews\ResearchController::class)->names([
            'show' => 'review.research.show',
            'edit' => 'review.research.edit',
            'update' => 'review.research.update',
            'destroy' => 'review.research.destroy'
        ]);
        Route::resource('researchpublication', \App\Http\Controllers\Hap\Reviews\ResearchPublicationController::class)->names([
            'show' => 'review.researchpublication.show',
            'edit' => 'review.researchpublication.edit',
            'update' => 'review.researchpublication.update',
            'destroy' => 'review.researchpublication.destroy'
        ]);
        Route::resource('researchpresentation', \App\Http\Controllers\Hap\Reviews\ResearchPresentationController::class)->names([
            'show' => 'review.researchpresentation.show',
            'edit' => 'review.researchpresentation.edit',
            'update' => 'review.researchpresentation.update',
            'destroy' => 'review.researchpresentation.destroy'
        ]);
        Route::resource('researchcitation', \App\Http\Controllers\Hap\Reviews\ResearchCitationController::class)->names([
            'show' => 'review.researchcitation.show',
            'edit' => 'review.researchcitation.edit',
            'update' => 'review.researchcitation.update',
            'destroy' => 'review.researchcitation.destroy'
        ]);
        Route::resource('researchutilization', \App\Http\Controllers\Hap\Reviews\ResearchUtilizationController::class)->names([
            'show' => 'review.researchutilization.show',
            'edit' => 'review.researchutilization.edit',
            'update' => 'review.researchutilization.update',
            'destroy' => 'review.researchutilization.destroy'
        ]);
        Route::resource('researchcopyright', \App\Http\Controllers\Hap\Reviews\ResearchCopyrightController::class)->names([
            'show' => 'review.researchcopyright.show',
            'edit' => 'review.researchcopyright.edit',
            'update' => 'review.researchcopyright.update',
            'destroy' => 'review.researchcopyright.destroy'
        ]);
        Route::resource('invention', \App\Http\Controllers\Hap\Reviews\InventionController::class)->names([
            'show' => 'review.invention.show',
            'edit' => 'review.invention.edit',
            'update' => 'review.invention.update',
            'destroy' => 'review.invention.destroy'
        ]);
        Route::resource('expertconsultant', \App\Http\Controllers\Hap\Reviews\ExpertConsultantController::class)->names([
            'show' => 'review.expertconsultant.show',
            'edit' => 'review.expertconsultant.edit',
            'update' => 'review.expertconsultant.update',
            'destroy' => 'review.expertconsultant.destroy'
        ]);
        Route::resource('expertconference', \App\Http\Controllers\Hap\Reviews\ExpertConferenceController::class)->names([
            'show' => 'review.expertconference.show',
            'edit' => 'review.expertconference.edit',
            'update' => 'review.expertconference.update',
            'destroy' => 'review.expertconference.destroy'
        ]);
        Route::resource('expertjournal', \App\Http\Controllers\Hap\Reviews\ExpertJournalController::class)->names([
            'show' => 'review.expertjournal.show',
            'edit' => 'review.expertjournal.edit',
            'update' => 'review.expertjournal.update',
            'destroy' => 'review.expertjournal.destroy'
        ]);
        Route::resource('extensionprogram', \App\Http\Controllers\Hap\Reviews\ExtensionProgramController::class)->names([
            'show' => 'review.extensionprogram.show',
            'edit' => 'review.extensionprogram.edit',
            'update' => 'review.extensionprogram.update',
            'destroy' => 'review.extensionprogram.destroy'
        ]);
        Route::resource('partnership', \App\Http\Controllers\Hap\Reviews\PartnershipController::class)->names([
            'show' => 'review.partnership.show',
            'edit' => 'review.partnership.edit',
            'update' => 'review.partnership.update',
            'destroy' => 'review.partnership.destroy'
        ]);
        Route::resource('facultyintercountry', \App\Http\Controllers\Hap\Reviews\FacultyInterCountryController::class)->names([
            'show' => 'review.facultyintercountry.show',
            'edit' => 'review.facultyintercountry.edit',
            'update' => 'review.facultyintercountry.update',
            'destroy' => 'review.facultyintercountry.destroy'
        ]);
        Route::resource('material', \App\Http\Controllers\Hap\Reviews\MaterialController::class)->names([
            'show' => 'review.material.show',
            'edit' => 'review.material.edit',
            'update' => 'review.material.update',
            'destroy' => 'review.material.destroy'
        ]);
        Route::resource('syllabus', \App\Http\Controllers\Hap\Reviews\SyllabusController::class)->names([
            'show' => 'review.syllabus.show',
            'edit' => 'review.syllabus.edit',
            'update' => 'review.syllabus.update',
            'destroy' => 'review.syllabus.destroy'
        ]);
        Route::resource('specialtask', \App\Http\Controllers\Hap\Reviews\SpecialTaskController::class)->names([
            'show' => 'review.specialtask.show',
            'edit' => 'review.specialtask.edit',
            'update' => 'review.specialtask.update',
            'destroy' => 'review.specialtask.destroy'
        ]);
        Route::resource('specialtaskefficiency', \App\Http\Controllers\Hap\Reviews\SpecialTaskEfficiencyController::class)->names([
            'show' => 'review.specialtaskefficiency.show',
            'edit' => 'review.specialtaskefficiency.edit',
            'update' => 'review.specialtaskefficiency.update',
            'destroy' => 'review.specialtaskefficiency.destroy'
        ]);
        Route::resource('specialtasktimeliness', \App\Http\Controllers\Hap\Reviews\SpecialTaskTimelinessController::class)->names([
            'show' => 'review.specialtasktimeliness.show',
            'edit' => 'review.specialtasktimeliness.edit',
            'update' => 'review.specialtasktimeliness.update',
            'destroy' => 'review.specialtasktimeliness.destroy'
        ]);
        Route::resource('attendancefunction', \App\Http\Controllers\Hap\Reviews\AttendanceFunctionController::class)->names([
            'show' => 'review.attendancefunction.show',
            'edit' => 'review.attendancefunction.edit',
            'update' => 'review.attendancefunction.update',
            'destroy' => 'review.attendancefunction.destroy'
        ]);
        Route::resource('viableproject', \App\Http\Controllers\Hap\Reviews\ViableProjectController::class)->names([
            'show' => 'review.viableproject.show',
            'edit' => 'review.viableproject.edit',
            'update' => 'review.viableproject.update',
            'destroy' => 'review.viableproject.destroy'
        ]);
        Route::resource('branchaward', \App\Http\Controllers\Hap\Reviews\BranchAwardController::class)->names([
            'show' => 'review.branchaward.show',
            'edit' => 'review.branchaward.edit',
            'update' => 'review.branchaward.update',
            'destroy' => 'review.branchaward.destroy'
        ]);
    });
    Route::group(['middleware' => 'role:professor', 'prefix' => 'professor', 'as' => 'professor.'], function(){
    
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
        Route::get('/announcements/hide/{announcement}', [\App\Http\Controllers\Administrators\AnnouncementController::class, 'hide'])->name('announcements.hide');
        Route::get('/announcements/activate/{announcement}', [\App\Http\Controllers\Administrators\AnnouncementController::class, 'activate'])->name('announcements.activate');
        // Route::get('/events/accept/{event}', [\App\Http\Controllers\Administrators\EventController::class, 'accept'])->name('events.accept');
        // Route::get('/events/reject/{event}', [\App\Http\Controllers\Administrators\EventController::class, 'reject'])->name('events.reject');
        // Route::get('/events/close/{event}', [\App\Http\Controllers\Administrators\EventController::class, 'close'])->name('events.close');
        Route::resource('announcements', \App\Http\Controllers\Administrators\AnnouncementController::class);
        // Route::resource('events', \App\Http\Controllers\Administrators\EventController::class);
        // Route::resource('event_types', \App\Http\Controllers\Administrators\EventTypeController::class);
    });
});


Route::get('/registration/{token}', [\App\Http\Controllers\Administrators\UserController::class, 'registration_view'])->name('registration')->middleware('guest');
Route::post('/registration/accept', [\App\Http\Controllers\Registration\RegisterController::class, 'create'])->name('accept')->middleware('guest');