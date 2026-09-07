<div>
    <style>
        /* ===== Page Container ===== */
        .notif-page { max-width: 860px; margin: 0 auto; }

        .notif-page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;
        }
        .notif-page-title {
            font-size: 1.5rem; font-weight: 800;
            color: var(--text-primary);
            display: flex; align-items: center; gap: 0.6rem;
        }
        .notif-page-title i { color: var(--gold-accent); }

        /* ===== Mark All Read Button ===== */
        .btn-mark-all {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.55rem 1.2rem; border-radius: 8px; font-size: 0.88rem;
            font-weight: 600; font-family: 'Tajawal', sans-serif;
            background: var(--sidebar-bg); color: #fff;
            border: none; cursor: pointer; transition: 0.2s;
            text-decoration: none;
        }
        .btn-mark-all:hover { opacity: 0.85; color: #fff; transform: translateY(-1px); }

        /* ===== Filter Tabs ===== */
        .notif-tabs {
            display: flex; gap: 0.5rem; margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;
        }
        .notif-tab {
            padding: 0.45rem 1rem; border-radius: 8px; font-size: 0.88rem; font-weight: 600;
            border: 1px solid transparent; background: none; color: var(--text-secondary);
            cursor: pointer; transition: 0.2s; font-family: 'Tajawal', sans-serif;
        }
        .notif-tab.active {
            background: var(--card-bg); border-color: var(--border-color); color: var(--gold-accent);
            box-shadow: var(--shadow-sm);
        }

        /* ===== Notification Item ===== */
        .notif-list { display: flex; flex-direction: column; gap: 0.6rem; }

        .notif-item {
            display: flex; align-items: flex-start; gap: 1rem;
            border-radius: 12px; padding: 1.1rem 1.4rem;
            border: 1px solid var(--border-color);
            cursor: pointer; transition: all 0.2s ease;
            text-decoration: none;
            position: relative;
            background: inherit; width: 100%; text-align: right;
            font-family: 'Tajawal', sans-serif;
        }

        /* ── UNREAD: prominent card ── */
        .notif-item.unread {
            background: var(--notif-unread-bg, #fffbeb);
            border-color: var(--gold-accent);
            border-right: 4px solid var(--gold-accent);
            box-shadow: 0 2px 8px rgba(212, 175, 55, 0.12);
        }
        .notif-item.unread .notif-message { font-weight: 700; color: var(--text-primary); }
        .notif-item.unread .notif-icon-wrap { background: rgba(212,175,55,0.15); color: var(--gold-accent); }

        /* ── READ: subtle, muted card ── */
        .notif-item.read {
            background: var(--notif-read-bg, #f8fafc);
            border-color: var(--border-color);
            opacity: 0.78;
        }
        .notif-item.read .notif-message { font-weight: 500; color: var(--text-secondary); }
        .notif-item.read .notif-icon-wrap { background: var(--primary-bg); color: var(--text-secondary); }

        .notif-item:hover {
            transform: translateX(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.07);
            opacity: 1 !important;
            border-color: var(--gold-accent) !important;
        }

        /* ===== Icon badge ===== */
        .notif-icon-wrap {
            width: 42px; height: 42px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 1.1rem; transition: 0.2s;
        }

        /* ===== Text content ===== */
        .notif-body { flex: 1; min-width: 0; }
        .notif-message { font-size: 0.95rem; line-height: 1.5; margin-bottom: 0.3rem; }
        .notif-meta { font-size: 0.78rem; color: var(--text-secondary); display: flex; gap: 0.8rem; flex-wrap: wrap; }

        /* ===== Unread dot ===== */
        .notif-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--gold-accent); flex-shrink: 0;
            margin-top: 6px;
        }

        /* ===== Empty state ===== */
        .notif-empty {
            text-align: center; padding: 4rem 2rem;
            color: var(--text-secondary);
        }
        .notif-empty i { font-size: 3.5rem; margin-bottom: 1rem; color: var(--border-color); display: block; }

        /* ===== Pagination ===== */
        .pagination-wrap { margin-top: 2rem; display: flex; justify-content: center; }

        /* ===== Dark mode overrides ===== */
        [data-theme="dark"] .notif-item.unread {
            --notif-unread-bg: rgba(212, 175, 55, 0.08);
            background: rgba(212, 175, 55, 0.08);
        }
        [data-theme="dark"] .notif-item.read {
            --notif-read-bg: rgba(255,255,255,0.03);
            background: rgba(255,255,255,0.03);
        }
    </style>

    <div class="notif-page">

        {{-- ===== Page Header ===== --}}
        <div class="notif-page-header">
            <h1 class="notif-page-title">
                <i class="fas fa-bell"></i>
                الإشعارات
                @if($unreadCount > 0)
                    <span style="font-size:0.8rem; background:var(--gold-accent); color:#1e293b;
                                 padding:0.2rem 0.6rem; border-radius:20px; font-weight:800;">
                        {{ $unreadCount }} جديد
                    </span>
                @endif
            </h1>

            @if($unreadCount > 0)
                <button type="button" wire:click="markAllAsRead" class="btn-mark-all" wire:loading.attr="disabled">
                    <i class="fas fa-check-double"></i>
                    <span>تعليم الكل كمقروء</span>
                </button>
            @endif
        </div>

        {{-- Filter Tabs --}}
        <div class="notif-tabs">
            <button type="button" wire:click="$set('filter', 'all')" class="notif-tab {{ $filter === 'all' ? 'active' : '' }}">
                الكل
            </button>
            <button type="button" wire:click="$set('filter', 'unread')" class="notif-tab {{ $filter === 'unread' ? 'active' : '' }}">
                غير مقروء ({{ $unreadCount }})
            </button>
            <button type="button" wire:click="$set('filter', 'read')" class="notif-tab {{ $filter === 'read' ? 'active' : '' }}">
                مقروء
            </button>
        </div>

        {{-- ===== Success flash ===== --}}
        @if(session()->has('success'))
            <div style="background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.3);
                        color:#15803d; border-radius:10px; padding:0.8rem 1.2rem; margin-bottom:1.5rem;
                        display:flex; align-items:center; gap:0.6rem;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        {{-- ===== Notification List ===== --}}
        @if($notifications->isEmpty())
            <div class="notif-empty">
                <i class="fas fa-bell-slash"></i>
                <p style="font-size:1.1rem; font-weight:600; margin-bottom:0.5rem;">لا توجد إشعارات</p>
                <p style="font-size:0.9rem;">ستظهر هنا إشعارات القضايا والجلسات</p>
            </div>
        @else
            <div class="notif-list">
                @foreach($notifications as $notification)
                    @php
                        $isUnread  = is_null($notification->read_at);
                        $data      = $notification->data;
                        $type      = $data['type'] ?? 'general';
                        $caseId    = $data['case_id'] ?? null;
                        $message   = $data['message'] ?? 'إشعار جديد';
                        $icon      = $type === 'case_assigned' ? 'fa-briefcase' : 'fa-calendar-check';
                        $timeAgo   = $notification->created_at->diffForHumans();
                    @endphp

                    <div wire:click="markAsRead('{{ $notification->id }}')"
                         class="notif-item {{ $isUnread ? 'unread' : 'read' }}">

                        {{-- Unread dot --}}
                        @if($isUnread)
                            <span class="notif-dot"></span>
                        @else
                            <span style="width:8px;flex-shrink:0;"></span>
                        @endif

                        {{-- Icon --}}
                        <span class="notif-icon-wrap">
                            <i class="fas {{ $icon }}"></i>
                        </span>

                        {{-- Body --}}
                        <span class="notif-body">
                            <span class="notif-message">{{ $message }}</span>
                            <span class="notif-meta">
                                @if($caseId)
                                    <span><i class="fas fa-folder-open" style="margin-left:3px;"></i> القضية #{{ $data['case_number'] ?? $caseId }}</span>
                                @endif
                                <span><i class="fas fa-clock" style="margin-left:3px;"></i> {{ $timeAgo }}</span>
                                @if($isUnread)
                                    <span style="color:var(--gold-accent); font-weight:700;">● غير مقروء</span>
                                @endif
                            </span>
                        </span>

                        {{-- Arrow --}}
                        <i class="fas fa-chevron-left"
                           style="color:var(--text-secondary); font-size:0.8rem; margin-top:4px; flex-shrink:0;"></i>
                    </div>
                @endforeach
            </div>

            {{-- ===== Pagination ===== --}}
            @if($notifications->hasPages())
                <div class="pagination-wrap">
                    {{ $notifications->links() }}
                </div>
            @endif
        @endif

    </div>
</div>
