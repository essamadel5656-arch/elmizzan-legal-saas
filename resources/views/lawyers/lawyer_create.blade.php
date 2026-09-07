@extends('layouts.app')

@section('title', 'إضافة محامي جديد | ' . $appName)

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

    /* ===== تنسيق حقول رفع الملفات ===== */
    input[type="file"].form-control { padding: 0.5rem; background-color: #ffffff; cursor: pointer; }
    input[type="file"]::file-selector-button {
        background-color: rgba(30, 41, 59, 0.05); color: var(--sidebar-bg);
        border: 1px solid rgba(30, 41, 59, 0.1); padding: 0.4rem 1rem;
        border-radius: 6px; cursor: pointer; font-weight: 700; margin-left: 1rem;
        transition: all 0.2s; font-family: inherit;
    }
    input[type="file"]::file-selector-button:hover { background-color: var(--sidebar-bg); color: #ffffff; }

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

    <form action="{{ route('lawyers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <div class="alert-box alert-danger">
                <div>
                    <i class="fas fa-exclamation-triangle"></i> <strong>برجاء تصحيح الأخطاء التالية للتمكن من الحفظ:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert-box alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-id-card"></i> البيانات الأساسية والاتصال</div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">اسم المحامي <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="أدخل اسم المحامي بالكامل" value="{{ old('name') }}" minlength="3" maxlength="255" required>
                </div>

                <div class="form-group">
                    <label class="form-label">رقم الموبايل <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" placeholder="أدخل رقم الموبايل (مثال: 01012345678)" value="{{ old('phone') }}" pattern="^(010|011|012|015)[0-9]{8}$" title="يجب أن يكون رقم موبايل مصري صحيح مكون من 11 رقم ويبدأ بـ 010، 011، 012، أو 015" required>
                </div>

                <div class="form-group">
                    <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="أدخل البريد الإلكتروني" value="{{ old('email') }}" maxlength="255" required>
                </div>

                <div class="form-group full">
                    <label class="form-label">العنوان <span class="text-danger">*</span></label>
                    <textarea name="address" class="form-control" placeholder="أدخل العنوان بالتفصيل" minlength="10" maxlength="500" required>{{ old('address') }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-briefcase"></i> البيانات المهنية</div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">التخصص <span class="text-danger">*</span></label>
                    <input type="text" name="specialization" class="form-control" placeholder="أدخل التخصص (مثال: مدني، جنائي...)" value="{{ old('specialization') }}" minlength="3" maxlength="100" required>
                </div>

                <div class="form-group">
                    <label class="form-label">رقم القيد <span class="text-danger">*</span></label>
                    <input type="text" name="license_number" class="form-control" placeholder="أدخل رقم القيد بنقابة المحامين" value="{{ old('license_number') }}" maxlength="50" required>
                </div>

                <div class="form-group full">
                    <label class="form-label">الدرجة <span class="text-danger">*</span></label>
                    <select name="degree" class="form-control" required>
                        <option value="" disabled {{ old('degree') ? '' : 'selected' }}>اختر الدرجة</option>
                        <option value="نقض" {{ old('degree') == 'نقض' ? 'selected' : '' }}>نقض</option>
                        <option value="استئناف" {{ old('degree') == 'استئناف' ? 'selected' : '' }}>استئناف</option>
                        <option value="ابتدائي" {{ old('degree') == 'ابتدائي' ? 'selected' : '' }}>ابتدائي</option>
                        <option value="جدول_عام" {{ old('degree') == 'جدول_عام' ? 'selected' : '' }}>جدول عام</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label class="form-label">نبذة مختصرة <span class="text-danger">*</span></label>
                    <textarea name="bio" class="form-control" placeholder="أدخل نبذة مختصرة عن المحامي وخبراته (20 حرف على الأقل)" minlength="20" maxlength="1000" required>{{ old('bio') }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-lock"></i> بيانات الدخول للحساب</div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                    <div style="display: flex; gap: 8px;">
                        <input type="password" id="lawyer_password" name="password" class="form-control"
                               placeholder="من 8 إلى 20 حرف أو رقم" minlength="8" maxlength="20" required style="flex: 1;">

                        <button type="button" onclick="generateAiPassword()" class="btn"
                                style="background-color: #0f172a; color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.4); padding: 0 16px; border-radius: .375rem; cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: 500;"
                                title="توليد كلمة مرور عشوائية ذكية">
                            <i class="fa-solid fa-robot"></i> AI
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                    <div style="display: flex; gap: 8px;">
                        <input type="password" id="lawyer_password_confirmation" name="password_confirmation" class="form-control"
                               placeholder="أعد إدخال كلمة المرور" minlength="8" maxlength="20" required style="flex: 1;">

                        <button type="button" onclick="syncConfirmPassword()" class="btn"
                                style="background-color: #4b5563; color: #ffffff; border: none; padding: 0 16px; border-radius: .375rem; cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: 500;"
                                title="نسخ كلمة المرور لحقل التأكيد">
                            <i class="fa-solid fa-copy"></i> نسخ
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function generateAiPassword() {
            const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

            // 🌟 تعديل الـ JavaScript لتوليد طول عشوائي ذكي بين 8 و 20 تماشياً مع الباك إند
            const randomLength = Math.floor(Math.random() * (20 - 8 + 1)) + 8;

            let generatedPassword = '';
            for (let i = 0; i < randomLength; i++) {
                generatedPassword += chars.charAt(Math.floor(Math.random() * chars.length));
            }

            const passwordInput = document.getElementById('lawyer_password');
            passwordInput.value = generatedPassword;

            // تحويل الحقل إلى text لسهولة الرؤية والـ UX
            passwordInput.type = 'text';
        }

        function syncConfirmPassword() {
            const passwordValue = document.getElementById('lawyer_password').value;
            const confirmationInput = document.getElementById('lawyer_password_confirmation');

            if (!passwordValue) {
                alert('برجاء توليد أو كتابة كلمة المرور أولاً!');
                return;
            }

            confirmationInput.value = passwordValue;

            const passwordInput = document.getElementById('lawyer_password');
            confirmationInput.type = passwordInput.type;
        }
        </script>

        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-images"></i> المرفقات والصور</div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">صورة الكارنيه <span class="text-danger">*</span></label>
                    <input type="file" name="bar_card_image" class="form-control" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label class="form-label">الصورة الشخصية (اختياري)</label>
                    <input type="file" name="profile_image" class="form-control" accept="image/*">
                </div>

                <div class="form-group full">
                    <label class="form-label">صورة البطاقة الشخصية (اختياري)</label>
                    <input type="file" name="national_id_image" class="form-control" accept="image/*">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('lawyers.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i> إلغاء
            </a>
            <button type="submit" class="btn-save">
                <i class="fas fa-user-plus"></i> إضافة المحامي
            </button>
        </div>

    </form>
</div>
@endsection