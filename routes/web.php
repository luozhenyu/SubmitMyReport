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

Route::get('/', 'HomeController@index')->name('home');


//Group
Route::get('/group', 'GroupController@index')->name('group');
Route::get('/group/all', 'GroupController@all');

Route::get('/group/create', 'GroupController@create');
Route::post('/group', 'GroupController@store');

Route::post('/group/{id}/join', 'GroupController@join');
Route::post('/group/{id}/quit', 'GroupController@quit');
Route::get('/group/{id}/member', 'GroupController@member');
Route::post('/group/{id}/member', 'GroupController@toggleAdmin');

Route::get('/group/{id}', 'AssignmentController@index');
Route::get('/group/{id}/create', 'AssignmentController@create');
Route::post('/group/{id}/store', 'AssignmentController@store');

Route::get('/assignment/{id}', 'SubmissionController@index');

Route::get('/assignment/{id}/create', 'SubmissionController@create');
Route::post('/assignment/{id}/store', 'SubmissionController@store');

Route::get('/submission/{id}', 'SubmissionController@show');
Route::post('/submission/{id}', 'SubmissionController@mark');

Route::get('/submission/{id}/score', 'SubmissionController@score');
