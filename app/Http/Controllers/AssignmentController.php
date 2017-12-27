<?php

namespace App\Http\Controllers;

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
}
