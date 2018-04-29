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

//Home
Route::get('/', 'HomeController@index')->name('home');

Route::post('/improve', 'HomeController@improve')->name('improve');


Route::get('/profile', 'Profile\UpdateProfileController@edit')->name('profile');
Route::put('/profile', 'Profile\UpdateProfileController@update');
Route::get('/profile/password', 'Profile\UpdatePasswordController@edit')
    ->name('profile.password');
Route::put('/profile/password', 'Profile\UpdatePasswordController@update');


//Joined group
Route::get('/group', 'GroupController@index')->name('group');

//all group
Route::get('/group/list', 'GroupController@list');

//Create group
Route::get('/group/create', 'GroupController@create');
Route::post('/group', 'GroupController@store');

//Update group description
Route::put('/group/{group_id}', 'GroupController@update');
//Show members
Route::get('/group/{group_id}/members', 'GroupController@showMembers');

//Join
Route::post('/group/{group_id}/join', 'GroupController@join');
//Quit
Route::post('/group/{group_id}/quit', 'GroupController@quit');
//Set admin
Route::post('/group/{group_id}/admin', 'GroupController@toggleAdmin');


//Show Assignments in group
Route::get('/group/{group_id}', 'AssignmentController@index');

//List and create assignment
Route::get('/group/{group_id}/create', 'AssignmentController@create');
Route::post('/group/{group_id}', 'AssignmentController@store');

//Show assignment in detail
Route::get('/assignment/{assignment_id}', 'AssignmentController@show');
Route::get('/assignment/{assignment_id}/edit', 'AssignmentController@edit');
Route::put('/assignment/{assignment_id}', 'AssignmentController@update');
//Route::delete('/assignment/{assignment_id}', 'AssignmentController@delete');//TODO:

//List submissions
Route::get('/assignment/{assignment_id}/submission', 'SubmissionController@index');

//Create submission
Route::get('/assignment/{assignment_id}/submit', 'SubmissionController@create');
Route::post('/assignment/{assignment_id}', 'SubmissionController@store');

//Show submission in detail
Route::get('/submission/{submission_id}', 'SubmissionController@show');//Admin can score while student can not
Route::get('/submission/{submission_id}/edit', 'SubmissionController@edit');
Route::put('/submission/{submission_id}', 'SubmissionController@update');

Route::post('/submission/{submission_id}/mark', 'SubmissionController@mark');


//File upload and download
Route::get('/file/{hash}', 'FileController@show');
Route::post('/file', 'FileController@store')->name('file.upload');

//Preview file
Route::get('/preview/{hash}', 'PreviewController@dispatchJob');
Route::post('/preview/{hash}', 'PreviewController@queryStatus');

Route::get('/test', 'HomeController@test');
