<?php

namespace Tests\Feature;

use App\Models\Check;
use App\Models\Employee;
use App\Models\Office;
use App\Models\User;
use App\Notifications\GuestCheckSubmittedNotification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckVerificationNotificationCleanupTest extends TestCase
{
    use RefreshDatabase;

    public function test_verifying_a_check_removes_matching_notifications_for_other_users_only(): void
    {
        /** @var User&Authenticatable $updater */
        $updater = User::factory()->create();
        /** @var User $otherUser */
        $otherUser = User::factory()->create();
        /** @var User $thirdUser */
        $thirdUser = User::factory()->create();

        $office = Office::create(['name' => 'IT Office']);
        $employee = Employee::create([
            'id' => '33',
            'full_name' => 'Guest Employee',
            'office_id' => $office->id,
        ]);

        $check = Check::create([
            'browser_id' => '123e4567-e89b-12d3-a456-426614174000',
            'ip_address' => '127.0.0.1',
            'os' => 'Windows',
            'employee_id' => $employee->id,
            'check_in' => true,
            'work_description' => 'Verify should remove stale notifications for other users.',
            'rephrase_count' => 0,
        ]);

        $matchingNotification = new GuestCheckSubmittedNotification($check->id, [
            'employee_no' => $employee->id,
            'full_name' => $employee->full_name,
            'office' => $office->name,
            'check' => 'Check In',
        ]);

        $differentNotification = new GuestCheckSubmittedNotification($check->id + 1, [
            'employee_no' => $employee->id,
            'full_name' => $employee->full_name,
            'office' => $office->name,
            'check' => 'Check Out',
        ]);

        $updater->notify($matchingNotification);
        $otherUser->notify($matchingNotification);
        $otherUser->notify($differentNotification);
        $thirdUser->notify($matchingNotification);

        $response = $this
            ->actingAs($updater)
            ->from('/dashboard/checks')
            ->patch(route('dashboard.checks.update', $check->id), [
                'type' => 'verify',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/dashboard/checks');

        $this->assertSame($updater->id, $check->fresh()->verified_user_id);
        $this->assertSame(1, $updater->notifications()->count());
        $this->assertSame(1, $otherUser->notifications()->count());
        $this->assertSame(0, $thirdUser->notifications()->count());

        $this->assertSame($check->id + 1, $otherUser->notifications()->first()->data['check_id']);
        $this->assertSame($check->id, $updater->notifications()->first()->data['check_id']);
        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => $otherUser->id,
            'notifiable_type' => User::class,
            'type' => GuestCheckSubmittedNotification::class,
            'data' => json_encode($matchingNotification->toArray($otherUser)),
        ]);
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $updater->id,
            'notifiable_type' => User::class,
            'type' => GuestCheckSubmittedNotification::class,
            'data' => json_encode($matchingNotification->toArray($updater)),
        ]);
        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => $thirdUser->id,
            'notifiable_type' => User::class,
            'type' => GuestCheckSubmittedNotification::class,
            'data' => json_encode($matchingNotification->toArray($thirdUser)),
        ]);
    }
}
