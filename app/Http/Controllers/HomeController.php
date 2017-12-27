<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;

class HomeController extends Controller
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
        $groups = $request->user()->joinedGroups;

        if ($request->has('group')) {
            $group = $request->input('group');
            if (!$current_group = $request->user()->joinedGroups()->find($group)) {
                $current_group = $groups->first();
            }
        } else {
            $current_group = $groups->first();
        }

        return view('index', [
            'current_group' => $current_group,
            'groups' => $groups,
        ]);
    }
}
