{{-- resources/views/appointments/index.blade.php --}}

@extends('layouts.app')

@section('title', 'إدارة المواعيد | ' . $appName)

@push('styles')
<style>
    .appointments-page-container { padding: 2rem; }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .welcome-title { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; }
    .date-text { color: var(--text-secondary); font-size: 0.95rem; }

    .panel {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
    }

    .search-panel {
        background-color: rgba(30, 41, 59, 0.02);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.5rem;
    }

    .table-responsive { overflow-x: auto; }

    .custom-table { width: 100%; border-collapse: collapse; text-align: right; }
    .custom-table th {
        color: var(--text-secondary);
        font-size: 0.85rem;
        padding: 1rem 0.75rem;
        border-bottom: 2px solid var(--primary-bg);
        white-space: nowrap;
    }
    .custom-table td {
        padding: 1rem 0.75rem;
        border-bottom: 1px solid var(--primary-bg);
        font-size: 0.95rem;
        color: var(--text-primary);
        vertical-align: middle;
    }
    .custom-table tbody tr:hover { background-color: rgba(244, 246, 249, 0.5); }

    .btn-action-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger-color);
        border: 1px solid transparent;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-action-delete:hover {
        background-color: var(--danger-color);
        color: #ffffff;
        transform: scale(1.05);
    }

    .empty-state-container { text-align: center; padding: 4rem 1rem; }
    .empty-state-icon-wrapper {
        width: 90px; height: 90px;
        background-color: rgba(30, 41, 59, 0.03);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.5rem;
    }
    .empty-state-icon-wrapper i { font-size: 3.5rem; color: var(--border-color); }

    @media (max-width: 768px) {
        .appointments-page-container { padding: 1rem; }
        .dashboard-header { flex-direction: column; align-items: stretch; }
    }
</style>
@endpush

@section('content')

