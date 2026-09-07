@extends('layouts.app')

@section('title', 'قائمة العملاء | ' . $appName)

@push('styles')
<style>
    /* ===== الحاوية والترويسة ===== */
    .clients-page-container { padding: 2rem; }
    
    .dashboard-header { 
        display: flex; justify-content: space-between; align-items: center; 
        flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem; 
    }
    
    .welcome-title { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; }
    .date-text { color: var(--text-secondary); font-size: 0.95rem; }

    /* ===== زر الإضافة الرئيسي ===== */
    .btn-add-new {
        display: inline-flex; align-items: center; gap: 12px;
        background-color: var(--sidebar-bg); color: #ffffff;
        padding: 6px 24px 6px 8px; border-radius: 50px;
        text-decoration: none; font-weight: 700; font-size: 0.95rem;
        transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(30, 41, 59, 0.15);
        border: 2px solid var(--sidebar-bg);
    }
    .btn-add-new .icon-circle {
        background-color: var(--gold-accent); color: var(--sidebar-bg);
        width: 34px; height: 34px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; transition: transform 0.3s ease;
    }
    .btn-add-new:hover {
        transform: translateY(-2px); box-shadow: 0 6px 16px rgba(30, 41, 59, 0.25);
        background-color: #ffffff; color: var(--sidebar-bg);
    }
    .btn-add-new:hover .icon-circle {
        transform: rotate(90deg); background-color: var(--sidebar-bg); color: var(--gold-accent);
    }

    /* ===== قسم البحث والفلترة ===== */
    .search-filter-section { 
        display: flex; align-items: center; gap: 1rem; 
        background: #ffffff; border: 1px solid var(--border-color); 
        border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; 
        box-shadow: var(--shadow-sm); flex-wrap: wrap;
    }
    .search-form { display: flex; gap: 0.75rem; align-items: center; flex: 1; min-width: 300px; }
    .search-input-wrapper { position: relative; display: flex; align-items: center; flex: 1; }
    .search-input-wrapper input { 
        width: 100%; padding: 0.8rem 1rem; padding-right: 45px; /* يمين لأننا عربي */
        background: var(--primary-bg); border: 1px solid var(--border-color); 
        border-radius: 8px; color: var(--text-primary); font-size: 0.95rem; 
        font-family: inherit; transition: all 0.2s ease; 
    }
    .search-input-wrapper input:focus { 
        outline: none; border-color: var(--gold-accent); 
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); background: #ffffff;
    }
    .search-icon-inside { 
        position: absolute; right: 15px; color: var(--text-secondary); 
        font-size: 1rem; pointer-events: none; 
    }
    .btn-search {
        padding: 0.8rem 1.5rem; background-color: var(--sidebar-bg); color: #fff;
        border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; font-family: inherit;
    }
    .btn-search:hover { background-color: var(--gold-accent); color: var(--sidebar-bg); }
    .btn-clear-search {
        padding: 0.8rem 1.5rem; background-color: transparent; color: var(--text-secondary);
        border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none;
        font-weight: 600; transition: 0.2s; display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-clear-search:hover { background-color: var(--primary-bg); color: var(--danger-color); border-color: var(--danger-color); }

    /* ===== إحصائيات البحث ===== */
    .results-header { margin-bottom: 1.5rem; }
    .results-info { font-size: 0.95rem; color: var(--text-secondary); font-weight: 500; }
    .results-info strong { color: var(--sidebar-bg); font-weight: 800; font-size: 1.1rem; }
    .search-term { background: rgba(212, 175, 55, 0.1); padding: 0.2rem 0.6rem; border-radius: 6px; color: #9a7b21; font-weight: 700; }

    /* ===== شبكة كروت العملاء ===== */
    .clients-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
    
    .client-card { 
        background: #ffffff; border: 1px solid var(--border-color); 
        border-radius: 12px; padding: 1.5rem; display: flex; 
        flex-direction: column; gap: 1rem; transition: all 0.3s ease; 
        position: relative; overflow: hidden; box-shadow: var(--shadow-sm);
        animation: slideIn 0.4s ease-out;
    }
    .client-card::before { 
        content: ''; position: absolute; top: 0; right: 0; left: 0; height: 4px; 
        background: var(--gold-accent); transform: scaleX(0); transition: transform 0.3s ease; 
    }
    .client-card:hover { border-color: var(--gold-accent); box-shadow: 0 8px 20px rgba(0,0,0,0.08); transform: translateY(-4px); }
    .client-card:hover::before { transform: scaleX(1); }

    .client-card-header { display: flex; align-items: center; gap: 1rem; border-bottom: 1px solid var(--primary-bg); padding-bottom: 1rem; }
    .client-avatar { 
        width: 48px; height: 48px; border-radius: 50%; 
        background: rgba(212, 175, 55, 0.1); color: var(--gold-accent); 
        display: flex; align-items: center; justify-content: center; 
        font-weight: 800; font-size: 1.2rem; flex-shrink: 0; border: 2px solid rgba(212, 175, 55, 0.2);
    }
    .client-card-title { flex: 1; }
    .client-name { color: var(--text-primary); font-size: 1.15rem; font-weight: 800; margin: 0 0 0.2rem 0; }
    .client-id { color: var(--text-secondary); font-size: 0.85rem; font-weight: 600; }

    .client-card-info { 
        display: flex; flex-direction: column; gap: 0.6rem; 
        background: var(--primary-bg); border-radius: 8px; 
        padding: 1rem; border: 1px solid var(--border-color); 
    }
    .info-item { display: flex; align-items: flex-start; gap: 0.75rem; font-size: 0.9rem; color: var(--text-primary); }
    .info-label { color: var(--gold-accent); width: 20px; text-align: center; font-size: 0.95rem; margin-top: 2px; }
    .info-value { flex: 1; word-break: break-word; font-weight: 500; line-height: 1.5; }
    .info-value-link { color: var(--sidebar-bg); text-decoration: none; font-weight: 700; transition: 0.2s; }
    .info-value-link:hover { color: var(--gold-accent); }

    /* ===== أزرار الإجراءات ===== */
    .client-card-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-top: auto; padding-top: 1rem; }
    
    .btn-action { 
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; 
        padding: 0.7rem; border-radius: 8px; font-size: 0.9rem; font-weight: 700; 
        cursor: pointer; transition: all 0.2s ease; text-decoration: none; border: 1px solid transparent; font-family: inherit;
    }
    .btn-action-view { background: rgba(30, 41, 59, 0.05); color: var(--sidebar-bg); border-color: rgba(30, 41, 59, 0.1); }
    .btn-action-view:hover { background: var(--sidebar-bg); color: #ffffff; border-color: var(--sidebar-bg); }
    
    .btn-action-delete { background: rgba(239, 68, 68, 0.08); color: var(--danger-color); border-color: rgba(239, 68, 68, 0.2); width: 100%; }
    .btn-action-delete:hover { background: var(--danger-color); color: #ffffff; border-color: var(--danger-color); }
    .delete-form { margin: 0; width: 100%; }

    /* ===== شاشة الفراغ Empty State ===== */
    .empty-state-container { text-align: center; padding: 5rem 1rem; background: #ffffff; border: 1px solid var(--border-color); border-radius: 12px; }
    .empty-state-icon-wrapper { width: 90px; height: 90px; background-color: rgba(30, 41, 59, 0.03); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: inset 0 0 20px rgba(0,0,0,0.02); }
    .empty-state-icon-wrapper i { font-size: 3.5rem; color: var(--border-color); }

    @keyframes slideIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 768px) {
        .clients-page-container { padding: 1rem; }
        .search-filter-section { flex-direction: column; align-items: stretch; }
        .search-form { flex-direction: column; align-items: stretch; }
        .clients-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="clients-page-container">
    
    <div class="dashboard-header">
        <div>
            <h1 class="welcome-title">إدارة العملاء</h1>
            <p class="date-text">عرض وتتبع بيانات ومقرات جميع العملاء والموكلين</p>
        </div>
        
        <a href="{{ route('add-client') }}" class="btn-add-new">
            <span class="icon-circle">
                <i class="fas fa-plus"></i>
            </span>
            <span>إضافة عميل جديد</span>
        </a>
    </div>

    <div class="search-filter-section">
        <form method="GET" action="{{ route('clients.index') }}" class="search-form">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon-inside"></i>
                <input type="text" name="search" placeholder="ابحث عن اسم العميل، الهاتف، الرقم القومي أو العنوان..." value="{{ $search ?? '' }}" autocomplete="off">
            </div>
            
            <button type="submit" class="btn-search">بحث</button>
            
            @if(!empty($search))
                <a href="{{ route('clients.index') }}" class="btn-clear-search">
                    <i class="fas fa-times"></i> مسح الفلتر
                </a>
            @endif
        </form>
    </div>

    <div class="results-header">
        <div class="results-info">
            @if(!empty($search))
                تم العثور على <strong>{{ $clients->count() }}</strong> عميل يطابق البحث الحالي <span class="search-term">"{{ $search }}"</span>
            @else
                إجمالي العملاء المسجلين بالنظام: <strong>{{ $clients->count() }}</strong> عميل
            @endif
        </div>
    </div>

    @if($clients->count() > 0)
        <div class="clients-grid">
            @foreach($clients as $client)
                <div class="client-card">
                    <div class="client-card-header">
                        <div class="client-avatar">{{ mb_substr($client->name, 0, 1) }}</div>
                        <div class="client-card-title">
                            <h3 class="client-name">{{ $client->name }}</h3>
                            <p class="client-id">معرّف النظام: #{{ $client->id }}</p>
                        </div>
                    </div>

                    <div class="client-card-info">
                        @if($client->phone)
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-phone-alt"></i></span>
                                <span class="info-value">
                                    <a href="tel:{{ $client->phone }}" class="info-value-link">{{ $client->phone }}</a>
                                </span>
                            </div>
                        @endif

                        @if($client->email)
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-envelope"></i></span>
                                <span class="info-value">
                                    <a href="mailto:{{ $client->email }}" class="info-value-link">{{ $client->email }}</a>
                                </span>
                            </div>
                        @endif

                        @if($client->nid)
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-id-card"></i></span>
                                <span class="info-value">{{ $client->nid }}</span>
                            </div>
                        @endif

                        @if($client->address)
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-map-marker-alt"></i></span>
                                <span class="info-value" title="{{ $client->address }}">{{ Str::limit($client->address, 45) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="client-card-actions">
                        <a href="{{ route('clients.show', $client->id) }}" class="btn-action btn-action-view">
                            <i class="fas fa-folder-open"></i> عرض الملف
                        </a>
                        
                        <form method="POST" action="{{ route('clients.destroy', $client->id) }}" class="delete-form" onsubmit="return confirm('هل أنت متأكد من حذف هذا العميل نهائياً من النظام؟ لا يمكن التراجع عن هذا الإجراء.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-action-delete">
                                <i class="fas fa-trash-alt"></i> حذف
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state-container">
            <div class="empty-state-icon-wrapper">
                <i class="fas {{ !empty($search) ? 'fa-search-minus' : 'fa-users-slash' }}"></i>
            </div>
            <h3 style="color: var(--text-primary); font-size: 1.4rem; margin-bottom: 0.5rem; font-weight: 800;">
                {{ !empty($search) ? 'لا توجد نتائج مطابقة' : 'لا يوجد عملاء مسجلين' }}
            </h3>
            <p style="color: var(--text-secondary); font-size: 1rem; margin-bottom: 2.5rem; max-width: 500px; margin-left: auto; margin-right: auto;">
                {{ !empty($search) ? 'لم نجد أي سجلات مطابقة لهذا البحث، يرجى مراجعة الكلمات أو تفريغ الفلتر والمحاولة مرة أخرى.' : 'لوحة البيانات فارغة حالياً. ابدأ بإضافة العميل الأول للنظام لتتمكن من ربطه بالقضايا والمواعيد.' }}
            </p>
            
            @if(empty($search))
                <a href="{{ route('add-client') }}" class="btn-add-new">
                    <span class="icon-circle">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span>إضافة العميل الأول</span>
                </a>
            @else
                <a href="{{ route('clients.index') }}" class="btn-clear-search">
                    <i class="fas fa-redo"></i> تفريغ البحث والعودة
                </a>
            @endif
        </div>
    @endif

</div>
@endsection