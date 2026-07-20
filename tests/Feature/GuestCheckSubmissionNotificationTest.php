<?php

namespace Tests\Feature;

use App\Models\Attachment;
use App\Models\Check;
use App\Models\User;
use App\Notifications\GuestCheckSubmittedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class GuestCheckSubmissionNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_submission_stores_a_database_notification_for_each_registered_user(): void
    {
        $adminOne = User::factory()->create(['email' => 'admin-one@example.com']);
        $adminTwo = User::factory()->create(['email' => 'admin-two@example.com']);

        Notification::fake();

        $response = $this->post('/', [
            'employee_no' => '123456789',
            'full_name' => 'Guest Employee',
            'college' => 'Engineering',
            'office' => 'IT Office',
            'check' => 'Check In',
            'work_description' => 'Working on direct in-app notifications for admins.',
            'images' => [UploadedFile::fake()->image('capture.jpg')],
            'preview_images' => [UploadedFile::fake()->image('preview.jpg')],
            'client_os' => 'Windows',
            'rephrase_count' => 1,
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('checks', [
            'employee_id' => '123456789',
            'check_in' => true,
        ]);
        $this->assertSame(1, Check::count());
        $this->assertSame(1, Attachment::count());

        Notification::assertSentTo($adminOne, GuestCheckSubmittedNotification::class, function (GuestCheckSubmittedNotification $notification, array $channels) use ($adminOne): bool {
            return $channels === ['database']
                && $notification->check_id === 1
                && $notification->toArray($adminOne)['check_id'] === 1
                && $notification->toArray($adminOne)['href'] === '/dashboard/employees/123456789'
                && $notification->submission['employee_no'] === '123456789'
                && $notification->submission['check'] === 'Check In';
        });
        Notification::assertSentTo($adminTwo, GuestCheckSubmittedNotification::class, function (GuestCheckSubmittedNotification $notification, array $channels) use ($adminTwo): bool {
            return $channels === ['database']
                && $notification->check_id === 1
                && $notification->toArray($adminTwo)['check_id'] === 1
                && $notification->toArray($adminTwo)['href'] === '/dashboard/employees/123456789'
                && $notification->submission['employee_no'] === '123456789'
                && $notification->submission['check'] === 'Check In';
        });

        foreach (Attachment::all() as $attachment) {
            $this->deleteUploadedFile($attachment->file_location);
            $this->deleteUploadedFile($attachment->preview_location);
        }
    }

    private function deleteUploadedFile(string $path): void
    {
        $parsedPath = parse_url($path, PHP_URL_PATH) ?: $path;
        $fullPath = public_path(ltrim($parsedPath, '/'));

        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}
