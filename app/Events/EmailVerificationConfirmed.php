<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationConfirmed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * The event's broadcast name.
    */
    public function broadcastAs(): string
    {
        return 'email-verification-confirmed-event';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'user' => $this->user
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array|string
     */
    public function broadcastOn()
    {
        return new Channel('email-verification-confirmed.' . $this->user->email);
    }
}
