<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ably\AblyRest;

class AblyController extends Controller
{
    public function store(Request $request)
    {
        $ably = new AblyRest([
            'key' => env('ABLY_API_KEY'),
        ]);

        return response()->json(
            $ably->auth->createTokenRequest([
                'clientId' => $this->getClientUUID($request),
            ])
        );
    }
}
