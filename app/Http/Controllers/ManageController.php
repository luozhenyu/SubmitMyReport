<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Group;
use Log;

class ManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create_group(Request $request) {
        $this->validate($request, [
            'group_name' => 'required|unique:homestead.groups.name|max:255',
            'group_description' => 'required',
        ]);

        $group = new Group;
        $group->name = $request->input('group_name');
        $group->description = $request->input('group_description');
        dd($group);
        $group->save();
    }

    public function show() {
        $user = Auth::user()['name'];

        // $groups = array(
        //     "All",
        //     "Operating System",
        //     "Compiler",
        //     "Data Mining",
        //     "Database Admin",
        //     "Math Modeling"
        // );
        $groups = array(
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
            'current_group' => 0,
            'active_page' => 'manage',
            'user' => $user,
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
    }
}
