<?php

namespace App\Http\Controllers\Profile;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UpdatePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function edit(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return view('profile.password', ['user' => $user]);
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        if ($this->checkCredentials($request)) {
            $this->validate($request, [
                'password' => 'required|string|between:6,32|confirmed',
            ]);

            $user->update([
                'password' => bcrypt($request->input('password')),
            ]);

            return view('profile.password', ['user' => $user, 'success' => true]);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    protected function checkCredentials(Request $request)
    {
        return $this->guard()->validate($this->credentials($request));
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return [
            'id' => $request->user()->id,
            'password' => $request->input('old_password'),
        ];
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'old_password' => [trans('auth.failed')],
        ]);
    }
}