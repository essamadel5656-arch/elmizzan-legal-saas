@extends('layouts.app')

@section('title', 'إضافة عميل جديد | ' . $appName)

@push('styles')
<style>
    /* ===== الحاوية والترويسة ===== */
    .create-page-container { padding: 2rem; max-width: 900px; margin: 0 auto; }
    
    .dashboard-header { 
        display: flex; justify-content: space-between; align-items: flex-start; 
        margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;
    }
    .header-info h1 { font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; }
    .header-info p { color: var(--text-secondary); font-size: 0.95rem; margin: 0; }

    /* ===== البانل والكروت ===== */
    .form-card { 
        background: #ffffff; border: 1px solid var(--border-color); 
        border-radius: 12px; padding: 2rem; margin-bottom: 1.5rem; 
        box-shadow: var(--shadow-sm); transition: all 0.3s ease;
    }
    .form-card:hover { border-color: var(--gold-accent); box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    
    .form-card-title { 
        font-size: 1.1rem; font-weight: 800; color: var(--sidebar-bg); 
        margin-bottom: 1.5rem; padding-bottom: 0.75rem; 
        border-bottom: 2px solid var(--primary-bg); display: flex; 
        align-items: center; justify-content: space-between;
    }
    .title-with-icon { display: flex; align-items: center; gap: 0.75rem; }
    .title-with-icon i { color: var(--gold-accent); font-size: 1.2rem; }

    /* ===== تقسيم الفورم ===== */
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
    
    .form-group { margin-bottom: 1rem; }
    .form-group.full { grid-column: 1 / -1; }
    .form-label { display: block; margin-bottom: 0.6rem; font-weight: 700; color: var(--text-primary); font-size: 0.9rem; }
    
    .form-control { 
        width: 100%; padding: 0.9rem 1rem; border: 1px solid var(--border-color); 
        border-radius: 8px; background-color: #ffffff; font-family: inherit; 
        font-size: 0.95rem; transition: all 0.2s;
    }
    .form-control:focus { outline: none; border-color: var(--gold-accent); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); }
    
    textarea.form-control { resize: vertical; min-height: 100px; }

    .error-msg { font-size: 0.8rem; color: var(--danger-color); display: block; margin-top: 4px; font-weight: 600; }

    /* ===== أزرار التحكم ===== */
    .form-actions { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem; }
    
    .btn-cancel { 
        padding: 12px 24px; background-color: transparent; color: var(--text-secondary); 
        border: 1px solid var(--border-color); border-radius: 8px; font-weight: 600; 
        cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;
    }
    .btn-cancel:hover { background-color: var(--primary-bg); color: var(--danger-color); border-color: var(--danger-color); }
    
    .btn-save { 
        padding: 12px 32px; background-color: var(--sidebar-bg); color: #ffffff; 
        border: none; border-radius: 8px; font-weight: 700; cursor: pointer; 
        transition: 0.3s; display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-save:hover { background-color: var(--gold-accent); color: var(--sidebar-bg); box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2); }

    /* ===== الأخطاء ===== */
    .error-box { background: rgba(239, 68, 68, 0.05); border: 1px solid var(--danger-color); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; }
    .error-box strong { color: var(--danger-color); font-size: 1.05rem; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; }
    .error-box ul { color: var(--danger-color); margin: 0; padding-right: 1.5rem; font-weight: 600; }
</style>
@endpush

