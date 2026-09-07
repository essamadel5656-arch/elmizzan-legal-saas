@extends('layouts.app')

@section('title', 'إضافة مستند للقضية | ' . $appName)

@push('styles')
<style>
    /* ===== الحاوية والترويسة ===== */
    .create-page-container { padding: 2rem; max-width: 800px; margin: 0 auto; }
    
    .dashboard-header { 
        display: flex; justify-content: space-between; align-items: flex-start; 
        margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;
    }
    .header-info h1 { font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; }
    .header-info p { color: var(--text-secondary); font-size: 0.95rem; margin: 0; }

    /* ===== البانل والكروت ===== */
    .form-card { 
        background: #ffffff; border: 1px solid var(--border-color); 
        border-radius: 12px; padding: 2.5rem; margin-bottom: 1.5rem; 
        box-shadow: var(--shadow-sm); transition: all 0.3s ease;
    }
    .form-card:hover { border-color: var(--gold-accent); box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    
    /* ===== تقسيم الفورم ===== */
    .form-grid { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
    
    .form-group { margin-bottom: 0.5rem; }
    .form-label { display: block; margin-bottom: 0.6rem; font-weight: 700; color: var(--text-primary); font-size: 0.95rem; }
    
    .form-control { 
        width: 100%; padding: 0.9rem 1rem; border: 1px solid var(--border-color); 
        border-radius: 8px; background-color: var(--primary-bg); font-family: inherit; 
        font-size: 0.95rem; transition: all 0.2s; color: var(--text-primary);
    }
    .form-control:focus { outline: none; border-color: var(--gold-accent); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); background-color: #ffffff; }

    /* ===== المرفقات (Upload Box) ===== */
    .upload-box { 
        border: 2px dashed var(--border-color); border-radius: 12px; 
        padding: 3rem 2rem; text-align: center; background: var(--primary-bg); 
        cursor: pointer; transition: all 0.3s; margin-top: 0.5rem;
    }
    .upload-box:hover { border-color: var(--gold-accent); background: #ffffff; }
    .upload-icon { font-size: 3rem; color: var(--sidebar-bg); margin-bottom: 1rem; transition: color 0.2s; opacity: 0.7;}
    .upload-box:hover .upload-icon { color: var(--gold-accent); opacity: 1;}

    /* ===== أزرار التحكم ===== */
    .form-actions { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; border-top: 1px dashed var(--border-color); padding-top: 1.5rem; }
    
    .btn-cancel { 
        padding: 12px 24px; background-color: transparent; color: var(--text-secondary); 
        border: 1px solid var(--border-color); border-radius: 8px; font-weight: 600; 
        cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;
    }
    .btn-cancel:hover { background-color: var(--primary-bg); color: var(--danger-color); border-color: var(--danger-color); }
    
    .btn-save { 
        padding: 12px 32px; background-color: var(--sidebar-bg); color: #ffffff; 
        border: none; border-radius: 8px; font-weight: 700; cursor: pointer; 
        transition: 0.3s; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 1rem;
    }
    .btn-save:hover { background-color: var(--gold-accent); color: var(--sidebar-bg); box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2); transform: translateY(-2px); }

    /* ===== الأخطاء ===== */
    .error-msg { font-size: 0.85rem; color: var(--danger-color); display: flex; align-items: center; gap: 0.4rem; margin-top: 6px; font-weight: 600; }
    .error-box { background: rgba(239, 68, 68, 0.05); border: 1px solid var(--danger-color); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; }
    .error-box strong { color: var(--danger-color); font-size: 1.05rem; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; }
    .error-box ul { color: var(--danger-color); margin: 0; padding-right: 1.5rem; font-weight: 600; }
</style>
@endpush

@section('content')
<div class="create-page-container">

    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-info">
            <h1><i class="fas fa-file-upload" style="color: var(--gold-accent); margin-left: 8px;"></i> رفع مستند جديد</h1>
            <p>إضافة مستند أو مرفق جديد لملف القضية رقم: <strong style="color: var(--sidebar-bg);">{{ $case->case_number ?? $case->id }}</strong></p>
        </div>
        <!-- زر الرجوع للقضية -->
        <a href="{{ route('cases.show', $case->id) }}" class="btn-cancel">
            <i class="fas fa-arrow-right"></i> رجوع لملف القضية
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

    <div class="form-card">
        <form action="/document/store" method="POST" enctype="multipart/form-data" id="documentForm">
            @csrf
            
            {{-- الحقل المخفي الخاص بالربط بالقضية --}}
            <input type="hidden" name="case_id" value="{{ $case->id }}">

            <div class="form-grid">
                {{-- نوع المستند --}}
                <div class="form-group">
                    <label class="form-label">نوع المستند <span style="color: var(--danger-color);">*</span></label>
                    <select name="document_type" class="form-control" required>
                        <option value="" disabled selected>-- اختر نوع المستند --</option>
                        <option value="contract" {{ old('document_type') == 'contract' ? 'selected' : '' }}>عقد</option>
                        <option value="report" {{ old('document_type') == 'report' ? 'selected' : '' }}>تقرير</option>
                        <option value="attachment" {{ old('document_type') == 'attachment' ? 'selected' : '' }}>مرفق</option>
                    </select>
                    @error('document_type')
                        <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- اسم المستند --}}
                <div class="form-group">
                    <label class="form-label">اسم وعنوان المستند <span style="color: var(--danger-color);">*</span></label>
                    <input type="text" name="document_name" class="form-control" placeholder="مثال: صورة من توكيل موثق..." value="{{ old('document_name') }}" required>
                    @error('document_name')
                        <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- رفع الملف (UI Custom) --}}
                <div class="form-group">
                    <label class="form-label" style="margin-bottom: 0;">الملف المرفق <span style="color: var(--danger-color);">*</span></label>
                    
                    <div class="upload-box" id="uploadBox">
                        <div id="uploadDefault">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <h3 style="color: var(--text-primary); font-weight: 700; margin-bottom: 0.5rem; font-size: 1.1rem;">اضغط هنا لاختيار الملف</h3>
                            <p style="color: var(--text-secondary); font-size: 0.9rem; font-weight: 500;">
                                أنواع الملفات المدعومة: PDF, DOC, DOCX, XLS, XLSX
                            </p>
                        </div>

                        <div id="uploadPreview" style="display: none; flex-direction: column; align-items: center; gap: 0.8rem;"></div>
                    </div>
                    
                    {{-- حقل الرفع المخفي --}}
                    <input type="file" id="document_file" name="file" style="display:none;" accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                    
                    @error('file')
                        <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- أزرار التحكم --}}
            <div class="form-actions">
                <a href="{{ route('cases.show', $case->id) }}" class="btn-cancel">
                    <i class="fas fa-times"></i> إلغاء
                </a>
                <button type="submit" class="btn-save">
                    <i class="fas fa-upload"></i> رفع المستند وحفظه
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    /* واجهة رفع المرفقات المخصصة بصرياً */
    const fileInput = document.getElementById('document_file');
    const uploadBox = document.getElementById('uploadBox');
    const uploadDefault = document.getElementById('uploadDefault');
    const uploadPreview = document.getElementById('uploadPreview');

    // فتح شاشة الرفع عند الضغط على المربع
    uploadBox.addEventListener('click', function (e) {
        if (e.target.closest('.remove-file-btn')) return;
        fileInput.click();
    });

    // عند اختيار ملف
    fileInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            uploadDefault.style.display = 'none';
            uploadPreview.style.display = 'flex';
            uploadPreview.innerHTML = '';

            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
            let fileIcon = 'fa-file-alt';
            let iconColor = 'var(--sidebar-bg)';

            // تحديد أيقونة الملف بناءً على الامتداد
            if (file.name.endsWith('.pdf')) { fileIcon = 'fa-file-pdf'; iconColor = 'var(--danger-color)'; }
            else if (file.name.endsWith('.doc') || file.name.endsWith('.docx')) { fileIcon = 'fa-file-word'; iconColor = '#2563eb'; }
            else if (file.name.endsWith('.xls') || file.name.endsWith('.xlsx')) { fileIcon = 'fa-file-excel'; iconColor = '#16a34a'; }

            uploadPreview.innerHTML = `
                <i class="fas ${fileIcon}" style="font-size: 3.5rem; color: ${iconColor};"></i>
                <p style="color: var(--success-color); font-size: 1rem; font-weight: 700; margin: 0.5rem 0;">
                    <i class="fas fa-check-circle"></i> تم اختيار: <span style="color: var(--text-primary);">${file.name}</span> (${fileSizeMB} MB)
                </p>
                <span class="remove-file-btn" style="color: var(--danger-color); cursor: pointer; font-size: 0.95rem; font-weight: 700; text-decoration: underline;">
                    <i class="fas fa-trash-alt"></i> حذف واختيار ملف آخر
                </span>
            `;
        } else {
            resetUploadBox();
        }
    });

    // تفريغ الملف
    function resetUploadBox() {
        fileInput.value = '';
        uploadDefault.style.display = 'block';
        uploadPreview.style.display = 'none';
        uploadPreview.innerHTML = '';
    }

    // زر الحذف داخل صندوق الرفع
    uploadPreview.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.remove-file-btn');
        if (removeBtn) {
            e.stopPropagation();
            resetUploadBox();
        }
    });
});
</script>
@endpush