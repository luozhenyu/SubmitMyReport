<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Group;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $id)
    {
        $group = $request->user()->managedGroups()->findOrFail($id);
        $assignments = $group->assignments()->paginate(15);

        return view('assignment.index', [
            'group' => $group,
            'assignments' => $assignments,
        ]);
    }

    public function create(Request $request, $id)
    {
        $group = $request->user()->managedGroups()->findOrFail($id);
        return view('assignment.create', [
            'group' => $group,
        ]);
    }

    public function store(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:40|unique:assignments',
            'description' => 'required|max:200',
        ]);

        $user = $request->user();
        $group = $user->managedGroups()->findOrFail($id);

        $group->assignments()->create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'user_id' => $user->id,
        ]);

        return redirect("group/{$id}");
    }

    public function show(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);
        abort_unless($group = $request->user()->joinedGroups()->find($assignment->group->id), 403);

        return view('assignment.show', [
            'group' => $group,
            'assignment' => $assignment,
        ]);
    }

    public function finish(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);
        $user = $request->user();
        abort_unless($group = $user->joinedGroups()->find($assignment->group->id), 403);

        $this->validate($request, [
            'content' => 'required|max:65535',
        ]);

        $assignment->submissions()->create([
            'content' => $request->input('content'),
            'user_id' => $user->id,
        ]);

        return redirect()->route('home');
    }
}
