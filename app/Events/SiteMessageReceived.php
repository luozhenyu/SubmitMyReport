<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReceiveMessageBroadcastingEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageCount;

    protected $to;

    /**
     * Create a new event instance.
     *
     * @param int $messageCount
     * @param User $to
     */
    public function __construct(int $messageCount, User $to)
    {
        $this->messageCount = $messageCount;
        $this->to = $to;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PrivateChannel
     */
    public function broadcastOn()
    {
        return new PrivateChannel("user.{$this->to->id}");
    }

    /**
     * 事件的广播名称.
     *
     * @return string
     */
//    public function broadcastAs()
//    {
//        return 'message';
//    }

}