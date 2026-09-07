@extends('layouts.app')

@section('title', 'تعديل القضية - ' . $case->case_number . ' | ' . $appName)

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
        border-bottom: 2px solid var(--primary-bg); display: flex; align-items: center; gap: 0.75rem;
    }
    .form-card-title i { color: var(--gold-accent); font-size: 1.2rem; }

    /* ===== تقسيم الفورم ===== */
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
    .form-grid.cols-3 { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
    
    .form-group { margin-bottom: 0.5rem; }
    .form-group.full { grid-column: 1 / -1; }
    
    .form-label { display: block; margin-bottom: 0.6rem; font-weight: 700; color: var(--text-primary); font-size: 0.9rem; }
    
    .form-control { 
        width: 100%; padding: 0.9rem 1rem; border: 1px solid var(--border-color); 
        border-radius: 8px; background-color: var(--primary-bg); font-family: inherit; 
        font-size: 0.95rem; transition: all 0.2s; color: var(--text-primary);
    }
    .form-control:focus { outline: none; border-color: var(--gold-accent); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); background-color: #ffffff; }
    
    textarea.form-control { resize: vertical; min-height: 100px; }

    /* ===== التحديد المتعدد (العملاء والمحامين) ===== */
    .multi-select-grid { 
        display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); 
        gap: 0.8rem; max-height: 280px; overflow-y: auto; padding: 1rem; 
        background: var(--primary-bg); border: 1px solid var(--border-color); border-radius: 8px; 
    }
    
    .selection-item { 
        display: flex; flex-direction: column; gap: 0.5rem; padding: 0.8rem; 
        background: #ffffff; border: 1px solid var(--border-color); border-radius: 8px; 
        transition: all 0.2s ease; cursor: pointer;
    }
    .selection-item:hover { border-color: var(--gold-accent); }
    .selection-item.selected { border-color: var(--success-color); background: rgba(34, 197, 94, 0.05); }
    
    .selection-header { display: flex; align-items: center; gap: 0.6rem; }
    .selection-header input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--success-color); cursor: pointer; }
    .selection-header label { margin: 0; cursor: pointer; flex: 1; font-weight: 700; font-size: 0.9rem; color: var(--text-primary); }
    .lawyer-spec { display: block; font-size: 0.8rem; color: var(--text-secondary); font-weight: 500; margin-top: 2px; }

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
        transition: 0.3s; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 1rem; font-family: inherit;
    }
    .btn-save:hover { background-color: var(--gold-accent); color: var(--sidebar-bg); box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2); transform: translateY(-2px); }

    /* ===== الأخطاء ===== */
    .error-msg { font-size: 0.85rem; color: var(--danger-color); margin-top: 4px; display: block; font-weight: 600; }
    .error-box { background: rgba(239, 68, 68, 0.05); border: 1px solid var(--danger-color); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; }
    .error-box strong { color: var(--danger-color); font-size: 1.05rem; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; }
    .error-box ul { color: var(--danger-color); margin: 0; padding-right: 1.5rem; font-weight: 600; }

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
            <h1><i class="fas fa-edit" style="color: var(--gold-accent); margin-left: 8px;"></i> تعديل بيانات القضية</h1>
            <p>تحديث ملف القضية رقم: <strong style="color: var(--sidebar-bg);">{{ $case->case_number }}</strong></p>
        </div>
        <a href="{{ route('cases.show', $case->id) }}" class="btn-cancel">
            <i class="fas fa-arrow-right"></i> رجوع لملف القضية
        </a>
    </div>

    @if($errors->any())
        <div class="error-box">
            <strong><i class="fas fa-exclamation-triangle"></i> حدث خطأ في البيانات:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cases.update', $case->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- 1. بيانات القضية الأساسية --}}
        <div class="form-card">
            <div class="form-card-title"><i class="fas fa-file-alt"></i> بيانات القضية الأساسية</div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">رقم القضية <span style="color: var(--danger-color);">*</span></label>
                    <input type="text" name="case_number" class="form-control" value="{{ old('case_number', $case->case_number) }}" required>
                </div>

                {{-- التعديل تم هنا: الحالات مطابقة للداتا بيز بالضبط --}}
                <div class="form-group">
                    <label class="form-label">حالة القضية <span style="color: var(--danger-color);">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="">-- اختر حالة القضية --</option>
                        @foreach(['مفتوحة', 'متداولة', 'مؤجلة', 'محجوزة للحكم', 'منتهية', 'مستأنفة', 'محفوظة', 'معلقة'] as $s)
                            <option value="{{ $s }}" {{ old('status', $case->status) == $s ? 'selected' : '' }}>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">المحكمة <span style="color: var(--danger-color);">*</span></label>
                    <select name="court_id" class="form-control" required>
                        <option value="">-- اختر المحكمة --</option>
                        @foreach($courts as $court)
                            <option value="{{ $court->id }}" {{ old('court_id', $case->court_id) == $court->id ? 'selected' : '' }}>
                                {{ $court->jurisdiction->name ?? '' }} - {{ $court->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">نوع الجهة القضائية <span style="color: var(--danger-color);">*</span></label>
                    <select name="judicial_authority_type" class="form-control" required>
                        <option value="">-- اختر النوع --</option>
                        @foreach($jurisdictions as $jurisdiction)
                            <option value="{{ $jurisdiction->name }}" {{ old('judicial_authority_type', $case->judicial_authority_type ?? '') == $jurisdiction->name ? 'selected' : '' }}>
                                {{ $jurisdiction->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full">
                    <label class="form-label">درجة التقاضي <span style="color: var(--danger-color);">*</span></label>

                    @php
                        $currentCourtLevelId = (int) old('court_level_id', $case->court_level_id ?? '');
                    @endphp

                    <select name="court_level_id" class="form-control" required>
                        <option value="">-- اختر درجة التقاضي --</option>
                        @foreach($court_levels as $level)
                            <option value="{{ $level->id }}" {{ $currentCourtLevelId === (int)$level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('court_level_id')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <input type="hidden" name="jurisdiction_id" value="{{ old('jurisdiction_id', $case->jurisdiction_id ?? '') }}">

                <div class="form-group full">
                    <label class="form-label">وصف القضية</label>
                    <textarea name="description" class="form-control">{{ old('description', $case->description) }}</textarea>
                </div>

                <div class="form-group full">
                    <label class="form-label">الإجراء السابق</label>
                    <textarea name="Previous_procedure" class="form-control">{{ old('Previous_procedure', $case->Previous_procedure) }}</textarea>
                </div>

                <div class="form-group full">
                    <label class="form-label">الحكم النهائي</label>
                    <textarea name="final_decision" class="form-control">{{ old('final_decision', $case->final_decision) }}</textarea>
                </div>

                <div class="form-group full">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control">{{ old('notes', $case->notes) }}</textarea>
                </div>
            </div>
        </div>

        {{-- 2. بيانات الخصم --}}
        <div class="form-card">
            <div class="form-card-title"><i class="fas fa-user-times"></i> بيانات الخصم</div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">اسم الخصم <span style="color: var(--danger-color);">*</span></label>
                    <input type="text" name="rival_name" class="form-control" value="{{ old('rival_name', $case->rival_name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">رقم الخصم <span style="color: var(--danger-color);">*</span></label>
                    <input type="text" name="rival_number" class="form-control" value="{{ old('rival_number', $case->rival_number) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">الرقم القومي للخصم <span style="color: var(--danger-color);">*</span></label>
                    <input type="text" name="rival_nid" class="form-control" value="{{ old('rival_nid', $case->rival_nid) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">عنوان الخصم <span style="color: var(--danger-color);">*</span></label>
                    <input type="text" name="rival_address" class="form-control" value="{{ old('rival_address', $case->rival_address) }}" required>
                </div>
            </div>
        </div>

        {{-- 3. البيانات المالية --}}
        <div class="form-card">
            <div class="form-card-title"><i class="fas fa-coins"></i> البيانات المالية</div>
            
            <div class="form-grid cols-3">
                <div class="form-group">
                    <label class="form-label">التكاليف</label>
                    <input type="number" name="costs" class="form-control" step="0.01" value="{{ old('costs', $case->costs) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">إجمالي الأتعاب (التكاليف الإجمالية)</label>
                    <input type="number" name="total_costs" class="form-control" step="0.01" value="{{ old('total_costs', $case->total_costs) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">المبلغ المدفوع (الإيداع)</label>
                    <input type="number" name="deposit" class="form-control" step="0.01" value="{{ old('deposit', $case->deposit) }}">
                </div>
            </div>
        </div>

        {{-- 4. العملاء المرتبطون بالقضية --}}
        <div class="form-card">
            <div class="form-card-title"><i class="fas fa-users"></i> العملاء المرتبطون بالقضية</div>
            
            <div class="multi-select-grid">
                @php 
                    $savedClientIds = $case->clients ? array_map('intval', $case->clients->pluck('id')->toArray()) : [];
                    $selectedClients = old('client_ids') ? array_map('intval', old('client_ids')) : $savedClientIds;
                @endphp
                
                @foreach($clients as $client)
                    @php $isClientSelected = in_array((int)$client->id, $selectedClients); @endphp
                    
                    <div class="selection-item {{ $isClientSelected ? 'selected' : '' }}">
                        <div class="selection-header">
                            <input type="checkbox" id="client-{{ $client->id }}" name="client_ids[]" value="{{ $client->id }}" {{ $isClientSelected ? 'checked' : '' }} onchange="this.closest('.selection-item').classList.toggle('selected', this.checked)">
                            <label for="client-{{ $client->id }}">{{ $client->name }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('client_ids')
                <span class="error-msg" style="margin-top:10px;">{{ $message }}</span>
            @enderror
        </div>

        {{-- 5. المحامون المرتبطون بالقضية --}}
        <div class="form-card">
            <div class="form-card-title"><i class="fas fa-user-tie"></i> المحامون المرتبطون بالقضية</div>
            
            @php 
                $caseLawyers     = $case->lawyers->keyBy('id');
                $savedLawyerIds  = array_map('intval', $case->lawyers->pluck('id')->toArray());
                $selectedLawyers = old('lawyer_ids') ? array_map('intval', old('lawyer_ids')) : $savedLawyerIds;
            @endphp

            <div style="font-size: 0.95rem; font-weight: 700; color: var(--sidebar-bg); margin-bottom: 1rem;">
                <i class="fas fa-star" style="color: var(--gold-accent);"></i> حدد المحامين وأدوارهم في هذه القضية:
            </div>

            <div class="multi-select-grid">
                @foreach($lawyers as $lawyer)
                    @php
                        $isSelected = in_array((int)$lawyer->id, $selectedLawyers);

                        if (old('lawyer_ids')) {
                            $currentRole = old("lawyer_roles.{$lawyer->id}", 'assistant');
                        } else {
                            $currentRole = $caseLawyers->has($lawyer->id)
                                ? $caseLawyers[$lawyer->id]->pivot->role
                                : 'assistant';
                        }
                    @endphp

                    <div class="selection-item {{ $isSelected ? 'selected' : '' }}" id="row-{{ $lawyer->id }}">
                        <div class="selection-header">
                            <input type="checkbox" id="lawyer-{{ $lawyer->id }}" name="lawyer_ids[]" value="{{ $lawyer->id }}" {{ $isSelected ? 'checked' : '' }} onchange="this.closest('.selection-item').classList.toggle('selected', this.checked)">
                            <label for="lawyer-{{ $lawyer->id }}">
                                {{ $lawyer->name }}
                                @if($lawyer->specialization)
                                    <span class="lawyer-spec">{{ $lawyer->specialization }}</span>
                                @endif
                            </label>
                        </div>
                        
                        <select name="lawyer_roles[{{ $lawyer->id }}]" class="form-control" style="padding: 0.5rem; font-size: 0.85rem; margin-top: 0.5rem;">
                            <option value="lead"        {{ $currentRole === 'lead'        ? 'selected' : '' }}>محامي رئيسي</option>
                            <option value="assistant"   {{ $currentRole === 'assistant'   ? 'selected' : '' }}>مساعد</option>
                            <option value="consultant"  {{ $currentRole === 'consultant'  ? 'selected' : '' }}>مستشار</option>
                        </select>
                    </div>
                @endforeach
            </div>
            @error('lawyer_ids')
                <span class="error-msg" style="margin-top:10px;">{{ $message }}</span>
            @enderror
        </div>

        {{-- أزرار التحكم --}}
        <div class="form-actions">
            <a href="{{ route('cases.show', $case->id) }}" class="btn-cancel">
                <i class="fas fa-times"></i> إلغاء والتراجع
            </a>
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> حفظ جميع التعديلات
            </button>
        </div>

    </form>
</div>
@endsection