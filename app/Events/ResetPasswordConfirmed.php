<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordConfirmed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $token;
    public $email;

    /**
     * Create a new event instance.
     */
    public function __construct(string $token, string $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * The event's broadcast name.
    */
    public function broadcastAs(): string
    {
        return 'reset-password-confirmed-event';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): ?array
    {
        return [
            'token' => $this->token,
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
        return new Channel('reset-password-confirmed.'.$this->email);
    }
}
