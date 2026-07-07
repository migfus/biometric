<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\UploadedFile;

use App\Models\{
    Attachment,
    Employee,
    College,
    Department,
    Check
};

class HomeController extends Controller
{

    public function index(Request $request)
    {
        $uuid = $this->getUUID($request);

        $checks = Check::query()
            ->with(['attachments'])
            ->where('browser_id', $uuid)
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return Inertia::render('index', [
            'page_title' => 'Log',
            'checks' => $checks
        ]);
    }

    public function store(Request $req)
    {
        $val = $req->validate([
            'employee_no' => ['required', 'min:9'],
            'full_name' => ['required', 'min:8'],
            'college' => ['nullable'],
            'department' => ['required'],
            'check' => ['required'],
            'work_description' => ['required', 'min:12'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['mimes:jpg,jpeg,png', 'max:2048'],
            'client_os' => ['nullable'],
            'rephrase_count' => ['required', 'integer']
        ]);

        $check_in = $val['check'] === 'Check In' ? true : false;

        $department = Department::firstOrCreate(
            ['name' => $val['department']],
            ['name' => $val['department']],
        );

        $college = null;
        if ($val['college'])
        {
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
                'department_id' => $department->id,
            ]
        );

        $check = Check::create([
            'browser_id' => $this->getUUID($req),
            'ip_address' => $this->getClientIp(),
            'os' => $val['client_os'],

            'employee_id' => $employee->id,
            'check_in' => $check_in,
            'work_description' => $val['work_description'],
            'rephrase_count' => $val['rephrase_count']
        ]);

        foreach ($val['images'] as $item)
        {
            $this->uploadImage($item, $check->id);
        }

        return redirect('/')->with([
            'success' => ['success']
        ]);
    }

    protected function uploadImage(UploadedFile $file, int $checkId)
    {
        $uploadDir = public_path('attachments');

        if (!is_dir($uploadDir))
        {
            mkdir($uploadDir, 0755, true);
        }

        $filename = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
        $fileSize = $file->getSize();
        $file->move($uploadDir, $filename);

        $relativePath = 'attachments/' . $filename;

        return Attachment::create([
            'check_id' => $checkId,
            'file_location' => $relativePath,
            'file_size' => $fileSize,
            'preview_location' => url($relativePath),
        ]);
    }

    function getClientIp(): ?string
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        }

        return $_SERVER['REMOTE_ADDR'] ?? null;
    }

    function getUUID(Request $req)
    {
        $clientUuid = $req->cookie('client_uuid');

        if (! $clientUuid)
        {
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
