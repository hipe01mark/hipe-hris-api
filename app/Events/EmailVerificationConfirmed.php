<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationConfirmed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $email;

    /**
     * Create a new event instance.
     */
    public function __construct(string $email)
    {
        $this->email = $email;
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
            'email' => $this->email
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array|string
     */
    public function broadcastOn()
    {
        return new Channel('email-verification-confirmed.' . $this->email);
    }
}
