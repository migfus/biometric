<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

use App\Models\User;
use Auth;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string {
        return parent::version($request);
    }

    public function share(Request $request): array {
        return [
            ...parent::share($request),

            'flash' => [
                'error' => fn () => $request->session()->get('error'),
                'success' => fn () => $request->session()->get('success'),
            ],

            'auth' => function () {
                if (Auth::check()) {
                    return User::where('id', Auth::user()->id)->select('id', 'name', 'email', 'avatar')->first();
                } else
                    return null;
            },
        ];
    }
}
