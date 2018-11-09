<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/occupations', 'API\OccupationController@getOccupations');
Route::get('/occupations/{occupation_id}/responsibilities', 'API\OccupationController@getResponsibilities');
Route::get('/resumes/preview', 'API\ResumeController@getPreview')->name('resumes.preview');
Route::get('/resumes/templates', 'API\ResumeController@getTemplates');
Route::get('/users', 'API\UserController@getAuthors');