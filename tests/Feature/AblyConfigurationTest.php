<?php

namespace Tests\Feature;

use Ably\AblyRest;
use Tests\TestCase;

class AblyConfigurationTest extends TestCase
{
    public function testAblyConfigurationIsLoaded(): void
    {
        $config = config('ably');

        $this->assertIsArray($config);
        $this->assertArrayHasKey('key', $config);
        $this->assertArrayHasKey('client_id', $config);
        $this->assertArrayHasKey('options', $config);
    }

    public function testAblyClientCanBeInstantiated(): void
    {
        $apiKey = config('ably.key');

        if (!$apiKey) {
            $this->markTestSkipped('ABLY_API_KEY is not configured');
        }

        $ably = new AblyRest($apiKey);

        $this->assertInstanceOf(AblyRest::class, $ably);
    }

    public function testAblyEnvironmentVariablesAreAvailable(): void
    {
        $this->assertTrue(
            !empty(config('ably.key')) || env('ABLY_API_KEY'),
            'ABLY_API_KEY should be configured in .env or config'
        );
    }
}
