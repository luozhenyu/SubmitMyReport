<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SiteMessage extends Notification implements ShouldQueue
{
    use Queueable;

    const sent = 1;
    const received = 2;

    /**
     * @var User
     */
    protected $from;

    /**
     * @var User
     */
    protected $to;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var string
     */
    protected $text;

    /**
     * Create a new notification instance.
     *
     * @param string $text
     * @param User $from
     * @param User $to
     * @param int $type
     */
    public function __construct(string $text, User $from, User $to, int $type)
    {
        $this->from = $from;
        $this->to = $to;
        $this->type = $type;
        $this->text = $text;
    }

    /**
     * @param string $text
     * @param User $from
     * @param User $to
     */
    public static function sendMessage(string $text, User $from, User $to)
    {
        $from->notifyNow(new SiteMessage($text, $from, $to, SiteMessage::sent));
        $to->notifyNow(new SiteMessage($text, $from, $to, SiteMessage::received));
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'from' => $this->from->id,
            'to' => $this->to->id,
            'type' => $this->type,
            'text' => $this->text,
        ];
    }
}
