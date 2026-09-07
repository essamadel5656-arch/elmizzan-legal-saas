@extends('layouts.app')

@section('title', 'ملف المحامي: ' . $lawyers->name . ' | ' . $appName)

@push('styles')
<style>
    /* ===== الحاوية والترويسة ===== */
    .show-page-container { padding: 2rem; max-width: 950px; margin: 0 auto; }
    
    .profile-header { 
        display: flex; justify-content: space-between; align-items: flex-start; 
        margin-bottom: 2rem; flex-wrap: wrap; gap: 1.5rem;
    }
    
    .profile-info-wrapper { display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap; }
    
    .profile-avatar { 
        width: 120px; height: 120px; border-radius: 50%; object-fit: cover; 
        border: 4px solid var(--primary-bg); box-shadow: 0 0 0 2px var(--gold-accent), var(--shadow-sm); 
        background-color: var(--sidebar-bg); color: var(--gold-accent); 
        display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 800; 
    }
    
    .profile-text h1 { font-size: 1.8rem; font-weight: 800; color: var(--sidebar-bg); margin: 0 0 0.4rem 0; }
    .profile-text .spec { font-size: 1.1rem; color: #9a7b21; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; background: rgba(212, 175, 55, 0.1); padding: 4px 12px; border-radius: 20px; width: fit-content;}

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
        border-bottom: 2px solid var(--primary-bg); display: flex; align-items: center; gap: 0.75rem;
    }
    .show-card-title i { color: var(--gold-accent); font-size: 1.2rem; }

    /* ===== شبكة البيانات (Info Grid) ===== */
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
    .info-item { display: flex; flex-direction: column; gap: 0.5rem; }
    .info-item.full { grid-column: 1 / -1; }
    
    .info-label { font-size: 0.9rem; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 0.4rem; }
    .info-label i { color: var(--gold-accent); }
    
    .info-value { 
        font-size: 0.95rem; font-weight: 600; color: var(--text-primary); 
        background: var(--primary-bg); border: 1px solid var(--border-color); 
        border-radius: 8px; padding: 0.9rem 1rem; min-height: 45px; display: flex; align-items: center;
    }
    .info-value-link { color: var(--sidebar-bg); text-decoration: none; transition: 0.2s; font-weight: 700; }
    .info-value-link:hover { color: var(--gold-accent); }
    .info-value.textarea-val { min-height: 80px; align-items: flex-start; line-height: 1.6; white-space: pre-wrap; }
    .info-value .empty { color: var(--text-secondary); font-style: italic; font-weight: 500; }

    /* ===== المرفقات والمستندات ===== */
    .documents-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
    
    @media (max-width: 600px) { .documents-grid { grid-template-columns: 1fr; } }

    .document-item { display: flex; flex-direction: column; gap: 0.5rem; }

    .document-wrapper {
        display: block; width: 100%; height: 220px; background-color: var(--primary-bg);
        border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden;
        position: relative; text-align: center; transition: all 0.3s ease; flex: 1;
    }
    .document-wrapper:hover { border-color: var(--gold-accent); box-shadow: 0 4px 15px rgba(212, 175, 55, 0.15); }

    .document-wrapper img {
        width: 100%; height: 100%; object-fit: contain; padding: 0.5rem;
        transition: transform 0.3s; display: block;
    }
    .document-wrapper:hover img { transform: scale(1.03); }

    .document-overlay {
        position: absolute; bottom: 0; left: 0; right: 0;
        background: rgba(30, 41, 59, 0.92); color: #ffffff; padding: 0.7rem;
        font-size: 0.9rem; font-weight: 700; transform: translateY(101%);
        transition: transform 0.3s ease; display: flex; justify-content: center;
        align-items: center; gap: 0.5rem; z-index: 2;
    }
    .document-wrapper:hover .document-overlay { transform: translateY(0); }

    /* ===== الأزرار والتحكم ===== */
    .form-actions { display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; border-top: 1px dashed var(--border-color); padding-top: 2rem; flex-wrap: wrap;}
    
    .btn { 
        display: inline-flex; align-items: center; gap: 0.5rem; padding: 12px 24px; 
        border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.3s; 
        text-decoration: none; font-size: 0.95rem; border: 1px solid transparent; font-family: inherit;
    }
    .btn-primary { background: var(--sidebar-bg); color: #ffffff; }
    .btn-primary:hover { background: var(--gold-accent); color: var(--sidebar-bg); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2); }
    
    .btn-secondary { background: transparent; color: var(--text-secondary); border-color: var(--border-color); }
    .btn-secondary:hover { background: var(--primary-bg); color: var(--text-primary); border-color: var(--text-secondary); }
    
    .btn-danger { background: rgba(239, 68, 68, 0.08); color: var(--danger-color); border-color: rgba(239, 68, 68, 0.2); }
    .btn-danger:hover { background: var(--danger-color); color: #ffffff; }

    @media (max-width: 768px) {
        .show-page-container { padding: 1rem; }
        .profile-header { flex-direction: column; align-items: center; text-align: center; }
        .profile-info-wrapper { flex-direction: column; }
        .form-actions { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="show-page-container">

    {{-- الترويسة العلوية (Profile Header) --}}
    <div class="profile-header">
        <div class="profile-info-wrapper">
            @if($lawyers->profile_image)
                <img src="{{ asset('storage/' . str_replace('public/', '', $lawyers->profile_image)) }}" alt="{{ $lawyers->name }}" class="profile-avatar">
            @else
                <div class="profile-avatar">
                    {{ mb_substr($lawyers->name, 0, 1) }}
                </div>
            @endif
            
            <div class="profile-text">
                <h1>{{ $lawyers->name }}</h1>
                <div class="spec"><i class="fas fa-briefcase"></i> {{ $lawyers->specialization }}</div>
            </div>
        </div>

        <div>
            @if(auth()->user()->role !== 'admin')
                <a href="/home" class="btn btn-secondary"><i class="fas fa-arrow-right"></i> العودة للرئيسية</a>
            @else
                <a href="{{ route('lawyers.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-right"></i> العودة للقائمة</a>
            @endif
        </div>
    </div>

    {{-- 1. معلومات الاتصال --}}
    <div class="show-card">
        <div class="show-card-title"><i class="fas fa-address-card"></i> معلومات الاتصال</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label"><i class="fas fa-phone-alt"></i> رقم الهاتف</span>
                <span class="info-value" style="direction: ltr; justify-content: flex-end;">
                    <a href="tel:{{ $lawyers->phone }}" class="info-value-link">{{ $lawyers->phone }}</a>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label"><i class="fas fa-envelope"></i> البريد الإلكتروني</span>
                <span class="info-value">
                    <a href="mailto:{{ $lawyers->email }}" class="info-value-link">{{ $lawyers->email }}</a>
                </span>
            </div>
            <div class="info-item full">
                <span class="info-label"><i class="fas fa-map-marker-alt"></i> العنوان</span>
                <span class="info-value">{{ $lawyers->address }}</span>
            </div>
        </div>
    </div>

    {{-- 2. المؤهلات والترخيص --}}
    <div class="show-card">
        <div class="show-card-title"><i class="fas fa-graduation-cap"></i> الدرجة والترخيص</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label"><i class="fas fa-award"></i> الدرجة</span>
                <span class="info-value">{{ $lawyers->degree }}</span>
            </div>
            <div class="info-item">
                <span class="info-label"><i class="fas fa-id-badge"></i> رقم القيد / الترخيص</span>
                <span class="info-value">{{ $lawyers->license_number }}</span>
            </div>
        </div>
    </div>

    {{-- 3. النبذة الشخصية (إن وجدت) --}}
    @if($lawyers->bio)
        <div class="show-card">
            <div class="show-card-title"><i class="fas fa-user-edit"></i> النبذة الشخصية</div>
            <div class="info-grid">
                <div class="info-item full">
                    <span class="info-value textarea-val">{{ $lawyers->bio }}</span>
                </div>
            </div>
        </div>
    @endif

    {{-- 4. المستندات المرفوعة --}}
    @if($lawyers->national_id_image || $lawyers->bar_card_image)
        <div class="show-card">
            <div class="show-card-title"><i class="fas fa-images"></i> المستندات المرفوعة</div>

            <div class="documents-grid">

                @if($lawyers->national_id_image)
                    <div class="document-item">
                        <span class="info-label" style="margin-bottom: 0.3rem;"><i class="fas fa-id-card"></i> صورة الهوية الوطنية</span>
                        <a href="{{ \Storage::url($lawyers->national_id_image) }}" target="_blank" class="document-wrapper">
                            <img src="{{ \Storage::url($lawyers->national_id_image) }}" alt="الهوية الوطنية">
                            <div class="document-overlay">
                                <i class="fas fa-search-plus"></i> عرض بالحجم الكامل
                            </div>
                        </a>
                    </div>
                @endif

                @if($lawyers->bar_card_image)
                    <div class="document-item">
                        <span class="info-label" style="margin-bottom: 0.3rem;"><i class="fas fa-id-badge"></i> بطاقة الترخيص / كارنيه النقابة</span>
                        <a href="{{ \Storage::url($lawyers->bar_card_image) }}" target="_blank" class="document-wrapper">
                            <img src="{{ \Storage::url($lawyers->bar_card_image) }}" alt="كارنيه النقابة">
                            <div class="document-overlay">
                                <i class="fas fa-search-plus"></i> عرض بالحجم الكامل
                            </div>
                        </a>
                    </div>
                @endif

            </div>
        </div>
    @endif

    {{-- أزرار الإجراءات (حسب الصلاحية) --}}
    @if(auth()->user()->role === 'admin' || auth()->user()->lawyer_id == $lawyers->id)
        <div class="form-actions">
            <a href="{{ route('lawyers.edit', $lawyers->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> تعديل البيانات
            </a>

            {{-- زر الحذف للأدمن بس --}}
            @if(auth()->user()->role === 'admin')
                <form action="{{ route('lawyers.destroy', $lawyers->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المحامي نهائياً من النظام؟ لا يمكن التراجع عن هذا الإجراء.');">
                        <i class="fas fa-trash-alt"></i> حذف المحامي
                    </button>
                </form>
            @endif
        </div>
    @endif

</div>
@endsection