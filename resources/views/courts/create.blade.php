@extends('layouts.app')

@section('title', 'إضافة محكمة جديدة | ' . $appName)

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

    /* ===== البانل ===== */
    .form-card { 
        background: #ffffff; border: 1px solid var(--border-color); 
        border-radius: 12px; padding: 2.5rem 2rem; box-shadow: var(--shadow-sm); 
        transition: all 0.3s ease;
    }
    .form-card:hover { border-color: var(--gold-accent); box-shadow: 0 4px 15px rgba(0,0,0,0.03); }

    /* ===== تقسيم الفورم ===== */
    .form-grid { 
        display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
        gap: 1.5rem; 
    }
    
    .form-group { margin-bottom: 1.5rem; }
    .form-group.full { grid-column: 1 / -1; }
    
    .form-label { display: block; margin-bottom: 0.6rem; font-weight: 700; color: var(--text-primary); font-size: 0.95rem; }
    
    .form-control { 
        width: 100%; padding: 0.9rem 1rem; border: 1px solid var(--border-color); 
        border-radius: 8px; background-color: var(--primary-bg); font-family: inherit; 
        font-size: 0.95rem; transition: all 0.2s; color: var(--text-primary);
    }
    .form-control:focus { 
        outline: none; border-color: var(--gold-accent); 
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); background-color: #ffffff;
    }

    .error-msg { font-size: 0.85rem; color: var(--danger-color); display: flex; align-items: center; gap: 0.4rem; margin-top: 6px; font-weight: 600; }

    /* ===== أزرار التحكم ===== */
    .form-actions { 
        display: flex; gap: 1rem; justify-content: flex-end; 
        margin-top: 2rem; border-top: 1px dashed var(--border-color); padding-top: 1.5rem; 
    }
    
    .btn-cancel { 
        padding: 12px 24px; background-color: transparent; color: var(--text-secondary); 
        border: 1px solid var(--border-color); border-radius: 8px; font-weight: 600; 
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
        .form-card { padding: 1.5rem; }
        .form-actions { flex-direction: column-reverse; }
        .btn-cancel, .btn-save { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="create-page-container">

    <div class="dashboard-header">
        <div class="header-info">
            <h1><i class="fas fa-landmark" style="color: var(--gold-accent); margin-left: 8px;"></i> إضافة محكمة جديدة</h1>
            <p>إعداد بيانات محكمة جديدة وربطها بجهة التقاضي</p>
        </div>
        <a href="{{ route('courts.index') }}" class="btn-cancel">
            <i class="fas fa-arrow-right"></i> رجوع للقائمة
        </a>
    </div>

    <div class="form-card">
        <form action="{{ route('courts.store') }}" method="POST" novalidate>
            @csrf

            <div class="form-grid">
                {{-- اسم المحكمة --}}
                <div class="form-group full">
                    <label for="name" class="form-label">اسم المحكمة <span style="color: var(--danger-color);">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="مثال: محكمة استئناف القاهرة" required>
                    @error('name')
                        <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- جهة التقاضي --}}
                <div class="form-group full">
                    <label for="jurisdiction_id" class="form-label">جهة التقاضي <span style="color: var(--danger-color);">*</span></label>
                    <select id="jurisdiction_id" name="jurisdiction_id" class="form-control" required>
                        <option value="" disabled selected>-- اختر جهة التقاضي --</option>
                        @foreach($jurisdictions as $jurisdiction)
                            <option value="{{ $jurisdiction->id }}" {{ old('jurisdiction_id') == $jurisdiction->id ? 'selected' : '' }}>
                                {{ $jurisdiction->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('jurisdiction_id')
                        <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- أزرار الإجراءات --}}
            <div class="form-actions">
                <a href="{{ route('courts.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> إلغاء
                </a>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> حفظ البيانات
                </button>
            </div>

        </form>
    </div>

</div>
@endsection