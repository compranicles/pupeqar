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
        Route::get('search', [\App\Http\Controllers\Professors\EventController::class, 'search'])->name('events.search');
        Route::post('submissions/delete/{event}', [\App\Http\Controllers\Professors\SubmissionController::class, 'deleteFile'])->name('file.delete');
        Route::resource('events', \App\Http\Controllers\Professors\EventController::class);
        Route::resource('events.submissions', \App\Http\Controllers\Professors\SubmissionController::class);
    });
    Route::group(['middleware' => 'role:administrator', 'prefix' => 'admin', 'as' => 'admin.'], function(){
        Route::view('/maintenances', 'admin.maintenances.index')->name('maintenances.index');
        Route::get('/users/invite', [\App\Http\Controllers\Administrators\UserController::class, 'invite'])->name('users.invite');
        Route::post('/users/invite/send', [\App\Http\Controllers\Administrators\UserController::class, 'send'])->name('users.sendinvite');
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