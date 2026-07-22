<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    public function index(Request $req): Response
    {
        $user = $req->user();
        $search = trim((string) $req->input('search', ''));

        $active_notifications_query = $user->notifications()
            ->where('read_at', null)
            ->orderBy('created_at', 'DESC');

        $read_notifications_query = $user->readNotifications()
            ->whereNotNull('read_at')
            ->orderBy('created_at', 'DESC');

        if ($search !== '') {
            $search = mb_strtolower($search);

            $active_notifications_query->where(function ($query) use ($search): void {
                $query->whereRaw('LOWER(json_extract(data, "$.title")) LIKE ?', ['%'.$search.'%'])
                    ->orWhereRaw('LOWER(json_extract(data, "$.content")) LIKE ?', ['%'.$search.'%'])
                    ->orWhere('data', 'LIKE', '%'.$search.'%');
            });

            $read_notifications_query->where(function ($query) use ($search): void {
                $query->whereRaw('LOWER(json_extract(data, "$.title")) LIKE ?', ['%'.$search.'%'])
                    ->orWhereRaw('LOWER(json_extract(data, "$.content")) LIKE ?', ['%'.$search.'%'])
                    ->orWhere('data', 'LIKE', '%'.$search.'%');
            });
        }

        $active_notifications = $active_notifications_query->paginate(20);
        $read_notifications = $read_notifications_query->paginate(20);

        return Inertia::render('dashboard/notifications/index', [
            'page_title' => 'Notifications',
            'navigation' => 'sidebar',
            'active_notifications' => $active_notifications,
            'read_notifications' => $read_notifications,
        ]);
    }

    public function update(Request $req, string $notification_id): RedirectResponse
    {
        $user = $req->user();

        if ($notification_id === '0') {
            $user->unreadNotifications->markAsRead();

            return back()
                ->with('success', ['content' => 'All notifications marked as read.']);
        } else {
            $notification = $user->notifications()
                ->where('id', $notification_id)
                ->firstOrFail();

            if ($notification->read_at === null) {
                $notification->markAsRead();
            } else {
                $notification->update(['read_at' => null]);
            }

            return back()
                ->with('success', ['content' => 'Notification marked as read.']);
        }
    }

    public function destroy(Request $req, string $notification_id): RedirectResponse
    {
        $user = $req->user();

        $notification = $user->notifications()
            ->where('id', $notification_id)
            ->firstOrFail();

        $notification->delete();

        return back()
            ->with('success', ['content' => 'Notification deleted.']);
    }
}
