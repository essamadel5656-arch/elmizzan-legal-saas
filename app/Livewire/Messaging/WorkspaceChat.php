<?php

namespace App\Livewire\Messaging;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\WorkspaceMessage;
use Illuminate\Database\Eloquent\Collection;

class WorkspaceChat extends Component
{
    use WithFileUploads;

    public string $newMessage = '';
    public ?int $activeUserId = null;
    public string $searchQuery = '';
    public $file;

    public function mount(?User $user = null): void
    {
        if ($user && $user->exists) {
            $this->activeUserId = $user->id;
            WorkspaceMessage::unreadFor(auth()->id())->where('sender_id', $user->id)->update(['read_at' => now()]);
        } else {
            // Null activeUserId means Broadcasts
            WorkspaceMessage::unreadFor(auth()->id())->whereNull('receiver_id')->update(['read_at' => now()]);
        }
    }

    public function selectConversation(?int $userId = null): void
    {
        $this->activeUserId = $userId;
        
        if ($userId) {
            WorkspaceMessage::unreadFor(auth()->id())->where('sender_id', $userId)->update(['read_at' => now()]);
        } else {
            WorkspaceMessage::unreadFor(auth()->id())->whereNull('receiver_id')->update(['read_at' => now()]);
        }

        $this->dispatch('message-sent'); // Scroll to bottom
    }

    public function sendMessage(): void
    {
        $this->validate([
            'newMessage' => 'required_without:file|string|max:1000',
            'file'       => 'nullable|file|max:10240', // 10MB
        ]);

        $attachmentPath = null;
        $attachmentName = null;

        if ($this->file) {
            $attachmentName = $this->file->getClientOriginalName();
            $attachmentPath = $this->file->store('workspace_attachments', 'public');
        }

        WorkspaceMessage::create([
            'sender_id'       => auth()->id(),
            'receiver_id'     => $this->activeUserId,
            'body'            => trim((string) $this->newMessage),
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);

        $this->reset(['newMessage', 'file']);
        $this->dispatch('message-sent');
    }

    public function getConversationThreadProperty(): Collection
    {
        $query = WorkspaceMessage::with('sender:id,name,role,lawyer_id,last_seen_at');

        if ($this->activeUserId) {
            $query->where(function ($q) {
                $q->where('sender_id', auth()->id())->where('receiver_id', $this->activeUserId);
            })->orWhere(function ($q) {
                $q->where('sender_id', $this->activeUserId)->where('receiver_id', auth()->id());
            });
        } else {
            // Broadcasts
            $query->whereNull('receiver_id');
        }

        return $query->orderBy('created_at', 'asc')->get();
    }

    public function getWorkspaceMembersProperty(): Collection
    {
        $query = User::where('role', '!=', 'client')->where('id', '!=', auth()->id());

        if (!empty($this->searchQuery)) {
            $query->where('name', 'like', '%' . $this->searchQuery . '%');
        }

        return $query->orderByDesc('last_seen_at')->get();
    }

    public function render()
    {
        return view('livewire.messaging.workspace-chat')
            ->title('الرسائل الداخلية | ' . firm_name());
    }
}
