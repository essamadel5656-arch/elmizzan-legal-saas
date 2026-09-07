@extends('layouts.app')
@section('title', 'تفاصيل القضية - ' . $case->case_number . ' | ' . $appName)

@push('styles')
<style>
    /* ===== الحاوية والترويسة ===== */
    .show-page-container { padding: 2rem; max-width: 1100px; margin: 0 auto; }
    
    .dashboard-header { 
        display: flex; justify-content: space-between; align-items: flex-start; 
        margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;
    }
    .header-info h1 { font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; }
    
    .header-actions { display: flex; gap: 0.8rem; align-items: center; flex-wrap: wrap; }

    /* ===== الكروت (Cards) ===== */
    .show-card { 
        background: #ffffff; border: 1px solid var(--border-color); 
        border-radius: 12px; padding: 2rem; margin-bottom: 1.5rem; 
        box-shadow: var(--shadow-sm); transition: all 0.3s ease;
    }
    .show-card:hover { border-color: var(--gold-accent); box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    
    .show-card-title { 
        font-size: 1.1rem; font-weight: 800; color: var(--sidebar-bg); 
        margin-bottom: 1.5rem; padding-bottom: 0.75rem; 
        border-bottom: 2px solid var(--primary-bg); display: flex; 
        align-items: center; justify-content: space-between;
    }
    .title-with-icon { display: flex; align-items: center; gap: 0.75rem; }
    .title-with-icon i { color: var(--gold-accent); font-size: 1.2rem; }

    /* ===== شبكة البيانات (Info Grid) ===== */
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem; }
    .info-item { display: flex; flex-direction: column; gap: 0.4rem; }
    .info-item.full { grid-column: 1 / -1; }
    
    .info-label { font-size: 0.85rem; font-weight: 700; color: var(--text-secondary); }
    .info-value { 
        font-size: 0.95rem; font-weight: 600; color: var(--text-primary); 
        background: var(--primary-bg); border: 1px solid var(--border-color); 
        border-radius: 8px; padding: 0.8rem 1rem; min-height: 45px; display: flex; align-items: center;
    }
    .info-value.textarea-val { min-height: 80px; align-items: flex-start; line-height: 1.6; white-space: pre-wrap; }
    .info-value.empty { color: var(--text-secondary); font-style: italic; font-weight: 500; }

    /* ===== الشارات (Badges) ===== */
    .status-badge { 
        display: inline-flex; align-items: center; gap: 0.4rem; 
        padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 700; 
    }
    .status-badge.مفتوحة     { background: rgba(34, 197, 94, 0.1); color: var(--success-color); border: 1px solid rgba(34, 197, 94, 0.2); }
    .status-badge.مؤجلة      { background: rgba(212, 175, 55, 0.15); color: #9a7b21; border: 1px solid rgba(212, 175, 55, 0.3); }
    .status-badge.مغلقة      { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); border: 1px solid rgba(239, 68, 68, 0.2); }
    .status-badge.حكم\ نهائي { background: rgba(37, 99, 235, 0.1); color: #2563eb; border: 1px solid rgba(37, 99, 235, 0.2); }

    /* ===== الماليات (Finance) ===== */
    .finance-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
    .finance-card { 
        background: var(--primary-bg); border: 1px solid var(--border-color); 
        border-radius: 10px; padding: 1.25rem 1rem; text-align: center; transition: 0.2s;
    }
    .finance-card.highlight { border-color: var(--gold-accent); background: rgba(212, 175, 55, 0.05); }
    .finance-card.danger { border-color: var(--danger-color); background: rgba(239, 68, 68, 0.05); }
    
    .finance-label { font-size: 0.85rem; color: var(--text-secondary); font-weight: 700; margin-bottom: 0.5rem; }
    .finance-amount { font-size: 1.3rem; font-weight: 800; color: var(--text-primary); }
    .finance-card.danger .finance-amount { color: var(--danger-color); }
    .finance-card.highlight .finance-amount { color: #9a7b21; }

    /* ===== الأشخاص (People) ===== */
    .people-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
    .person-card { 
        background: #ffffff; border: 1px solid var(--border-color); border-radius: 10px; 
        padding: 1rem; display: flex; align-items: center; gap: 0.8rem; box-shadow: var(--shadow-sm);
    }
    .person-avatar { 
        width: 45px; height: 45px; border-radius: 50%; background: rgba(30, 41, 59, 0.05); 
        color: var(--sidebar-bg); display: flex; align-items: center; justify-content: center; 
        font-size: 1.1rem; font-weight: 800; flex-shrink: 0; border: 1px solid rgba(30, 41, 59, 0.1);
    }
    .person-avatar.lawyer { background: rgba(212, 175, 55, 0.1); color: var(--gold-accent); border-color: rgba(212, 175, 55, 0.2); }
    .person-info { flex: 1; overflow: hidden; }
    .person-name { font-size: 0.95rem; font-weight: 800; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .person-meta { font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.2rem; font-weight: 500; }
    .role-badge { 
        display: inline-block; padding: 0.2rem 0.6rem; border-radius: 12px; font-size: 0.75rem; 
        font-weight: 700; background: var(--sidebar-bg); color: #ffffff; margin-top: 0.4rem; 
    }

    /* ===== الجداول المصغرة (Tables) ===== */
    .table-responsive { overflow-x: auto; margin-top: 0.5rem; border: 1px solid var(--border-color); border-radius: 8px; }
    .custom-table { width: 100%; border-collapse: collapse; text-align: right; }
    .custom-table th { background: var(--primary-bg); color: var(--text-secondary); font-size: 0.85rem; padding: 1rem; border-bottom: 2px solid var(--border-color); white-space: nowrap; }
    .custom-table td { padding: 1rem; border-bottom: 1px solid var(--primary-bg); font-size: 0.95rem; color: var(--text-primary); vertical-align: middle; }
    .custom-table tbody tr:hover { background-color: rgba(244, 246, 249, 0.5); }
    .empty-msg { color: var(--text-secondary); font-style: italic; font-weight: 500; margin-top: 0.5rem; display: block; }

    /* ===== الأزرار ===== */
    .btn { 
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; 
        padding: 0.6rem 1.2rem; border-radius: 8px; font-size: 0.9rem; font-weight: 700; 
        cursor: pointer; transition: all 0.2s ease; text-decoration: none; border: 1px solid transparent; font-family: inherit;
    }
    .btn-edit { background: rgba(212, 175, 55, 0.1); color: #9a7b21; border-color: rgba(212, 175, 55, 0.2); }
    .btn-edit:hover { background: var(--gold-accent); color: #ffffff; }
    .btn-back { background: transparent; color: var(--text-secondary); border-color: var(--border-color); }
    .btn-back:hover { background: var(--primary-bg); color: var(--text-primary); }
    .btn-add-sm { background: var(--sidebar-bg); color: #ffffff; padding: 0.4rem 1rem; font-size: 0.85rem; border-radius: 6px; }
    .btn-add-sm:hover { background: var(--gold-accent); color: var(--sidebar-bg); transform: translateY(-2px); }

    @media (max-width: 768px) {
        .show-page-container { padding: 1rem; }
        .dashboard-header { flex-direction: column; align-items: stretch; }
        .header-actions { justify-content: space-between; }
        .info-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="show-page-container">

    {{-- Header --}}
    <div class="dashboard-header">
        <div class="header-info">
            <h1><i class="fas fa-folder-open" style="color: var(--gold-accent);"></i> ملف القضية</h1>
            <div style="display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem;">
                <span style="font-size: 1.1rem; font-weight: 700; color: var(--sidebar-bg);"># {{ $case->case_number }}</span>
                <span class="status-badge {{ str_replace(' ', '_', $case->status) }}">{{ $case->status }}</span>
            </div>
        </div>
        
        <div class="header-actions">
            <a href="{{ route('cases.edit', $case->id) }}" class="btn btn-edit">
                <i class="fas fa-edit"></i> تعديل البيانات
            </a>
            <a href="{{ route('cases.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-right"></i> رجوع
            </a>
        </div>
    </div>

    {{-- 1. بيانات القضية الأساسية --}}
    <div class="show-card">
        <div class="show-card-title">
            <div class="title-with-icon"><i class="fas fa-file-alt"></i> البيانات الأساسية</div>
        </div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">المحكمة</span>
                <span class="info-value">{{ $case->court->name ?? '—' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">نوع الجهة</span>
                <span class="info-value">{{ $case->court->jurisdiction->name ?? '—' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">درجة التقاضي</span>
                <span class="info-value {{ !$case->court_level ? 'empty' : '' }}">
                    {{ $case->courtLevel->name ?? 'غير محدد' }}
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">اسم الخصم</span>
                <span class="info-value {{ !$case->rival_name ? 'empty' : '' }}">
                    {{ $case->rival_name ?? 'غير محدد' }}
                </span>
            </div>
            <div class="info-item full">
                <span class="info-label">وصف القضية وملخص الوقائع</span>
                <span class="info-value textarea-val {{ !$case->description ? 'empty' : '' }}">
                    {{ $case->description ?? 'لا يوجد وصف' }}
                </span>
            </div>
            <div class="info-item full">
                <span class="info-label">الإجراء السابق</span>
                <span class="info-value textarea-val {{ !$case->Previous_procedure ? 'empty' : '' }}">
                    {{ $case->Previous_procedure ?? 'لا يوجد' }}
                </span>
            </div>
            <div class="info-item full">
                <span class="info-label">الحكم النهائي</span>
                <span class="info-value textarea-val {{ !$case->final_decision ? 'empty' : '' }}">
                    {{ $case->final_decision ?? 'لا يوجد' }}
                </span>
            </div>
            <div class="info-item full">
                <span class="info-label">ملاحظات إدارية</span>
                <span class="info-value textarea-val {{ !$case->notes ? 'empty' : '' }}">
                    {{ $case->notes ?? 'لا توجد ملاحظات' }}
                </span>
            </div>
        </div>
    </div>

    {{-- 2. بيانات الخصم --}}
    <div class="show-card">
        <div class="show-card-title">
            <div class="title-with-icon"><i class="fas fa-user-times"></i> بيانات الخصم</div>
        </div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">اسم الخصم</span>
                <span class="info-value {{ !$case->rival_name ? 'empty' : '' }}">{{ $case->rival_name ?? '—' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">رقم الهاتف</span>
                <span class="info-value {{ !$case->rival_number ? 'empty' : '' }}">{{ $case->rival_number ?? '—' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">الرقم القومي</span>
                <span class="info-value {{ !$case->rival_nid ? 'empty' : '' }}">{{ $case->rival_nid ?? '—' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">العنوان</span>
                <span class="info-value {{ !$case->rival_address ? 'empty' : '' }}">{{ $case->rival_address ?? '—' }}</span>
            </div>
        </div>
    </div>

    {{-- 3. البيانات المالية --}}
    <div class="show-card">
        <div class="show-card-title">
            <div class="title-with-icon"><i class="fas fa-coins"></i> الحسابات المالية</div>
        </div>
        @php
            $remaining = ($case->total_costs ?? 0) - ($case->deposit ?? 0);
        @endphp
        <div class="finance-grid">
            <div class="finance-card">
                <div class="finance-label">المصاريف الإدارية</div>
                <div class="finance-amount">{{ number_format($case->costs ?? 0, 2) }} ر.ع.</div>
            </div>
            <div class="finance-card highlight">
                <div class="finance-label">إجمالي الأتعاب</div>
                <div class="finance-amount">{{ number_format($case->total_costs ?? 0, 2) }} ر.ع.</div>
            </div>
            <div class="finance-card">
                <div class="finance-label">المدفوع مقدماً</div>
                <div class="finance-amount">{{ number_format($case->deposit ?? 0, 2) }} ر.ع.</div>
            </div>
            <div class="finance-card {{ $remaining > 0 ? 'danger' : 'highlight' }}">
                <div class="finance-label">المبلغ المتبقي</div>
                <div class="finance-amount">{{ number_format($remaining, 2) }} ر.ع.</div>
            </div>
        </div>
    </div>

    {{-- 4. العملاء والمحامون (مدمجين في نفس الـ Row للشاشات الكبيرة) --}}
    <div class="info-grid">
        
        <div class="show-card" style="margin-bottom: 0;">
            <div class="show-card-title">
                <div class="title-with-icon">
                    <i class="fas fa-users"></i> العملاء الموكلين 
                    <span style="font-size: 0.85rem; color: var(--text-secondary); background: var(--primary-bg); padding: 2px 8px; border-radius: 12px;">{{ $case->clients->count() }}</span>
                </div>
            </div>
            @if($case->clients->isEmpty())
                <span class="empty-msg">لا يوجد عملاء مرتبطون بهذه القضية.</span>
            @else
                <div class="people-grid">
                    @foreach($case->clients as $client)
                        <div class="person-card">
                            <div class="person-avatar">{{ mb_substr($client->name, 0, 1) }}</div>
                            <div class="person-info">
                                <div class="person-name" title="{{ $client->name }}">{{ $client->name }}</div>
                                <div class="person-meta"><i class="fas fa-phone-alt"></i> {{ $client->phone ?? '—' }}</div>
                                <div class="person-meta"><i class="fas fa-id-card"></i> {{ $client->nid ?? '—' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="show-card" style="margin-bottom: 0;">
            <div class="show-card-title">
                <div class="title-with-icon">
                    <i class="fas fa-user-tie"></i> الفريق القانوني
                    <span style="font-size: 0.85rem; color: var(--text-secondary); background: var(--primary-bg); padding: 2px 8px; border-radius: 12px;">{{ $case->lawyers->count() }}</span>
                </div>
            </div>
            @if($case->lawyers && $case->lawyers->count() > 0)
                <div class="people-grid">
                    @foreach($case->lawyers as $lawyer)
                        <div class="person-card">
                            <div class="person-avatar lawyer">{{ mb_substr($lawyer->name, 0, 1) }}</div>
                            <div class="person-info">
                                <div class="person-name" title="{{ $lawyer->name }}">{{ $lawyer->name }}</div>
                                @if(!empty($lawyer->specialization))
                                    <div class="person-meta">{{ $lawyer->specialization }}</div>
                                @endif
                                @php
                                    $roleLabel = 'مساعد';
                                    if(isset($lawyer->pivot->role)) {
                                        $roleLabel = $lawyer->pivot->role === 'lead' ? 'محامي رئيسي' : 'مساعد';
                                    }
                                @endphp
                                <span class="role-badge">{{ $roleLabel }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <span class="empty-msg">لا يوجد محامون مرتبطون بهذه القضية.</span>
            @endif
        </div>

    </div>

    {{-- 5. المواعيد --}}
    <div class="show-card" style="margin-top: 1.5rem;">
        <div class="show-card-title">
            <div class="title-with-icon">
                <i class="fas fa-calendar-check"></i> الجلسات والمواعيد
                <span style="font-size: 0.85rem; color: var(--text-secondary); background: var(--primary-bg); padding: 2px 8px; border-radius: 12px;">{{ $case->appointments->count() }}</span>
            </div>
<a href="{{ route('appointments.create', $case->id) }}" class="btn btn-add-sm"><i class="fas fa-plus"></i> إضافة موعد</a>
        </div>

        @if($case->appointments && $case->appointments->count() > 0)
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 15%;">رقم الموعد</th>
                            <th style="width: 25%;">التاريخ والوقت</th>
                            <th style="width: 60%;">تفاصيل الجلسة أو الموعد</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($case->appointments as $appointment)
                            <tr>
                                <td><strong>#{{ $appointment->id }}</strong></td>
                                <td style="direction: ltr; text-align: right; font-weight: 600;">{{ $appointment->date }}</td>
                                <td style="color: var(--text-secondary);">{{ $appointment->notes ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <span class="empty-msg">لا توجد مواعيد أو جلسات مسجلة لهذه القضية حتى الآن.</span>
        @endif
    </div>

    {{-- 6. المستندات والمرفقات --}}
    <div class="show-card">
        <div class="show-card-title">
            <div class="title-with-icon">
                <i class="fas fa-paperclip"></i> المستندات والمرفقات
                <span style="font-size: 0.85rem; color: var(--text-secondary); background: var(--primary-bg); padding: 2px 8px; border-radius: 12px;">{{ $case->documents->count() }}</span>
            </div>
            <a href="{{ route('document.add_documents', ['case' => $case->id]) }}" class="btn btn-add-sm"><i class="fas fa-upload"></i> رفع مستند</a>
        </div>

        @if($case->documents && $case->documents->count() > 0)
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">#</th>
                            <th style="width: 40%;">اسم المستند</th>
                            <th style="width: 20%;">تاريخ الرفع</th>
                            <th style="width: 30%; text-align: center;">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($case->documents as $document)
                            <tr>
                                <td><strong>{{ $document->id }}</strong></td>
                                <td style="font-weight: 600;"><i class="fas fa-file-alt" style="color: var(--text-secondary); margin-left: 5px;"></i> {{ $document->title }}</td>
                                <td style="direction: ltr; text-align: right;">{{ $document->created_at ? $document->created_at->format('Y-m-d') : '-' }}</td>
                                <td style="text-align: center;">
                                    @if($document->file_path)
                                        <a href="{{ asset('storage/' . ltrim(str_replace('public/', '', $document->file_path), '/')) }}" target="_blank" class="btn btn-edit" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                            <i class="fas fa-external-link-alt"></i> عرض الملف
                                        </a>
                                    @else
                                        <span style="color: var(--text-secondary); font-size: 0.85rem;">لا يوجد ملف</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <span class="empty-msg">لا توجد مستندات مرفوعة في هذا الملف.</span>
        @endif
    </div>

</div>
@endsection