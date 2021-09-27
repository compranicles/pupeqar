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
    //announcement view route
    Route::get('announcement/{id}', [\App\Http\Controllers\AnnouncementController::class, 'showMessage']);

    // HAP routes
    Route::group(['middleware' => 'role:hap', 'prefix' => 'hap', 'as' => 'hap.'], function(){
    });

    // faculty/professor routes
    Route::group(['middleware' => 'role:professor', 'prefix' => 'professor', 'as' => 'professor.'], function(){
    });
    
    // admin routes
    Route::group(['middleware' => 'role:administrator', 'prefix' => 'admin', 'as' => 'admin.'], function(){

        // announcements
        Route::get('/announcements/hide/{announcement}', [\App\Http\Controllers\Administrators\AnnouncementController::class, 'hide'])->name('announcements.hide');
        Route::get('/announcements/activate/{announcement}', [\App\Http\Controllers\Administrators\AnnouncementController::class, 'activate'])->name('announcements.activate');
        Route::resource('announcements', \App\Http\Controllers\Administrators\AnnouncementController::class);

        // dropdowns
        Route::get('dropdowns/modal/{id}', [\App\Http\Controllers\FormBuilder\DropdownController::class, 'dropdowndata']);
        Route::get('dropdowns/options/{id}', [\App\Http\Controllers\FormBuilder\DropdownController::class, 'options']);
        Route::resource('dropdowns', \App\Http\Controllers\FormBuilder\DropdownController::class);

        // forms
        Route::post('forms/qar', [\App\Http\Controllers\FormBuilder\FormController::class, 'qarArrange'])->name('forms.qar');
        Route::post('forms/nonqar', [\App\Http\Controllers\FormBuilder\FormController::class, 'nonQarArrange'])->name('forms.nonqar');
        Route::resource('forms', \App\Http\Controllers\FormBuilder\FormController::class);
        
        // form's fields
        Route::resource('forms.fields', \App\Http\Controllers\FormBuilder\FieldController::class);

        //users
        Route::get('/users/invite', [\App\Http\Controllers\Administrators\UserController::class, 'invite'])->name('users.invite');
        Route::post('/users/invite/send', [\App\Http\Controllers\Administrators\UserController::class, 'send'])->name('users.sendinvite');
        Route::resource('users', \App\Http\Controllers\Administrators\UserController::class);
    });
});

