<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    public function improve(Request $request)
    {
        $this->validate($request, [
            'advice' => 'required|max:255',
        ]);

        /** @var User $user */
        $from = $request->user();
        $to = User::findOrFail(1);

        $message = $request->input('advice');
        SiteMessageController::sendMessage($message, $from, $to);

        return [
            'message' => '反馈成功！我们诚挚的感谢您的意见',
        ];
    }
}
