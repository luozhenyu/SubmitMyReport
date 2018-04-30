<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\SiteMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;

class SiteMessageController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return $user->siteMessages()->groupBy(DB::raw("data->>'from'"))->select(DB::raw("data->>'from' as from"))
            ->get()->map(function (DatabaseNotification $item) use ($user) {
                /** @var User $from */
                $from = User::findOrFail($item->from);
                return [
                    'from' => $from->only('student_id', 'name'),
                    'count' => $user->unreadReceivedSiteMessages($from)->count(),
                ];
            });
    }

    public function show(Request $request, $studentId)
    {
        /** @var User $user */
        $user = $request->user();
        $theOther = User::where('student_id', $studentId)->firstOrFail();

        $user->siteMessages($theOther)->update(['read_at' => now()]);

        return $user->siteMessages($theOther)->get()->map(function (DatabaseNotification $item) {
            /** @var Carbon $created_at */
            if (($created_at = $item->created_at) > Carbon::now()->subHour(1)) {
                $created_at = $created_at->diffForHumans();
            } else {
                $created_at = $created_at->toDateTimeString();
            }

            return [
                'id' => $item->id,
                'created_at' => $created_at,
                'data' => [
                    'type' => $item->data['type'],
                    'text' => $item->data['text'],
                ],
            ];
        });
    }

    public function put(Request $request, $studentId)
    {
        /** @var User $user */
        $user = $request->user();
        $theOther = User::where('student_id', $studentId)->firstOrFail();

        $this->validate($request, [
            'text' => 'required|max:255',
        ]);
        SiteMessage::sendMessage($request->input('text'), $user, $theOther);

        return [
            'message' => 'ack',
        ];
    }

    public function delete(Request $request, $studentId)
    {
        /** @var User $user */
        $user = $request->user();
        $theOther = User::where('student_id', $studentId)->firstOrFail();

        $user->siteMessages($theOther)->delete();
        return [
            'message' => 'ack',
        ];
    }
}