@section('content')
<div class="create-page-container">

    <div class="dashboard-header">
        <div class="header-info">
            <h1><i class="fas fa-user-plus" style="color: var(--gold-accent); margin-left: 8px;"></i> إضافة عميل جديد</h1>
            <p>تسجيل بيانات عميل جديد في قاعدة بيانات المكتب</p>
        </div>
        <a href="{{ route('clients.index') }}" class="btn-cancel">
            <i class="fas fa-arrow-right"></i> رجوع للقائمة
        </a>
    </div>

    @if ($errors->any())
        <div class="error-box">
            <strong><i class="fas fa-exclamation-triangle"></i> يرجى مراجعة الأخطاء التالية:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="clientForm" method="POST" action="/clients/create" novalidate>
        @csrf

        {{-- 1. البيانات الأساسية للعميل --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-id-card"></i> البيانات الأساسية للعميل</div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="clientName" class="form-label">اسم العميل <span style="color: var(--danger-color);">*</span></label>
                    <input id="clientName" name="name" type="text" class="form-control" placeholder="الاسم الكامل للعميل" value="{{ old('name') }}" required />
                    <small class="error-msg" data-error-for="clientName"></small>
                </div>

                <div class="form-group">
                    <label for="clientType" class="form-label">نوع العميل <span style="color: var(--danger-color);">*</span></label>
                    <select id="clientType" name="clientType" class="form-control" required>
                        <option value="" disabled selected>اختر النوع...</option>
                        <option value="individual" {{ old('clientType') == 'individual' ? 'selected' : '' }}>فرد</option>
                        <option value="company" {{ old('clientType') == 'company' ? 'selected' : '' }}>شركة / مؤسسة</option>
                    </select>
                    <small class="error-msg" data-error-for="clientType"></small>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">رقم الهاتف <span style="color: var(--danger-color);">*</span></label>
                    <input id="phone" name="phone" type="tel" class="form-control" placeholder="مثال: 010xxxxxxxx" value="{{ old('phone') }}" required />
                    <small class="error-msg" data-error-for="phone"></small>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input id="email" name="email" type="email" class="form-control" placeholder="example@email.com" value="{{ old('email') }}" />
                </div>

                <div class="form-group full">
                    <label for="nationalId" class="form-label">الرقم القومي / السجل التجاري</label>
                    <input id="nationalId" name="nid" type="text" class="form-control" placeholder="مكون من 14 رقم للأفراد" maxlength="14" minlength="14" inputmode="numeric" pattern="\d{14}" value="{{ old('nid') }}" />
                    <small class="error-msg" data-error-for="nationalId"></small>
                </div>

                <div class="form-group full">
                    <label for="address" class="form-label">العنوان بالكامل <span style="color: var(--danger-color);">*</span></label>
                    <input id="address" name="address" type="text" class="form-control" placeholder="المحافظة، الحي، الشارع، رقم العقار..." value="{{ old('address') }}" required />
                    <input type="hidden" name="case_id" value="1">
                </div>
            </div>
        </div>

        {{-- 2. البيانات الإضافية --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-sliders-h"></i> تصنيف وشؤون العميل</div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="clientStatus" class="form-label">حالة العميل القانونية <span style="color: var(--danger-color);">*</span></label>
                    <select id="clientStatus" name="clientStatus" class="form-control" required>
                        <option value="" disabled selected>اختر الحالة...</option>
                        <option value="active" {{ old('clientStatus', 'active') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="vip" {{ old('clientStatus') == 'vip' ? 'selected' : '' }}>عميل مهم (VIP)</option>
                        <option value="inactive" {{ old('clientStatus') == 'inactive' ? 'selected' : '' }}>متوقف / أرشيف</option>
                    </select>
                    <small class="error-msg" data-error-for="clientStatus"></small>
                </div>

                <div class="form-group">
                    <label for="lastContact" class="form-label">تاريخ فتح الملف / أول تعامل</label>
                    <input id="lastContact" name="lastContact" type="date" class="form-control" value="{{ old('lastContact', date('Y-m-d')) }}" />
                </div>

                <div class="form-group full">
                    <label for="notes" class="form-label">ملاحظات وتقرير داخلي عن العميل</label>
                    <textarea id="notes" name="note" class="form-control" placeholder="اكتب هنا أي ملاحظات إدارية أو تفاصيل خاصة بملف العميل...">{{ old('note') }}</textarea>
                </div>
            </div>
        </div>

        {{-- أزرار التحكم --}}
        <div class="form-actions">
            <button type="reset" class="btn-cancel"><i class="fas fa-eraser"></i> مسح المدخلات</button>
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> حفظ بيانات العميل
            </button>
        </div>
    </form>
</div>
@endsection