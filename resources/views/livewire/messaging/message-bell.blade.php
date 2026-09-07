<div class="msg-bell-container" wire:poll.10s style="position: relative;">
    {{-- Bell Icon Button --}}
    <button class="notif-bell" wire:click="toggleDropdown" @click.outside="$wire.closeDropdown()">
        <i class="fas fa-comments"></i>
        @if($unreadCount > 0)
            <span class="notif-badge">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
        @endif
    </button>

    {{-- Dropdown Panel --}}
    @if($open)
        <div class="msg-dropdown" style="position: absolute; left: 0; top: 120%; width: 320px; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; box-shadow: var(--shadow-md); z-index: 1050; overflow: hidden;">
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; border-bottom: 1px solid var(--border-color); background: var(--primary-bg);">
                <h6 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--text-primary);">الرسائل الداخلية</h6>
                @if($unreadCount > 0)
                    <button wire:click="markAllAsRead" style="background: none; border: none; color: var(--gold-accent); font-size: 0.8rem; cursor: pointer; font-weight: 600;">
                        تحديد الكل كمقروء
                    </button>
                @endif
            </div>

            <div style="max-height: 350px; overflow-y: auto;">
                @forelse($latestMessages as $msg)
                    @php
                        $isUnread = is_null($msg->read_at);
                        $profileImage = $msg->sender->role === 'lawyer' && $msg->sender->lawyer ? $msg->sender->lawyer->profile_image : null;
                        $avatarUrl = $profileImage ? asset('storage/' . $profileImage) : 'https://ui-avatars.com/api/?name=' . urlencode($msg->sender->name) . '&background=1e293b&color=d4af37';
                    @endphp
                    <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--border-color); display: flex; gap: 0.75rem; transition: 0.2s; background: {{ $isUnread ? 'var(--notif-unread-bg)' : 'var(--notif-read-bg)' }};" 
                         wire:click="markAsRead({{ $msg->id }})">
                        <img src="{{ $avatarUrl }}" alt="{{ $msg->sender->name }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem;">
                                <strong style="font-size: 0.9rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $msg->sender->name }}</strong>
                                <small style="font-size: 0.75rem; color: var(--text-secondary); white-space: nowrap;">{{ $msg->created_at->diffForHumans() }}</small>
                            </div>
                            <p style="margin: 0; font-size: 0.85rem; color: var(--text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ Str::limit($msg->body, 60) }}
                            </p>
                        </div>
                        @if($isUnread)
                            <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--gold-accent); margin-top: 0.4rem; flex-shrink: 0;"></div>
                        @endif
                    </div>
                @empty
                    <div style="padding: 2rem 1rem; text-align: center; color: var(--text-secondary); font-size: 0.9rem;">
                        <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                        <p style="margin: 0;">لا توجد رسائل جديدة.</p>
                    </div>
                @endforelse
            </div>

            <div style="padding: 0.75rem; text-align: center; border-top: 1px solid var(--border-color); background: var(--primary-bg);">
                <a href="{{ route('workspace.chat') }}" wire:navigate style="color: var(--gold-accent); text-decoration: none; font-size: 0.85rem; font-weight: 700;">
                    عرض جميع الرسائل <i class="fas fa-arrow-left" style="font-size: 0.75rem; margin-right: 0.25rem;"></i>
                </a>
            </div>
        </div>
    @endif
</div>
