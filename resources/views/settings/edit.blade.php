@extends('layouts.app')
@section('title', 'إعدادات المكتب | ' . $appName)

@push('styles')
<style>
    .settings-page { max-width: 700px; margin: 0 auto; }

    .settings-header {
        display: flex; align-items: center; gap: 0.75rem;
        margin-bottom: 2rem;
    }
    .settings-header h1 {
        font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0;
    }
    .settings-header i { color: var(--gold-accent); font-size: 1.4rem; }

    .settings-card {
        background: var(--card-bg, #ffffff);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 2rem;
        box-shadow: var(--shadow-sm);
    }

    .settings-section-title {
        font-size: 1rem; font-weight: 700;
        color: var(--sidebar-bg);
        border-bottom: 2px solid var(--primary-bg);
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: 0.5rem;
    }

    .form-group { margin-bottom: 1.5rem; }
    .form-label {
        display: block; font-weight: 600; font-size: 0.9rem;
        color: var(--text-primary); margin-bottom: 0.5rem;
    }
    .form-hint { font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.35rem; }

    .form-input {
        width: 100%; padding: 0.75rem 1rem;
        border: 1.5px solid var(--border-color); border-radius: 8px;
        font-size: 0.95rem; font-family: 'Tajawal', sans-serif;
        color: var(--text-primary);
        background: var(--input-bg, #f8fafc);
        transition: 0.2s;
        outline: none;
    }
    .form-input:focus {
        border-color: var(--gold-accent);
        box-shadow: 0 0 0 3px rgba(212,175,55,0.15);
        background: var(--input-focus-bg, #ffffff);
    }

    .btn-save {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.75rem 2rem; border-radius: 8px;
        background: var(--sidebar-bg); color: #fff;
        font-size: 0.95rem; font-weight: 700;
        font-family: 'Tajawal', sans-serif;
        border: none; cursor: pointer; transition: 0.2s;
    }
    .btn-save:hover { opacity: 0.88; transform: translateY(-1px); }

    .preview-box {
        background: var(--primary-bg);
        border: 1px dashed var(--border-color);
        border-radius: 8px;
        padding: 1rem 1.2rem;
        margin-top: 1.5rem;
        font-size: 0.88rem;
        color: var(--text-secondary);
    }
    .preview-box strong { color: var(--text-primary); }

    /* Dark mode */
    [data-theme="dark"] .settings-card { background: var(--card-bg); }
    [data-theme="dark"] .form-input { background: rgba(255,255,255,0.06); }
    [data-theme="dark"] .form-input:focus { background: rgba(255,255,255,0.1); }
</style>
@endpush

@section('content')
<div class="settings-page">

    {{-- Header --}}
    <div class="settings-header">
        <i class="fas fa-cog"></i>
        <h1>إعدادات المكتب</h1>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div style="background:rgba(34,197,94,0.12); border:1px solid rgba(34,197,94,0.35);
                    color:#15803d; border-radius:10px; padding:0.85rem 1.2rem;
                    margin-bottom:1.5rem; display:flex; align-items:center; gap:0.6rem;">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Errors --}}
    @if($errors->any())
        <div style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3);
                    color:#b91c1c; border-radius:10px; padding:0.85rem 1.2rem;
                    margin-bottom:1.5rem;">
            <ul style="margin:0; padding-right:1.2rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Settings Card --}}
    <div class="settings-card">

        <div class="settings-section-title">
            <i class="fas fa-building" style="color:var(--gold-accent);"></i>
            هوية المكتب (White-Labeling)
        </div>

        <form action="{{ route('settings.update') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="app_name" class="form-label">اسم المكتب / التطبيق</label>
                <input type="text"
                       id="app_name"
                       name="app_name"
                       class="form-input"
                       value="{{ old('app_name', $appName) }}"
                       placeholder="مثال: الميزان، مكتب أحمد للمحاماة..."
                       maxlength="100"
                       oninput="updatePreview(this.value)">
                <p class="form-hint">
                    <i class="fas fa-info-circle"></i>
                    سيظهر هذا الاسم في: عنوان المتصفح، الشريط الجانبي، التذييل، والمساعد الذكي.
                </p>
            </div>

            {{-- Live Preview --}}
            <div class="preview-box" id="previewBox">
                <i class="fas fa-eye" style="margin-left:5px;"></i>
                معاينة: <strong id="previewName">{{ $appName }}</strong>
                &nbsp;·&nbsp; عنوان المتصفح: <strong id="previewTitle">الرئيسية | {{ $appName }}</strong>
            </div>

            <div style="margin-top:1.5rem;">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i>
                    حفظ الإعدادات
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function updatePreview(name) {
        const safeVal = name || 'الميزان';
        document.getElementById('previewName').textContent = safeVal;
        document.getElementById('previewTitle').textContent = 'الرئيسية | ' + safeVal;
    }
</script>
@endpush
