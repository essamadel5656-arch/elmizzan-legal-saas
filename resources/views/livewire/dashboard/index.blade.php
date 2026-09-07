<div>
    <style>
        .dashboard-header { margin-bottom: 2rem; }
        .welcome-title { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; }
        .date-text { color: var(--text-secondary); font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem; }

        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
        @media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 580px) { .stats-grid { grid-template-columns: 1fr; } }

        .stat-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 24px rgba(0, 0, 0, 0.1);
            border-color: var(--gold-accent, #d4af37);
        }

        .stat-card::before {
            content: "";
            position: absolute;
            top: 0; right: -100%;
            width: 50%; height: 100%;
            background: linear-gradient(to left, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-25deg);
            transition: right 0.6s ease-in-out;
            z-index: 1;
            pointer-events: none;
        }
        .stat-card:hover::before { right: 200%; }

        .stat-icon {
            width: 54px; height: 54px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; flex-shrink: 0;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            z-index: 2;
        }

        .stat-card:hover .stat-icon { transform: scale(1.15) rotate(-8deg); }

        .icon-blue   { background-color: rgba(30, 41, 59, 0.1);   color: var(--sidebar-bg); }
        .icon-gold   { background-color: rgba(212, 175, 55, 0.15); color: var(--gold-accent); }
        .icon-green  { background-color: rgba(21, 128, 61, 0.1);   color: var(--success-color); }
        .icon-red    { background-color: rgba(220, 38, 38, 0.1);   color: var(--danger-color); }
        .icon-purple { background-color: rgba(99, 102, 241, 0.1);  color: #6366f1; }
        .icon-teal   { background-color: rgba(20, 184, 166, 0.1);  color: #14b8a6; }

        .stat-details { position: relative; z-index: 2; }
        .stat-details h3 { font-size: 1.8rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.2rem 0; }
        .stat-details p  { color: var(--text-secondary); font-size: 0.9rem; font-weight: 600; margin: 0; }

        .stat-details-wide { flex: 1; position: relative; z-index: 2; }
        .stat-details-wide h3 { font-size: 1.8rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.15rem 0; }
        .stat-details-wide p  { color: var(--text-secondary); font-size: 0.9rem; font-weight: 600; margin: 0 0 0.4rem 0; }
        .collection-bar-wrap {
            width: 100%; background: rgba(0,0,0,0.07);
            border-radius: 20px; height: 6px; overflow: hidden;
        }
        .collection-bar-fill {
            height: 100%; border-radius: 20px;
            background: linear-gradient(90deg, #14b8a6, #6366f1);
            transition: width 1.5s ease-in-out;
        }

        .content-grid { display: grid; grid-template-columns: 2fr 1.2fr; gap: 1.5rem; }

        .panel {
            background-color: var(--card-bg, #ffffff); border: 1px solid var(--border-color);
            border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            transition: box-shadow 0.3s ease;
        }
        .panel:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.06); }

        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--primary-bg); padding-bottom: 1rem; }
        .panel-title { font-size: 1.1rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 0.5rem; }

        .table-responsive { overflow-x: auto; }
        .custom-table { width: 100%; border-collapse: collapse; text-align: {{ $isEnglish ? 'left' : 'right' }}; }
        .custom-table th { color: var(--text-secondary); font-size: 0.85rem; padding: 0.8rem 0.5rem; border-bottom: 2px solid var(--primary-bg); }
        .custom-table td { padding: 1rem 0.5rem; border-bottom: 1px solid var(--primary-bg); font-size: 0.95rem; color: var(--text-primary); }

        .badge { padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; display: inline-block; }
        .badge-success   { background-color: rgba(21, 128, 61, 0.1);  color: var(--success-color); }
        .badge-warning   { background-color: rgba(212, 175, 55, 0.15); color: #9a7b21; }
        .badge-danger    { background-color: rgba(220, 38, 38, 0.1);  color: var(--danger-color); }
        .badge-secondary { background-color: rgba(100, 116, 139, 0.1); color: var(--text-secondary); }

        .empty-state { text-align: center; padding: 3rem 1rem; color: var(--text-secondary); }
        .empty-state i { font-size: 3rem; color: var(--border-color); margin-bottom: 1rem; display: block; }
        .empty-state p { font-size: 0.95rem; font-weight: 500; margin-bottom: 1rem; }

        .chart-container { position: relative; height: 300px; width: 100%; }
        .chart-empty-state {
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; height: 100%; color: var(--text-secondary);
        }
        .chart-empty-state i { font-size: 2.5rem; color: var(--border-color); margin-bottom: 0.75rem; }
        .chart-empty-state p { font-size: 0.9rem; font-weight: 500; margin: 0; }

        /* ===== Calendar ===== */
        .mini-calendar { width: 100%; text-align: center; direction: {{ $isEnglish ? 'ltr' : 'rtl' }}; }
        .cal-header-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .cal-header-nav button { background: var(--primary-bg); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; color: var(--sidebar-bg); transition: 0.3s; display: flex; align-items: center; justify-content: center; }
        .cal-header-nav button:hover { background: var(--gold-accent); color: #fff; }
        .cal-month-year { font-weight: 800; color: var(--text-primary); font-size: 1.1rem; }

        .cal-days-row { display: grid; grid-template-columns: repeat(7, 1fr); font-weight: 700; margin-bottom: 0.8rem; color: var(--text-secondary); font-size: 0.85rem; }
        .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; }

        .cal-cell {
            aspect-ratio: 1; display: flex; align-items: center; justify-content: center;
            border-radius: 8px; font-size: 0.95rem; font-weight: 600; position: relative; transition: all 0.3s;
            color: var(--text-primary); background-color: transparent; border: 1px solid transparent;
        }

        .cal-cell:not(.empty):not(.has-event):not(.today):hover { background-color: var(--primary-bg); cursor: pointer; }
        .cal-cell.today { background-color: var(--sidebar-bg); color: #fff; box-shadow: 0 4px 10px rgba(30, 41, 59, 0.3); }
        .cal-cell.has-event {
            background-color: rgba(212, 175, 55, 0.15);
            color: #9a7b21;
            border: 1px solid rgba(212, 175, 55, 0.4);
            cursor: pointer;
        }
        .cal-cell.has-event:hover { background-color: var(--gold-accent); color: #fff; transform: scale(1.05); border-color: var(--gold-accent); }

        .cal-cell.has-event::after {
            content: ''; position: absolute; bottom: 4px; left: 50%; transform: translateX(-50%);
            width: 4px; height: 4px; background-color: currentColor; border-radius: 50%;
        }

        .cal-tooltip {
            position: absolute; bottom: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(10px);
            background: #1e293b; color: #fff; padding: 0.8rem; border-radius: 8px; font-size: 0.85rem;
            width: max-content; min-width: 150px; max-width: 220px; text-align: {{ $isEnglish ? 'left' : 'right' }};
            opacity: 0; visibility: hidden; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 100; box-shadow: 0 8px 16px rgba(0,0,0,0.15); pointer-events: none;
        }
        .cal-tooltip::before {
            content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%);
            border-width: 6px; border-style: solid; border-color: #1e293b transparent transparent transparent;
        }

        .cal-cell.has-event:hover .cal-tooltip { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0); }

        .event-item { border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 0.5rem; margin-bottom: 0.5rem; }
        .event-item:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
        .event-time { display: block; color: var(--gold-accent); font-size: 0.75rem; font-weight: 700; margin-bottom: 0.1rem; }
        .event-title { white-space: normal; line-height: 1.4; }

        .badge-primary   { background-color: rgba(59, 130, 246, 0.1); color: #2563eb; }
        .badge-info      { background-color: rgba(14, 165, 233, 0.1); color: #0284c7; }
        .badge-warning   { background-color: rgba(245, 158, 11, 0.1); color: #d97706; }
        .badge-success   { background-color: rgba(34, 197, 94, 0.1);  color: #16a34a; }
        .badge-danger    { background-color: rgba(239, 68, 68, 0.1);  color: #dc2626; }
        .badge-secondary { background-color: rgba(107, 114, 128, 0.1); color: #4b5563; }

        @media (max-width: 1024px) { .content-grid { grid-template-columns: 1fr; } }
    </style>

    {{-- ===== Welcome Header ===== --}}
    <div class="dashboard-header">
        <h1 class="welcome-title">
            {{ __('Welcome,') }} {{ auth()->user()->name }}!
        </h1>
        <div class="date-text">
            <i class="fas fa-calendar-day"></i>
            @php
                $carbonDate = \Carbon\Carbon::now();
                $dateStr = $isEnglish
                    ? $carbonDate->locale('en')->translatedFormat('l, F j, Y')
                    : $carbonDate->locale('ar')->translatedFormat('l، j F Y');
            @endphp
            <span>{{ $isEnglish ? __('Today:') : 'اليوم:' }} {{ $dateStr }}</span>
        </div>
    </div>

    {{-- ===== Stats Grid ===== --}}
    <div class="stats-grid">

        {{-- 1. Active Cases --}}
        <div class="stat-card" onclick="Livewire.navigate('{{ route('cases.index') }}')">
            <div class="stat-icon icon-blue"><i class="fas fa-briefcase"></i></div>
            <div class="stat-details">
                <h3>{{ $activeCasesCount ?? 0 }}</h3>
                <p>{{ __('Active Cases') }}</p>
            </div>
        </div>

        {{-- 2. Today's Sessions --}}
        <div class="stat-card" onclick="Livewire.navigate('{{ route('appointments.index') }}')">
            <div class="stat-icon icon-gold"><i class="fas fa-gavel"></i></div>
            <div class="stat-details">
                <h3>{{ $todaySessionsCount ?? 0 }}</h3>
                <p>{{ __("Today's Sessions") }}</p>
            </div>
        </div>

        {{-- 3. Total Clients --}}
        <div class="stat-card" onclick="Livewire.navigate('{{ route('clients.index') }}')">
            <div class="stat-icon icon-green"><i class="fas fa-users"></i></div>
            <div class="stat-details">
                <h3>{{ $totalClientsCount ?? 0 }}</h3>
                <p>{{ __('Total Clients') }}</p>
            </div>
        </div>

        {{-- 4. Urgent Tasks --}}
        <div class="stat-card" onclick="Livewire.navigate('{{ route('appointments.notifications') }}')">
            <div class="stat-icon icon-red"><i class="fas fa-clock"></i></div>
            <div class="stat-details">
                <h3>{{ $urgentTasksCount ?? 0 }}</h3>
                <p>{{ __('Urgent Tasks') }}</p>
            </div>
        </div>

        {{-- 5. Total Fees (Admin Only) --}}
        @if($isAdmin)
        <div class="stat-card">
            <div class="stat-icon icon-purple"><i class="fas fa-file-invoice-dollar"></i></div>
            <div class="stat-details">
                <h3>{{ format_currency($totalFees ?? 0) }}</h3>
                <p>{{ __('Total Fees') }}</p>
            </div>
        </div>

        {{-- 6. Collection Rate (Admin Only) --}}
        <div class="stat-card">
            <div class="stat-icon icon-teal"><i class="fas fa-percentage"></i></div>
            <div class="stat-details-wide">
                <h3>{{ $collectionRate ?? 0 }}%</h3>
                <p>
                    {{ __('Collection Rate') }}
                    <span style="font-size:0.8rem; color: var(--text-secondary); font-weight:500;">
                        ({{ format_currency($totalPaid ?? 0) }} / {{ format_currency($totalFees ?? 0) }})
                    </span>
                </p>
                <div class="collection-bar-wrap">
                    <div class="collection-bar-fill" style="width: {{ $collectionRate ?? 0 }};"></div>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- ===== Main Content Grid ===== --}}
    <div class="content-grid">

        {{-- Recent Cases Table --}}
        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title"><i class="fas fa-folder-open" style="color: var(--gold-accent);"></i> {{ __('Recent Cases') }}</h2>
                <a href="{{ route('cases.index') }}" wire:navigate class="btn btn-secondary" style="padding: 0.4rem 1rem; font-size: 0.85rem;">{{ __('View All') }}</a>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __('Case No.') }}</th>
                            <th>{{ __('Client') }}</th>
                            <th>{{ __('Opponent') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCases ?? [] as $case)
                            <tr>
                                <td><strong><a href="{{ route('cases.show', $case->id) }}" wire:navigate style="text-decoration:none; color:inherit;">{{ $case->case_number ?? __('غير محدد') }}</a></strong></td>
                                <td>{{ $case->display_client_name }}</td>
                                <td>{{ $case->opponent_name }}</td>
                                <td>
                                    <span class="badge badge-{{ $case->status_color }}">
                                        {{ $case->status_name }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i class="fas fa-folder-open"></i>
                                        <p>{{ __('لم يتم تسجيل أي قضايا في النظام حتى الآن.') }}</p>
                                        <a href="{{ route('cases.create') }}" wire:navigate style="background-color: var(--sidebar-bg); color: #fff; padding: 0.5rem 1.25rem; border-radius: 8px; text-decoration: none; font-weight: 700;">
                                            {{ __('إضافة قضية جديدة') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Monthly Calendar --}}
        <div class="panel" wire:ignore id="monthlyCalendarPanel">
            <div class="panel-header">
                <h2 class="panel-title"><i class="fas fa-calendar-alt" style="color: var(--gold-accent);"></i> {{ __('التقويم الشهري') }}</h2>
                <a href="{{ route('cases.index') }}" wire:navigate title="{{ __('إضافة موعد') }}">
                    <i class="fas fa-plus-circle" style="color: var(--gold-accent); font-size: 1.2rem;"></i>
                </a>
            </div>

            <div class="mini-calendar">
                <div class="cal-header-nav">
                    <button id="cal-prev" title="{{ __('الشهر السابق') }}"><i class="fas fa-chevron-{{ $isEnglish ? 'left' : 'right' }}"></i></button>
                    <div class="cal-month-year" id="cal-month-year">...</div>
                    <button id="cal-next" title="{{ __('الشهر التالي') }}"><i class="fas fa-chevron-{{ $isEnglish ? 'right' : 'left' }}"></i></button>
                </div>

                <div class="cal-days-row">
                    <div>{{ __('أحد') }}</div><div>{{ __('إثنين') }}</div><div>{{ __('ثلاثاء') }}</div><div>{{ __('أربعاء') }}</div><div>{{ __('خميس') }}</div><div>{{ __('جمعة') }}</div><div>{{ __('سبت') }}</div>
                </div>

                <div class="cal-grid" id="cal-grid"></div>
            </div>
        </div>

    </div>

    {{-- ===== Admin: Pending Expense Claims Widget ===== --}}
    @if($isAdmin && $pendingExpenses->isNotEmpty())
    <div class="content-grid" style="margin-top:1.5rem;">
        <div class="panel" style="grid-column:1/-1;">
            <div class="panel-header">
                <h2 class="panel-title">
                    <i class="fas fa-receipt" style="color:var(--gold-accent);"></i>
                    {{ __('طلبات المصاريف بانتظار الموافقة') }}
                    <span style="background:rgba(239,68,68,.12);color:#dc2626;padding:.15rem .55rem;border-radius:20px;font-size:.78rem;">
                        {{ $pendingExpenses->count() }}
                    </span>
                </h2>
            </div>
            @if(session()->has('expense_flash'))
                <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);color:#15803d;border-radius:8px;padding:.6rem 1rem;margin-bottom:1rem;font-size:.88rem;">
                    ✅ {{ session('expense_flash') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __('القضية') }}</th>
                            <th>{{ __('المبلغ') }}</th>
                            <th>{{ __('التصنيف') }}</th>
                            <th>{{ __('مقدَّم بواسطة') }}</th>
                            <th>{{ __('ملاحظات') }}</th>
                            <th>{{ __('الإجراء') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingExpenses as $expense)
                            <tr wire:key="dash-exp-{{ $expense->id }}">
                                <td><strong>{{ $expense->case?->case_number ?? '—' }}</strong></td>
                                <td><strong>{{ format_currency($expense->amount) }}</strong></td>
                                <td>{{ $expense->category }}</td>
                                <td style="font-size:.85rem;">{{ $expense->submittedBy?->name ?? '—' }}</td>
                                <td style="font-size:.83rem;color:var(--text-secondary);">{{ Str::limit($expense->notes, 40) }}</td>
                                <td>
                                    <div style="display:flex;gap:.4rem;">
                                        <button wire:click="approveExpense({{ $expense->id }})" wire:loading.attr="disabled"
                                            style="background:rgba(34,197,94,.12);color:#15803d;border:1px solid rgba(34,197,94,.3);padding:.3rem .8rem;border-radius:7px;font-size:.82rem;font-weight:700;cursor:pointer;font-family:inherit;">
                                            ✓ {{ __('قبول') }}
                                        </button>
                                        <button wire:click="rejectExpense({{ $expense->id }})" wire:loading.attr="disabled"
                                            style="background:rgba(239,68,68,.1);color:#dc2626;border:1px solid rgba(239,68,68,.25);padding:.3rem .8rem;border-radius:7px;font-size:.82rem;font-weight:700;cursor:pointer;font-family:inherit;">
                                            ✕ {{ __('رفض') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== Admin: Lawyer Performance Leaderboard Widget ===== --}}
    @if($isAdmin && $lawyerLeaderboard->isNotEmpty())
    <div class="content-grid" style="margin-top:1.5rem;">
        <div class="panel" style="grid-column:1/-1;">
            <div class="panel-header">
                <h2 class="panel-title">
                    <i class="fas fa-trophy" style="color:var(--gold-accent);"></i>
                    {{ __('أفضل المحامين أداءً') }}
                </h2>
            </div>
            <div style="padding: 1rem 1.5rem;">
                @foreach($lawyerLeaderboard as $index => $lawyer)
                    @php
                        $medal = match($index) {
                            0 => '🥇',
                            1 => '🥈',
                            2 => '🥉',
                            default => '<span style="display:inline-block;width:24px;text-align:center;font-weight:700;color:var(--text-secondary);">'.($index + 1).'</span>'
                        };
                        $barColor = match($index) {
                            0 => '#d4af37',
                            1 => '#94a3b8',
                            2 => '#cd7f32',
                            default => 'var(--border-color)'
                        };
                        $maxScore = $lawyerLeaderboard->first()?->performance_score ?? 1;
                        $barWidth = $maxScore > 0 ? round(($lawyer->performance_score / $maxScore) * 100) : 0;
                        $profileImage = $lawyer->profile_image ? asset('storage/' . $lawyer->profile_image) : null;
                        $avatarUrl = $profileImage ?: 'https://ui-avatars.com/api/?name=' . urlencode($lawyer->name) . '&background=1e293b&color=d4af37';
                    @endphp
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                        <div style="font-size: 1.5rem; width: 30px; text-align: center;">{!! $medal !!}</div>
                        <img src="{{ $avatarUrl }}" alt="{{ $lawyer->name }}" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem;">
                                <a href="{{ route('lawyers.show', $lawyer->id) }}" wire:navigate style="font-weight: 800; color: var(--text-primary); text-decoration: none; font-size: 1.05rem;">
                                    {{ $lawyer->name }}
                                </a>
                                <span style="background: rgba(212, 175, 55, 0.1); color: var(--gold-accent); padding: 0.2rem 0.6rem; border-radius: 20px; font-weight: 700; font-size: 0.85rem;">
                                    {{ $lawyer->performance_score }} {{ __('نقطة') }}
                                </span>
                            </div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                                {{ $lawyer->active_cases }} {{ __('نشطة') }} · {{ $lawyer->won_cases }} {{ __('منتهية') }} · {{ $lawyer->total_clients }} {{ __('موكل') }}
                            </div>
                            <div style="width: 100%; height: 6px; background: var(--border-color); border-radius: 10px; overflow: hidden;">
                                <div style="width: {{ $barWidth }}%; height: 100%; background: {{ $barColor }}; border-radius: 10px; transition: width 0.8s ease;"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="content-grid" style="margin-top: 1.5rem;">

        {{-- Line Chart --}}
        <div class="panel" wire:ignore>
            <div class="panel-header">
                <h2 class="panel-title"><i class="fas fa-chart-area" style="color: var(--gold-accent);"></i> {{ __('معدل تسجيل القضايا خلال العام') }}</h2>
            </div>
            <div wire:ignore class="chart-container" id="trendChartWrapper">
                <canvas id="casesTrendChart"></canvas>
            </div>
        </div>

        {{-- Pie Chart --}}
        <div class="panel" wire:ignore>
            <div class="panel-header">
                <h2 class="panel-title"><i class="fas fa-chart-pie" style="color: var(--gold-accent);"></i> {{ __('توزيع القضايا حسب الحالة') }}</h2>
            </div>
            <div wire:ignore class="chart-container" id="statusChartWrapper">
                <canvas id="casesStatusChart"></canvas>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
    (function () {
        const isEnglish = {{ $isEnglish ? 'true' : 'false' }};

        function initDashboardCharts() {
            /* 1. Monthly Calendar */
            const calEvents = @json($calendarEvents ?? []);
            let currentDate = new Date();

            function renderCalendar() {
                const calMonthYear = document.getElementById('cal-month-year');
                const grid = document.getElementById('cal-grid');
                if (!calMonthYear || !grid) return;

                const year  = currentDate.getFullYear();
                const month = currentDate.getMonth();

                const firstDay    = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                const monthNames   = {!! json_encode([__("يناير"),__("فبراير"),__("مارس"),__("أبريل"),__("مايو"),__("يونيو"),__("يوليو"),__("أغسطس"),__("سبتمبر"),__("أكتوبر"),__("نوفمبر"),__("ديسمبر")]) !!};
                calMonthYear.innerText = `${monthNames[month]} ${year}`;

                grid.innerHTML = '';

                for (let i = 0; i < firstDay; i++) {
                    grid.innerHTML += `<div class="cal-cell empty"></div>`;
                }

                const todayObj = new Date();
                const todayStr = `${todayObj.getFullYear()}-${String(todayObj.getMonth() + 1).padStart(2, '0')}-${String(todayObj.getDate()).padStart(2, '0')}`;

                for (let day = 1; day <= daysInMonth; day++) {
                    const m       = String(month + 1).padStart(2, '0');
                    const d       = String(day).padStart(2, '0');
                    const dateStr = `${year}-${m}-${d}`;
                    const dayEvents = calEvents.filter(e => e.date === dateStr);

                    let classList = "cal-cell";
                    if (dateStr === todayStr)    classList += " today";
                    if (dayEvents.length > 0)   classList += " has-event";

                    let tooltipHtml = '';
                    if (dayEvents.length > 0) {
                        let items = dayEvents.map(e => `
                            <div class="event-item" style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 0.6rem; margin-bottom: 0.6rem;">
                                <div class="event-title" style="font-weight: 800; font-size: 0.9rem; margin-bottom: 0.3rem;">${e.title}</div>
                                <div class="event-time" style="color: var(--gold-accent, #d4af37); font-size: 0.8rem; font-weight: bold; margin-bottom: 0.3rem;">
                                    <i class="fas fa-clock"></i> ${e.time}
                                </div>
                                <div class="event-client" style="color: #cbd5e1; font-size: 0.75rem; margin-bottom: 0.3rem;">
                                    <i class="fas fa-user"></i> ${e.client_name}
                                </div>
                                ${e.notes ? `
                                <div class="event-notes" style="font-size: 0.75rem; color: #cbd5e1; background: rgba(0,0,0,0.2); padding: 0.4rem; border-radius: 4px; line-height: 1.4;">
                                    <i class="fas fa-info-circle"></i> ${e.notes}
                                </div>` : ''}
                            </div>
                        `).join('');
                        tooltipHtml = `<div class="cal-tooltip">${items}</div>`;
                    }
                    grid.innerHTML += `<div class="${classList}">${day}${tooltipHtml}</div>`;
                }
            }

            renderCalendar();

            const prevBtn = document.getElementById('cal-prev');
            const nextBtn = document.getElementById('cal-next');
            if (prevBtn && !prevBtn._hasCalListener) {
                prevBtn._hasCalListener = true;
                prevBtn.addEventListener('click', () => {
                    currentDate.setMonth(currentDate.getMonth() - 1);
                    renderCalendar();
                });
            }
            if (nextBtn && !nextBtn._hasCalListener) {
                nextBtn._hasCalListener = true;
                nextBtn.addEventListener('click', () => {
                    currentDate.setMonth(currentDate.getMonth() + 1);
                    renderCalendar();
                });
            }

            /* 2. Charts */
            if (typeof Chart === 'undefined') return;

            const isDark       = document.documentElement.getAttribute('data-theme') === 'dark';
            const colorText    = isDark ? '#cbd5e1' : '#475569';
            const colorHeading = isDark ? '#f8fafc' : '#1e293b';
            const colorMuted   = isDark ? '#94a3b8' : '#64748b';
            const colorGrid    = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.05)';
            const tooltipBg    = isDark ? '#1e293b' : '#0f172a';
            const tooltipBorder= isDark ? '#334155' : 'transparent';
            const colorGold    = '#d4af37';
            const pointBg      = isDark ? '#0f172a' : '#1e293b';

            if (typeof Chart !== 'undefined') {
                Chart.defaults.color       = colorText;
                Chart.defaults.font.family = 'inherit';
                Chart.defaults.borderColor = colorGrid;
            }

            function showEmptyChart(wrapperId, canvasId, iconClass) {
                const wrapper = document.getElementById(wrapperId);
                const canvas  = document.getElementById(canvasId);
                if (canvas) canvas.style.display = 'none';
                if (wrapper && !wrapper.querySelector('.chart-empty-state')) {
                    const emptyDiv = document.createElement('div');
                    emptyDiv.className = 'chart-empty-state';
                    emptyDiv.innerHTML = `<i class="${iconClass}"></i><p>{{ __('لا توجد بيانات لعرضها حتى الآن') }}</p>`;
                    wrapper.appendChild(emptyDiv);
                }
            }

            // Line chart
            const trendCasesData = @json($chartCasesCount ?? []);
            const trendLabels    = @json($monthsLabels ?? []);
            const hasTrendData   = trendCasesData.some(v => v > 0);

            const trendCanvas = document.getElementById('casesTrendChart');
            if (trendCanvas) {
                if (!hasTrendData) {
                    showEmptyChart('trendChartWrapper', 'casesTrendChart', 'fas fa-chart-area');
                } else {
                    const trendWrapper = document.getElementById('trendChartWrapper');
                    if (trendWrapper) {
                        const existingEmpty = trendWrapper.querySelector('.chart-empty-state');
                        if (existingEmpty) existingEmpty.remove();
                    }
                    trendCanvas.style.display = 'block';
                    const trendCtx = trendCanvas.getContext('2d');
                    let gradientFill = trendCtx.createLinearGradient(0, 0, 0, 300);
                    gradientFill.addColorStop(0, isDark ? 'rgba(212, 175, 55, 0.35)' : 'rgba(212, 175, 55, 0.4)');
                    gradientFill.addColorStop(1, 'rgba(212, 175, 55, 0.0)');

                    if (window._trendChartInstance) window._trendChartInstance.destroy();
                    window._trendChartInstance = new Chart(trendCtx, {
                        type: 'line',
                        data: {
                            labels: trendLabels,
                            datasets: [{
                                label: '{{ __('عدد القضايا المضافة') }}',
                                data: trendCasesData,
                                borderColor: colorGold,
                                backgroundColor: gradientFill,
                                borderWidth: 3,
                                pointBackgroundColor: pointBg,
                                pointBorderColor: colorGold,
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: tooltipBg,
                                    titleColor: colorHeading,
                                    bodyColor: colorText,
                                    borderColor: tooltipBorder,
                                    borderWidth: isDark ? 1 : 0,
                                    titleFont: { family: 'inherit', size: 13 },
                                    bodyFont: { family: 'inherit', size: 14, weight: 'bold' },
                                    padding: 10,
                                    displayColors: false,
                                    rtl: !isEnglish
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { color: colorMuted, font: { family: 'inherit' } }
                                },
                                y: {
                                    grid: { color: colorGrid },
                                    ticks: { color: colorMuted, font: { family: 'inherit' }, stepSize: 1 },
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            }

            // Donut chart
            const statusLabels  = @json($chartStatusLabels ?? []);
            const statusData    = @json($chartStatusData ?? []);
            const hasStatusData = statusData.length > 0 && statusData.some(v => v > 0);

            const statusCanvas = document.getElementById('casesStatusChart');
            if (statusCanvas) {
                if (!hasStatusData) {
                    showEmptyChart('statusChartWrapper', 'casesStatusChart', 'fas fa-chart-pie');
                } else {
                    const statusWrapper = document.getElementById('statusChartWrapper');
                    if (statusWrapper) {
                        const existingEmpty = statusWrapper.querySelector('.chart-empty-state');
                        if (existingEmpty) existingEmpty.remove();
                    }
                    statusCanvas.style.display = 'block';
                    const statusCtx = statusCanvas.getContext('2d');

                    const statusColorMap = {
                        'مفتوحة':       isDark ? '#34d399' : '#10b981',
                        'Open':          isDark ? '#34d399' : '#10b981',
                        'متداولة':      isDark ? '#60a5fa' : '#2563eb',
                        'In Progress':   isDark ? '#60a5fa' : '#2563eb',
                        'مؤجلة':        isDark ? '#fbbf24' : '#d97706',
                        'Adjourned':     isDark ? '#fbbf24' : '#d97706',
                        'محجوزة للحكم': isDark ? '#c084fc' : '#7c3aed',
                        'Reserved for Ruling': isDark ? '#c084fc' : '#7c3aed',
                        'منتهية':       isDark ? '#94a3b8' : '#64748b',
                        'Closed':        isDark ? '#94a3b8' : '#64748b',
                        'مستأنفة':      isDark ? '#f472b6' : '#db2777',
                        'Appealed':      isDark ? '#f472b6' : '#db2777',
                        'محفوظة':       isDark ? '#38bdf8' : '#0284c7',
                        'Archived':      isDark ? '#38bdf8' : '#0284c7',
                        'معلقة':        isDark ? '#f87171' : '#dc2626',
                    };
                    const fallbackPalette = isDark
                        ? ['#34d399', '#60a5fa', '#fbbf24', '#c084fc', '#f472b6', '#38bdf8', '#f87171']
                        : ['#10b981', '#2563eb', '#d97706', '#7c3aed', '#db2777', '#0284c7', '#dc2626'];

                    const bgColors = statusLabels.map((lbl, i) => statusColorMap[lbl] || fallbackPalette[i % fallbackPalette.length]);

                    if (window._statusChartInstance) window._statusChartInstance.destroy();
                    window._statusChartInstance = new Chart(statusCtx, {
                        type: 'doughnut',
                        data: {
                            labels: statusLabels,
                            datasets: [{
                                data: statusData,
                                backgroundColor: bgColors,
                                borderWidth: isDark ? 2 : 0,
                                borderColor: isDark ? '#1e293b' : 'transparent',
                                hoverOffset: 5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '70%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    rtl: !isEnglish,
                                    labels: {
                                        color: colorText,
                                        font: { family: 'inherit', size: 12, weight: 'bold' },
                                        usePointStyle: true,
                                        padding: 20
                                    }
                                },
                                tooltip: {
                                    backgroundColor: tooltipBg,
                                    titleColor: colorHeading,
                                    bodyColor: colorText,
                                    borderColor: tooltipBorder,
                                    borderWidth: isDark ? 1 : 0,
                                    bodyFont: { family: 'inherit', size: 13 },
                                    rtl: !isEnglish
                                }
                            }
                        }
                    });
                }
            }
        }

        if (document.readyState !== 'loading') {
            initDashboardCharts();
        } else {
            document.addEventListener('DOMContentLoaded', initDashboardCharts);
        }
        document.addEventListener('livewire:navigated', () => {
            initDashboardCharts();
        });

        if (!window._dashboardThemeObserver) {
            window._dashboardThemeObserver = new MutationObserver((mutations) => {
                for (const m of mutations) {
                    if (m.attributeName === 'data-theme') {
                        initDashboardCharts();
                        break;
                    }
                }
            });
            window._dashboardThemeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
        }
    })();
    </script>
    @endpush
</div>
