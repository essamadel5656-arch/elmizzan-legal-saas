@extends('layouts.app')

@section('title', 'ملف العميل: ' . $client->name . ' | ' . $appName)

@push('styles')
<style>
    /* ===== الحاوية والترويسة ===== */
    .show-page-container { padding: 2rem; max-width: 950px; margin: 0 auto; }
    
    .dashboard-header { 
        display: flex; justify-content: space-between; align-items: flex-start; 
        margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;
    }
    .header-info h1 { font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.6rem; }
    
    .meta-info {
        font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;
        background: rgba(30, 41, 59, 0.04); padding: 0.5rem 1rem; border-radius: 8px; border: 1px dashed var(--border-color);
    }
    .meta-info strong { color: var(--sidebar-bg); font-weight: 700; margin-right: 4px; }

    .header-actions { display: flex; gap: 0.8rem; align-items: center; }

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
        align-items: center; gap: 0.75rem;
    }
    .show-card-title i { color: var(--gold-accent); font-size: 1.2rem; }

    /* ===== شبكة البيانات (Info Grid) ===== */
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
    .info-item { display: flex; flex-direction: column; gap: 0.5rem; }
    .info-item.full { grid-column: 1 / -1; }
    
    .info-label { font-size: 0.9rem; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 0.4rem;}
    .info-label i { color: var(--gold-accent); }
    
    .info-value { 
        font-size: 0.95rem; font-weight: 600; color: var(--text-primary); 
        background: var(--primary-bg); border: 1px solid var(--border-color); 
        border-radius: 8px; padding: 0.9rem 1rem; min-height: 45px; display: flex; align-items: center;
    }
    .info-value-link { color: var(--sidebar-bg); text-decoration: none; font-weight: 700; transition: color 0.2s; }
    .info-value-link:hover { color: var(--gold-accent); }
    
    .info-value.textarea-val { min-height: 100px; align-items: flex-start; line-height: 1.6; white-space: pre-wrap; }
    .info-value .empty { color: var(--text-secondary); font-style: italic; font-weight: 500; opacity: 0.8;}

    /* ===== الأزرار ===== */
    .btn-cancel { 
        padding: 10px 20px; background-color: transparent; color: var(--text-secondary); 
        border: 1px solid var(--border-color); border-radius: 8px; font-weight: 600; 
        cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;
    }
    .btn-cancel:hover { background-color: var(--primary-bg); color: var(--sidebar-bg); border-color: var(--sidebar-bg); }

    @media (max-width: 768px) {
        .show-page-container { padding: 1rem; }
        .dashboard-header { flex-direction: column; align-items: stretch; gap: 1.5rem;}
        .meta-info { width: 100%; text-align: right; }
        .header-actions { justify-content: flex-end; }
        .info-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="show-page-container">

    {{-- ترويسة الصفحة --}}
    <div class="dashboard-header">
        <div class="header-info">
            <h1><i class="fas fa-user-shield" style="color: var(--gold-accent);"></i> ملف العميل: <span style="color: var(--sidebar-bg);">{{ $client->name }}</span></h1>
            <div class="meta-info" style="margin-top: 0.8rem;">
                <div>معرّف النظام للعميل: <strong>#{{ $client->id }}</strong></div>
                <div>تاريخ التسجيل: <strong>{{ $client->created_at ? $client->created_at->format('Y-m-d') : 'غير مسجل' }}</strong></div>
            </div>
        </div>
        
        <div class="header-actions">
            <a href="{{ route('clients.index') }}" class="btn-cancel">
                <i class="fas fa-arrow-right"></i> العودة للقائمة
            </a>
        </div>
    </div>

    {{-- 1. بيانات الاتصال والهوية --}}
    <div class="show-card">
        <div class="show-card-title">
            <i class="fas fa-address-card"></i> بيانات الاتصال والهوية الشخصية
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label"><i class="fas fa-phone-alt"></i> رقم الهاتف المحمول</span>
                <span class="info-value">
                    @if($client->phone)
                        <a href="tel:{{ $client->phone }}" class="info-value-link" style="direction: ltr;">{{ $client->phone }}</a>
                    @else
                        <span class="empty">— غير مسجل —</span>
                    @endif
                </span>
            </div>

            <div class="info-item">
                <span class="info-label"><i class="fas fa-envelope"></i> البريد الإلكتروني</span>
                <span class="info-value">
                    @if($client->email)
                        <a href="mailto:{{ $client->email }}" class="info-value-link">{{ $client->email }}</a>
                    @else
                        <span class="empty">— غير مسجل —</span>
                    @endif
                </span>
            </div>

            <div class="info-item">
                <span class="info-label"><i class="fas fa-id-card"></i> الرقم القومي / السجل التجاري</span>
                <span class="info-value">
                    @if($client->nid)
                        {{ $client->nid }}
                    @else
                        <span class="empty">— غير مسجل —</span>
                    @endif
                </span>
            </div>

            <div class="info-item">
                <span class="info-label"><i class="fas fa-map-marker-alt"></i> العنوان الحالي التفصيلي</span>
                <span class="info-value">
                    @if($client->address)
                        {{ $client->address }}
                    @else
                        <span class="empty">— غير مسجل —</span>
                    @endif
                </span>
            </div>
        </div>
    </div>

    {{-- 2. الملاحظات الإدارية الملحقة للعميل --}}
    <div class="show-card">
        <div class="show-card-title">
            <i class="fas fa-sticky-note"></i> تقرير وملاحظات المكتب الداخلي
        </div>
        
        <div class="info-grid">
            <div class="info-item full">
                <span class="info-value textarea-val">@if($client->note){{ $client->note }}@else<span class="empty">لا توجد ملاحظات إدارية مضافة لملف هذا العميل حتى الآن.</span>@endif</span>
            </div>
        </div>
    </div>

</div>
@endsection