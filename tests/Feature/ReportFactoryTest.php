<?php

namespace Tests\Feature;

use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_factory_can_create_report_with_requested_relations_loaded(): void
    {
        $report = Report::factory()->withRelations()->create();

        $this->assertNotNull($report->employee);
        $this->assertNotNull($report->checkStatus);
        $this->assertNotNull($report->reportType);
        $this->assertNotNull($report->biometricDevice);
    }
}
