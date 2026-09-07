<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use Livewire\Attributes\On;

class NotificationBell extends Component
{
    public bool $open = false;

    #[On('notification-read')]
    #[On('notifications-updated')]
    public function refreshNotifications(): void
    {
        // Livewire will re-render and re-query unread notifications automatically
    }

    public function toggleDropdown(): void
    {
        $this->open = !$this->open;
    }

    public function closeDropdown(): void
    {
        $this->open = false;
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
    }

    public function render()
    {
        $unreadCount = 0;
        $latestNotifications = collect();

        if (auth()->check()) {
            $unreadCount = auth()->user()->unreadNotifications()->count();
            $latestNotifications = auth()->user()->notifications()->take(5)->get();
        }

        return view('livewire.notifications.notification-bell', [
            'unreadCount' => $unreadCount,
            'latestNotifications' => $latestNotifications,
        ]);
    }
}
