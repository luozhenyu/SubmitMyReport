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

    public function create (Request $request)
    {
        $user = $request->user();

        $this->validate($request, [
            'name' => 'required|unique:groups|max:255',
            'description' => 'required',
        ]);

        $group = new Group;
        $group->name = $request->input('name');
        $group->description = $request->input('description');
        $group->user_id = $user->id;
        $group->save();

        $user->managedGroups()->attach($group->id, ['is_admin' => 1]);

        return redirect()->back();
    }

    public function join (Request $request) {
        $user = $request->user();
        $group_id = $request->input('group_id');

        $user->joinedGroups()->attach($group_id);

        return redirect('/joined?current_group='.$group_id);
    }

    public function quit (Request $request) {
        $user = $request->user();
        $group_id = $request->input('group_id');

        $user->joinedGroups()->detach($group_id);

        return redirect('/joined');
    }

    public function remove (Request $request) {
        $user = $request->user();
        $group_id = $request->input('group_id');
        $user_id = $request->input('user_id');

        DB::table('group_user')
            ->where('group_id', $group_id)
            ->where('user_id', $user_id)
            ->delete();

        return redirect('/manage?current_group='.$group_id);
    }

    public function destroy (Request $request) {
        $user = $request->user();
        $group_id = $request->input('group_id');

        DB::table('groups')->where('id', '=', $group_id)->delete();
        DB::table('group_user')->where('group_id', '=', $group_id)->delete();

        return redirect('/manage');
    }

    public function resign (Request $request) {
        $user = $request->user();
        $group_id = $request->input('group_id');

        DB::table('group_user')
            ->where('group_id', $group_id)
            ->where('user_id', $user->id)
            ->update(['is_admin' => 0]);

        return redirect('/manage');
    }

    public function appoint (Request $request) {
        $user = $request->user();
        $group_id = $request->input('group_id');
        $user_id = $request->input('user_id');

        DB::table('group_user')
            ->where('group_id', $group_id)
            ->where('user_id', $user_id)
            ->update(['is_admin' => 1]);

        return redirect('/manage?current_group='.$group_id);
    }
    public function fire (Request $request) {
        $user = $request->user();
        $group_id = $request->input('group_id');
        $user_id = $request->input('user_id');

        DB::table('group_user')
            ->where('group_id', $group_id)
            ->where('user_id', $user_id)
            ->update(['is_admin' => 0]);

        return redirect('/manage?current_group='.$group_id);
    }

}
