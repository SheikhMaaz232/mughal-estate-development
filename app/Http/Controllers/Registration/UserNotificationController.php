<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserNotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('registration.notifications.index', compact('notifications'));
    }

    public function open(Request $request, string $notification): RedirectResponse
    {
        $userNotification = $request->user()
            ->notifications()
            ->whereKey($notification)
            ->firstOrFail();

        $userNotification->markAsRead();

        $target = $userNotification->data['url'] ?? null;
        $path = is_string($target) ? parse_url($target, PHP_URL_PATH) : null;
        $query = is_string($target) ? parse_url($target, PHP_URL_QUERY) : null;

        if (!is_string($path) || !str_starts_with($path, '/') || str_starts_with($path, '//')) {
            return redirect()->route('notifications.index');
        }

        return redirect()->to($path . ($query ? '?' . $query : ''));
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return redirect()
            ->route('notifications.index')
            ->with('success', __('messages.notifications_marked_read'));
    }
}
