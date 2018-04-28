<?php

namespace App\Http\Controllers\Profile;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return view('profile.profile', ['user' => $user]);
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $this->validate($request, [
            'email' => "required|string|email|max:255|unique:users,email,{$user->id}",
            'name' => 'required|string|max:255',
        ]);

        $user->update([
            'email' => $request->input('email'),
            'name' => $request->input('name'),
        ]);

        return view('profile.profile', ['user' => $user, 'success' => true]);
    }
}