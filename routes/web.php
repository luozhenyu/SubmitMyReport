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

Route::get('/', 'JoindController@show')->name('index');

//Joined
Route::get('/joined', 'JoindController@show');
Route::get('/join_group', 'JoindController@join_group');
Route::post('/join_group', 'JoindController@join_group');

//Manage
Route::get('/manage', 'ManageController@show');
Route::get('/post', 'PostController@show');

//Group and User
Route::get('/group', 'GroupController@index');
Route::post('/group/create', 'GroupController@create');
Route::get('/group/destroy', 'GroupController@destroy');

Route::get('/group/join', 'GroupController@join');
Route::get('/group/remove', 'GroupController@remove');
Route::get('/group/quit', 'GroupController@quit');
Route::get('/group/resign', 'GroupController@resign');
Route::get('/group/appoint', 'GroupController@appoint');
Route::get('/group/fire', 'GroupController@fire');


Route::get('/submit', function (Request $request) {
    $data = array(
        'user' => "yzhq97",
        'title' => "Submission",
        'active_page' => 'joined',
        'assignment_title' => "Lab 9 - Linux Kernel Compilation",
        'ddl' => "2017-12-27",
        'group' => "Operating System",
        'description' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."
    );
    return view('submit.submit', $data);
});

Route::get('/assignment', function () {
    $sub1 = array(
        'name' => "杨卓谦",
        'time' => '2017-7-9',
        'files' => array('a.txt', 'b.jpg', 'c.doc')
    );
    $sub2 = array(
        'name' => "杜腰",
        'time' => '2017-8-17',
        'files' => array('a.txt', 'c.doc')
    );
    $sub3 = array(
        'name' => "次诚信",
        'time' => '2017-7-1',
        'files' => array('a.txt', 'b.jpg', 'c.doc')
    );
    $sub4 = array(
        'name' => "名瑜伽",
        'time' => '2017-7-42',
        'files' => array('a.txt')
    );
    $sub5 = array(
        'name' => "盘沧江",
        'time' => '2018-7-9',
        'files' => array('a.txt', 'b.jpg', 'c.doc')
    );
    $sub6 = array(
        'name' => "某某人",
        'time' => '2017-8-9',
        'files' => array('a.txt', 'b.jpg', 'c.doc')
    );
    $sub7 = array(
        'name' => "一个人",
        'time' => '2017-7-19',
        'files' => array('a.txt', 'b.jpg', 'c.doc')
    );
    $submissions = array(
        $sub1, $sub2, $sub3, $sub4, $sub5, $sub6, $sub7
    );

    $data = array(
        'user' => "yzhq97",
        'title' => "Submission",
        'active_page' => 'manage',
        'assignment_title' => "Lab 9 - Linux Kernel Compilation",
        'ddl' => "2017-12-27",
        'group' => "Operating System",
        'description' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
        'submissions' => $submissions
    );
    return view('assignment.assignment', $data);
});