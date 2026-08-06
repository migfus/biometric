<?php

namespace App\Http\Controllers;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\{Request, UploadedFile};
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

use App\Models\{BiometricDevice, Office, ReportType, CheckStatus, EmploymentType};

abstract class Controller {
    protected int $cache_ttl = 60 * 60 * 24;

    protected function getCacheExpiration(): DateTimeInterface {
        $environment = (string) config('app.env');
        $cache_ttl = in_array($environment, ['local', 'development'], true) ? 10 : $this->cache_ttl;

        return now()->addSeconds($cache_ttl);
    }

    protected function getCachedCollection(string $cache_key, callable $callback): EloquentCollection {
        $cached = Cache::get($cache_key);

        if ($cached instanceof EloquentCollection) {
            return $cached;
        }

        if ($cached !== null) {
            Cache::forget($cache_key);
        }

        return Cache::remember($cache_key, $this->getCacheExpiration(), $callback);
    }

    public function getClientUUID(Request $req): string {
        $rawClientUuid = $req->cookie('client_uuid');

        if (! $rawClientUuid) {
            return '';
        }

        // Try to decrypt the cookie value if it was encrypted by Laravel, otherwise use raw value.
        try {
            $clientUuid = decrypt($rawClientUuid);
        } catch (\Throwable $e) {
            $clientUuid = $rawClientUuid;
        }

        return $clientUuid;
    }

    public function uploadAvatarImage(UploadedFile $file): string {
        $uploadDir = public_path('avatars');

        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = (string) Str::uuid().'.'.$file->getClientOriginalExtension();
        $file->move($uploadDir, $filename);

        return '/avatars/'.$filename;
    }

    public function getCachedBiometricDevices(): EloquentCollection {
        return $this->getCachedCollection('biometric_devices', fn () => BiometricDevice::all());
    }

    public function getCachedReportTypes(): EloquentCollection {
        return $this->getCachedCollection('report_types', fn () => ReportType::all());
    }

    public function getCachedCheckStatuses(): EloquentCollection {
        return $this->getCachedCollection('check_statuses', fn () => CheckStatus::all());
    }

    public function getCachedOffices(): EloquentCollection {
        return $this->getCachedCollection('offices', fn () => Office::all());
    }

    public function getCachedEmploymentTypes(): EloquentCollection {
        return $this->getCachedCollection('employment_types', fn () => EmploymentType::all());
    }
}
