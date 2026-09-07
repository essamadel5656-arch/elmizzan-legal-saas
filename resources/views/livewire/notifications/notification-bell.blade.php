<div class="notif-dropdown-wrapper" style="position: relative;" x-data="{ open: @entangle('open') }" @click.outside="open = false">
    {{-- Bell Trigger Button --}}
    <button type="button" class="notif-bell" @click="open = !open" title="الإشعارات" aria-label="الإشعارات" style="cursor: pointer;">
        <i class="fas fa-bell"></i>
        @if($unreadCount > 0)
            <span class="notif-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
        @endif
    </button>

    {{-- Dropdown Panel --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="notif-dropdown-menu"
         style="display: none; position: absolute; left: 0; top: calc(100% + 10px); width: 330px; background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15); z-index: 2100; overflow: hidden; font-family: 'Tajawal', sans-serif;">
        
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1rem; border-bottom: 1px solid var(--border-color); background: rgba(0,0,0,0.02);">
            <div style="font-weight: 700; font-size: 0.95rem; color: var(--text-primary); display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-bell" style="color: var(--gold-accent);"></i>
                <span>الإشعارات</span>
                @if($unreadCount > 0)
                    <span style="font-size: 0.75rem; background: rgba(212,175,55,0.15); color: var(--gold-accent); padding: 2px 8px; border-radius: 20px; font-weight: 700;">{{ $unreadCount }} جديدة</span>
                @endif
            </div>
            @if($unreadCount > 0)
                <button type="button" wire:click="markAllAsRead" style="background: none; border: none; font-size: 0.78rem; color: var(--gold-accent); cursor: pointer; font-weight: 600; padding: 2px 6px; border-radius: 4px;">
                    تعليم الكل كمقروء
                </button>
            @endif
        </div>

        <div style="max-height: 320px; overflow-y: auto;">
            @forelse($latestNotifications as $item)
                @php
                    $isUnread = $item->unread();
                    $msg      = $item->data['message'] ?? 'إشعار جديد';
                    $caseNum  = $item->data['case_number'] ?? null;
                    $timeAgo  = $item->created_at->diffForHumans();
                @endphp
                <div wire:click="markAsRead('{{ $item->id }}')"
                     style="padding: 0.8rem 1rem; border-bottom: 1px solid var(--border-color); cursor: pointer; transition: background 0.2s; display: flex; gap: 0.75rem; align-items: flex-start; {{ $isUnread ? 'background: var(--notif-unread-bg); border-right: 3px solid var(--gold-accent);' : 'background: var(--card-bg); opacity: 0.75;' }}"
                     onmouseover="this.style.backgroundColor='rgba(212,175,55,0.08)'"
                     onmouseout="this.style.backgroundColor='{{ $isUnread ? 'var(--notif-unread-bg)' : 'var(--card-bg)' }}'">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: {{ $isUnread ? 'rgba(212,175,55,0.15)' : 'var(--primary-bg)' }}; color: {{ $isUnread ? 'var(--gold-accent)' : 'var(--text-secondary)' }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.85rem;">
                        <i class="fas {{ str_contains($item->type, 'Assigned') ? 'fa-briefcase' : 'fa-calendar-alt' }}"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <p style="font-size: 0.85rem; margin: 0 0 0.2rem 0; color: var(--text-primary); font-weight: {{ $isUnread ? '700' : '500' }}; line-height: 1.4; word-break: break-word;">
                            {{ $msg }}
                        </p>
                        <div style="font-size: 0.72rem; color: var(--text-secondary); display: flex; justify-content: space-between;">
                            <span>{{ $timeAgo }}</span>
                            @if($caseNum)
                                <span style="color: var(--gold-accent); font-weight: 600;">#{{ $caseNum }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div style="padding: 2rem 1rem; text-align: center; color: var(--text-secondary); font-size: 0.88rem;">
                    <i class="fas fa-bell-slash" style="font-size: 1.8rem; margin-bottom: 0.5rem; opacity: 0.4; display: block;"></i>
                    لا توجد إشعارات حالياً
                </div>
            @endforelse
        </div>

        <div style="padding: 0.65rem; border-top: 1px solid var(--border-color); text-align: center; background: rgba(0,0,0,0.02);">
            <a href="{{ route('notifications.index') }}" wire:navigate @click="open = false" style="font-size: 0.82rem; color: var(--text-primary); font-weight: 600; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 0.4rem;">
                <span>عرض جميع الإشعارات</span>
                <i class="fas fa-arrow-left" style="font-size: 0.75rem;"></i>
            </a>
        </div>
    </div>
</div>
