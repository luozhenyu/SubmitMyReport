<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;

class PostController extends Controller
{
    public function show (Request $request) {
        $user = $request->user();

        $group_id = $request->input('group_id');

        $group = Group::find($group_id);

        $data = array(
            'current_page' => 'manage',
            'title' => "post Assignment",

            'user' => $user,

            'group' => $group
        );
        return view('post.post', $data);
    }
}
