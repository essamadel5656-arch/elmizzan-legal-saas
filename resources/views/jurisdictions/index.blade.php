@extends('layouts.app')

@section('title', 'جهات التقاضي | ' . $appName)

@push('styles')
<style>
    /* ===== الحاوية والترويسة ===== */
    .jurisdictions-page-container { padding: 2rem; }
    
    .dashboard-header { 
        display: flex; justify-content: space-between; align-items: center; 
        flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem; 
    }
    
    .welcome-title { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; }
    .date-text { color: var(--text-secondary); font-size: 0.95rem; }

    /* ===== أزرار الهيدر ===== */
    .header-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    /* الزرار الثانوي (إضافة محكمة) */
    .btn-action-secondary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 20px; border-radius: 50px;
        background: transparent; color: var(--sidebar-bg);
        border: 2px solid var(--sidebar-bg);
        font-weight: 700; font-size: 0.95rem; text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-action-secondary:hover {
        background: var(--sidebar-bg); color: #ffffff;
        transform: translateY(-2px); box-shadow: 0 4px 12px rgba(30, 41, 59, 0.15);
    }

    /* الزرار الرئيسي الموحد (إضافة جهة تقاضي) */
    .btn-add-new {
        display: inline-flex; align-items: center; gap: 12px;
        background-color: var(--sidebar-bg); color: #ffffff;
        padding: 6px 20px 6px 8px; border-radius: 50px;
        text-decoration: none; font-weight: 700; font-size: 0.95rem;
        transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(30, 41, 59, 0.15);
        border: 2px solid var(--sidebar-bg);
    }
    .btn-add-new .icon-circle {
        background-color: var(--gold-accent); color: var(--sidebar-bg);
        width: 34px; height: 34px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; transition: transform 0.3s ease; flex-shrink: 0;
    }
    .btn-add-new:hover {
        transform: translateY(-2px); box-shadow: 0 6px 16px rgba(30, 41, 59, 0.25);
        background-color: #ffffff; color: var(--sidebar-bg);
    }
    .btn-add-new:hover .icon-circle {
        transform: rotate(90deg); background-color: var(--sidebar-bg); color: var(--gold-accent);
    }

    /* ===== البانل والجدول ===== */
    .panel { 
        background-color: #ffffff; border: 1px solid var(--border-color); 
        border-radius: 12px; padding: 1.5rem; box-shadow: var(--shadow-sm); 
    }
    .table-responsive { overflow-x: auto; }
    .custom-table { width: 100%; border-collapse: collapse; text-align: right; }
    .custom-table th { 
        color: var(--text-secondary); font-size: 0.85rem; 
        padding: 1rem 0.75rem; border-bottom: 2px solid var(--primary-bg); 
        white-space: nowrap;
    }
    .custom-table td { 
        padding: 1rem 0.75rem; border-bottom: 1px solid var(--primary-bg); 
        font-size: 0.95rem; color: var(--text-primary); vertical-align: middle;
    }
    .custom-table tbody tr:hover { background-color: rgba(244, 246, 249, 0.5); }

    /* ===== تنسيق بيانات الجهة والمحاكم ===== */
    .jurisdiction-name { font-weight: 800; font-size: 1.05rem; color: var(--text-primary); display: block; }
    .courts-count { font-size: 0.85rem; color: var(--text-secondary); margin-top: 4px; font-weight: 600; }
    
    .courts-badges-container { display: flex; flex-wrap: wrap; gap: 0.6rem; }
    .badge-court {
        background-color: var(--primary-bg); border: 1px solid var(--border-color);
        color: var(--text-primary); padding: 0.4rem 0.75rem;
        border-radius: 8px; font-size: 0.85rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 0.4rem;
        transition: 0.2s;
    }
    .badge-court:hover { border-color: var(--gold-accent); background-color: #ffffff; box-shadow: var(--shadow-sm); }
    
    .badge-court-level {
        background-color: var(--sidebar-bg); color: #ffffff;
        font-size: 0.7rem; padding: 0.15rem 0.5rem;
        border-radius: 4px; margin-right: 0.4rem;
    }
    
    .empty-courts-text {
        font-size: 0.85rem; color: var(--text-secondary); opacity: 0.8;
        display: flex; align-items: center; gap: 0.4rem; font-weight: 500;
    }

    /* ===== أزرار الإجراءات ===== */
    .btn-action { 
        display: inline-flex; align-items: center; justify-content: center; 
        width: 34px; height: 34px; border-radius: 8px; font-size: 0.9rem; 
        cursor: pointer; transition: all 0.2s ease; border: 1px solid transparent; 
        text-decoration: none;
    }
    .btn-action-edit { background: rgba(212, 175, 55, 0.1); color: #9a7b21; border-color: rgba(212, 175, 55, 0.2); }
    .btn-action-edit:hover { background: var(--gold-accent); color: #ffffff; }
    
    .btn-action-delete { background: rgba(239, 68, 68, 0.08); color: var(--danger-color); border-color: rgba(239, 68, 68, 0.2); }
    .btn-action-delete:hover { background: var(--danger-color); color: #ffffff; }

    /* ===== الـ Empty State ===== */
    .empty-state-container { text-align: center; padding: 4rem 1rem; }
    .empty-state-icon-wrapper { 
        width: 90px; height: 90px; background-color: rgba(30, 41, 59, 0.03); 
        border-radius: 50%; display: flex; align-items: center; justify-content: center; 
        margin: 0 auto 1.5rem; box-shadow: inset 0 0 20px rgba(0,0,0,0.02); 
    }
    .empty-state-icon-wrapper i { font-size: 3.5rem; color: var(--border-color); }

    @media (max-width: 768px) {
        .jurisdictions-page-container { padding: 1rem; }
        .header-actions { width: 100%; flex-direction: column; }
        .header-actions a { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="jurisdictions-page-container">

    <div class="dashboard-header">
        <div>
            <h1 class="welcome-title">إدارة جهات التقاضي</h1>
            <p class="date-text">إعداد وتكوين جهات التقاضي والمحاكم التابعة لها</p>
        </div>
        
        <div class="header-actions">
            <a href="{{ route('courts.create') }}" class="btn-action-secondary">
                <i class="fas fa-landmark"></i> إضافة محكمة
            </a>
            
            <a href="{{ route('jurisdictions.create') }}" class="btn-add-new">
                <span class="icon-circle">
                    <i class="fas fa-plus"></i>
                </span>
                <span>إضافة جهة تقاضي</span>
            </a>
        </div>
    </div>

    <div class="panel">
        @if($jurisdictions->count() > 0)
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 8%; text-align: center;">المعرف</th>
                            <th style="width: 25%;">جهة التقاضي</th>
                            <th style="width: 52%;">المحاكم التابعة لها</th>
                            <th style="width: 15%; text-align: center;">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jurisdictions as $jurisdiction)
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: var(--sidebar-bg);">
                                    #{{ $jurisdiction->id }}
                                </td>

                                <td>
                                    <span class="jurisdiction-name">{{ $jurisdiction->name }}</span>
                                    <div class="courts-count">
                                        <i class="fas fa-sitemap" style="color: var(--gold-accent); margin-left: 3px;"></i>
                                        {{ $jurisdiction->courts->count() }} محكمة
                                    </div>
                                </td>

                                <td>
                                    <div class="courts-badges-container">
                                        @forelse($jurisdiction->courts as $court)
                                            <span class="badge-court">
                                                <i class="fas fa-balance-scale" style="color: var(--gold-accent);"></i>
                                                {{ $court->name }}
                                                @if($court->level)
                                                    <span class="badge-court-level">{{ $court->level->name }}</span>
                                                @endif
                                            </span>
                                        @empty
                                            <span class="empty-courts-text">
                                                <i class="fas fa-exclamation-circle" style="color: var(--gold-accent);"></i>
                                                لا توجد محاكم مسجلة بعد تابعة لهذه الجهة.
                                            </span>
                                        @endforelse
                                    </div>
                                </td>

                                <td style="text-align: center;">
                                    <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                        <a href="{{ route('jurisdictions.edit', $jurisdiction->id) }}" class="btn-action btn-action-edit" title="تعديل جهة التقاضي">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('jurisdictions.destroy', $jurisdiction->id) }}" method="POST" style="margin: 0; display: inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف جهة التقاضي: {{ $jurisdiction->name }}؟\nتنبيه: سيتم حذف جميع المحاكم التابعة لها!');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete" title="حذف جهة التقاضي">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state-container">
                <div class="empty-state-icon-wrapper">
                    <i class="fas fa-sitemap"></i>
                </div>
                <h3 style="color: var(--text-primary); font-size: 1.4rem; margin-bottom: 0.5rem; font-weight: 800;">لا توجد جهات تقاضي مسجلة</h3>
                <p style="color: var(--text-secondary); font-size: 1rem; margin-bottom: 2.5rem;">لم يتم إضافة أي جهات تقاضي للنظام حتى الآن. قم بإضافة جهات التقاضي لربط المحاكم بها لاحقاً.</p>
                
                <a href="{{ route('jurisdictions.create') }}" class="btn-add-new">
                    <span class="icon-circle">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span>إضافة أول جهة تقاضي</span>
                </a>
            </div>
        @endif
    </div>

</div>
@endsection