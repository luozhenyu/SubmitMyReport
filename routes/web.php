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
Route::put('/group/{groupId}', 'GroupController@update');
//Show members
Route::get('/group/{groupId}/members', 'GroupController@showMembers');

//Join
Route::post('/group/{groupId}/join', 'GroupController@join');
//Quit
Route::post('/group/{groupId}/quit', 'GroupController@quit');
//Set admin
Route::post('/group/{groupId}/admin', 'GroupController@toggleAdmin');


//Show Assignments in group
Route::get('/group/{groupId}', 'AssignmentController@index');

//List and create assignment
Route::get('/group/{groupId}/create', 'AssignmentController@create');
Route::post('/group/{groupId}', 'AssignmentController@store');

//Show assignment in detail
Route::get('/assignment/{assignmentId}', 'AssignmentController@show');
Route::get('/assignment/{assignmentId}/edit', 'AssignmentController@edit');
Route::put('/assignment/{assignmentId}', 'AssignmentController@update');
//Route::delete('/assignment/{assignmentId}', 'AssignmentController@delete');//TODO:

//List submissions
Route::get('/assignment/{assignmentId}/submission', 'SubmissionController@index');

//Create submission
Route::get('/assignment/{assignmentId}/submit', 'SubmissionController@create');
Route::post('/assignment/{assignmentId}', 'SubmissionController@store');

//Show submission in detail
Route::get('/submission/{submissionId}', 'SubmissionController@show');//Admin can score while student can not
Route::get('/submission/{submissionId}/edit', 'SubmissionController@edit');
Route::put('/submission/{submissionId}', 'SubmissionController@update');

Route::post('/submission/{submissionId}/mark', 'SubmissionController@mark');


//File upload and download
Route::get('/file/{hash}', 'FileController@show');
Route::post('/file', 'FileController@store')->name('file.upload');

//Preview file
Route::get('/preview/{hash}', 'PreviewController@dispatchJob');
//Route::post('/preview/{hash}', 'PreviewController@queryStatus');

//Site Message
Route::get('/message', 'SiteMessageController@index')->name('message');
Route::post('/message/query', 'SiteMessageController@queryUser');
Route::get('/message/{studentId}', 'SiteMessageController@show');
Route::put('/message/{studentId}', 'SiteMessageController@put');
Route::delete('/message/{studentId}', 'SiteMessageController@delete');
