<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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
        $user = $request->user();

        /** @var Collection $groupCollection */
        $groupCollection = $user->joinedGroups;

        if ((!$group = $request->input('group')) || (!$selectedGroup = $user->joinedGroups()->find($group))) {
            $selectedGroup = $groupCollection->first();
        }

        return view('index', [
            'selectedGroup' => $selectedGroup,
            'groups' => $groupCollection,
        ]);
    }
}
