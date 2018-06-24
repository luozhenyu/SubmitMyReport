<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\Request;

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
        $groups = $request->user()->joinedGroups()
            ->paginate(15);
        return view('group.index', [
            'groups' => $groups,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list(Request $request)
    {
        $query = Group::query();

        if ($wd = $request->input('wd')) {
            $parsedWord = str_replace(['%', '_'], ['\%', '\_'], $wd);
            $parsedWord = str_replace('*', '%', $parsedWord);
            $query = $query->where('name', 'like', "%{$parsedWord}%")
                ->orWhere('description', 'like', "%{$parsedWord}%");
        }
        $groups = $query->paginate(10);

        return view('group.list', [
            'wd' => $wd,
            'groups' => $groups,
        ]);
    }

    public function create()
    {
        return view('group.create');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $this->validate($request, [
            'name' => 'required|max:20|unique:groups',
            'description' => 'required|max:255',
        ]);

        /** @var Group $group */
        $group = $user->createdGroups()->create(
            $request->only(['name', 'description'])
        );

        $group->members()->attach($user, ['is_admin' => true]);
        return redirect()->route('group');
    }

    public function update(Request $request, $groupId)
    {
        /** @var User $user */
        $user = $request->user();

        $this->validate($request, [
            'description' => 'required|max:255',
        ]);

        /** @var Group $group */
        $group = $user->managedGroups()->findOrFail($groupId);

        $group->description = $request->input('description');
        $group->save();

        return 'ok';
    }

    public function showMembers($id)
    {
        /** @var Group $group */
        $group = Group::query()->findOrFail($id);

        $members = $group->members()->paginate(15);
        return view('group.member', [
            'group' => $group,
            'members' => $members,
            'memberOffset' => ($members->currentPage() - 1) * $members->perPage(),
        ]);
    }

    public function join(Request $request, $id)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Group $group */
        $group = Group::query()->findOrFail($id);

        $group->members()->syncWithoutDetaching($user->id);

        return 'ok';
    }

    public function quit(Request $request, $groupId)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Group $group */
        $group = $user->joinedGroups()->findOrFail($groupId);

        //创建者不允许退出
        abort_if($group->owner->id === $user->id, 403);

        $group->members()->detach($user);
        return 'ok';
    }

    public function toggleAdmin(Request $request, $groupId)
    {
        /** @var Group $group */
        $group = Group::query()->findOrFail($groupId);
        abort_unless($group->owner->id === $request->user()->id, 403);

        $userId = $request->input('user_id');
        /** @var User $user */
        $user = $group->members()->findOrFail($userId);

        /** @var Pivot $pivot */
        $pivot = $user->pivot;
        $pivot->is_admin = !$pivot->is_admin;
        $pivot->save();

        return 'ok';
    }
}
