<?php

namespace Tests\Feature;

use App\Notifications\Channels\AblyChannel;
use Illuminate\Notifications\ChannelManager;
use Tests\TestCase;

class AblyNotificationChannelTest extends TestCase
{
    public function test_ably_notification_driver_is_registered(): void
    {
        $driver = $this->app->make(ChannelManager::class)->driver('ably');

        $this->assertInstanceOf(AblyChannel::class, $driver);
    }
}
