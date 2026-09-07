@extends('layouts.app')

@section('title', 'إضافة قضية جديدة | ' . $appName)

@push('styles')
<style>
    /* ===== الحاوية والترويسة ===== */
    .create-page-container { padding: 2rem; max-width: 1000px; margin: 0 auto; }
    
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
    .form-grid.cols-3 { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
    
    .form-group { margin-bottom: 1rem; }
    .form-group.full { grid-column: 1 / -1; }
    .form-label { display: block; margin-bottom: 0.6rem; font-weight: 700; color: var(--text-primary); font-size: 0.9rem; }
    
    .form-control { 
        width: 100%; padding: 0.9rem 1rem; border: 1px solid var(--border-color); 
        border-radius: 8px; background-color: #ffffff; font-family: inherit; 
        font-size: 0.95rem; transition: all 0.2s;
    }
    .form-control:focus { outline: none; border-color: var(--gold-accent); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); }
    .form-control[readonly], .form-control:disabled { 
        background-color: var(--primary-bg); border-style: dashed; cursor: not-allowed; color: var(--text-secondary);
    }

    textarea.form-control { resize: vertical; min-height: 100px; }

    /* ===== أزرار الإضافة المصغرة ===== */
    .btn-outline-sm {
        display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.4rem 0.8rem;
        border: 1px solid var(--gold-accent); color: #9a7b21; background: rgba(212, 175, 55, 0.05);
        border-radius: 6px; font-size: 0.85rem; font-weight: 700; text-decoration: none; transition: all 0.2s;
    }
    .btn-outline-sm:hover { background: var(--gold-accent); color: #ffffff; }

    /* ===== المرفقات (Upload Box) ===== */
    .upload-box { 
        border: 2px dashed var(--border-color); border-radius: 12px; 
        padding: 2.5rem 2rem; text-align: center; background: var(--primary-bg); 
        cursor: pointer; transition: all 0.3s; margin-top: 1rem;
    }
    .upload-box:hover { border-color: var(--gold-accent); background: #ffffff; }
    .upload-icon { font-size: 2.5rem; color: var(--text-secondary); margin-bottom: 1rem; transition: color 0.2s; }
    .upload-box:hover .upload-icon { color: var(--gold-accent); }

    /* ===== أزرار التحكم ===== */
    .form-actions { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; }
    
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

    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-info">
            <h1><i class="fas fa-folder-plus" style="color: var(--gold-accent); margin-left: 8px;"></i> إضافة ملف قضية جديد</h1>
            <p>سجل بيانات القضية والطرف الموكل، الخصم، والماليات المترتبة عليها</p>
        </div>
        <a href="{{ route('cases.index') }}" class="btn-cancel">
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

    <form id="caseForm" action="{{ route('cases.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf

        {{-- 1. بيانات العميل --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-user-tie"></i> بيانات العميل الموكل</div>
                <a href="{{ route('add-client') }}" class="btn-outline-sm" target="_blank">
                    <i class="fas fa-plus"></i> عميل جديد
                </a>
            </div>

            <div class="form-grid">
                <div class="form-group full">
                    <label for="client_search" class="form-label">ابحث عن اسم العميل الموكل <span style="color: var(--danger-color);">*</span></label>
                    <input type="text" id="client_search" list="clients_list" class="form-control" placeholder="ابدأ بكتابة اسم العميل للربط التلقائي..." autocomplete="off" required>
                    <datalist id="clients_list">
                        @foreach ($clients as $client)
                            <option value="{{ $client->name }}" 
                                    data-id="{{ $client->id }}" 
                                    data-phone="{{ $client->phone ?? 'غير مسجل' }}" 
                                    data-national="{{ $client->nid ?? 'غير مسجل' }}" 
                                    data-address="{{ $client->address ?? 'غير مسجل' }}">
                            </option>
                        @endforeach
                    </datalist>
                    <input type="hidden" id="client_id" name="client_id" value="{{ old('client_id') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">رقم الهاتف</label>
                    <input type="text" id="display_phone" class="form-control" placeholder="سيتم السحب تلقائياً" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">الرقم القومي</label>
                    <input type="text" id="display_national_id" class="form-control" placeholder="سيتم السحب تلقائياً" readonly>
                </div>

                <div class="form-group full">
                    <label class="form-label">العنوان المسجل</label>
                    <input type="text" id="display_address" class="form-control" placeholder="سيتم السحب تلقائياً" readonly>
                </div>
            </div>
        </div>

        {{-- 2. الفريق القانوني --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-users-cog"></i> الفريق القانوني المسؤول</div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="lawyer_id" class="form-label">المحامي المسؤول عن القضية <span style="color: var(--danger-color);">*</span></label>
                    @if(auth()->user()->role === 'admin')
                        <select id="lawyer_id" name="lawyer_id" class="form-control" required>
                            <option value="" disabled selected>اختر المحامي المسؤول...</option>
                            @foreach ($lawyers as $lawyer)
                                <option value="{{ $lawyer->id }}" {{ old('lawyer_id') == $lawyer->id ? 'selected' : '' }}>{{ $lawyer->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="lawyer_id" value="{{ auth()->user()->lawyer_id }}">
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                    @endif
                </div>
            </div>
        </div>

        {{-- 3. تفاصيل القضية والماليات --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-gavel"></i> تفاصيل القضية والماليات</div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="case_number" class="form-label">رقم القضية <span style="color: var(--danger-color);">*</span></label>
                    <input type="text" id="case_number" name="case_number" class="form-control" value="{{ old('case_number') }}" placeholder="مثال: 2525 أو 2026/123" required>
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">حالة القضية الحالية <span style="color: var(--danger-color);">*</span></label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="مفتوحة" {{ old('status', 'مفتوحة') == 'مفتوحة' ? 'selected' : '' }}>مفتوحة</option>
                        <option value="متداولة" {{ old('status') == 'متداولة' ? 'selected' : '' }}>متداولة</option>
                        <option value="مؤجلة" {{ old('status') == 'مؤجلة' ? 'selected' : '' }}>مؤجلة</option>
                        <option value="محجوزة للحكم" {{ old('status') == 'محجوزة للحكم' ? 'selected' : '' }}>محجوزة للحكم</option>
                        <option value="منتهية" {{ old('status') == 'منتهية' ? 'selected' : '' }}>منتهية</option>
                        <option value="مستأنفة" {{ old('status') == 'مستأنفة' ? 'selected' : '' }}>مستأنفة</option>
                        <option value="محفوظة" {{ old('status') == 'محفوظة' ? 'selected' : '' }}>محفوظة</option>
                        <option value="معلقة" {{ old('status') == 'معلقة' ? 'selected' : '' }}>معلقة</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jurisdiction_id" class="form-label">جهة التقاضي <span style="color: var(--danger-color);">*</span></label>
                    <select id="jurisdiction_id" name="jurisdiction_id" class="form-control" required>
                        <option value="" disabled selected>اختر الجهة...</option>
                        @foreach ($jurisdictions as $jurisdiction)
                            <option value="{{ $jurisdiction->id }}" {{ old('jurisdiction_id') == $jurisdiction->id ? 'selected' : '' }}>{{ $jurisdiction->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="court_level_id" class="form-label">درجة التقاضي / المحكمة <span style="color: var(--danger-color);">*</span></label>
                    <select id="court_level_id" name="court_level_id" class="form-control" required>
                        <option value="" disabled selected>اختر درجة التقاضي...</option>
                        @foreach ($court_levels as $level)
                            <option value="{{ $level->id }}" {{ old('court_level_id') == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="court_id" class="form-label">المحكمة <span style="color: var(--danger-color);">*</span></label>
                    <select id="court_id" name="court_id" class="form-control" required>
                        <option value="" disabled selected>اختر المحكمة...</option>
                        @foreach ($courts as $court)
                            <option value="{{ $court->id }}" data-jurisdiction="{{ $court->jurisdiction_id }}" {{ old('court_id') == $court->id ? 'selected' : '' }}>
                                {{ $court->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="circuit" class="form-label">الدائرة</label>
                    <input type="text" id="circuit" name="circuit" class="form-control" value="{{ old('circuit') }}" placeholder="مثال: الدائرة الثالثة مدني">
                </div>

                <div class="form-group full">
                    <label for="Previous_procedure" class="form-label">الإجراء السابق أو الموقف الحالي للدعوى</label>
                    <input type="text" id="Previous_procedure" name="Previous_procedure" class="form-control" value="{{ old('Previous_procedure', 'لا يوجد إشعار سابق') }}" placeholder="مثال: تقديم مستندات، إعادة إعلان...">
                </div>
            </div>

           <div class="form-grid cols-3" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px dashed var(--border-color);">
    <div class="form-group">
        <label for="total_costs" class="form-label">إجمالي الأتعاب (ج.م)</label>
        <input type="number" id="total_costs" name="total_costs" class="form-control" value="{{ old('total_costs') }}" min="0" placeholder="مثال: 10000">
    </div>

    <div class="form-group">
        <label for="deposit" class="form-label">المدفوع مقدماً (ج.م)</label>
        <input type="number" id="deposit" name="deposit" class="form-control" value="{{ old('deposit') }}" min="0" placeholder="مثال: 1000">
    </div>

    <div class="form-group">
        <label class="form-label">المبلغ المتبقي (ج.م)</label>
        <input type="number" id="remaining_amount" class="form-control" value="" readonly style="color: var(--danger-color); font-weight: 800; background: rgba(239, 68, 68, 0.05);">
    </div>

    <div class="form-group full">
        <label for="costs" class="form-label">المصاريف الإدارية والرسوم (ج.م)</label>
        <input type="number" id="costs" name="costs" class="form-control" value="{{ old('costs') }}" min="0" placeholder="مثال: 500">
    </div>
</div> 
  {{-- 4. الخصم وموضوع الدعوى --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-user-shield"></i> موضوع الدعوى والخصم</div>
            </div>

            <div class="form-group full">
                <label for="description" class="form-label">ملخص وقائع الدعوى <span style="color: var(--danger-color);">*</span></label>
                <textarea id="description" name="description" class="form-control" placeholder="شرح تفصيلي للوقائع..." required>{{ old('description') }}</textarea>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="rival_name" class="form-label">اسم الخصم بالكامل <span style="color: var(--danger-color);">*</span></label>
                    <input type="text" id="rival_name" name="rival_name" class="form-control" value="{{ old('rival_name') }}" required>
                </div>
                <div class="form-group">
                    <label for="rival_number" class="form-label">رقم هاتف الخصم</label>
                    <input type="text" id="rival_number" name="rival_number" class="form-control" value="{{ old('rival_number') }}">
                </div>
                <div class="form-group">
                    <label for="rival_nid" class="form-label">الرقم القومي للخصم</label>
                    <input type="text" id="rival_nid" name="rival_nid" class="form-control" value="{{ old('rival_nid') }}" maxlength="14">
                </div>
                <div class="form-group">
                    <label for="rival_address" class="form-label">عنوان الخصم</label>
                    <input type="text" id="rival_address" name="rival_address" class="form-control" value="{{ old('rival_address') }}">
                </div>
            </div>
        </div>

        {{-- 5. المرفقات والتوكيل --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-paperclip"></i> المرفقات والتوكيلات</div>
            </div>

            <div class="form-grid">
                <div class="form-group full">
                    <label for="procuration" class="form-label">بيانات أو رقم التوكيل الرسمي الخاص بالقضية</label>
                    <input type="text" id="procuration" name="procuration" class="form-control" value="{{ old('procuration', 'لا يوجد') }}" placeholder="مثال: توكيل رقم 1234 ص توثيق أسوان النموذجي">
                </div>

          <div class="form-group full">
    <label for="final_decision" class="form-label">الحكم النهائي أو القرار (في حال كانت منتهية)</label>
    <input type="text" id="final_decision" name="final_decision" class="form-control" value="{{ old('final_decision', 'لم يصدر حكم بعد') }}" placeholder="مثال: قبول الدعوى شكلاً وفي الموضوع..." readonly style="cursor: not-allowed; opacity: 0.7;">
</div>

                <div class="form-group full">
                    <label for="notes" class="form-label">ملاحظات إضافية على القضية</label>
                    <textarea id="notes" name="notes" class="form-control" placeholder="أي تفاصيل أو ملاحظات أخرى للمكتب...">{{ old('notes', 'لا توجد ملاحظات') }}</textarea>
                </div>
            </div>

            <div class="upload-box" id="uploadBox">
                <div id="uploadDefault">
                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                    <p style="color: var(--text-secondary); font-weight: 600;">اضغط لرفع التوكيل (PDF, Word, Images)</p>
                </div>

                <div id="uploadPreview" style="display: none; flex-direction: column; align-items: center; gap: 0.8rem;"></div>
            </div>
            <input type="file" id="case_file" name="case_file" style="display:none;" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
        </div>

        {{-- أزرار التحكم --}}
        <div class="form-actions">
            <button type="button" class="btn-cancel" id="resetFormBtn">
                <i class="fas fa-eraser"></i> مسح المدخلات
            </button>
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> حفظ ملف القضية
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('client_search');
    const datalist = document.getElementById('clients_list');
    const hiddenClientId = document.getElementById('client_id');

    const displayPhone = document.getElementById('display_phone');
    const displayNationalId = document.getElementById('display_national_id');
    const displayAddress = document.getElementById('display_address');

    const totalCostsInput = document.getElementById('total_costs');
    const depositInput = document.getElementById('deposit');
    const remainingInput = document.getElementById('remaining_amount');

    /* حساب المتبقي تلقائياً */
    function calculateRemaining() {
        const totalCosts = parseFloat(totalCostsInput.value) || 0;
        const deposit = parseFloat(depositInput.value) || 0;
        const remaining = totalCosts - deposit;
        remainingInput.value = remaining;
    }

    totalCostsInput.addEventListener('input', calculateRemaining);
    depositInput.addEventListener('input', calculateRemaining);
    calculateRemaining();

    /* البحث عن عميل والربط التلقائي */
    searchInput.addEventListener('input', function () {
        const inputValue = this.value.trim();
        const options = datalist.options;
        let found = false;

        for (let i = 0; i < options.length; i++) {
            if (options[i].value === inputValue) {
                hiddenClientId.value = options[i].getAttribute('data-id');
                displayPhone.value = options[i].getAttribute('data-phone');
                displayNationalId.value = options[i].getAttribute('data-national');
                displayAddress.value = options[i].getAttribute('data-address');
                found = true;
                break;
            }
        }

        if (!found) {
            hiddenClientId.value = '';
            displayPhone.value = '';
            displayNationalId.value = '';
            displayAddress.value = '';
        }
    });

    /* تفريغ ومسح الفورم بالكامل وعودة القيم الافتراضية */
    document.getElementById('resetFormBtn').addEventListener('click', function() {
        if(confirm('هل أنت متأكد من مسح جميع البيانات المدخلة؟')) {
            document.getElementById('caseForm').reset();
            hiddenClientId.value = '';
            displayPhone.value = '';
            displayNationalId.value = '';
            displayAddress.value = '';
            setTimeout(() => {
                filterCourts();
                resetUploadBox();
                calculateRemaining();
            }, 20);
        }
    });

    /* فلترة المحاكم بناءً على جهة التقاضي المحددة */
    const judicialSelect = document.getElementById('jurisdiction_id');
    const courtSelect = document.getElementById('court_id');

    if (judicialSelect && courtSelect) {
        const allCourtOptions = Array.from(courtSelect.querySelectorAll('option')).filter(opt => opt.value !== '');
        const oldCourtValue = "{{ old('court_id') }}";

        function filterCourts() {
            const selectedJurisdictionId = judicialSelect.value;
            courtSelect.innerHTML = '';

            const placeholderOpt = document.createElement('option');
            placeholderOpt.value = '';
            placeholderOpt.textContent = 'اختر المحكمة...';
            placeholderOpt.disabled = true;
            placeholderOpt.selected = true;
            courtSelect.appendChild(placeholderOpt);

            let hasMatches = false;

            allCourtOptions.forEach(opt => {
                const courtJurisdiction = opt.getAttribute('data-jurisdiction');
                if (!selectedJurisdictionId || courtJurisdiction == selectedJurisdictionId) {
                    const clone = opt.cloneNode(true);
                    if (clone.value === oldCourtValue) {
                        clone.selected = true;
                    }
                    courtSelect.appendChild(clone);
                    hasMatches = true;
                }
            });

            if (selectedJurisdictionId && !hasMatches) {
                const noMatchesOpt = document.createElement('option');
                noMatchesOpt.value = '';
                noMatchesOpt.textContent = 'لا توجد محاكم تابعة لهذه الجهة';
                noMatchesOpt.disabled = true;
                courtSelect.appendChild(noMatchesOpt);
            }
        }

        filterCourts();
        judicialSelect.addEventListener('change', filterCourts);
    }

    /* واجهة رفع المرفقات المخصصة بصرياً */
    const fileInput = document.getElementById('case_file');
    const uploadBox = document.getElementById('uploadBox');
    const uploadDefault = document.getElementById('uploadDefault');
    const uploadPreview = document.getElementById('uploadPreview');

    uploadBox.addEventListener('click', function (e) {
        if (e.target.closest('.remove-file-btn')) return;
        fileInput.click();
    });

    fileInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            uploadDefault.style.display = 'none';
            uploadPreview.style.display = 'flex';
            uploadPreview.innerHTML = '';

            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    uploadPreview.innerHTML = `
                        <div style="position: relative; display: inline-block;">
                            <img src="${e.target.result}" style="max-width: 140px; max-height: 140px; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        </div>
                        <p style="color: var(--success-color); font-size: 0.95rem; font-weight: 700; margin: 0.5rem 0;">
                            <i class="fas fa-check-circle"></i> تم اختيار: <span style="color: var(--text-primary);">${file.name}</span> (${fileSizeMB} MB)
                        </p>
                        <span class="remove-file-btn" style="color: var(--danger-color); cursor: pointer; font-size: 0.9rem; font-weight: 700; text-decoration: underline;">
                            <i class="fas fa-trash-alt"></i> حذف واختيار ملف آخر
                        </span>
                    `;
                }
                reader.readAsDataURL(file);
            } else {
                let fileIcon = 'fa-file-pdf';
                let iconColor = 'var(--danger-color)';

                if (file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
                    fileIcon = 'fa-file-word';
                    iconColor = '#3498db';
                }

                uploadPreview.innerHTML = `
                    <i class="fas ${fileIcon}" style="font-size: 3.5rem; color: ${iconColor};"></i>
                    <p style="color: var(--success-color); font-size: 0.95rem; font-weight: 700; margin: 0.5rem 0;">
                        <i class="fas fa-check-circle"></i> تم اختيار: <span style="color: var(--text-primary);">${file.name}</span> (${fileSizeMB} MB)
                    </p>
                    <span class="remove-file-btn" style="color: var(--danger-color); cursor: pointer; font-size: 0.9rem; font-weight: 700; text-decoration: underline;">
                        <i class="fas fa-trash-alt"></i> حذف واختيار ملف آخر
                    </span>
                `;
            }
        } else {
            resetUploadBox();
        }
    });

    function resetUploadBox() {
        fileInput.value = '';
        uploadDefault.style.display = 'block';
        uploadPreview.style.display = 'none';
        uploadPreview.innerHTML = '';
    }

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