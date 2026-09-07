{{-- resources/views/appointments/create.blade.php --}}

@extends('layouts.app')

@section('title', 'إضافة موعد جديد | ' . $appName)

@push('styles')
<style>
    .create-page-container { padding: 2rem; max-width: 900px; margin: 0 auto; }
    
    .dashboard-header { 
        display: flex; justify-content: space-between; align-items: flex-start; 
        margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;
    }
    .header-info h1 { font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; }
    .header-info p { color: var(--text-secondary); font-size: 0.95rem; margin: 0; }

    .form-card { 
        background: #ffffff; border: 1px solid var(--border-color); 
        border-radius: 12px; padding: 2rem; box-shadow: var(--shadow-sm); 
    }

    /* ===== الصف الأول: القضية تاخد العرض كله ===== */
    .form-row-full { margin-bottom: 1.5rem; }

    /* ===== الصف الثاني: التاريخ والوقت جنب بعض ===== */
    .form-row-half { 
        display: grid; grid-template-columns: 1fr 1fr; 
        gap: 1.5rem; margin-bottom: 1.5rem; 
    }

    .form-group { }
    .form-label { 
        display: block; margin-bottom: 0.6rem; font-weight: 700; 
        color: var(--text-primary); font-size: 0.9rem; 
    }
    .form-control { 
        width: 100%; padding: 0.9rem 1rem; border: 1px solid var(--border-color); 
        border-radius: 8px; background-color: #ffffff; font-family: inherit; 
        font-size: 0.95rem; transition: all 0.2s; color: var(--text-primary);
    }
    .form-control:focus { 
        outline: none; border-color: var(--gold-accent); 
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); 
    }
    textarea.form-control { resize: vertical; min-height: 110px; }

    .form-actions { 
        display: flex; gap: 1rem; justify-content: flex-end; 
        margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem;
    }
    .btn-cancel { 
        padding: 12px 24px; background-color: transparent; color: var(--text-secondary); 
        border: 1px solid var(--border-color); border-radius: 8px; font-weight: 600; 
        cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; 
        gap: 0.5rem; text-decoration: none;
    }
    .btn-cancel:hover { background-color: var(--primary-bg); color: var(--danger-color); border-color: var(--danger-color); }
    
    .btn-save { 
        padding: 12px 32px; background-color: var(--sidebar-bg); color: #ffffff; 
        border: none; border-radius: 8px; font-weight: 700; cursor: pointer; 
        transition: 0.3s; display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-save:hover { background-color: var(--gold-accent); color: var(--sidebar-bg); box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2); }

    @media (max-width: 600px) {
        .form-row-half { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="create-page-container">

    <div class="dashboard-header">
        <div class="header-info">
            <h1><i class="fas fa-calendar-plus" style="color: var(--gold-accent); margin-left: 8px;"></i> إضافة موعد جديد</h1>
            <p>سجل موعداً جديداً لجلسة قضائية أو اجتماع مع موكل</p>
        </div>
        {{-- تم تعديل زر الرجوع ليعود إلى القضية نفسها --}}
        <a href="{{ route('cases.show', $case->id) }}" class="btn-cancel">
            <i class="fas fa-arrow-right"></i> رجوع للقضية
        </a>
    </div>

    <div class="form-card">
        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf

            {{-- القضية — صف كامل (ثابتة، مش قابلة للتعديل) --}}
            <div class="form-row-full">
                <div class="form-group">
                    <label class="form-label">القضية</label>
                    <div class="form-control" style="background-color: var(--primary-bg); display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-folder-open" style="color: var(--gold-accent);"></i>
                        <strong>{{ $case->case_number }}</strong>
                    </div>
                    <input type="hidden" name="case_id" value="{{ $case->id }}">
                </div>
            </div>

            {{-- التاريخ والوقت — نصفين --}}
            <div class="form-row-half">
                <div class="form-group">
                    <label for="date" class="form-label">
                        التاريخ <span style="color: var(--danger-color);">*</span>
                    </label>
                    <input type="date" id="date" name="date" class="form-control" 
                           value="{{ old('date') }}" required>
                    @error('date')
                        <div style="font-size:0.85rem; color:var(--danger-color); margin-top:6px; font-weight:600;">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="time" class="form-label">
                        الوقت <span style="color: var(--danger-color);">*</span>
                    </label>
                    <input type="time" id="time" name="time" class="form-control" 
                           value="{{ old('time') }}" required>
                    @error('time')
                        <div style="font-size:0.85rem; color:var(--danger-color); margin-top:6px; font-weight:600;">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- الملاحظات — صف كامل --}}
            <div class="form-group">
                <label for="notes" class="form-label">الملاحظات</label>
                <textarea id="notes" name="notes" class="form-control" 
                          placeholder="اكتب أي ملاحظات هنا...">{{ old('notes') }}</textarea>
                @error('notes')
                    <div style="font-size:0.85rem; color:var(--danger-color); margin-top:6px; font-weight:600;">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-actions">
                {{-- تم تعديل زر الإلغاء ليعود إلى القضية نفسها --}}
                <a href="{{ route('cases.show', $case->id) }}" class="btn-cancel">
                    <i class="fas fa-times"></i> إلغاء
                </a>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> حفظ الموعد
                </button>
            </div>
        </form>
    </div>

</div>
@endsection