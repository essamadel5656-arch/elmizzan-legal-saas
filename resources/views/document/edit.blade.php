@extends('layouts.app')

@section('title', 'تعديل المستند | ' . $appName)

@push('styles')
<style>
    /* ===== الحاوية والترويسة ===== */
    .edit-page-container { padding: 2rem; max-width: 900px; margin: 0 auto; }

    .dashboard-header {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;
    }
    .header-info h1 { font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; }
    .header-info p  { color: var(--text-secondary); font-size: 0.95rem; margin: 0; }

    /* ===== البانل ===== */
    .form-card {
        background: #ffffff; border: 1px solid var(--border-color);
        border-radius: 12px; padding: 2.5rem; margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm); transition: all 0.3s;
    }
    .form-card:hover { border-color: var(--gold-accent); }

    /* ===== تقسيم الفورم ===== */
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
    .form-group { margin-bottom: 0.5rem; }
    .form-group.full { grid-column: 1 / -1; }
    
    .form-label { display: block; margin-bottom: 0.6rem; font-weight: 700; color: var(--text-primary); font-size: 0.95rem; }

    .form-control {
        width: 100%; padding: 0.9rem 1rem; border: 1px solid var(--border-color);
        border-radius: 8px; background-color: var(--primary-bg); font-family: inherit;
        font-size: 0.95rem; transition: all 0.2s; color: var(--text-primary);
    }
    .form-control:focus { outline: none; border-color: var(--gold-accent); box-shadow: 0 0 0 3px rgba(212,175,55,0.1); background: #fff; }

    /* ===== صندوق الملف الحالي ===== */
    .current-file-box {
        display: flex; align-items: center; gap: 0.75rem;
        background: var(--primary-bg); border: 1px dashed var(--border-color);
        border-radius: 8px; padding: 1rem; margin-bottom: 1rem;
    }
    .current-file-box i { font-size: 1.8rem; }
    .current-file-label { font-size: 0.82rem; color: var(--text-secondary); font-weight: 700; margin-bottom: 0.2rem; }
    .current-file-name  { font-size: 0.95rem; font-weight: 700; color: var(--text-primary); }

    /* ===== Upload Box ===== */
    .upload-box {
        border: 2px dashed var(--border-color); border-radius: 12px;
        padding: 2.5rem 2rem; text-align: center; background: var(--primary-bg);
        cursor: pointer; transition: all 0.3s;
    }
    .upload-box:hover { border-color: var(--gold-accent); background: #fff; }
    .upload-icon { font-size: 2.5rem; color: var(--sidebar-bg); opacity: 0.6; margin-bottom: 0.75rem; transition: 0.2s; }
    .upload-box:hover .upload-icon { opacity: 1; color: var(--gold-accent); }

    /* ===== أزرار التحكم ===== */
    .form-actions {
        display: flex; gap: 1rem; justify-content: flex-end;
        margin-top: 2rem; border-top: 1px dashed var(--border-color); padding-top: 1.5rem;
    }
    .btn-cancel {
        padding: 12px 24px; background: transparent; color: var(--text-secondary);
        border: 1px solid var(--border-color); border-radius: 8px; font-weight: 600;
        cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center;
        gap: 0.5rem; text-decoration: none;
    }
    .btn-cancel:hover { background: var(--primary-bg); color: var(--danger-color); border-color: var(--danger-color); }

    .btn-save {
        padding: 12px 32px; background: var(--sidebar-bg); color: #fff;
        border: none; border-radius: 8px; font-weight: 700; cursor: pointer;
        transition: 0.3s; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 1rem; font-family: inherit;
    }
    .btn-save:hover { background: var(--gold-accent); color: var(--sidebar-bg); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(212,175,55,0.2); }

    /* ===== الأخطاء ===== */
    .error-msg { font-size: 0.85rem; color: var(--danger-color); display: flex; align-items: center; gap: 0.4rem; margin-top: 6px; font-weight: 600; }
    .error-box { background: rgba(239,68,68,0.05); border: 1px solid var(--danger-color); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; }
    .error-box strong { color: var(--danger-color); display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-size: 1.05rem; }
    .error-box ul { color: var(--danger-color); margin: 0; padding-right: 1.5rem; font-weight: 600; }

    @media (max-width: 768px) {
        .edit-page-container { padding: 1rem; }
        .form-grid { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column-reverse; }
        .btn-cancel, .btn-save { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="edit-page-container">

    {{-- ===== الترويسة ===== --}}
    <div class="dashboard-header">
        <div class="header-info">
            <h1>
                <i class="fas fa-file-signature" style="color: var(--gold-accent); margin-left: 8px;"></i>
                تعديل المستند
            </h1>
            <p>
                تعديل بيانات المستند المرتبط بالقضية رقم:
                <strong style="color: var(--sidebar-bg);">{{ $document->case->case_number ?? '#' . $document->case_id }}</strong>
            </p>
        </div>
        <a href="{{ route('document.show', $document->id) }}" class="btn-cancel">
            <i class="fas fa-arrow-right"></i> رجوع للمستند
        </a>
    </div>

    @if($errors->any())
        <div class="error-box">
            <strong><i class="fas fa-exclamation-triangle"></i> يرجى مراجعة الأخطاء التالية:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <form action="{{ route('document.update', $document->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">

                {{-- نوع المستند --}}
                <div class="form-group">
                    <label class="form-label">نوع المستند <span style="color: var(--danger-color);">*</span></label>
                    <select name="document_type" class="form-control" required>
                        <option value="contract"   {{ old('document_type', $document->document_type) == 'contract'   ? 'selected' : '' }}>عقد</option>
                        <option value="report"     {{ old('document_type', $document->document_type) == 'report'     ? 'selected' : '' }}>تقرير</option>
                        <option value="attachment" {{ old('document_type', $document->document_type) == 'attachment' ? 'selected' : '' }}>مرفق</option>
                    </select>
                    @error('document_type')
                        <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- عنوان المستند --}}
                <div class="form-group">
                    <label class="form-label">عنوان المستند <span style="color: var(--danger-color);">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $document->title) }}" required>
                    @error('title')
                        <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- الوصف (أخذ العرض بالكامل) --}}
                <div class="form-group full">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="وصف مختصر للمستند (اختياري)...">{{ old('description', $document->description) }}</textarea>
                    @error('description')
                        <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- الملف (أخذ العرض بالكامل) --}}
                <div class="form-group full">
                    <label class="form-label">الملف المرفق <span style="color: var(--text-secondary); font-weight: 500; margin-right: 5px;">(ارفع ملف جديد فقط إذا أردت استبدال الملف الحالي)</span></label>

                    {{-- الملف الحالي --}}
                    @if($document->file_path)
                        @php
                            $ext = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
                            $iconMap = [
                                'pdf'  => ['icon' => 'fa-file-pdf',   'color' => 'var(--danger-color)'],
                                'doc'  => ['icon' => 'fa-file-word',  'color' => '#2563eb'],
                                'docx' => ['icon' => 'fa-file-word',  'color' => '#2563eb'],
                                'xls'  => ['icon' => 'fa-file-excel', 'color' => '#16a34a'],
                                'xlsx' => ['icon' => 'fa-file-excel', 'color' => '#16a34a'],
                            ];
                            $style = $iconMap[$ext] ?? ['icon' => 'fa-file-alt', 'color' => 'var(--sidebar-bg)'];
                        @endphp
                        <div class="current-file-box">
                            <i class="fas {{ $style['icon'] }}" style="color: {{ $style['color'] }};"></i>
                            <div>
                                <div class="current-file-label">الملف المرفوع حالياً بالنظام</div>
                                <div class="current-file-name" dir="ltr" style="text-align: right;">{{ $document->title }}.{{ $ext }}</div>
                            </div>
                        </div>
                    @endif

                    <div class="upload-box" id="uploadBox">
                        <div id="uploadDefault">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <p style="color: var(--text-primary); font-weight: 700; margin-bottom: 0.3rem;">اضغط هنا لاختيار ملف جديد لاستبدال القديم</p>
                            <p style="color: var(--text-secondary); font-size: 0.85rem;">PDF, DOC, DOCX, XLS, XLSX</p>
                        </div>
                        <div id="uploadPreview" style="display: none; flex-direction: column; align-items: center; gap: 0.6rem;"></div>
                    </div>
                    <input type="file" id="document_file" name="file" style="display:none;" accept=".pdf,.doc,.docx,.xls,.xlsx">

                    @error('file')
                        <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- أزرار التحكم --}}
            <div class="form-actions">
                <a href="{{ route('document.show', $document->id) }}" class="btn-cancel">
                    <i class="fas fa-times"></i> التراجع
                </a>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> تحديث المستند
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput    = document.getElementById('document_file');
    const uploadBox    = document.getElementById('uploadBox');
    const uploadDefault = document.getElementById('uploadDefault');
    const uploadPreview = document.getElementById('uploadPreview');

    uploadBox.addEventListener('click', function (e) {
        if (e.target.closest('.remove-file-btn')) return;
        fileInput.click();
    });

    fileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return resetUploadBox();

        uploadDefault.style.display = 'none';
        uploadPreview.style.display = 'flex';

        const sizeMB   = (file.size / (1024 * 1024)).toFixed(2);
        const ext      = file.name.split('.').pop().toLowerCase();
        const iconMap  = { pdf: ['fa-file-pdf','var(--danger-color)'], doc: ['fa-file-word','#2563eb'], docx: ['fa-file-word','#2563eb'], xls: ['fa-file-excel','#16a34a'], xlsx: ['fa-file-excel','#16a34a'] };
        const [icon, color] = iconMap[ext] ?? ['fa-file-alt', 'var(--sidebar-bg)'];

        uploadPreview.innerHTML = `
            <i class="fas ${icon}" style="font-size:3.5rem; color:${color}; margin-bottom: 0.5rem;"></i>
            <p style="color:var(--success-color); font-weight:700; margin:0;">
                <i class="fas fa-check-circle"></i>
                تم اختيار الملف الجديد: <span style="color:var(--text-primary);" dir="ltr">${file.name}</span> (${sizeMB} MB)
            </p>
            <span class="remove-file-btn" style="color:var(--danger-color); cursor:pointer; font-weight:700; text-decoration:underline; font-size:0.9rem; margin-top: 0.5rem;">
                <i class="fas fa-trash-alt"></i> التراجع واستخدام الملف القديم
            </span>
        `;
    });

    function resetUploadBox() {
        fileInput.value = '';
        uploadDefault.style.display = 'block';
        uploadPreview.style.display = 'none';
        uploadPreview.innerHTML = '';
    }

    uploadPreview.addEventListener('click', function (e) {
        if (e.target.closest('.remove-file-btn')) { e.stopPropagation(); resetUploadBox(); }
    });
});
</script>
@endpush