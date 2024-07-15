<?php

use App\Broadcasting\UserChannel;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('reset-password-confirmed.{email}', UserChannel::class);
Broadcast::channel('email-verification-confirmed.{email}', UserChannel::class);
Broadcast::channel('attendance-updated', UserChannel::class);
Broadcast::channel('leave-updated', UserChannel::class);
Broadcast::channel('leave-deleted', UserChannel::class);
