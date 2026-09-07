<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Show the full notifications page.
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a single notification as read and redirect to the relevant case.
     */
    public function markRead(string $id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        // Redirect to the case page if data contains a case_id
        $caseId = $notification->data['case_id'] ?? null;
        if ($caseId) {
            return redirect()->route('cases.show', $caseId);
        }

        return redirect()->route('notifications.index');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->route('notifications.index')
            ->with('success', 'تم تعليم جميع الإشعارات كمقروءة');
    }
}
