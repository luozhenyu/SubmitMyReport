<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Group;

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);
        abort_unless($group = $request->user()->managedGroups()->find($assignment->group->id), 403);

        $submissions = $assignment->submissions()->paginate(15);

        return view('submission.index', [
            'group' => $group,
            'assignment' => $assignment,
            'submissions' => $submissions,
        ]);
    }

    public function create(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);
        abort_unless($group = $request->user()->joinedGroups()->find($assignment->group->id), 403);

        return view('submission.create', [
            'group' => $group,
            'assignment' => $assignment,
        ]);
    }

    public function store(Request $request, $id)
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

    public function show(Request $request, $id)
    {
        $submission = Submission::findOrFail($id);
        $assignment = $submission->assignment;
        abort_unless($group = $request->user()->joinedGroups()->find($assignment->group->id), 403);

        return view('submission.show', [
            'group' => $group,
            'assignment' => $assignment,
            'submission' => $submission,
            'author' => $submission->user,
        ]);
    }

    public function mark(Request $request, $id)
    {
        $this->validate($request, [
            'score' => 'required|numeric|between:0,100',
            'remark' => 'nullable|max:65536',
        ]);

        $submission = Submission::findOrFail($id);
        $assignment = $submission->assignment;
        abort_unless($group = $request->user()->managedGroups()->find($assignment->group->id), 403);

        $submission->score = intval($request->input('score'));
        $submission->remark = $request->input('remark');
        $submission->save();

        return redirect()->back();
    }

    public function score(Request $request, $id)
    {
        $submission = Submission::findOrFail($id);
        $assignment = $submission->assignment;
        abort_unless($submission->user->id === $request->user()->id, 403);

        return view('submission.score', [
            'assignment' => $assignment,
            'submission' => $submission,
        ]);
    }
}
