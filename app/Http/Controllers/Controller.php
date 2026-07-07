<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    public function getClientUUID(Request $req): string
    {
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
}
