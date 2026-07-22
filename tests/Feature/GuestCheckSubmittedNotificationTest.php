<?php

namespace Tests\Feature;

use App\Notifications\GuestCheckSubmittedNotification;
use Tests\TestCase;
use stdClass;

class GuestCheckSubmittedNotificationTest extends TestCase
{
    private stdClass $notifiable;

    protected function setUp(): void
    {
        parent::setUp();
        $this->notifiable = new stdClass();
    }

    public function testNotificationIncludesAblyChannel(): void
    {
        $notification = new GuestCheckSubmittedNotification(
            check_id: 1,
            submission: [
                'check' => 'Check In',
                'full_name' => 'John Doe',
                'office' => 'NYC Office',
                'employee_no' => 'EMP001',
            ],
            browser_id: 'test-browser-123',
        );

        $channels = $notification->via($this->notifiable);

        $this->assertContains('database', $channels);
        $this->assertContains('ably', $channels);
    }

    public function testNotificationDataStructure(): void
    {
        $submission = [
            'check' => 'Check In',
            'full_name' => 'Jane Smith',
            'office' => 'San Francisco',
            'employee_no' => 'EMP002',
        ];

        $notification = new GuestCheckSubmittedNotification(
            check_id: 2,
            submission: $submission,
            browser_id: 'test-browser-456',
        );

        $data = $notification->toArray($this->notifiable);

        $this->assertEquals('Jane Smith', $data['title']);
        $this->assertStringContainsString('Check In', $data['content']);
        $this->assertStringContainsString('San Francisco', $data['content']);
        $this->assertEquals(2, $data['check_id']);
        $this->assertEquals('EMP002', $data['employee_no']);
    }

    public function testNotificationSkipsAblyIfNoBrowserId(): void
    {
        $notification = new GuestCheckSubmittedNotification(
            check_id: 1,
            submission: [
                'check' => 'Check In',
                'full_name' => 'John Doe',
                'office' => 'NYC Office',
                'employee_no' => 'EMP001',
            ],
        );

        // This should not throw an error even without a browser_id
        $notification->toAbly($this->notifiable);

        $this->assertTrue(true);
    }

    public function testNotificationHasOptionalBrowserId(): void
    {
        $notification = new GuestCheckSubmittedNotification(
            check_id: 1,
            submission: [
                'check' => 'Check In',
                'full_name' => 'John Doe',
                'office' => 'NYC Office',
                'employee_no' => 'EMP001',
            ],
        );

        $this->assertNull($notification->browser_id);
    }
}
