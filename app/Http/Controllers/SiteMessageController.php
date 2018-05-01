<?php

namespace App\Http\Controllers;

use App\Events\SiteMessageReceived;
use App\Models\User;
use App\Notifications\SiteMessage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;

class SiteMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function queryUser(Request $request)
    {
        $this->validate($request, [
            'q' => 'nullable|max:32',
        ]);

        /** @var Builder $query */
        $query = new User;
        if ($wd = $request->input('q')) {
            $wd = str_replace(['%', '_'], ['\%', '\_'], $wd);
            $query = $query->where('name', 'like', "%{$wd}%")
                ->orWhere('student_id', 'like', "%{$wd}%");
        }
        $users = $query->orderBy('student_id')
            ->paginate(6);

        return [
            "results" => array_map(function (User $user) {
                return [
                    'id' => $user->student_id,
                    'text' => $user->name,
                ];
            }, $users->items()),
            "pagination" => [
                "more" => $users->hasMorePages(),
            ]
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return $user->siteMessages()->groupBy(DB::raw("data->>'to'"))
            ->where(DB::raw("data->>'type'"), SiteMessage::sent)
            ->select(DB::raw("data->>'to' as \"theOther\""))
            ->union(
                $user->siteMessages()->groupBy(DB::raw("data->>'from'"))
                    ->where(DB::raw("data->>'type'"), SiteMessage::received)
                    ->select(DB::raw("data->>'from' as \"theOther\""))
            )->orderBy('theOther')->get()
            ->map(function (DatabaseNotification $item) use ($user) {
                /** @var User $from */
                $from = User::findOrFail($item->theOther);
                return [
                    'from' => $from->only('student_id', 'name'),
                    'count' => $user->unreadReceivedSiteMessages($from)->count(),
                ];
            });
    }

    /**
     * @param Request $request
     * @param $studentId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function show(Request $request, $studentId)
    {
        /** @var User $user */
        $user = $request->user();
        $theOther = User::where('student_id', $studentId)->firstOrFail();

        $user->siteMessages($theOther)->update(['read_at' => now()]);

        $collect = collect(array_reverse(
            $user->siteMessages($theOther)
                ->orderByDesc('created_at')
                ->paginate(30)->items()
        ));
        //TODO: javascript dynamic load

        return $collect->map(function (DatabaseNotification $item) {
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

    /**
     * @param Request $request
     * @param $studentId
     * @return array
     */
    public function put(Request $request, $studentId)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var User $theOther */
        $theOther = User::where('student_id', $studentId)->firstOrFail();

        $this->validate($request, [
            'text' => 'required|max:255',
        ]);
        SiteMessage::sendMessage($request->input('text'), $user, $theOther);

        $unread = $theOther->unreadReceivedSiteMessages()->count();

        event(new SiteMessageReceived($unread, $theOther));

        return [
            'message' => 'ack',
        ];
    }

    /**
     * @param Request $request
     * @param $studentId
     * @return array
     */
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
