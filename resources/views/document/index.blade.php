@extends('layouts.app')

@section('title', 'إدارة المستندات | ' . $appName)

@push('styles')
<style>
    /* ===== الحاوية والترويسة ===== */
    .documents-page-container { padding: 2rem; }
    
    .dashboard-header { 
        display: flex; justify-content: space-between; align-items: center; 
        flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem; 
    }
    
    .welcome-title { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; }
    .date-text { color: var(--text-secondary); font-size: 0.95rem; }

    /* ===== زر الإضافة الرئيسي (الموحد) ===== */
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

    /* ===== أزرار الإجراءات ===== */
    .btn-action { 
        display: inline-flex; align-items: center; justify-content: center; 
        width: 34px; height: 34px; border-radius: 8px; font-size: 0.9rem; 
        cursor: pointer; transition: all 0.2s ease; border: 1px solid transparent; 
        text-decoration: none;
    }
    .btn-action-view { background: rgba(30, 41, 59, 0.05); color: var(--sidebar-bg); border-color: rgba(30, 41, 59, 0.1); }
    .btn-action-view:hover { background: var(--sidebar-bg); color: #ffffff; }
    
    .btn-action-edit { background: rgba(212, 175, 55, 0.1); color: #9a7b21; border-color: rgba(212, 175, 55, 0.2); }
    .btn-action-edit:hover { background: var(--gold-accent); color: #ffffff; }
    
    .btn-action-delete { background: rgba(239, 68, 68, 0.08); color: var(--danger-color); border-color: rgba(239, 68, 68, 0.2); }
    .btn-action-delete:hover { background: var(--danger-color); color: #ffffff; }

    /* ===== شاشة الفراغ (Empty State) ===== */
    .empty-state-container { text-align: center; padding: 4rem 1rem; }
    .empty-state-icon-wrapper { 
        width: 90px; height: 90px; background-color: rgba(30, 41, 59, 0.03); 
        border-radius: 50%; display: flex; align-items: center; justify-content: center; 
        margin: 0 auto 1.5rem; box-shadow: inset 0 0 20px rgba(0,0,0,0.02); 
    }
    .empty-state-icon-wrapper i { font-size: 3.5rem; color: var(--border-color); }

    @media (max-width: 768px) {
        .documents-page-container { padding: 1rem; }
    }
</style>
@endpush

@section('content')
<div class="documents-page-container">

    <div class="dashboard-header">
        <div>
            <h1 class="welcome-title">إدارة المستندات</h1>
            <p class="date-text">عرض وتتبع جميع المرفقات والمستندات الخاصة بالقضايا</p>
        </div>
        
        <a href="{{ route('cases.index') }}" class="btn-add-new" title="الذهاب لصفحة القضايا لإضافة مستند">
            <span class="icon-circle">
                <i class="fas fa-file-upload"></i>
            </span>
            <div style="display: flex; flex-direction: column; align-items: flex-start; line-height: 1.2;">
                <span>إضافة مستند</span>
                <span style="font-size: 0.7rem; font-weight: 500; opacity: 0.8;">(من صفحة القضية)</span>
            </div>
        </a>
    </div>

    <div class="panel">
        {{-- ===== شريط البحث ===== --}}
<form method="GET" action="{{ route('document.index') }}" style="margin-bottom: 1.5rem;">
    <div style="display: flex; gap: 0.75rem; align-items: center; max-width: 450px;">
        <div style="position: relative; flex: 1;">
            <i class="fas fa-search" style="
                position: absolute; top: 50%; transform: translateY(-50%);
                right: 1rem; color: var(--text-secondary); pointer-events: none;
            "></i>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="ابحث باسم المستند..."
                style="
                    width: 100%; padding: 0.75rem 2.75rem 0.75rem 1rem;
                    border: 1px solid var(--border-color); border-radius: 8px;
                    font-family: inherit; font-size: 0.95rem;
                    background: var(--primary-bg); color: var(--text-primary);
                    transition: all 0.2s;
                "
                onfocus="this.style.borderColor='var(--gold-accent)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'; this.style.background='#fff';"
                onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'; this.style.background='var(--primary-bg)';"
            >
        </div>

        <button type="submit" style="
            padding: 0.75rem 1.25rem; background: var(--sidebar-bg); color: #fff;
            border: none; border-radius: 8px; font-weight: 700; cursor: pointer;
            font-family: inherit; font-size: 0.9rem; white-space: nowrap;
            transition: background 0.2s;
        "
        onmouseover="this.style.background='var(--gold-accent)'; this.style.color='var(--sidebar-bg)';"
        onmouseout="this.style.background='var(--sidebar-bg)'; this.style.color='#fff';">
            <i class="fas fa-search"></i> بحث
        </button>

        {{-- زر مسح البحث (يظهر فقط لو فيه بحث نشط) --}}
        @if(request('search'))
            <a href="{{ route('document.index') }}" style="
                padding: 0.75rem 1rem; background: rgba(239,68,68,0.08);
                color: var(--danger-color); border: 1px solid rgba(239,68,68,0.2);
                border-radius: 8px; text-decoration: none; font-weight: 700;
                font-size: 0.9rem; white-space: nowrap; transition: all 0.2s;
            "
            onmouseover="this.style.background='var(--danger-color)'; this.style.color='#fff';"
            onmouseout="this.style.background='rgba(239,68,68,0.08)'; this.style.color='var(--danger-color)';">
                <i class="fas fa-times"></i> مسح
            </a>
        @endif
    </div>

    {{-- عداد النتائج عند وجود بحث --}}
    @if(request('search'))
        <p style="margin-top: 0.6rem; font-size: 0.88rem; color: var(--text-secondary); font-weight: 600;">
            <i class="fas fa-filter" style="color: var(--gold-accent);"></i>
            نتائج البحث عن: "<strong style="color: var(--text-primary);">{{ request('search') }}</strong>"
            — {{ $documents->count() }} نتيجة
        </p>
    @endif
</form>

        {{-- ===== جدول المستندات ===== --}}
        @if($documents->count())
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 8%;">المعرف</th>
                            <th style="width: 25%;">عنوان المستند</th>
                            <th style="width: 35%;">النوع</th>
                            <th style="width: 15%;">تاريخ الإضافة</th>
                            <th style="width: 17%; text-align: center;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $doc)
                            <tr>
                                <td><strong>#{{ $doc->id }}</strong></td>
                                
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600;">
                                        <i class="fas fa-file-pdf" style="color: var(--danger-color); font-size: 1.2rem;"></i>
                                        <span>{{ $doc->title }}</span>
                                    </div>
                                </td>
                                
                                <td>
                                    <div style="display: inline-block; padding: 0.4rem 0.8rem; background-color: rgba(212, 175, 55, 0.1); border-radius: 6px; font-weight: 600; color: #9a7b21;">
                                        @if($doc->type === 'contract')
                                            <i class="fas fa-file-contract" style="margin-left: 0.4rem;"></i>عقد
                                        @elseif($doc->type === 'report')
                                            <i class="fas fa-file-alt" style="margin-left: 0.4rem;"></i>تقرير
                                        @elseif($doc->type === 'attachment')
                                            <i class="fas fa-paperclip" style="margin-left: 0.4rem;"></i>مرفق
                                        @else
                                            {{ $doc->type ?? '—' }}
                                        @endif
                                    </div>
                                </td>
                                
                                <td>
                                    <div style="direction: ltr; text-align: right; font-weight: 500;">
                                        {{ $doc->created_at->format('Y-m-d') }}
                                    </div>
                                </td>
                                
                                <td style="text-align: center;">
                                    <div style="display: flex; justify-content: center; gap: 0.4rem;">
                                        <a href="{{ route('document.show', $doc->id) }}" class="btn-action btn-action-view" title="عرض الملف">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <a href="{{ route('document.edit', $doc->id) }}" class="btn-action btn-action-edit" title="تعديل البيانات">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('document.destroy', $doc->id) }}" method="POST" style="margin: 0; display: inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستند نهائياً؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete" title="حذف المستند">
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
                    <i class="fas fa-file-excel"></i>
                </div>
                <h3 style="color: var(--text-primary); font-size: 1.4rem; margin-bottom: 0.5rem; font-weight: 800;">لا توجد مستندات مسجلة</h3>
                <p style="color: var(--text-secondary); font-size: 1rem; margin-bottom: 2.5rem;">لم يتم رفع أي مستندات للنظام حتى الآن. يتم إضافة المستندات مباشرة من داخل ملف كل قضية.</p>
                
                <a href="{{ route('cases.index') }}" class="btn-add-new">
                    <span class="icon-circle">
                        <i class="fas fa-folder-open"></i>
                    </span>
                    <span>الذهاب لصفحة القضايا</span>
                </a>
            </div>
        @endif
    </div>

</div>
@endsection