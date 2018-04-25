<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Group;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $assignment_id)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Assignment $assignment */
        $assignment = Assignment::findOrFail($assignment_id);

        /** @var Group $group */
        $group = $user->managedGroups()->find($assignment->group_id);
        abort_if(empty($group), 403);

        $submissions = $assignment->submissions()
            ->orderByDesc('mark_user_id')
            ->paginate(15);

        return view('submission.index', [
            'group' => $group,
            'assignment' => $assignment,
            'submissions' => $submissions,
        ]);
    }

    public function create(Request $request, $assignment_id)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Assignment $assignment */
        $assignment = Assignment::findOrFail($assignment_id);

        //检查是否是当前小组的成员
        /** @var Group $group */
        $group = $user->joinedGroups()->find($assignment->group_id);
        abort_if(empty($group), 403);

        //不允许重复提交
        abort_if($assignment->loginSubmissions()->count() > 0, 403);

        return view('submission.create', [
            'group' => $group,
            'assignment' => $assignment,
        ]);
    }

    public function store(Request $request, $assignment_id)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Assignment $assignment */
        $assignment = Assignment::findOrFail($assignment_id);

        //检查是否是当前小组的成员
        /** @var Group $group */
        $group = $user->joinedGroups()->find($assignment->group_id);
        abort_if(empty($group), 403);

        //不允许重复提交
        abort_if($assignment->loginSubmissions()->count() > 0, 403);

        $this->validate($request, [
            'content' => 'nullable|max:65535',
            'attachment.*' => 'nullable|exists:files,random',
        ]);

        $attachments = (array)$request->input('attachment');
        $files = $user->files()->whereIn('random', $attachments)->get();

        /** @var Submission $submission */
        $submission = $assignment->submissions()->create([
            'content' => $request->input('content'),
            'owner_id' => $user->id,
        ]);

        $submission->files()->sync($files);

        return redirect()->route('home');
    }

    public function show(Request $request, $submission_id)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Submission $submission */
        if (!$submission = $user->createdSubmissions()->find($submission_id)) {
            $submission = Submission::findOrFail($submission_id);
            $assignment = $submission->assignment;
            $group = $user->managedGroups()->find($assignment->group_id);
            $admin = true;
            abort_unless((bool)$group, 403);
        } else {
            $assignment = $submission->assignment;
            $group = $assignment->group;
            $admin = false;
        }

        return view('submission.show', [
            'group' => $group,
            'assignment' => $assignment,
            'submission' => $submission,
            'admin' => $admin,
        ]);
    }

    public function edit(Request $request, $submission_id)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Submission $submission */
        $submission = $user->createdSubmissions()->findOrFail($submission_id);

        //批改后不允许修改作业
        abort_if($submission->corrected(), 403);

        /** @var Assignment $assignment */
        $assignment = $submission->assignment;

        /** @var Group $group */
        $group = $assignment->group;

        return view('submission.edit', [
            'submission' => $submission,
            'assignment' => $assignment,
            'group' => $group,
        ]);
    }

    public function update(Request $request, $submission_id)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Submission $submission */
        $submission = $user->createdSubmissions()->findOrFail($submission_id);

        //批改后不允许修改作业
        abort_if($submission->corrected(), 403);

        $this->validate($request, [
            'content' => 'nullable|max:65535',
            'attachment.*' => 'nullable|exists:files,random',
        ]);

        $attachments = (array)$request->input('attachment');
        $files = $user->files()->whereIn('random', $attachments)->get();

        /** @var Submission $submission */
        $submission->update([
            'content' => $request->input('content'),
            'owner_id' => $user->id,
        ]);

        $submission->files()->sync($files);

        return redirect()->route('home');
    }

    public function mark(Request $request, $submission_id)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Submission $submission */
        $submission = Submission::findOrFail($submission_id);
        $assignment = $submission->assignment;
        $group = $user->managedGroups()->find($assignment->group_id);
        abort_unless((bool)$group, 403);

        $this->validate($request, [
            'score' => "required|array|size:{$assignment->sub_problem}",
            'remark' => "required|array|size:{$assignment->sub_problem}",
            'score.*' => "required|integer|between:0,100",
            'remark.*' => "nullable|max:255",
        ]);

        $score = $request->input('score');
        $remark = $request->input('remark');

        $json = [];
        $scoreSum = 0.;
        for ($i = 0; $i < $assignment->sub_problem; $i++) {
            $scoreInteger = intval($score[$i]);
            $scoreSum += $scoreInteger;
            $json[] = [
                'score' => $scoreInteger,
                'remark' => $remark[$i],
            ];
        }

        $submission->mark_user()->associate($user);
        $submission->mark = json_encode($json);
        $submission->average_score = $scoreSum / $assignment->sub_problem;
        $submission->save();

        return redirect("/assignment/{$assignment->id}/submission");
    }
}
