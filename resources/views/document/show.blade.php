@extends('layouts.app')

@section('title', 'عرض المستند | ' . $appName)

@push('styles')
<style>
    .show-page-container { padding: 2rem; max-width: 860px; margin: 0 auto; }

    .dashboard-header {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;
    }
    .header-info h1 { font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.4rem; }
    .header-info p  { color: var(--text-secondary); font-size: 0.95rem; margin: 0; }

    .header-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }

    /* ===== كروت التفاصيل ===== */
    .detail-card {
        background: #ffffff; border: 1px solid var(--border-color);
        border-radius: 12px; padding: 2rem; margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
    }
    .detail-card-title {
        font-size: 1rem; font-weight: 800; color: var(--text-primary);
        margin-bottom: 1.5rem; padding-bottom: 0.75rem;
        border-bottom: 2px dashed var(--border-color);
        display: flex; align-items: center; gap: 0.5rem;
    }
    .detail-card-title i { color: var(--gold-accent); }

    .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }

    .detail-item {}
    .detail-label {
        font-size: 0.82rem; font-weight: 700; color: var(--text-secondary);
        text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 0.4rem;
    }
    .detail-value {
        font-size: 0.97rem; font-weight: 600; color: var(--text-primary);
        background: var(--primary-bg); padding: 0.65rem 1rem;
        border-radius: 8px; border: 1px solid var(--border-color);
        min-height: 42px; display: flex; align-items: center;
    }
    .detail-value.full-width { grid-column: 1 / -1; align-items: flex-start; min-height: 60px; }

    /* ===== Badge نوع المستند ===== */
    .badge-type {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.3rem 0.85rem; border-radius: 50px;
        font-size: 0.85rem; font-weight: 700;
    }
    .badge-contract  { background: rgba(37,99,235,0.1);  color: #2563eb; }
    .badge-report    { background: rgba(212,175,55,0.12); color: #9a7b21; }
    .badge-attachment{ background: rgba(30,41,59,0.07);   color: var(--sidebar-bg); }

    /* ===== معاينة الملف ===== */
    .file-preview-box {
        border: 1px solid var(--border-color); border-radius: 10px;
        padding: 1.25rem 1.5rem; background: var(--primary-bg);
        display: flex; align-items: center; justify-content: space-between;
        gap: 1rem; flex-wrap: wrap;
    }
    .file-info { display: flex; align-items: center; gap: 0.75rem; }
    .file-icon { font-size: 2.5rem; }
    .file-name { font-weight: 700; color: var(--text-primary); font-size: 0.95rem; }
    .file-meta { color: var(--text-secondary); font-size: 0.82rem; margin-top: 0.2rem; }

    /* ===== الأزرار ===== */
    .btn-back {
        padding: 10px 20px; background: transparent; color: var(--text-secondary);
        border: 1px solid var(--border-color); border-radius: 8px;
        font-weight: 600; cursor: pointer; display: inline-flex; align-items: center;
        gap: 0.5rem; text-decoration: none; font-size: 0.9rem; transition: 0.2s;
    }
    .btn-back:hover { background: var(--primary-bg); color: var(--danger-color); border-color: var(--danger-color); }

    .btn-edit {
        padding: 10px 22px; background: var(--sidebar-bg); color: #fff;
        border: none; border-radius: 8px; font-weight: 700;
        display: inline-flex; align-items: center; gap: 0.5rem;
        text-decoration: none; font-size: 0.9rem; transition: 0.3s;
    }
    .btn-edit:hover { background: var(--gold-accent); color: var(--sidebar-bg); transform: translateY(-1px); }

    .btn-download {
        padding: 10px 22px; background: rgba(37,99,235,0.08); color: #2563eb;
        border: 1px solid rgba(37,99,235,0.2); border-radius: 8px; font-weight: 700;
        display: inline-flex; align-items: center; gap: 0.5rem;
        text-decoration: none; font-size: 0.9rem; transition: 0.2s;
    }
    .btn-download:hover { background: #2563eb; color: #fff; }

    @media (max-width: 640px) {
        .detail-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="show-page-container">

    {{-- ===== الترويسة ===== --}}
    <div class="dashboard-header">
        <div class="header-info">
            <h1>
                <i class="fas fa-file-alt" style="color: var(--gold-accent); margin-left: 8px;"></i>
                {{ $document->title }}
            </h1>
            <p>
                مستند مرتبط بالقضية:
                <a href="{{ route('cases.show', $document->case_id) }}" style="color: var(--sidebar-bg); font-weight: 700; text-decoration: none;">
                    {{ $document->case->case_number ?? '#' . $document->case_id }}
                </a>
            </p>
        </div>
        <div class="header-actions">
            <a href="{{ route('document.index') }}" class="btn-back">
                <i class="fas fa-arrow-right"></i> رجوع
            </a>
            <a href="{{ route('document.edit', $document->id) }}" class="btn-edit">
                <i class="fas fa-edit"></i> تعديل
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: rgba(22,163,74,0.08); border: 1px solid #16a34a; color: #16a34a;
                    padding: 1rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem;
                    font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- ===== بيانات المستند ===== --}}
    <div class="detail-card">
        <div class="detail-card-title">
            <i class="fas fa-info-circle"></i> تفاصيل المستند
        </div>

        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">المعرف</div>
                <div class="detail-value"># {{ $document->id }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">نوع المستند</div>
                <div class="detail-value">
                    @php
                        $typeMap = [
                            'contract'   => ['label' => 'عقد',   'class' => 'badge-contract',   'icon' => 'fa-file-contract'],
                            'report'     => ['label' => 'تقرير', 'class' => 'badge-report',     'icon' => 'fa-file-alt'],
                            'attachment' => ['label' => 'مرفق',  'class' => 'badge-attachment', 'icon' => 'fa-paperclip'],
                        ];
                        $type = $typeMap[$document->document_type] ?? ['label' => $document->document_type, 'class' => 'badge-attachment', 'icon' => 'fa-file'];
                    @endphp
                    <span class="badge-type {{ $type['class'] }}">
                        <i class="fas {{ $type['icon'] }}"></i> {{ $type['label'] }}
                    </span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">تاريخ الرفع</div>
                <div class="detail-value">
                    <i class="fas fa-calendar-alt" style="color: var(--gold-accent); margin-left: 6px;"></i>
                    {{ $document->created_at->format('Y/m/d') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">آخر تحديث</div>
                <div class="detail-value">
                    <i class="fas fa-clock" style="color: var(--text-secondary); margin-left: 6px;"></i>
                    {{ $document->updated_at->format('Y/m/d') }}
                </div>
            </div>

            <div class="detail-item full-width">
                <div class="detail-label">الوصف</div>
                <div class="detail-value" style="display: block; padding: 0.75rem 1rem; line-height: 1.7;">
                    {{ $document->description ?: 'لا يوجد وصف مضاف لهذا المستند.' }}
                </div>
            </div>
        </div>
    </div>

    {{-- ===== الملف المرفق ===== --}}
    <div class="detail-card">
        <div class="detail-card-title">
            <i class="fas fa-paperclip"></i> الملف المرفق
        </div>

        @if($document->file_path)
            @php
                $fileName  = basename($document->file_path);
                $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $iconMap   = [
                    'pdf'  => ['icon' => 'fa-file-pdf',   'color' => 'var(--danger-color)'],
                    'doc'  => ['icon' => 'fa-file-word',  'color' => '#2563eb'],
                    'docx' => ['icon' => 'fa-file-word',  'color' => '#2563eb'],
                    'xls'  => ['icon' => 'fa-file-excel', 'color' => '#16a34a'],
                    'xlsx' => ['icon' => 'fa-file-excel', 'color' => '#16a34a'],
                ];
                $fileStyle = $iconMap[$extension] ?? ['icon' => 'fa-file-alt', 'color' => 'var(--sidebar-bg)'];
            @endphp
            <div class="file-preview-box">
                <div class="file-info">
                    <span class="file-icon">
                        <i class="fas {{ $fileStyle['icon'] }}" style="color: {{ $fileStyle['color'] }};"></i>
                    </span>
                    <div>
                        <div class="file-name">{{ $document->title }}</div>
                        <div class="file-meta">{{ strtoupper($extension) }} • تم الرفع {{ $document->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="btn-download">
                    <i class="fas fa-download"></i> تحميل الملف
                </a>
            </div>
        @else
            <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                <i class="fas fa-exclamation-circle" style="font-size: 2rem; margin-bottom: 0.75rem; opacity: 0.4; display: block;"></i>
                لا يوجد ملف مرفق بهذا المستند
            </div>
        @endif
    </div>

</div>
@endsection