<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ControllerCacheTtlTest extends TestCase
{
    public function test_biometric_devices_cache_uses_ten_seconds_in_local_environment(): void
    {
        config(['app.env' => 'local']);

        $controller = new class extends Controller {};

        $captured_ttl = null;

        Cache::shouldReceive('get')
            ->once()
            ->with('biometric_devices')
            ->andReturn(null);

        Cache::shouldReceive('remember')
            ->once()
            ->withArgs(function (string $key, $ttl, callable $callback) use (&$captured_ttl): bool {
                $captured_ttl = $ttl;

                return $key === 'biometric_devices' && is_callable($callback);
            })
            ->andReturn(new EloquentCollection());

        $controller->getCachedBiometricDevices();

        $this->assertInstanceOf(Carbon::class, $captured_ttl);
        $this->assertEqualsWithDelta(10, now()->diffInSeconds($captured_ttl, false), 1.0);
    }

    public function test_invalid_cached_report_types_value_is_forgotten_and_rebuilt(): void
    {
        $controller = new class extends Controller {};

        Cache::shouldReceive('get')
            ->once()
            ->with('report_types')
            ->andReturn((object) ['invalid' => true]);

        Cache::shouldReceive('forget')
            ->once()
            ->with('report_types')
            ->andReturnTrue();

        Cache::shouldReceive('remember')
            ->once()
            ->withArgs(function (string $key, $ttl, callable $callback): bool {
                return $key === 'report_types' && is_callable($callback);
            })
            ->andReturn(new EloquentCollection);

        $result = $controller->getCachedReportTypes();

        $this->assertInstanceOf(EloquentCollection::class, $result);
    }
}
