<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class AblyChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toAbly')) {
            return;
        }

        $notification->toAbly($notifiable);
    }
}
