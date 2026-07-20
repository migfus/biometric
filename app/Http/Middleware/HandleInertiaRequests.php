<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            'flash' => [
                'error' => fn () => $request->session()->get('error'),
                'success' => fn () => $request->session()->get('success'),
            ],

            'auth' => function () {
                if (Auth::check()) {
                    return User::where('id', Auth::user()->id)->select('id', 'name', 'email', 'avatar')->first();
                } else {
                    return null;
                }
            },

            'notifications' => function () use ($request) {
                if (! $request->user()) {
                    return [];
                }

                return $request->user()
                    ->notifications()
                    ->where('read_at', null)
                    ->latest()
                    ->limit(5)
                    ->get()
                    ->map(function ($notification): array {
                        return [
                            'id' => $notification->id,
                            'title' => $notification->data['title'] ?? 'Notification',
                            'content' => $notification->data['content'] ?? '',
                            'href' => $notification->data['href'] ?? route('dashboard.checks.index'),
                            'read_at' => $notification->read_at?->toISOString(),
                            'created_at' => $notification->created_at?->toISOString(),
                        ];
                    })
                    ->values();
            },

            'unread_notifications_count' => function () use ($request): int {
                if (! $request->user()) {
                    return 0;
                }

                return $request->user()->unreadNotifications()->count();
            },
        ];
    }
}
