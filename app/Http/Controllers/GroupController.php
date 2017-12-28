<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Group;

class GroupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $groups = $request->user()->joinedGroups()->paginate(15);
        return view('group.index', [
            'groups' => $groups,
        ]);
    }

    public function all(Request $request)
    {
        $query = new Group;

        if ($request->has('wd')) {
            $wd = $request->input('wd');
            $wd = str_replace(['%', '_'], ['\%', '\_'], $wd);
            $query = $query->where('name', 'like', "%{$wd}%")
                ->orWhere('description', 'like', "%{$wd}%");
        } else {
            $wd = '';
        }
        $groups = $query->paginate(15);

        return view('group.all', [
            'wd' => $wd,
            'groups' => $groups,
        ]);
    }

    public function join(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $user = $request->user();
        $group->members()->syncWithoutDetaching($user->id);

        return 'ok';
    }

    public function quit(Request $request, $id)
    {
        $user = $request->user();
        $group = $user->joinedGroups()->findOrFail($id);

        $group->members()->detach($user);
        return 'ok';
    }

    public function create()
    {
        return view('group.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:20|unique:groups',
            'description' => 'required|max:200',
        ]);

        $user = $request->user();
        $group = $user->createdGroups()->create(
            $request->only(['name', 'description'])
        );

        $group->members()->attach($user, ['is_admin' => true]);

        return redirect()->route('group');
    }

    public function member($id)
    {
        $group = Group::findOrFail($id);
        $members = $group->members()->paginate(25);
        return view('group.member', [
            'group' => $group,
            'members' => $members,
            'offset' => ($members->currentPage() - 1) * $members->perPage(),
        ]);
    }

    public function toggleAdmin(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        abort_unless($group->user->id === $request->user()->id, 403);

        $user_id = $request->input('user_id');
        $user = $group->members()->findOrFail($user_id);

        $pivot = $user->pivot;
        $pivot->is_admin = !$pivot->is_admin;
        $pivot->save();

        return 'ok';
    }
}
