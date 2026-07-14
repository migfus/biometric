<?php

namespace App\Http\Controllers;

use App\Mail\SubmissionReceived;
use App\Models\Attachment;
use App\Models\Check;
use App\Models\College;
use App\Models\Employee;
use App\Models\Office;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(Request $request): Response {
        return Inertia::render('index', [
            'page_title' => 'Log',
        ]);
    }

    public function store(Request $req): RedirectResponse {
        $val = $req->validate([
            'employee_no' => ['required', 'min:9'],
            'full_name' => ['required', 'min:8'],
            'college' => ['nullable'], // or deparment
            'office' => ['required'],
            'check' => ['required'],
            'work_description' => ['required', 'min:12'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['mimes:jpg,jpeg,png', 'max:2048'],
            'preview_images' => ['required', 'array', 'min:1'],
            'preview_images.*' => ['mimes:jpg,jpeg,png', 'max:2048'],
            'client_os' => ['nullable'],
            'rephrase_count' => ['required', 'integer'],
        ]);

        if (count($val['images']) !== count($val['preview_images'])) {
            abort(422, 'Preview images must match uploaded images.');
        }

        $check_in = $val['check'] === 'Check In' ? 1 : 0;

        $office = Office::firstOrCreate(
            ['name' => $val['office']],
            ['name' => $val['office']],
        );

        $college = null; // or department
        if ($val['college']) {
            $college = College::firstOrCreate(
                ['name' => $val['college']],
                ['name' => $val['college']],
            );
        }

        $employee = Employee::updateOrCreate(
            ['id' => $val['employee_no']],
            [
                'full_name' => $val['full_name'],
                'college_id' => $college?->id,
                'office_id' => $office->id,
            ]
        );

        $check = Check::create([
            'browser_id' => $this->getUUID($req),
            'ip_address' => $this->getClientIp(),
            'os' => $val['client_os'],

            'employee_id' => $employee->id,
            'check_in' => $check_in,
            'work_description' => $val['work_description'],
            'rephrase_count' => $val['rephrase_count'],
        ]);

        foreach ($val['images'] as $index => $item) {
            $this->uploadImage($item, $val['preview_images'][$index], $check->id);
        }

        // if ($val['email']) {
        //     Mail::to($val['email'])->queue(new SubmissionReceived([
        //         'employee_no' => $val['employee_no'],
        //         'full_name' => $val['full_name'],
        //         'college' => $val['college'],
        //         'office' => $val['office'],
        //         'check' => $val['check'],
        //         'work_description' => $val['work_description'],
        //         'rephrase_count' => $val['rephrase_count'],
        //     ]));
        // }

        return to_route('records.index')
            ->with('success', [
                'title' => 'Successfuly submitted!',
                'content' => 'New check has been recorded.',
            ]);
    }

    protected function uploadImage(UploadedFile $file, UploadedFile $previewFile, int $checkId): Attachment {
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

        return Attachment::create([
            'check_id' => $checkId,
            'file_location' => $relativePath,
            'file_size' => $fileSize,
            'preview_location' => url($previewRelativePath),
        ]);
    }

    public function getClientIp(): ?string {
        if (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        }

        return $_SERVER['REMOTE_ADDR'] ?? null;
    }

    public function getUUID(Request $req): string {
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
}
