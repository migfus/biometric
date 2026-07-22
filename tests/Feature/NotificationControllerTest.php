<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_can_search_notification_json_payload(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $user->notifications()->create([
            'id' => '11111111-1111-1111-1111-111111111111',
            'type' => 'App\\Notifications\\GuestCheckSubmittedNotification',
            'data' => json_encode([
                'title' => 'Invoice overdue',
                'content' => 'Please review the invoice.',
            ]),
        ]);

        $user->notifications()->create([
            'id' => '22222222-2222-2222-2222-222222222222',
            'type' => 'App\\Notifications\\GuestCheckSubmittedNotification',
            'data' => json_encode([
                'title' => 'Weekly summary',
                'content' => 'Nothing urgent.',
            ]),
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.notifications.index', ['search' => 'overdue']));

        $response->assertOk();
        $response->assertJsonPath('props.active_notifications.total', 1);
        $response->assertJsonPath('props.active_notifications.data.0.data.title', 'Invoice overdue');
    }
}
