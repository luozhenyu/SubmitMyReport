<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class SiteMessage extends Notification
{
    use Queueable;

    /**
     * @var User
     */
    protected $from;

    /**
     * @var string
     */
    protected $text;

    /**
     * Create a new notification instance.
     *
     * @param string $text
     * @param User $from
     */
    public function __construct(string $text, User $from = null)
    {
        $this->from = $from ? $from : Auth::user();
        $this->text = $text;
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
            'text' => $this->text,
        ];
    }
}
