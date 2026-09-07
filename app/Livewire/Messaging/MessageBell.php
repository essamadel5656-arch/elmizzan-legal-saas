<?php

namespace App\Livewire\Messaging;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\WorkspaceMessage;

class MessageBell extends Component
{
    public bool $open = false;

    #[On('messages-updated')]
    public function refreshMessages(): void
    {
        // Re-renders the component to update the unread count and latest messages
    }

    public function toggleDropdown(): void
    {
        $this->open = !$this->open;
    }

    public function closeDropdown(): void
    {
        $this->open = false;
    }

    public function markAsRead(int $id): void
    {
        if (!auth()->check()) return;

        $message = WorkspaceMessage::visibleTo(auth()->id())->where('id', $id)->first();
        if ($message && is_null($message->read_at)) {
            $message->update(['read_at' => now()]);
            $this->dispatch('messages-updated');
        }
    }

    public function markAllAsRead(): void
    {
        if (!auth()->check()) return;

        WorkspaceMessage::unreadFor(auth()->id())->update(['read_at' => now()]);
        $this->dispatch('messages-updated');
    }

    public function render()
    {
        $userId = auth()->id();
        $unreadCount = 0;
        $latestMessages = collect();

        if ($userId) {
            $unreadCount = WorkspaceMessage::unreadFor($userId)->count();
            $latestMessages = WorkspaceMessage::visibleTo($userId)
                ->with('sender:id,name,role,lawyer_id')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('livewire.messaging.message-bell', compact('unreadCount', 'latestMessages'));
    }
}
