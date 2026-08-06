<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\{Inertia, Response};
use Illuminate\Http\{Request, UploadedFile};
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

use App\Models\{Report, Attachment, Office, Employee};

class ReportController extends Controller
{
    public function index(Request $req) : Response {
        return Inertia::render('reports/index', [
            'page_title' => 'Your Reports',
            'reports' => Report::query()
                ->where('browser_id', $this->getUUID($req))
                ->with(['reportType', 'employee.office', 'checkStatus', 'biometricDevice.area'])
                ->orderBy('created_at', 'DESC')
                ->paginate(20),
        ]);
    }

    public function create(): Response {
        return Inertia::render('reports/create', [
            'page_title' => 'Report',
            'biometric_devices' => $this->getCachedBiometricDevices(),
            'report_types' => $this->getCachedReportTypes(),
            'check_statuses' => $this->getCachedCheckStatuses(),
            'offices' => $this->getCachedOffices(),
            'employment_types' => $this->getCachedEmploymentTypes(),
        ]);
    }

    public function store(Request $req): RedirectResponse  {
        $req->validate([
            'biometric_device_id' => ['required', 'exists:biometric_devices,id'],
            'report_type_id' => ['required', 'exists:report_types,id'],
            'check_status_id' => ['required', 'exists:check_statuses,id'],
            'employment_type_id' => ['required', 'exists:employment_types,id'],

            'full_name' => ['required', 'min:6'],
            'office' => ['nullable'],
            'description' => ['required', 'min:8'],
            'action_taken' => ['nullable', 'min:8'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'min:9'],

            'client_os' => ['nullable'],
            'rephrase_count' => ['required', 'integer'],

            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'preview_images' => ['nullable', 'array'],
            'preview_images.*' => ['file', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $office = null;
        if ($req->input('office')) {
            $office = Office::firstOrCreate(
                ['name' => $req->input('office')],
                ['name' => $req->input('office')],
            );
        }

        $employee = Employee::updateOrCreate(
            ['id' => $req->input('employee_id')],
            [
                'office_id' => $office?->id ?? null,
                'employment_type_id' => $req->input('employment_type_id'),
                'full_name' => $req->input('full_name'),
                'email' => $req->input('email'),
                'phone' => $req->input('phone'),
            ]
        );

        $report = Report::create([
            'employee_id' => $employee->id,
            'biometric_device_id' => $req->input('biometric_device_id'),
            'report_type_id' => $req->input('report_type_id'),
            'check_status_id' => $req->input('check_status_id'),
            'employment_type_id' => $req->input('employment_type_id'),

            'full_name' => $req->input('full_name'),
            'office' => $office ? $office->name : null,
            'description' => $req->input('description'),
            'action_taken' => $req->input('action_taken'),

            'browser_id' => $this->getUUID($req),
            'ip_address' => $req->ip(),
            'os' => $req->input('client_os'),
            'rephrase_count' => $req->input('rephrase_count', 0),
        ]);

        if ($req->file('images')) {
            foreach ($req->file('images') as $index => $item) {
                $this->uploadImage($item, $req->file('preview_images')[$index], $report);
            }
        }


        return to_route('reports.index')->with('success', 'Report submitted successfully.');
    }

    private function getUUID(Request $req): string {
        $clientUuid = $req->cookie('client_uuid');

        if (! $clientUuid) {
            $clientUuid = (string) Str::uuid();

            Cookie::queue(
                cookie(
                    name: 'client_uuid',
                    value: $clientUuid,
                    minutes: 60 * 24 * 365,
                    path: '/',
                    domain: null,
                    secure: app()->environment('production'),
                    httpOnly: true,
                    raw: false,
                    sameSite: 'lax',
                )
            );
        }

        return $clientUuid;
    }

    protected function uploadImage(UploadedFile $file, UploadedFile $previewFile, Report $report): Attachment {
        $uploadDir = public_path('attachments');

        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = (string) Str::uuid().'.'.$file->getClientOriginalExtension();
        $previewFilename = (string) Str::uuid().'.'.$previewFile->getClientOriginalExtension();
        $fileSize = $file->getSize();
        $file->move($uploadDir, $filename);
        $previewFile->move($uploadDir, $previewFilename);

        $relativePath = '/attachments/'.$filename;
        $previewRelativePath = '/attachments/'.$previewFilename;

        return $report->attachments()->create([
            'file_location' => $relativePath,
            'file_size' => $fileSize,
            'preview_location' => url($previewRelativePath),
        ]);
    }
}
