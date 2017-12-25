<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\User;

class JoindController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function show(Request $request) {
        $user = $request->user();

        $groups = $user->joinedGroups;
        $group = null;
        if ($request->input('current_group')) {
            $current_group = $request->input('current_group');
            $group = Group::find($current_group);
        } else {
            if (count($groups) > 0) {
                $group = $groups[0];
                $current_group = $group->id;
            } else {
                $current_group = -1;
            }
        }

        $assignments = $group ? $group->assignments:[];

        $members = $group ? $group->member:[];
        $admins = $group ? $group->admin:[];

        $data = array(
            'current_page' => 'joined',
            'title' => "Manage",

            'groups' => $groups,
            'current_group' => $current_group,
            'user' => $user,

            'assignments' => $assignments,

            'members' => $members,
            'admins' => $admins,

            'group' => $group
        );
        return view('joined.joined', $data);
    }

    public function join_group(Request $request) {
        $user = $request->user();
        $joined_groups = $user->joinedGroups;

        $group_name = $request->input('group_name');
        $search_result = null;

        if ($group_name) {
            $search_result = DB::select('select * from groups where name like ?', ["%".$group_name."%"]);
            for ($i = 0; $i < count($search_result); $i++) {
                $joined = false;
                foreach ($joined_groups as $group) {
                    if ($search_result[$i]->id == $group->id) {
                        $joined = true;
                        break;
                    }
                }
                $search_result[$i]->joined = $joined;
            }
        } else {
            $search_result = [];
        }

        $data = array(
            'current_page' => 'joined',
            'title' => 'Join Group',

            'user' => $user,
            'search_result' => $search_result
        );

        return view('joined.join_group', $data);
    }
}