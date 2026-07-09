<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, UploadedFile};
use Illuminate\Support\Str;

abstract class Controller
{
    public function getClientUUID(Request $req): string {
        $rawClientUuid = $req->cookie('client_uuid');

        if (! $rawClientUuid) {
            return '';
        }

        // Try to decrypt the cookie value if it was encrypted by Laravel, otherwise use raw value.
        try {
            $clientUuid = decrypt($rawClientUuid);
        }
        catch (\Throwable $e) {
            $clientUuid = $rawClientUuid;
        }

        return $clientUuid;
    }

    public function uploadAvatarImage(UploadedFile $file) : string {
        $uploadDir = public_path('avatars');

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move($uploadDir, $filename);

        return '/avatars/' . $filename;
    }
}