<div class="appointments-page-container">

    {{-- ===== الترويسة ===== --}}
    <div class="dashboard-header">
        <div>
            <h1 class="welcome-title">إدارة المواعيد والجلسات</h1>
            <p class="date-text">عرض وتتبع جميع المواعيد المسجلة في النظام</p>
        </div>
    </div>

    {{-- ===== فورم البحث ===== --}}
    <div class="search-panel">
        <form method="GET" action="{{ route('appointments.index') }}" 
              style="display: flex; gap: 0.75rem; align-items: flex-end; flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 250px;">
                <label for="search" 
                       style="display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-weight: 600; font-size: 0.95rem;">
                    البحث برقم القضية
                </label>
                <input type="text" id="search" name="search"
                    placeholder="ادخل رقم القضية..."
                    value="{{ request('search') }}"
                    style="width: 100%; padding: 0.7rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.95rem; color: var(--text-primary); background-color: #ffffff; transition: all 0.3s ease;"
                    onfocus="this.style.borderColor='var(--sidebar-bg)'; this.style.boxShadow='0 0 0 3px rgba(30,41,59,0.1)';"
                    onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none';">
            </div>

            <button type="submit"
                style="padding: 0.7rem 1.5rem; background-color: var(--sidebar-bg); color: #ffffff; border: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease;"
                onmouseover="this.style.backgroundColor='#1a2a47'; this.style.transform='translateY(-2px)';"
                onmouseout="this.style.backgroundColor='var(--sidebar-bg)'; this.style.transform='translateY(0)';">
                <i class="fas fa-search"></i>
                <span>بحث</span>
            </button>

            @if(request('search'))
                <a href="{{ route('appointments.index') }}"
                    style="padding: 0.7rem 1.5rem; background-color: rgba(107,114,128,0.1); color: var(--text-primary); border: 1px solid rgba(107,114,128,0.3); border-radius: 8px; font-weight: 600; font-size: 0.95rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease;">
                    <i class="fas fa-times"></i>
                    <span>مسح البحث</span>
                </a>
            @endif

        </form>

        @if(request('search'))
            <div style="margin-top: 1rem; padding: 0.75rem 1rem; background-color: rgba(59,130,246,0.1); border-right: 3px solid #3b82f6; border-radius: 4px; color: var(--text-primary); font-size: 0.95rem;">
                <i class="fas fa-info-circle" style="margin-right: 0.5rem; color: #3b82f6;"></i>
                نتائج البحث عن: <strong>{{ request('search') }}</strong>
            </div>
        @endif
    </div>

    {{-- ===== الجدول أو Empty State ===== --}}
    <div class="panel">
        @if($appointments->isEmpty())

            @if(request('search'))
                {{-- لا يوجد نتائج بحث --}}
                <div class="empty-state-container">
                    <div class="empty-state-icon-wrapper">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 style="color: var(--text-primary); font-size: 1.4rem; margin-bottom: 0.5rem; font-weight: 800;">
                        لم يتم العثور على نتائج
                    </h3>
                    <p style="color: var(--text-secondary); font-size: 1rem; margin-bottom: 1.5rem;">
                        لا توجد مواعيد مطابقة لرقم القضية "{{ request('search') }}".
                    </p>
                    <a href="{{ route('appointments.index') }}"
                        style="display: inline-flex; align-items: center; gap: 0.5rem; background-color: rgba(107,114,128,0.1); color: var(--text-primary); padding: 0.7rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600;">
                        <i class="fas fa-arrow-right"></i>
                        <span>عرض جميع المواعيد</span>
                    </a>
                </div>
            @else
                {{-- لا توجد مواعيد أصلاً --}}
                <div class="empty-state-container">
                    <div class="empty-state-icon-wrapper">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h3 style="color: var(--text-primary); font-size: 1.4rem; margin-bottom: 0.5rem; font-weight: 800;">
                        لا توجد مواعيد مسجلة
                    </h3>
                    <p style="color: var(--text-secondary); font-size: 1rem; margin-bottom: 0;">
                        يمكنك إضافة موعد جديد من داخل صفحة القضية الخاصة بها.
                    </p>
                </div>
            @endif

        @else
            {{-- ===== الجدول ===== --}}
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 15%;">رقم القضية</th>
                            <th style="width: 20%;">التاريخ والوقت</th>
                            <th style="width: 25%;">ملاحظات</th>
                            <th style="width: 10%; text-align: center;">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td><strong>{{ $loop->iteration }}</strong></td>

                            <td>
                                <span class="badge" style="background-color: rgba(30,41,59,0.08); color: var(--text-primary); border: 1px solid rgba(30,41,59,0.1);">
                                    {{ $appointment->case?->case_number ?? '—' }}
                                </span>
                            </td>

                            {{-- تم تعديل هذا الجزء لعرض التاريخ والوقت منفصلين --}}
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 0.3rem;">
                                    <span style="font-weight: 600; color: var(--sidebar-bg);">
                                        <i class="fas fa-calendar-day" style="margin-left: 0.3rem; color: var(--text-secondary);"></i>
                                        {{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d') }}
                                    </span>
                                    <span style="font-weight: 600; color: var(--gold-accent); font-size: 0.85rem;">
                                        <i class="fas fa-clock" style="margin-left: 0.3rem;"></i>
                                        {{ $appointment->time ? \Carbon\Carbon::parse($appointment->time)->format('h:i A') : 'غير محدد' }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div style="max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text-secondary); font-size: 0.95rem;" 
                                     title="{{ $appointment->notes }}">
                                    {{ $appointment->notes ?: 'لا توجد ملاحظات' }}
                                </div>
                            </td>

                            <td style="text-align: center;">
                                <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-delete" title="حذف الموعد"
                                        onclick="return confirm('هل أنت متأكد من حذف هذا الموعد نهائياً؟')">
                                        <i class="fas fa-trash-alt" style="font-size: 1rem;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

@endsection