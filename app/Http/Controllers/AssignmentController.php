<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $group_id)
    {
        /** @var Group $group */
        $group = $request->user()
            ->managedGroups()->findOrFail($group_id);

        /** @var Assignment $assignments */
        $assignments = $group->assignments()
            ->orderByDesc('updated_at')
            ->paginate(6);

        return view('assignment.index', [
            'group' => $group,
            'assignments' => $assignments,
        ]);
    }

    public function create(Request $request, $group_id)
    {
        /** @var Group $group */
        $group = $request->user()
            ->managedGroups()->findOrFail($group_id);

        return view('assignment.create', [
            'group' => $group,
        ]);
    }

    public function store(Request $request, $group_id)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Group $group */
        $group = $user->managedGroups()->findOrFail($group_id);

        $this->validate($request, [
            'title' => 'required|max:40|unique:assignments',
            'deadline' => 'required|date|after:now',
            'description' => 'required|max:10000',
            'sub_problem' => 'required|between:1,10',
            'attachment.*' => 'nullable|exists:files,random',
        ]);

        $attachments = (array)$request->input('attachment');
        $files = $user->files()->whereIn('random', $attachments)->get();

        /** @var Assignment $assignment */
        $assignment = $group->assignments()->create([
            'title' => $request->input('title'),
            'deadline' => $request->input('deadline'),
            'description' => clean($request->input('description')),
            'sub_problem' => $request->input('sub_problem'),
            'owner_id' => $user->id,
        ]);
        $assignment->files()->sync($files);

        return redirect("/group/{$group_id}");
    }

    public function show(Request $request, $assignment_id)
    {
        /** @var User $user */
        $user = $request->user();

        $assignment = Assignment::findOrFail($assignment_id);

        /** @var Group $group */
        $group = $user->joinedGroups()->where('group_id', $assignment->group_id)->first();

        return view('assignment.show', [
            'group' => $group,
            'assignment' => $assignment,
        ]);
    }

    public function edit(Request $request, $assignment_id)
    {
        /** @var User $user */
        $user = $request->user();

        $assignment = Assignment::findOrFail($assignment_id);

        /** @var Group $group */
        $group = $user->managedGroups()->where('group_id', $assignment->group_id)->first();
        abort_if(empty($group), 403);

        return view('assignment.edit', [
            'group' => $group,
            'assignment' => $assignment,
        ]);
    }

    public function update(Request $request, $assignment_id)
    {
        /** @var User $user */
        $user = $request->user();

        $assignment = Assignment::findOrFail($assignment_id);

        /** @var Group $group */
        $group = $user->managedGroups()->where('group_id', $assignment->group_id)->first();
        abort_if(empty($group), 403);

        $this->validate($request, [
            'title' => [
                'required',
                'max:40',
                Rule::unique('assignments')->ignore($assignment->id),
            ],
            'deadline' => 'required|date|after:now',
            'description' => 'required|max:10000',
            'sub_problem' => 'required|between:1,10',
            'attachment.*' => 'nullable|exists:files,random',
        ]);

        $attachments = (array)$request->input('attachment');
        $files = $user->files()->whereIn('random', $attachments)->get();

        /** @var Assignment $assignment */
        $assignment->update([
            'title' => $request->input('title'),
            'deadline' => $request->input('deadline'),
            'description' => clean($request->input('description')),
            'sub_problem' => $request->input('sub_problem'),
        ]);
        $assignment->files()->sync($files);

        $assignment->save();

        return redirect("/assignment/{$assignment_id}/show");
    }
}
