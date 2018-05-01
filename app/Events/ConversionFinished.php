<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversionFinished implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $success;
    private $toWhom;

    /**
     * Create a new event instance.
     *
     * @param bool $success
     * @param User $toWhom
     */
    public function __construct(bool $success, User $toWhom)
    {
        $this->success = $success;

        $this->toWhom = $toWhom;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PrivateChannel
     */
    public function broadcastOn()
    {
        return new PrivateChannel("user.{$this->toWhom->id}");
    }

    /**
     * 事件的广播名称.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'conversion.finished';
    }
}
