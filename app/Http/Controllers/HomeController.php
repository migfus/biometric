<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Check;
use App\Models\College;
use App\Models\Employee;
use App\Models\Office;
use App\Models\User;
use App\Notifications\GuestCheckSubmittedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('index', [
            'page_title' => 'Log',
        ]);
    }

    public function store(Request $req): RedirectResponse
    {
        $req->validate([
            'employee_no' => ['required', 'min:9'],
            'full_name' => ['required', 'min:8'],
            'college' => ['nullable'], // or deparment
            'office' => ['nullable'],
            'check' => ['required'],
            'work_description' => ['required', 'min:12'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['mimes:jpg,jpeg,png', 'max:2048'],
            'preview_images' => ['required', 'array', 'min:1'],
            'preview_images.*' => ['mimes:jpg,jpeg,png', 'max:2048'],
            'client_os' => ['nullable'],
            'rephrase_count' => ['required', 'integer'],
        ]);

        if (count($req->file('images')) !== count($req->file('preview_images'))) {
            abort(422, 'Preview images must match uploaded images.');
        }

        $check_in = $req->input('check') === 'Check In' ? 1 : 0;

        $college = null; // or department
        if ($req->input('college')) {
            $college = College::firstOrCreate(
                ['name' => $req->input('college')],
                ['name' => $req->input('college')],
            );
        }

        $office = null;
        if ($req->input('office')) {
            $office = Office::firstOrCreate(
                ['name' => $req->input('office')],
                ['name' => $req->input('office')],
            );
        }

        $employee = Employee::updateOrCreate(
            ['id' => $req->input('employee_no')],
            [
                'full_name' => $req->input('full_name'),
                'college_id' => $college?->id ?? null,
                'office_id' => $office?->id ?? null,
            ]
        );

        $check = Check::create([
            'browser_id' => $this->getUUID($req),
            'ip_address' => $this->getClientIp(),
            'os' => $req->input('client_os'),

            'employee_id' => $employee->id,
            'check_in' => $check_in,
            'work_description' => $req->input('work_description'),
            'rephrase_count' => $req->input('rephrase_count'),
        ]);

        foreach ($req->file('images') as $index => $item) {
            $this->uploadImage($item, $req->file('preview_images')[$index], $check);
        }

        $submission = [
            'employee_no' => $req->input('employee_no'),
            'full_name' => $req->input('full_name'),
            'college' => $req->input('college'),
            'office' => $req->input('office'),
            'check' => $req->input('check'),
            'work_description' => $req->input('work_description'),
            'rephrase_count' => $req->input('rephrase_count'),
        ];

        User::query()
            ->get()
            ->each(function (User $user) use ($check, $submission): void {
                $user->notify(new GuestCheckSubmittedNotification($check->id, $submission));
            });

        return to_route('records.index')
            ->with('success', [
                'content' => 'New check has been recorded.',
            ]);
    }

    protected function uploadImage(UploadedFile $file, UploadedFile $previewFile, Check $check): Attachment
    {
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

        return $check->attachments()->create([
            'file_location' => $relativePath,
            'file_size' => $fileSize,
            'preview_location' => url($previewRelativePath),
        ]);
    }

    public function getClientIp(): ?string
    {
        if (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        }

        return $_SERVER['REMOTE_ADDR'] ?? null;
    }

    public function getUUID(Request $req): string
    {
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
