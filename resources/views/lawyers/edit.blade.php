@extends('layouts.app')

@section('title', 'تعديل بيانات المحامي | ' . $appName)

@push('styles')
<style>
    /* ===== الحاوية والترويسة ===== */
    .create-page-container { padding: 2rem; max-width: 950px; margin: 0 auto; }
    
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
    
    .form-label { display: block; margin-bottom: 0.6rem; font-weight: 700; color: var(--text-primary); font-size: 0.95rem; }
    .form-label .text-danger { color: var(--danger-color); margin-right: 4px; }

    .form-control { 
        width: 100%; padding: 0.9rem 1rem; border: 1px solid var(--border-color); 
        border-radius: 8px; background-color: var(--primary-bg); font-family: inherit; 
        font-size: 0.95rem; transition: all 0.2s; color: var(--text-primary);
    }
    .form-control:focus { outline: none; border-color: var(--gold-accent); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); background-color: #ffffff; }
    
    textarea.form-control { resize: vertical; min-height: 100px; }

    /* ===== الحقول المقفولة لغير المسؤولين ===== */
    .form-control[readonly] {
        background-color: #f1f5f9;
        cursor: not-allowed;
        color: var(--text-secondary);
        border-color: var(--border-color);
        border-style: dashed;
    }
    .field-locked-note {
        display: flex; align-items: center; gap: 0.4rem;
        margin-top: 0.5rem; font-size: 0.8rem; color: var(--text-secondary);
        font-weight: 600;
    }
    .field-locked-note i { color: var(--gold-accent); }

    /* ===== تنسيق حقول رفع الملفات والمملفات الحالية ===== */
    input[type="file"].form-control { padding: 0.5rem; background-color: #ffffff; cursor: pointer; }
    input[type="file"]::file-selector-button {
        background-color: rgba(30, 41, 59, 0.05); color: var(--sidebar-bg);
        border: 1px solid rgba(30, 41, 59, 0.1); padding: 0.4rem 1rem;
        border-radius: 6px; cursor: pointer; font-weight: 700; margin-left: 1rem;
        transition: all 0.2s; font-family: inherit;
    }
    input[type="file"]::file-selector-button:hover { background-color: var(--sidebar-bg); color: #ffffff; }

    .current-file-link {
        display: inline-flex; align-items: center; gap: 0.4rem; margin-top: 0.6rem;
        font-size: 0.85rem; font-weight: 600; color: var(--text-secondary);
        background: var(--primary-bg); padding: 0.3rem 0.8rem; border-radius: 6px; border: 1px solid var(--border-color);
    }
    .current-file-link a { color: var(--sidebar-bg); text-decoration: none; font-weight: 700; }
    .current-file-link a:hover { color: var(--gold-accent); text-decoration: underline; }

    /* ===== التنبيهات والأخطاء ===== */
    .alert-box { padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: flex-start; gap: 0.75rem; border: 1px solid transparent; }
    .alert-success { background: rgba(34, 197, 94, 0.1); border-color: var(--success-color); color: var(--success-color); }
    .alert-danger { background: rgba(239, 68, 68, 0.05); border-color: var(--danger-color); color: var(--danger-color); }
    .alert-danger ul { margin: 0.5rem 0 0 0; padding-right: 1.5rem; }

    /* ===== أزرار التحكم ===== */
    .form-actions { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; border-top: 1px dashed var(--border-color); padding-top: 1.5rem; }
    
    .btn-cancel { 
        padding: 12px 24px; background-color: transparent; color: var(--text-secondary); 
        border: 1px solid var(--border-color); border-radius: 8px; font-weight: 700; 
        cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;
    }
    .btn-cancel:hover { background-color: var(--primary-bg); color: var(--text-primary); border-color: var(--text-secondary); }
    
    .btn-save { 
        padding: 12px 32px; background-color: var(--sidebar-bg); color: #ffffff; 
        border: none; border-radius: 8px; font-weight: 700; cursor: pointer; 
        transition: 0.3s; display: inline-flex; align-items: center; gap: 0.5rem; font-family: inherit; font-size: 1rem;
    }
    .btn-save:hover { background-color: var(--gold-accent); color: var(--sidebar-bg); box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2); transform: translateY(-2px); }

    @media (max-width: 768px) {
        .create-page-container { padding: 1rem; }
        .form-grid { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column-reverse; }
        .btn-cancel, .btn-save { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="create-page-container">

    <div class="dashboard-header">
        <div class="header-info">
            <h1><i class="fas fa-user-edit" style="color: var(--gold-accent); margin-left: 8px;"></i> تعديل بيانات المحامي</h1>
            <p>تحديث المعلومات الشخصية والمهنية للمحامي: <strong style="color: var(--sidebar-bg);">{{ $lawyers->name }}</strong></p>
        </div>
        <a href="{{ route('lawyers.index') }}" class="btn-cancel">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
    </div>

    @include('layouts.toaster')
    
    @if(session('success'))
        <div class="alert-box alert-success">
            <i class="fas fa-check-circle" style="margin-top: 4px; font-size: 1.1rem;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-box alert-danger">
            <i class="fas fa-exclamation-triangle" style="margin-top: 4px; font-size: 1.1rem;"></i>
            <div>
                <strong>يرجى مراجعة الأخطاء التالية:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('lawyers.update', $lawyers->id) }}" method="POST" enctype="multipart/form-data" dir="rtl">
        @csrf
        @method('PUT')

        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-id-card"></i> البيانات الأساسية والاتصال</div>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">اسم المحامي <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="أدخل اسم المحامي" value="{{ old('name', $lawyers->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">رقم الموبايل <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" placeholder="أدخل رقم الموبايل" value="{{ old('phone', $lawyers->phone) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="أدخل البريد الإلكتروني" 
                           value="{{ old('email', $lawyers->email) }}" required
                           {{ auth()->user()->role !== 'admin' ? 'readonly' : '' }}>
                    @if(auth()->user()->role !== 'admin')
                        <div class="field-locked-note">
                            <i class="fas fa-lock"></i> لا يمكن تعديل هذا الحقل، يرجى التواصل مع الإدارة
                        </div>
                    @endif
                </div>

                <div class="form-group full">
                    <label class="form-label">العنوان <span class="text-danger">*</span></label>
                    <textarea name="address" class="form-control" placeholder="أدخل العنوان بالتفصيل" required>{{ old('address', $lawyers->address) }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-user-tie"></i> البيانات المهنية</div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">التخصص <span class="text-danger">*</span></label>
                    <input type="text" name="specialization" class="form-control" placeholder="أدخل التخصص" 
                           value="{{ old('specialization', $lawyers->specialization) }}" required
                           {{ auth()->user()->role !== 'admin' ? 'readonly' : '' }}>
                    @if(auth()->user()->role !== 'admin')
                        <div class="field-locked-note">
                            <i class="fas fa-lock"></i> لا يمكن تعديل هذا الحقل، يرجى التواصل مع الإدارة
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">رقم القيد <span class="text-danger">*</span></label>
                    <input type="text" name="license_number" class="form-control" placeholder="أدخل رقم القيد" 
                           value="{{ old('license_number', $lawyers->license_number) }}" required
                           {{ auth()->user()->role !== 'admin' ? 'readonly' : '' }}>
                    @if(auth()->user()->role !== 'admin')
                        <div class="field-locked-note">
                            <i class="fas fa-lock"></i> لا يمكن تعديل هذا الحقل، يرجى التواصل مع الإدارة
                        </div>
                    @endif
                </div>

                <div class="form-group full">
                    <label class="form-label">الدرجة <span class="text-danger">*</span></label>
                    <select name="degree" id="degree" class="form-control" required>
                        <option value="" disabled>اختر الدرجة</option>
                        <option value="نقض" {{ old('degree', $lawyers->degree) == 'نقض' ? 'selected' : '' }}>نقض</option>
                        <option value="استئناف" {{ old('degree', $lawyers->degree) == 'استئناف' ? 'selected' : '' }}>استئناف</option>
                        <option value="ابتدائي" {{ old('degree', $lawyers->degree) == 'ابتدائي' ? 'selected' : '' }}>ابتدائي</option>
                        <option value="تجاري" {{ old('degree', $lawyers->degree) == 'تجاري' ? 'selected' : '' }}>جدول عام</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label class="form-label">نبذة مختصرة <span class="text-danger">*</span></label>
                    <textarea name="bio" class="form-control" placeholder="أدخل نبذة مختصرة عن المحامي">{{ old('bio', $lawyers->bio) }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-paperclip"></i> المرفقات والصور</div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">صورة الكارنيه (اختياري)</label>
                    <input type="file" name="bar_card_image" class="form-control" accept="image/*">
                    @if($lawyers->bar_card_image && file_exists(storage_path('app/' . $lawyers->bar_card_image)))
                        <div class="current-file-link">
                            <i class="fas fa-image"></i> الصورة الحالية: <a href="{{ asset('storage/' . $lawyers->bar_card_image) }}" target="_blank">عرض</a>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">الصورة الشخصية (اختياري)</label>
                    <input type="file" name="profile_image" class="form-control" accept="image/*">
                    @if($lawyers->profile_image && file_exists(storage_path('app/' . $lawyers->profile_image)))
                        <div class="current-file-link">
                            <i class="fas fa-user-circle"></i> الصورة الحالية: <a href="{{ asset('storage/' . $lawyers->profile_image) }}" target="_blank">عرض</a>
                        </div>
                    @endif
                </div>

                <div class="form-group full">
                    <label class="form-label">صورة البطاقة الشخصية (اختياري)</label>
                    <input type="file" name="national_id_image" class="form-control" accept="image/*">
                    @if($lawyers->national_id_image && file_exists(storage_path('app/' . $lawyers->national_id_image)))
                        <div class="current-file-link">
                            <i class="fas fa-id-card"></i> الصورة الحالية: <a href="{{ asset('storage/' . $lawyers->national_id_image) }}" target="_blank">عرض</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if(auth()->user()->role === 'admin')
            <div class="form-card" style="border-color: rgba(239, 68, 68, 0.3);">
                <div class="form-card-title">
                    <div class="title-with-icon"><i class="fas fa-key" style="color: var(--danger-color);"></i> تغيير كلمة المرور (للمدير فقط)</div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">كلمة المرور الجديدة</label>
                        <input type="password" name="password" class="form-control" placeholder="اتركه فارغاً إذا لم تريد التغيير">
                    </div>

                    <div class="form-group">
                        <label class="form-label">تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="أعد كتابة كلمة المرور الجديدة">
                    </div>
                </div>
            </div>
        @endif

        <div class="form-actions">
            <a href="{{ route('lawyers.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i> إلغاء والتراجع
            </a>
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> حفظ جميع التعديلات
            </button>
        </div>

    </form>
</div>
@endsection