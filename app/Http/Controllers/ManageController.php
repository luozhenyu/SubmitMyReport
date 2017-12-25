<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Group;

class ManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request) {
        $user = $request->user();

        $groups = $user->managedGroups;
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

        if ($group) {
            if ($group->user->id == $user->id)
                $user->is_creator = true;
        }

        $assignments = $group ? $group->assignments:[];

        $members = $group ? $group->member:[];
        $admins = $group ? $group->admin:[];

        for ($i = 0; $i < count($members); $i++) {
            $is_admin = false;
            foreach ($admins as $admin) {
                if ($admin->id == $members[$i]->id) {
                    $is_admin = true;
                    break;
                }
            }
            $members[$i]->is_admin = $is_admin;
            if ($members[$i]->id == $group->user->id)
                $members[$i]->is_creator = true;
        }

        $data = array(
            'current_page' => 'manage',
            'title' => "Manage",

            'groups' => $groups,
            'current_group' => $current_group,
            'user' => $user,

            'assignments' => $assignments,

            'members' => $members,
            'admins' => $admins,

            'group' => $group
        );
        return view('manage.manage', $data);
    }
}
