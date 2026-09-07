<div>
    <style>
        .portal-header { margin-bottom:2rem; }
        .portal-title  { font-size:1.6rem; font-weight:800; color:var(--text-primary); margin-bottom:.4rem; }
        .portal-subtitle { color:var(--text-secondary); font-size:.95rem; display:flex; align-items:center; gap:.5rem; }

        .portal-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:1.5rem; margin-bottom:2rem; }

        .portal-card {
            background:var(--card-bg,#fff); border:1px solid var(--border-color);
            border-radius:14px; padding:1.5rem;
            box-shadow:0 2px 8px rgba(0,0,0,.04); transition:.3s;
        }
        .portal-card:hover { box-shadow:0 8px 24px rgba(0,0,0,.08); transform:translateY(-2px); }

        .portal-card-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem; }
        .portal-card-title  { font-size:.85rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.5px; }
        .portal-card-icon   { width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.3rem; }

        .case-status-pill { padding:.25rem .75rem; border-radius:20px; font-size:.78rem; font-weight:700; }
        .status-مفتوحة    { background:rgba(34,197,94,.1); color:#15803d; }
        .status-متداولة   { background:rgba(59,130,246,.1); color:#2563eb; }
        .status-مؤجلة     { background:rgba(245,158,11,.1); color:#d97706; }
        .status-منتهية    { background:rgba(107,114,128,.1); color:#6b7280; }
        .status-default   { background:rgba(99,102,241,.1); color:#6366f1; }

        .doc-alert {
            display:flex; align-items:center; justify-content:space-between;
            padding:.85rem 1rem; border-radius:10px; margin-bottom:.6rem;
            border:1px solid rgba(239,68,68,.3); background:rgba(239,68,68,.05);
            animation:urgentPulse 2s infinite;
        }
        @keyframes urgentPulse { 0%,100%{border-color:rgba(239,68,68,.3);} 50%{border-color:rgba(239,68,68,.7);} }

        .portal-section-title { font-size:1.05rem; font-weight:800; color:var(--text-primary); display:flex; align-items:center; gap:.6rem; margin-bottom:1.25rem; }

        .fee-progress-bar { height:8px; border-radius:20px; background:rgba(0,0,0,.07); overflow:hidden; margin-top:.5rem; }
        .fee-progress-fill { height:100%; border-radius:20px; background:linear-gradient(90deg,#d4af37,#6366f1); transition:width 1s ease; }

        .hearing-item { display:flex; align-items:center; gap:1rem; padding:.75rem; border-radius:10px; margin-bottom:.5rem; background:rgba(212,175,55,.05); border:1px solid rgba(212,175,55,.15); }
        .hearing-date { font-size:.8rem; font-weight:800; color:var(--gold-accent,#d4af37); text-align:center; min-width:55px; }

        .payment-row { display:flex; justify-content:space-between; align-items:center; padding:.7rem 0; border-bottom:1px solid var(--primary-bg,#f1f5f9); }
        .payment-row:last-child { border-bottom:none; }
    </style>

    {{-- Header --}}
    <div class="portal-header">
        <h1 class="portal-title">👤 مرحباً، {{ $client->name }}</h1>
        <div class="portal-subtitle">
            <span>⚖️ {{ firm_name() }}</span>
            <span>·</span>
            <span>{{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('l، j F Y') }}</span>
        </div>
    </div>

    {{-- Urgent Document Alerts --}}
    @if($pendingDocRequests->isNotEmpty())
        <div style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);border-radius:12px;padding:1.25rem 1.5rem;margin-bottom:2rem;">
            <div style="font-weight:800;font-size:1rem;color:#dc2626;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                🚨 مستندات مطلوبة منك ({{ $pendingDocRequests->count() }})
            </div>
            @foreach($pendingDocRequests as $docReq)
                <div class="doc-alert">
                    <div>
                        <div style="font-weight:700;font-size:.9rem;color:var(--text-primary);">{{ $docReq->title }}</div>
                        @if($docReq->description)
                            <div style="font-size:.82rem;color:var(--text-secondary);">{{ $docReq->description }}</div>
                        @endif
                        <div style="font-size:.78rem;color:var(--text-secondary);margin-top:.2rem;">القضية: {{ $docReq->case?->case_number }}</div>
                    </div>
                    <a href="{{ route('client-portal.upload', $docReq->id) }}" wire:navigate
                       style="background:#dc2626;color:#fff;padding:.5rem 1.1rem;border-radius:8px;font-size:.85rem;font-weight:700;text-decoration:none;white-space:nowrap;">
                        📎 رفع الآن
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Cases Overview --}}
    <div class="portal-section-title">📁 قضاياي</div>
    <div class="portal-grid" style="margin-bottom:2rem;">
        @forelse($cases as $case)
            <div class="portal-card">
                <div class="portal-card-header">
                    <div>
                        <div class="portal-card-title">رقم القضية</div>
                        <div style="font-size:1.1rem;font-weight:800;color:var(--text-primary);">{{ $case['case_number'] }}</div>
                    </div>
                    <span class="case-status-pill status-{{ $case['status'] ?? 'default' }}" style="margin-top:.2rem;">
                        {{ $case['status'] }}
                    </span>
                </div>
                <div style="font-size:.85rem;color:var(--text-secondary);margin-bottom:.5rem;">
                    🏛 {{ $case['court'] }}
                </div>
                @if($case['next_hearing'])
                    <div style="background:rgba(212,175,55,.08);border:1px solid rgba(212,175,55,.2);border-radius:8px;padding:.5rem .85rem;font-size:.83rem;margin-bottom:.85rem;">
                        📅 الجلسة القادمة: <strong>{{ $case['next_hearing'] }}</strong>
                    </div>
                @endif
                {{-- Fee balance (client-visible portion only) --}}
                @if($case['agreed_fee'])
                    <div style="margin-top:.5rem;">
                        <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:.35rem;">
                            <span style="color:var(--text-secondary);">الأتعاب المتفق عليها</span>
                            <span style="font-weight:700;color:var(--text-primary);">{{ number_format($case['agreed_fee']) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:.35rem;">
                            <span style="color:#15803d;">✅ المدفوع</span>
                            <span style="color:#15803d;font-weight:700;">{{ number_format($case['paid']) }}</span>
                        </div>
                        @if($case['remaining'] > 0)
                            <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:.5rem;">
                                <span style="color:#dc2626;">💳 المتبقي</span>
                                <span style="color:#dc2626;font-weight:700;">{{ number_format($case['remaining']) }}</span>
                            </div>
                        @endif
                        @php $pct = $case['agreed_fee'] > 0 ? min(100, round(($case['paid'] / $case['agreed_fee']) * 100)) : 0; @endphp
                        <div class="fee-progress-bar"><div class="fee-progress-fill" style="width:{{ $pct }}%;"></div></div>
                        <div style="font-size:.78rem;color:var(--text-secondary);text-align:left;margin-top:.25rem;">{{ $pct }}%</div>
                    </div>
                @endif
            </div>
        @empty
            <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--text-secondary);">
                📂 لا توجد قضايا مرتبطة بحسابك حالياً.
            </div>
        @endforelse
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
        {{-- Upcoming Hearings --}}
        <div class="portal-card" style="height:fit-content;">
            <div class="portal-section-title">📅 الجلسات القادمة</div>
            @forelse($upcomingHearings as $h)
                <div class="hearing-item">
                    <div class="hearing-date">{{ \Carbon\Carbon::parse($h['date'])->format('j\nM') }}</div>
                    <div style="flex:1;">
                        <div style="font-weight:700;font-size:.9rem;color:var(--text-primary);">{{ $h['case_number'] }}</div>
                        @if($h['time']) <div style="font-size:.8rem;color:var(--text-secondary);">🕐 {{ $h['time'] }}</div> @endif
                        @if($h['notes']) <div style="font-size:.8rem;color:var(--text-secondary);">{{ $h['notes'] }}</div> @endif
                    </div>
                </div>
            @empty
                <div style="text-align:center;padding:1.5rem;color:var(--text-secondary);font-size:.9rem;">لا توجد جلسات قادمة.</div>
            @endforelse
        </div>

        {{-- Payment History --}}
        <div class="portal-card" style="height:fit-content;">
            <div class="portal-section-title">💳 سجل المدفوعات</div>
            @forelse($payments as $p)
                <div class="payment-row">
                    <div>
                        <div style="font-weight:700;font-size:.9rem;">{{ number_format($p->amount) }} ج.م.</div>
                        <div style="font-size:.78rem;color:var(--text-secondary);">{{ $p->case?->case_number }} · {{ $p->payment_date?->format('d/m/Y') ?? 'غير محدد' }}</div>
                    </div>
                    <span style="padding:.2rem .65rem;border-radius:20px;font-size:.78rem;font-weight:700;
                        background:{{ $p->isConfirmed() ? 'rgba(34,197,94,.1)' : 'rgba(245,158,11,.1)' }};
                        color:{{ $p->isConfirmed() ? '#15803d' : '#d97706' }};">
                        {{ $p->isConfirmed() ? '✅ مؤكد' : '⏳ قيد المراجعة' }}
                    </span>
                </div>
            @empty
                <div style="text-align:center;padding:1.5rem;color:var(--text-secondary);font-size:.9rem;">لا توجد مدفوعات مسجلة.</div>
            @endforelse
        </div>
    </div>
</div>
