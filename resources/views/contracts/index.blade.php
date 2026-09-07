@extends('layouts.app')

@section('title', 'المكتبة القانونية | ' . $appName)

@push('styles')
<style>
    /* ===== الحاوية والترويسة الموحدة ===== */
    .library-page-container { padding: 2rem; max-width: 1200px; margin: 0 auto; }
    
    .dashboard-header { 
        display: flex; justify-content: space-between; align-items: flex-start; 
        margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;
    }
    .header-info h1 { font-size: 1.8rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.6rem;}
    .header-info p { color: var(--text-secondary); font-size: 1rem; margin: 0; font-weight: 500;}

    /* ===== شبكة العقود ===== */
    .contracts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    /* ===== تصميم كارت العقد ===== */
    .contract-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        text-decoration: none;
        display: flex;
        align-items: flex-start;
        gap: 1.2rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* حركة Smooth جداً */
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    /* تأثير الخلفية عند الـ Hover */
    .contract-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.05), rgba(30, 41, 59, 0.02));
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: -1;
    }

    .contract-card:hover {
        border-color: var(--gold-accent);
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(212, 175, 55, 0.12);
    }

    .contract-card:hover::after {
        opacity: 1;
    }

    /* الأيقونة داخل الكارت */
    .contract-icon {
        width: 55px; height: 55px;
        background: rgba(30, 41, 59, 0.04);
        color: var(--sidebar-bg);
        border-radius: 12px;
        display: flex; justify-content: center; align-items: center;
        font-size: 1.6rem; flex-shrink: 0;
        transition: all 0.3s ease;
        border: 1px solid rgba(30, 41, 59, 0.05);
    }

    .contract-card:hover .contract-icon {
        background: var(--sidebar-bg);
        color: var(--gold-accent);
        transform: scale(1.1) rotate(-5deg); /* حركة احترافية للأيقونة */
        border-color: var(--sidebar-bg);
        box-shadow: 0 4px 10px rgba(30, 41, 59, 0.2);
    }

    /* النصوص داخل الكارت */
    .contract-details { flex: 1; }
    
    .contract-name {
        font-size: 1.15rem; font-weight: 800; color: var(--text-primary); 
        margin: 0 0 0.6rem 0; line-height: 1.4; transition: color 0.3s;
    }

    .contract-card:hover .contract-name {
        color: var(--sidebar-bg);
    }

    .contract-action {
        font-size: 0.9rem; color: var(--text-secondary); font-weight: 700;
        display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s;
    }

    .contract-card:hover .contract-action {
        color: var(--gold-accent); gap: 0.8rem; /* السهم بيتحرك لليسار */
    }

    /* نص توضيحي يظهر عند الـ Hover */
    .contract-preview {
        max-height: 0; opacity: 0; overflow: hidden;
        font-size: 0.85rem; color: var(--text-secondary);
        transition: all 0.4s ease; font-weight: 500;
    }

    .contract-card:hover .contract-preview {
        max-height: 50px; opacity: 1; margin-top: 0.6rem;
        padding-top: 0.6rem; border-top: 1px dashed var(--border-color);
    }

    @media (max-width: 768px) {
        .library-page-container { padding: 1rem; }
        .contracts-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="library-page-container" dir="rtl">

    <div class="dashboard-header">
        <div class="header-info">
            <h1><i class="fas fa-book-open" style="color: var(--gold-accent);"></i> المكتبة القانونية</h1>
            <p>اختر نوع العقد أو النموذج، قم بتعبئة البيانات المطلوبة، واطبعه مباشرة</p>
        </div>
    </div>

    <div class="contracts-grid">
        @foreach($contracts as $type => $name)
            <a href="{{ route('contracts.show', $type) }}" class="contract-card">
                
                <div class="contract-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                
                <div class="contract-details">
                    <h3 class="contract-name">{{ $name }}</h3>
                    
                    <div class="contract-action">
                        <span>تعبئة وطباعة</span>
                        <i class="fas fa-arrow-left"></i>
                    </div>
                    
                    <div class="contract-preview">
                        اضغط هنا لإدخال أطراف العقد والبيانات المطلوبة لطباعته فوراً.
                    </div>
                </div>

            </a>
        @endforeach
    </div>

</div>
@endsection