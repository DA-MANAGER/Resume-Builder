<?php

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

Auth::routes();

Route::name('payments.')
    ->prefix('payments')
    ->group(function () {
        Route::get('/plans', 'RaveController@showPlans')->name('plans');
        Route::post('/pay', 'RaveController@initialize')->name('pay');
        Route::post('/rave/callback', 'RaveController@callback')->name('callback');
});

Route::name('resumes.')
    ->prefix('resumes')
    ->group(function () {
        Route::get('/new', 'ResumeController@showResumeForm')->name('create');
        Route::post('/new', 'ResumeController@storeResume')->name('store');

        Route::get('/{resume_id}/download', 'ResumeController@showStartingDownloadPage')->name('starting-download');
        Route::post('/{resume_id}/download', 'ResumeController@downloadResume')->name('download');
        Route::post('/{resume_id}/duplicate', 'ResumeController@duplicateResume')->name('duplicate');

        Route::get('/{resume_id}', 'ResumeController@showResume')->name('single');
        Route::put('/{resume_id}', 'ResumeController@updateResume')->name('update');
        Route::delete('/{resume_id}', 'ResumeController@deleteResume')->name('destroy');
});

Route::middleware('auth')
    ->name('dashboard.')
    ->prefix('dashboard')
    ->group(function () {
        Route::post('/resumes/templates/ignore', 'DashboardController@ignoreResumeTemplate')->name('resumes.templates.ignore');
        Route::delete('/resumes/templates/ignore', 'DashboardController@unignoreResumeTemplate');

        Route::get('/resumes/templates/upload', 'DashboardController@showUploadResumeTemplateForm')->name('resumes.templates-upload');
        Route::post('/resumes/templates/upload', 'DashboardController@uploadResumeTemplate');

        Route::get('/resumes/templates', 'DashboardController@showResumeTemplates')->name('resumes.templates');
        Route::delete('/resumes/templates', 'DashboardController@deleteResumeTemplate');

        Route::get('/resumes', 'ResumeController@showAllResumes')->name('resumes.all');

        Route::get('/users', 'DashboardController@showUsers')->name('users');

        Route::get('/cloud/dropbox/callback', 'CloudController@storeDropboxToken')->name('cloud.dropbox.callback');
        Route::post('/{username}/cloud/dropbox', 'CloudController@connectDropbox')->name('cloud.dropbox');
        Route::delete('/{username}/cloud/dropbox', 'CloudController@disconnectDropbox')->name('cloud.dropbox');
        Route::get('/{username}/cloud', 'DashboardController@showCloudConnections')->name('cloud');

        Route::get('/{username}/profile', 'DashboardController@showProfile')->name('profile');
        Route::post('/{username}/profile', 'DashboardController@updateProfile');

        Route::get('/{username}/resumes', 'ResumeController@showAllResumes')->name('resumes');

        Route::get('/{username}/subscriptions', 'DashboardController@showSubscriptions')->name('subscriptions');

        Route::get('/{username}', 'DashboardController@showStatistics')->name('statistics');
        Route::delete('/{username}', 'DashboardController@deleteProfile')->name('profile.delete');
});

Route::get('/', function () {
    return redirect()->route('resumes.create');
})->name('index');
