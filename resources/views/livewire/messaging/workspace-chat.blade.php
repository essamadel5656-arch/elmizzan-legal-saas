<div>
    <style>
        .chat-container {
            display: flex;
            height: calc(100vh - 120px);
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .chat-sidebar {
            width: 300px;
            background: var(--primary-bg);
            border-left: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
        }

        .chat-sidebar-header {
            padding: 1.25rem;
            border-bottom: 1px solid var(--border-color);
        }

        .chat-search-input {
            width: 100%;
            padding: 0.6rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            color: var(--text-primary);
            font-size: 0.9rem;
            font-family: 'Tajawal', sans-serif;
            outline: none;
            transition: 0.2s;
        }

        .chat-search-input:focus {
            border-color: var(--gold-accent);
        }

        .chat-member-list {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem 0;
        }

        .chat-member {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            cursor: pointer;
            transition: 0.2s;
            border-right: 3px solid transparent;
        }

        .chat-member:hover {
            background: rgba(0, 0, 0, 0.03);
        }

        .chat-member.active {
            background: rgba(212, 175, 55, 0.1);
            border-right-color: var(--gold-accent);
        }

        .online-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #22c55e;
            border: 2px solid var(--card-bg);
            position: absolute;
            bottom: 0;
            right: 0;
        }

        .offline-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--text-secondary);
            border: 2px solid var(--card-bg);
            position: absolute;
            bottom: 0;
            right: 0;
        }

        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--card-bg);
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            background: var(--primary-bg);
        }

        .msg-bubble-wrap {
            display: flex;
            flex-direction: column;
            max-width: 75%;
        }

        .msg-bubble-wrap.self {
            align-self: flex-start; /* RTL: left side */
        }

        .msg-bubble-wrap.other {
            align-self: flex-end; /* RTL: right side */
        }

        .msg-info {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
            display: flex;
            gap: 0.5rem;
        }

        .msg-bubble-wrap.self .msg-info {
            flex-direction: row-reverse;
        }

        .msg-bubble {
            padding: 0.85rem 1.1rem;
            border-radius: 12px;
            font-size: 0.95rem;
            line-height: 1.5;
            word-wrap: break-word;
        }

        .msg-bubble-wrap.self .msg-bubble {
            background: rgba(212, 175, 55, 0.12);
            border-right: 2px solid var(--gold-accent);
            color: var(--text-primary);
            border-top-right-radius: 0;
        }

        .msg-bubble-wrap.other .msg-bubble {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-top-left-radius: 0;
        }

        .chat-input-area {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color);
            background: var(--card-bg);
            display: flex;
            gap: 1rem;
            align-items: flex-end;
        }

        .chat-textarea {
            flex: 1;
            resize: none;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--input-bg);
            color: var(--text-primary);
            font-family: 'Tajawal', sans-serif;
            outline: none;
            max-height: 120px;
            overflow-y: auto;
        }
        .chat-textarea:focus {
            border-color: var(--gold-accent);
        }

        .chat-send-btn {
            background: var(--sidebar-bg);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-family: 'Tajawal', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
            height: 45px;
        }
        .chat-send-btn:hover {
            opacity: 0.9;
        }
    </style>

    <div class="chat-container">
        {{-- Sidebar --}}
        <div class="chat-sidebar">
            <div class="chat-sidebar-header">
                <input type="text" wire:model.live.debounce.300ms="searchQuery" class="chat-search-input" placeholder="بحث عن عضو...">
            </div>
            
            <div class="chat-member-list" wire:poll.30s>
                <div class="chat-member {{ is_null($activeUserId) ? 'active' : '' }}" wire:click="selectConversation(null)">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--sidebar-bg); color: var(--gold-accent); display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: var(--text-primary);">الكل (بث عام)</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">رسائل لجميع الأعضاء</div>
                    </div>
                </div>

                @foreach($this->workspaceMembers as $member)
                    @php
                        $isOnline = $member->isOnline();
                        $profileImage = $member->role === 'lawyer' && $member->lawyer ? $member->lawyer->profile_image : null;
                        $avatarUrl = $profileImage ? asset('storage/' . $profileImage) : 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=1e293b&color=d4af37';
                    @endphp
                    <div class="chat-member {{ $activeUserId === $member->id ? 'active' : '' }}" wire:click="selectConversation({{ $member->id }})">
                        <div style="position: relative;">
                            <img src="{{ $avatarUrl }}" alt="{{ $member->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            <div class="{{ $isOnline ? 'online-dot' : 'offline-dot' }}"></div>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: var(--text-primary);">{{ $member->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $isOnline ? 'متصل الآن' : ($member->last_seen_at ? 'منذ ' . $member->last_seen_at->diffForHumans() : 'غير متصل') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Main Chat Area --}}
        <div class="chat-main">
            <div class="chat-header">
                @if(is_null($activeUserId))
                    <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--sidebar-bg); color: var(--gold-accent); display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--text-primary);">الكل (بث عام)</h2>
                        <div style="font-size: 0.8rem; color: var(--text-secondary);">مرئية لجميع أعضاء مساحة العمل</div>
                    </div>
                @else
                    @php
                        $activeUser = $this->workspaceMembers->firstWhere('id', $activeUserId);
                        if($activeUser) {
                            $isOnline = $activeUser->isOnline();
                            $profileImage = $activeUser->role === 'lawyer' && $activeUser->lawyer ? $activeUser->lawyer->profile_image : null;
                            $avatarUrl = $profileImage ? asset('storage/' . $profileImage) : 'https://ui-avatars.com/api/?name=' . urlencode($activeUser->name) . '&background=1e293b&color=d4af37';
                        }
                    @endphp
                    @if($activeUser)
                        <img src="{{ $avatarUrl }}" alt="{{ $activeUser->name }}" style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover;">
                        <div>
                            <h2 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--text-primary);">{{ $activeUser->name }}</h2>
                            <div style="font-size: 0.8rem; color: var(--text-secondary);">
                                <i class="fas fa-circle" style="font-size: 0.6rem; color: {{ $isOnline ? '#22c55e' : 'var(--text-secondary)' }}; margin-left: 3px;"></i>
                                {{ $isOnline ? 'متصل الآن' : ($activeUser->last_seen_at ? 'آخر ظهور منذ ' . $activeUser->last_seen_at->diffForHumans() : 'غير متصل') }}
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <div class="chat-messages" id="chatMessages" wire:poll.10s>
                @forelse($this->conversationThread as $msg)
                    @php
                        $isSelf = $msg->sender_id === auth()->id();
                    @endphp
                    <div class="msg-bubble-wrap {{ $isSelf ? 'self' : 'other' }}">
                        <div class="msg-info">
                            <span>{{ $msg->sender->name }}</span>
                            <span>•</span>
                            <span>{{ $msg->created_at->format('h:i A') }}</span>
                            @if($isSelf && is_null($activeUserId))
                            @elseif($isSelf)
                                <span title="مقروءة">
                                    <i class="fas fa-check-double" style="color: {{ $msg->read_at ? '#3b82f6' : 'var(--text-secondary)' }}; font-size: 0.7rem;"></i>
                                </span>
                            @endif
                        </div>
                        <div class="msg-bubble">
                            @if($msg->body)
                                {!! nl2br(e($msg->body)) !!}
                            @endif
                            
                            @if($msg->attachment_path)
                                <div style="margin-top: {{ $msg->body ? '0.75rem' : '0' }}; padding-top: {{ $msg->body ? '0.75rem' : '0' }}; border-top: {{ $msg->body ? '1px solid rgba(148, 163, 184, 0.2)' : 'none' }};">
                                    @php
                                        $ext = strtolower(pathinfo($msg->attachment_path, PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    @if($isImage)
                                        <a href="{{ asset('storage/' . $msg->attachment_path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $msg->attachment_path) }}" alt="attachment" style="max-width: 100%; border-radius: 8px; max-height: 200px; object-fit: cover;">
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $msg->attachment_path) }}" target="_blank" style="display: flex; align-items: center; gap: 0.5rem; color: inherit; text-decoration: none; background: rgba(0,0,0,0.05); padding: 0.5rem 0.75rem; border-radius: 8px;">
                                            <i class="fas fa-paperclip" style="font-size: 1.2rem;"></i>
                                            <span style="font-size: 0.85rem; font-weight: bold; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 150px;">{{ $msg->attachment_name ?? 'مرفق' }}</span>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: var(--text-secondary); margin-top: auto; margin-bottom: auto;">
                        <i class="fas fa-comments" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                        <p>لا توجد رسائل في هذه المحادثة حتى الآن. ابدأ بإرسال رسالة!</p>
                    </div>
                @endforelse
            </div>

            <div class="chat-input-area" style="position: relative;" x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
                <div style="position: relative; flex: 1;">
                    <textarea wire:model="newMessage" 
                              class="chat-textarea" 
                              style="width: 100%;"
                              rows="1" 
                              placeholder="اكتب رسالتك هنا..." 
                              wire:keydown.enter.prevent="sendMessage"></textarea>
                </div>
                
                <label style="cursor: pointer; padding: 0.75rem; color: var(--text-secondary); transition: 0.2s;" onmouseover="this.style.color='var(--gold-accent)'" onmouseout="this.style.color='var(--text-secondary)'">
                    <i class="fas fa-paperclip" style="font-size: 1.2rem;"></i>
                    <input type="file" wire:model="file" style="display: none;">
                </label>

                <!-- Progress Bar -->
                <div x-show="isUploading" style="position: absolute; top: -10px; left: 0; right: 0; background: var(--primary-bg); height: 4px; border-radius: 4px; overflow: hidden;">
                    <div :style="`width: ${progress}%; transition: width 0.2s;`" style="height: 100%; background: var(--gold-accent);"></div>
                </div>

                <button class="chat-send-btn" wire:click="sendMessage" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="sendMessage, file">إرسال <i class="fas fa-paper-plane" style="margin-right: 5px;"></i></span>
                    <span wire:loading wire:target="sendMessage, file"><i class="fas fa-circle-notch fa-spin"></i></span>
                </button>
            </div>
            @if($file)
                <div style="padding: 0 1.5rem 1rem; background: var(--card-bg); font-size: 0.8rem; color: var(--gold-accent); display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-file-alt"></i> {{ $file->getClientOriginalName() }} 
                    <button wire:click="$set('file', null)" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.8rem;"><i class="fas fa-times"></i> إزالة</button>
                </div>
            @endif
            @error('file') <span style="color: #ef4444; font-size: 0.8rem; padding: 0 1.5rem 1rem; background: var(--card-bg);">{{ $message }}</span> @enderror
        </div>
    </div>

    <script>
        function scrollToBottom() {
            const chatMessages = document.getElementById('chatMessages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }

        document.addEventListener('livewire:initialized', () => {
            scrollToBottom();
            
            Livewire.on('message-sent', () => {
                setTimeout(scrollToBottom, 50);
            });
        });
    </script>
</div>
