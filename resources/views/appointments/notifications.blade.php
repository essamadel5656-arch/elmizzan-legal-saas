@extends('layouts.app')

@section('title', 'الإشعارات والمواعيد | ' . $appName)

@push('styles')
<style>
    .notifications-page-container { padding: 2rem; max-width: 1100px; margin: 0 auto; }

    /* ===== الترويسة ===== */
    .dashboard-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;
    }
    .header-info h1 { font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.4rem; }
    .header-info p  { color: var(--text-secondary); font-size: 0.95rem; margin: 0; }

    .btn-view-all {
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--sidebar-bg); color: #fff;
        padding: 10px 22px; border-radius: 8px; font-weight: 700;
        text-decoration: none; transition: 0.3s;
    }
    .btn-view-all:hover { background: var(--gold-accent); color: var(--sidebar-bg); transform: translateY(-2px); }

    /* ===== شريط التلخيص ===== */
    .summary-bar {
        display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;
    }
    .summary-chip {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.5rem 1.1rem; border-radius: 50px; font-weight: 700; font-size: 0.9rem;
    }
    .chip-red    { background: rgba(239,68,68,0.1);   color: var(--danger-color);  border: 1px solid rgba(239,68,68,0.2); }
    .chip-gold   { background: rgba(212,175,55,0.1);  color: #9a7b21;              border: 1px solid rgba(212,175,55,0.2); }
    .chip-blue   { background: rgba(30,41,59,0.07);   color: var(--sidebar-bg);    border: 1px solid rgba(30,41,59,0.12); }

    /* ===== مجموعة التاريخ ===== */
    .date-group    { margin-bottom: 2.5rem; }
    .date-label {
        display: flex; align-items: center; gap: 0.75rem;
        margin-bottom: 1.25rem; padding: 0.75rem 1.25rem;
        border-radius: 10px; font-weight: 800; font-size: 1.05rem;
    }
    .date-label.tomorrow {
        background: rgba(239,68,68,0.06); color: var(--danger-color);
        border: 1px solid rgba(239,68,68,0.15); border-right: 4px solid var(--danger-color);
    }
    .date-label.upcoming {
        background: rgba(30,41,59,0.04); color: var(--sidebar-bg);
        border: 1px solid rgba(30,41,59,0.1); border-right: 4px solid var(--sidebar-bg);
    }
    .date-label .count-bubble {
        margin-right: auto; background: rgba(30,41,59,0.1); color: var(--sidebar-bg);
        width: 26px; height: 26px; border-radius: 50%; font-size: 0.85rem;
        display: flex; align-items: center; justify-content: center;
    }
    .date-label.tomorrow .count-bubble { background: rgba(239,68,68,0.15); color: var(--danger-color); }

    /* ===== كرت الموعد ===== */
    .appointment-card {
        background: #ffffff; border: 1px solid var(--border-color);
        border-right: 4px solid var(--gold-accent);
        border-radius: 10px; padding: 1.5rem;
        margin-bottom: 1rem; transition: all 0.3s;
        box-shadow: var(--shadow-sm);
    }
    .appointment-card:hover { transform: translateX(-4px); border-right-color: var(--sidebar-bg); box-shadow: 0 4px 14px rgba(0,0,0,0.07); }
    .appointment-card.urgent { border-right-color: var(--danger-color); }
    .appointment-card.urgent:hover { border-right-color: var(--danger-color); }

    /* صف الترويسة في الكرت */
    .card-top {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1.1rem; padding-bottom: 1rem;
        border-bottom: 1px dashed var(--border-color); flex-wrap: wrap; gap: 0.75rem;
    }
    .card-case-number {
        font-size: 1.1rem; font-weight: 800; color: var(--sidebar-bg);
        display: flex; align-items: center; gap: 0.5rem;
    }
    .card-case-number i { color: var(--gold-accent); }

    /* الوقت والتاريخ معاً */
    .card-datetime {
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
    }
    .datetime-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.35rem 0.85rem; border-radius: 6px; font-weight: 700; font-size: 0.88rem;
    }
    .badge-date { background: rgba(30,41,59,0.07);   color: var(--sidebar-bg); }
    .badge-time { background: rgba(212,175,55,0.12); color: #9a7b21; }
    .badge-urgent-time { background: rgba(239,68,68,0.1); color: var(--danger-color); }

    /* صف التفاصيل */
    .card-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.75rem; margin-bottom: 0.75rem; }
    .detail-row { display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.92rem; }
    .detail-row .lbl { color: var(--text-secondary); font-weight: 700; white-space: nowrap; display: flex; align-items: center; gap: 0.35rem; }
    .detail-row .lbl i { color: var(--gold-accent); width: 14px; text-align: center; }
    .detail-row .val { color: var(--text-primary); font-weight: 600; line-height: 1.5; }

    /* الملاحظات */
    .card-notes {
        background: rgba(212,175,55,0.05); border-right: 3px solid var(--gold-accent);
        padding: 0.75rem 1rem; border-radius: 8px; margin-top: 0.5rem;
        font-size: 0.9rem; color: var(--text-primary); line-height: 1.6;
    }
    .card-notes .notes-label { font-weight: 800; color: #9a7b21; display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.3rem; font-size: 0.85rem; }

    /* ===== Empty State ===== */
    .empty-state-container {
        text-align: center; padding: 5rem 1rem;
        background: #ffffff; border: 1px solid var(--border-color); border-radius: 12px;
    }
    .empty-icon { font-size: 4rem; color: var(--border-color); margin-bottom: 1.5rem; display: block; }

    @media (max-width: 640px) {
        .notifications-page-container { padding: 1rem; }
        .card-details { grid-template-columns: 1fr; }
        .card-top { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')
<div class="notifications-page-container">

    {{-- ===== الترويسة ===== --}}
    <div class="dashboard-header">
        <div class="header-info">
            <h1>
                <i class="fas fa-bell" style="color: var(--gold-accent); margin-left: 8px;"></i>
                الإشعارات والمواعيد
            </h1>
            <p>مواعيد الأسبوع القادم — {{ \Carbon\Carbon::now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}</p>
        </div>
        <a href="{{ route('appointments.index') }}" class="btn-view-all">
            <i class="fas fa-calendar-alt"></i> كل المواعيد
        </a>
    </div>

    @if($appointments->isEmpty())
        <div class="empty-state-container">
            <i class="fas fa-calendar-check empty-icon"></i>
            <h3 style="color: var(--text-primary); font-size: 1.4rem; font-weight: 800; margin-bottom: 0.5rem;">
                لا توجد مواعيد قريبة
            </h3>
            <p style="color: var(--text-secondary); font-size: 1rem;">
                لا توجد جلسات أو مواعيد مجدولة في الأيام السبعة القادمة.
            </p>
        </div>
    @else
        @php
            $appointmentsByDate = $appointments->groupBy(function ($appt) {
                return \Carbon\Carbon::parse($appt->date)->format('Y-m-d');
            })->sortKeys();

            $tomorrow = \Carbon\Carbon::tomorrow()->format('Y-m-d');
            $today    = \Carbon\Carbon::today()->format('Y-m-d');
        @endphp

        {{-- ===== شريط التلخيص ===== --}}
        <div class="summary-bar">
            <span class="summary-chip chip-blue">
                <i class="fas fa-calendar-week"></i>
                إجمالي المواعيد: {{ $appointments->count() }}
            </span>
            @if(isset($appointmentsByDate[$tomorrow]))
                <span class="summary-chip chip-red">
                    <i class="fas fa-exclamation-circle"></i>
                    مواعيد غداً: {{ $appointmentsByDate[$tomorrow]->count() }}
                </span>
            @endif
            <span class="summary-chip chip-gold">
                <i class="fas fa-layer-group"></i>
                أيام مختلفة: {{ $appointmentsByDate->count() }}
            </span>
        </div>

        {{-- ===== المواعيد مجمعة حسب التاريخ ===== --}}
        @foreach($appointmentsByDate as $date => $dateAppointments)
            @php
                $isToday    = $date === $today;
                $isTomorrow = $date === $tomorrow;
                $parsedDate = \Carbon\Carbon::parse($date)->locale('ar');
            @endphp

            <div class="date-group">

                {{-- عنوان التاريخ --}}
                <div class="date-label {{ $isTomorrow ? 'tomorrow' : 'upcoming' }}">
                    <i class="fas {{ $isTomorrow ? 'fa-exclamation-circle' : 'fa-calendar-day' }}"></i>
                    @if($isToday)
                        اليوم — {{ $parsedDate->isoFormat('dddd، D MMMM') }}
                    @elseif($isTomorrow)
                        غداً ⚠️ — {{ $parsedDate->isoFormat('dddd، D MMMM') }}
                    @else
                        {{ $parsedDate->isoFormat('dddd، D MMMM YYYY') }}
                    @endif
                    <span class="count-bubble">{{ $dateAppointments->count() }}</span>
                </div>

                {{-- كروت المواعيد --}}
                @foreach($dateAppointments as $appointment)
                    @php
                        $timeFormatted = $appointment->time
                            ? \Carbon\Carbon::parse($appointment->time)->format('h:i A')
                            : null;
                    @endphp

                    <div class="appointment-card {{ $isTomorrow ? 'urgent' : '' }}">

                        {{-- ترويسة الكرت --}}
                        <div class="card-top">
                            <div class="card-case-number">
                                <i class="fas fa-gavel"></i>
                                قضية رقم: {{ $appointment->case?->case_number ?? '—' }}
                            </div>

                            <div class="card-datetime">
                                <span class="datetime-badge badge-date">
                                    <i class="fas fa-calendar"></i>
                                    {{ $parsedDate->isoFormat('D MMMM YYYY') }}
                                </span>
                                @if($timeFormatted)
                                    <span class="datetime-badge {{ $isTomorrow ? 'badge-urgent-time' : 'badge-time' }}">
                                        <i class="fas fa-clock"></i>
                                        {{ $timeFormatted }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- تفاصيل القضية --}}
                        <div class="card-details">
                            @if($appointment->case?->court?->name)
                                <div class="detail-row">
                                    <span class="lbl"><i class="fas fa-landmark"></i> المحكمة:</span>
                                    <span class="val">{{ $appointment->case->court->name }}</span>
                                </div>
                            @endif

                            @if($appointment->case?->status)
                                <div class="detail-row">
                                    <span class="lbl"><i class="fas fa-info-circle"></i> حالة القضية:</span>
                                    <span class="val">{{ $appointment->case->status }}</span>
                                </div>
                            @endif

                            @if($appointment->case?->rival_name)
                                <div class="detail-row">
                                    <span class="lbl"><i class="fas fa-user-shield"></i> الخصم:</span>
                                    <span class="val">{{ $appointment->case->rival_name }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- الملاحظات --}}
                        @if($appointment->notes)
                            <div class="card-notes">
                                <div class="notes-label">
                                    <i class="fas fa-thumbtack"></i> ملاحظات الجلسة
                                </div>
                                {{ $appointment->notes }}
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>
        @endforeach
    @endif

</div>
@endsection