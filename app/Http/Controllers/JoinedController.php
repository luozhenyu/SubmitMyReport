<?php

namespace App\Http\Controllers;

class JoinedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();

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
            'urgent' => true,
        );
        $assignment2 = array(
            'title' => 'Final Project DDL',
            'group' => 'Database Admin',
            'ddl' => '2017-12-21',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'submitted' => true,
            'urgent' => false,
        );
        $assignment3 = array(
            'title' => 'PL0 Compiler',
            'group' => 'Compiler',
            'ddl' => '2018-1-5',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'submitted' => false,
            'urgent' => false,
        );
        $assignments = array($assignment1, $assignment2, $assignment3, $assignment1, $assignment2, $assignment3, $assignment1, $assignment2, $assignment3);

        $data = array(
            'groups' => $groups,
            'active_group' => 0,
            'active_page' => 'joined',
            'user' => $user,
            'title' => "Joined",
            'assignments' => $assignments,

            'group_name' => 'Compiler',
            'creator' => 'yzhq97',
            'created_on' => '2017-9-7',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'administrators' => array('yzhq97', 'abc123', '123abc', '2ddf4'),

            'search_result' => array(
                "Operating System 1521",
                "Operating System 1421",
                "Compiler",
                "Computer Network",
                "Data Mining",
                "Compiler 1421"
            )
        );
        return view('joined.joined', $data);
    }

    public function testshow()
    {
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
            'urgent' => true,
        );
        $assignment2 = array(
            'title' => 'Final Project DDL',
            'group' => 'Database Admin',
            'ddl' => '2017-12-21',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'submitted' => true,
            'urgent' => false,
        );
        $assignment3 = array(
            'title' => 'PL0 Compiler',
            'group' => 'Compiler',
            'ddl' => '2018-1-5',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'submitted' => false,
            'urgent' => false,
        );
        $assignments = array($assignment1, $assignment2, $assignment3, $assignment1, $assignment2, $assignment3, $assignment1, $assignment2, $assignment3);

        $data = array(
            'groups' => $groups,
            'active_group' => 0,
            'active_page' => 'joined',
            'user' => "yzhq97",
            'title' => "Joined",
            'assignments' => $assignments,

            'group_name' => 'Compiler',
            'creator' => 'yzhq97',
            'created_on' => '2017-9-7',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'administrators' => array('yzhq97', 'abc123', '123abc', '2ddf4'),

            'search_result' => array(
                "Operating System 1521",
                "Operating System 1421",
                "Compiler",
                "Computer Network",
                "Data Mining",
                "Compiler 1421"
            )
        );
        return view('joined.joined', $data);
    }
}
