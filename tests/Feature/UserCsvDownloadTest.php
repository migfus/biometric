<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCsvDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_download_users_as_csv(): void
    {
        /** @var User $authUser */
        $authUser = User::factory()->createOne();

        User::factory()->create([
            'name' => 'Alice Export',
            'email' => 'alice-export@example.com',
        ]);

        User::factory()->create([
            'name' => 'Bob Hidden',
            'email' => 'bob-hidden@example.com',
        ]);

        $response = $this->actingAs($authUser)->get(route('dashboard.users.print', [
            'search' => 'Alice',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $contentDisposition = (string) $response->headers->get('content-disposition');
        $this->assertStringContainsString('attachment;', $contentDisposition);
        $this->assertStringContainsString('.csv', $contentDisposition);

        $csv = $response->streamedContent();

        $this->assertStringContainsString('ID,Name,Email,"Created At"', $csv);
        $this->assertStringContainsString('Alice Export,alice-export@example.com', $csv);
        $this->assertStringNotContainsString('Bob Hidden,bob-hidden@example.com', $csv);
    }
}
