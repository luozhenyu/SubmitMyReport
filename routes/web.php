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

Route::get('/', 'JoinedController@show')->name('index');


Route::get('/group', 'GroupController@index')->name('group');


Route::post('/group', 'GroupController@create');

Route::get('/test', function (){
    return view('homework.submithomework');
});

Route::get('/submit',function(Request $request){
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

Route::get('/joined', 'JoinedController@testshow');

Route::get('/manage', function() {
    $groups = array(
        "All",
        "Operating System",
        "Compiler",
        "Data Mining",
        "Database Admin",
        "Math Modeling"
    );

    $assignment1 = array(
        'title' => 'Linux Kernel Experiment',
        'group' => 'Operating System',
        'ddl' => '2017-12-23',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'submitted' => false,
        'got' => 114,
        'total' => 157
    );
    $assignment2 = array(
        'title' => 'Final Project DDL',
        'group' => 'Database Admin',
        'ddl' => '2017-12-21',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'submitted' => true,
        'urgent' => false,
        'got' => 79,
        'total' => 79
    );
    $assignment3 = array(
        'title' => 'PL0 Compiler',
        'group' => 'Compiler',
        'ddl' => '2018-1-5',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'submitted' => false,
        'urgent' => false,
        'got' => 74,
        'total' => 197
    );
    $assignments = array($assignment1, $assignment2, $assignment3, $assignment1, $assignment2, $assignment3, $assignment1, $assignment2, $assignment3);

    $member1 = array(
        'username' => 'dsf43',
        'is_admin' => true
    );
    $member2 = array(
        'username' => 'htzzf444',
        'is_admin' => true
    );
    $member3 = array(
        'username' => 'gbhtyhty',
        'is_admin' => false
    );
    $member4 = array(
        'username' => 'zzzzzz434',
        'is_admin' => false
    );
    $member5 = array(
        'username' => 'ffr5dd4',
        'is_admin' => false
    );
    $member6 = array(
        'username' => 'uxjakssk3',
        'is_admin' => false
    );
    $member7 = array(
        'username' => 'fasfads',
        'is_admin' => false
    );
    $member8 = array(
        'username' => 'ewrth',
        'is_admin' => false
    );

    $members = array($member1, $member2, $member3, $member4, $member5, $member6, $member7, $member8);

    $data = array(
        'groups' => $groups,
        'active_group' => 0,
        'active_page' => 'manage',
        'user' => "yzhq97",
        'title' => "Manage",
        'assignments' => $assignments,
        'members' => $members,

        'group_name' => 'Compiler',
        'creator' => 'yzhq97',
        'created_on' => '2017-9-7',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'administrators' => array('yzhq97', 'abc123', '123abc', '2ddf4')
    );
    return view('manage.manage', $data);
});

Route::get('/assignment', function() {
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