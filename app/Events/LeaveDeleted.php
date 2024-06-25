<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaveDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userLeaveId;

    /**
     * Create a new event instance.
     */
    public function __construct(int $userLeaveId)
    {
        $this->userLeaveId = $userLeaveId;
    }

    /**
     * The event's broadcast name.
    */
    public function broadcastAs(): string
    {
        return 'leave-deleted-event';
    }

    /**
     * Get the data to broadcast.
     * 
     * @return array<string, int>
     */
    public function broadcastWith(): array
    {
        return [
            'leaveId' => $this->userLeaveId,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('leave-deleted');
    }
}
