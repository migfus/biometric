<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, RedirectResponse};
use Inertia\{Inertia, Response};

class NotificationController extends Controller
{
    public function index(Request $req) : Response {
        $user = $req->user();

        $active_notifications = $user->notifications()
            ->where('read_at', null)
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return Inertia::render('dashboard/notifications/index', [
            'page_title' => 'Notifications',
            'navigation' => 'sidebar',
            'active_notifications' => $active_notifications,
            'read_notifications' => $user->readNotifications()->orderBy('created_at', 'DESC')->paginate(20),
        ]);
    }

    public function update(Request $req, string $notification_id) : RedirectResponse {
        $user = $req->user();

        if($notification_id === '0') {
            $user->unreadNotifications->markAsRead();
            return back()
                ->with('success', ['content' => 'All notifications marked as read.']);
        }
        else {
            $notification = $user->notifications()
                ->where('id', $notification_id)
                ->firstOrFail();

            if ($notification->read_at === null) {
                $notification->markAsRead();
            }
            else {
                $notification->update(['read_at' => null]);
            }
            return back()
                ->with('success', ['content' => 'Notification marked as read.']);
        }
    }

    public function destroy(Request $req, string $notification_id) : RedirectResponse {
        $user = $req->user();

        $notification = $user->notifications()
            ->where('id', $notification_id)
            ->firstOrFail();

        $notification->delete();

        return back()
            ->with('success', ['content' => 'Notification deleted.']);
    }
}
