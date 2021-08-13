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

Route::group(['middleware' => 'auth'], function() {
    Route::get('announcement/{id}', [\App\Http\Controllers\AnnouncementController::class, 'showMessage']);
    Route::group(['middleware' => 'role:hap', 'prefix' => 'hap', 'as' => 'hap.'], function(){
        Route::resource('submissions', \App\Http\Controllers\Hap\SubmissionController::class);
    });
    Route::group(['middleware' => 'role:professor', 'prefix' => 'professor', 'as' => 'professor.'], function(){
        Route::get('search', [\App\Http\Controllers\Professors\EventController::class, 'search'])->name('events.search');
        Route::resource('submissions', \App\Http\Controllers\Professors\SubmissionController::class);
        Route::resource('events', \App\Http\Controllers\Professors\EventController::class);
    });
    Route::group(['middleware' => 'role:administrator', 'prefix' => 'admin', 'as' => 'admin.'], function(){
        Route::get('/users/invite', [\App\Http\Controllers\Administrators\UserController::class, 'invite'])->name('users.invite');
        Route::get('/announcements/hide/{announcement}', [\App\Http\Controllers\Administrators\AnnouncementController::class, 'hide'])->name('announcements.hide');
        Route::get('/announcements/activate/{announcement}', [\App\Http\Controllers\Administrators\AnnouncementController::class, 'activate'])->name('announcements.activate');
        Route::get('/events/accept/{event}', [\App\Http\Controllers\Administrators\EventController::class, 'accept'])->name('events.accept');
        Route::get('/events/reject/{event}', [\App\Http\Controllers\Administrators\EventController::class, 'reject'])->name('events.reject');
        Route::get('/events/close/{event}', [\App\Http\Controllers\Administrators\EventController::class, 'close'])->name('events.close');
        Route::post('/users/invite/send', [\App\Http\Controllers\Administrators\UserController::class, 'send'])->name('users.sendinvite');
        Route::resource('announcements', \App\Http\Controllers\Administrators\AnnouncementController::class);
        Route::resource('users', \App\Http\Controllers\Administrators\UserController::class);
        Route::resource('events', \App\Http\Controllers\Administrators\EventController::class);
        Route::resource('event_types', \App\Http\Controllers\Administrators\EventTypeController::class);
    });
});


Route::get('/registration/{token}', [\App\Http\Controllers\Administrators\UserController::class, 'registration_view'])->name('registration')->middleware('guest');
Route::post('/registration/accept', [\App\Http\Controllers\Registration\RegisterController::class, 'create'])->name('accept')->middleware('guest');