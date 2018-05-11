<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Group;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $assignmentId)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Assignment $assignment */
        $assignment = Assignment::findOrFail($assignmentId);

        /** @var Group $group */
        $group = $user->managedGroups()->find($assignment->group_id);
        abort_if(empty($group), 403);

        $query = $assignment->submissions()
            ->leftJoin('marks', 'submissions.id', 'marks.submission_id')
            ->select('submissions.*');
        if ($wd = $request->input('wd')) {
            $parsedWord = str_replace(['%', '_'], ['\%', '\_'], $wd);
            $parsedWord = str_replace('*', '%', $parsedWord);
            $query->whereHas('owner', function (Builder $query) use ($parsedWord) {
                $query->where('name', 'like', "%{$parsedWord}%")
                    ->orWhere('student_id', 'like', "%{$parsedWord}%");
            });
        };

        $submissions = $query->orderByRaw('CAST(marks.id AS boolean) desc, submissions.updated_at desc')
            ->paginate(15);

        return view('submission.index', [
            'wd' => $wd,
            'group' => $group,
            'assignment' => $assignment,
            'submissions' => $submissions,
        ]);
    }

    public function create(Request $request, $assignmentId)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Assignment $assignment */
        $assignment = Assignment::findOrFail($assignmentId);

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

    public function store(Request $request, $assignmentId)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Assignment $assignment */
        $assignment = Assignment::findOrFail($assignmentId);

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
            'content' => clean($request->input('content')),
            'owner_id' => $user->id,
        ]);

        $submission->files()->sync($files);

        return redirect()->route('home');
    }

    public function show(Request $request, $submissionId)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Submission $submission */
        if (!$submission = $user->createdSubmissions()->find($submissionId)) {
            $submission = Submission::findOrFail($submissionId);
            $assignment = $submission->assignment;
            $group = $user->managedGroups()->find($assignment->group_id);
            $admin = true;
            abort_if(empty($group), 403);
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

    public function edit(Request $request, $submissionId)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Submission $submission */
        $submission = $user->createdSubmissions()->findOrFail($submissionId);

        //批改后不允许修改作业
        abort_if($submission->mark, 403);

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

    public function update(Request $request, $submissionId)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Submission $submission */
        $submission = $user->createdSubmissions()->findOrFail($submissionId);

        //批改后不允许修改作业
        abort_if($submission->mark, 403);

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
        $submission->touch();

        return redirect()->route('home');
    }

    public function mark(Request $request, $submissionId)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Submission $submission */
        $submission = Submission::findOrFail($submissionId);
        $assignment = $submission->assignment;
        $group = $user->managedGroups()->find($assignment->group_id);
        abort_if(empty($group), 403);

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

        $submission->mark()->updateOrCreate([
        ], [
            'owner_id' => $user->id,
            'average_score' => $scoreSum / $assignment->sub_problem,
            'data' => json_encode($json),
        ]);

        return redirect("/assignment/{$assignment->id}/submission");
    }

    public function unmark(Request $request, $submissionId)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Submission $submission */
        $submission = Submission::findOrFail($submissionId);
        $assignment = $submission->assignment;
        $group = $user->managedGroups()->find($assignment->group_id);
        abort_if(empty($group), 403);

        $submission->mark()->delete();

        return redirect("/assignment/{$assignment->id}/submission");
    }
}
