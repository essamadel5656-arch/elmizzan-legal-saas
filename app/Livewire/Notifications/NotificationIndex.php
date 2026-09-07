<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use Livewire\WithPagination;

class NotificationIndex extends Component
{
    use WithPagination;

    public string $filter = 'all'; // all, unread, read

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function markAsRead(string $id): void
    {
        if (!auth()->check()) {
            return;
        }

        $notification = auth()->user()->unreadNotifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
            $this->dispatch('notification-read');

            $caseId = $notification->data['case_id'] ?? null;
            if ($caseId) {
                $this->redirect(route('cases.show', $caseId), navigate: true);
            }
        }
    }

    public function markAllAsRead(): void
    {
        if (!auth()->check()) {
            return;
        }

        auth()->user()->unreadNotifications->markAsRead();
        $this->dispatch('notification-read');
        session()->flash('success', 'تم تعليم جميع الإشعارات كمقروءة بنجاح.');
    }

    public function render()
    {
        $user = auth()->user();

        $query = $user ? $user->notifications() : collect();

        if ($this->filter === 'unread' && $user) {
            $query = $user->unreadNotifications();
        } elseif ($this->filter === 'read' && $user) {
            $query = $user->readNotifications();
        }

        $notifications = $user ? $query->paginate(15) : collect();

        return view('livewire.notifications.notification-index', [
            'notifications' => $notifications,
            'unreadCount'   => $user ? $user->unreadNotifications()->count() : 0,
        ])->title('الإشعارات | ' . firm_name());
    }
}
