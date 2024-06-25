<?php

namespace App\Events;

use App\Models\UserLeave;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaveUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userLeave;

    /**
     * Create a new event instance.
     */
    public function __construct(UserLeave $userLeave)
    {
        $this->userLeave = $userLeave;
    }

    /**
     * The event's broadcast name.
    */
    public function broadcastAs(): string
    {
        return 'leave-updated-event';
    }

    /**
     * Get the data to broadcast.
     * 
     * @return array<string, UserLeave>
     */
    public function broadcastWith(): array
    {
        return [
            'leave' => $this->userLeave,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('leave-updated');
    }
}
