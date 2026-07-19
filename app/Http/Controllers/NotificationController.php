<?php

namespace App\Http\Controllers;

use App\Services\NotificationDispatchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request, NotificationDispatchService $dispatcher): View
    {
        $dispatcher->syncFor($request->user());

        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request, string $notification): RedirectResponse
    {
        $item = $this->findOwnedNotification($request, $notification);
        $item->markAsRead();

        return back()->with('success', __('Notification marked as read.'));
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('success', __('All notifications marked as read.'));
    }

    private function findOwnedNotification(Request $request, string $id): DatabaseNotification
    {
        return $request->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();
    }
}
