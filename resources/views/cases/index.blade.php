@extends('layouts.app')

@section('title', 'إدارة القضايا | ' . $appName)

@push('styles')
<style>
    /* ===== الحاوية والترويسة ===== */
    .cases-page-container { padding: 2rem; }
    
    .dashboard-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        flex-wrap: wrap; 
        gap: 1rem; 
        margin-bottom: 2rem; 
    }
    
    .welcome-title { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; }
    .date-text { color: var(--text-secondary); font-size: 0.95rem; }

    /* ===== زر الإضافة الرئيسي (الموحد في السيستم) ===== */
    .btn-add-new {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background-color: var(--sidebar-bg);
        color: #ffffff;
        padding: 6px 24px 6px 8px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(30, 41, 59, 0.15);
        border: 2px solid var(--sidebar-bg);
    }
    .btn-add-new .icon-circle {
        background-color: var(--gold-accent);
        color: var(--sidebar-bg);
        width: 34px; height: 34px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        transition: transform 0.3s ease;
    }
    .btn-add-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(30, 41, 59, 0.25);
        background-color: #ffffff;
        color: var(--sidebar-bg);
    }
    .btn-add-new:hover .icon-circle {
        transform: rotate(90deg);
        background-color: var(--sidebar-bg);
        color: var(--gold-accent);
    }

    /* ===== قسم البحث والفلترة ===== */
    .search-filter-section { 
        display: flex; align-items: flex-end; flex-wrap: wrap; gap: 1rem; 
        background: #ffffff; border: 1px solid var(--border-color); 
        border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; 
        box-shadow: var(--shadow-sm);
    }
    .search-group { display: flex; flex-direction: column; gap: 0.5rem; flex: 1; min-width: 250px; }
    .search-group label { font-size: 0.85rem; font-weight: 600; color: var(--text-primary); }
    
    .search-input-wrapper { position: relative; display: flex; align-items: center; }
    .search-input-wrapper input { 
        width: 100%; padding: 0.8rem 1rem; padding-left: 90px; 
        background: var(--primary-bg); border: 1px solid var(--border-color); 
        border-radius: 8px; color: var(--text-primary); font-size: 0.9rem; 
        font-family: inherit; transition: all 0.2s ease; 
    }
    .search-input-wrapper input:focus { 
        outline: none; border-color: var(--gold-accent); 
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); background: #ffffff;
    }
    
    .search-icons { position: absolute; left: 0; display: flex; align-items: center; height: 100%; }
    .search-icon-btn { 
        display: flex; align-items: center; justify-content: center; 
        width: 40px; height: 100%; background: none; border: none; 
        cursor: pointer; transition: all 0.2s ease; font-size: 1rem; 
    }
    .search-icon-btn.do-search { color: var(--sidebar-bg); }
    .search-icon-btn.do-search:hover { color: var(--gold-accent); }
    .search-icon-btn.do-clear { color: var(--text-secondary); border-right: 1px solid var(--border-color); }
    .search-icon-btn.do-clear:hover { color: var(--danger-color); }
    
    .btn-clear-filters { 
        display: inline-flex; align-items: center; gap: 0.5rem; 
        padding: 0.8rem 1.4rem; background: transparent; 
        border: 1px solid var(--border-color); border-radius: 8px; 
        color: var(--text-secondary); font-size: 0.9rem; cursor: pointer; 
        transition: all 0.2s ease; white-space: nowrap; font-weight: 600;
    }
    .btn-clear-filters:hover { background-color: var(--primary-bg); color: var(--text-primary); }

    .cases-count { font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 1.5rem; font-weight: 600; }
    .cases-count span { color: var(--sidebar-bg); font-weight: 800; font-size: 1.1rem; }

    /* ===== كروت القضايا (Grid) ===== */
    .cases-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.5rem; }
    
    .case-card { 
        background: #ffffff; border: 1px solid var(--border-color); 
        border-radius: 12px; padding: 1.5rem; display: flex; 
        flex-direction: column; gap: 1rem; transition: all 0.3s ease; 
        position: relative; overflow: hidden; box-shadow: var(--shadow-sm);
        animation: slideIn 0.4s ease-out;
    }
    .case-card::before { 
        content: ''; position: absolute; top: 0; right: 0; left: 0; height: 4px; 
        background: var(--gold-accent); transform: scaleX(0); transition: transform 0.3s ease; 
    }
    .case-card:hover { border-color: var(--gold-accent); box-shadow: 0 8px 20px rgba(0,0,0,0.08); transform: translateY(-4px); }
    .case-card:hover::before { transform: scaleX(1); }
    
    .case-card-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; border-bottom: 1px solid var(--primary-bg); padding-bottom: 1rem; }
    .case-client-info { display: flex; flex-direction: column; gap: 0.2rem; }
    .case-client-name { font-size: 1.1rem; font-weight: 800; color: var(--text-primary); display: flex; align-items: center; gap: 0.4rem; }
    .case-client-name i { color: var(--gold-accent); font-size: 0.9rem; }
    .case-number { font-size: 0.85rem; color: var(--text-secondary); font-weight: 600; direction: ltr; text-align: right; background: var(--primary-bg); padding: 2px 8px; border-radius: 4px; display: inline-block; width: fit-content;}
    
    /* شارات الحالة (Badges) */
    .case-status-badge { flex-shrink: 0; padding: 0.35rem 0.85rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; }
    .case-status-badge.مفتوحة     { background: rgba(21,128,61,0.1);  color: var(--success-color); }
    .case-status-badge.مؤجلة      { background: rgba(212,175,55,0.15); color: #9a7b21; }
    .case-status-badge.مغلقة      { background: rgba(220,38,38,0.1);  color: var(--danger-color); }
    .case-status-badge.حكم\ نهائي { background: rgba(37,99,235,0.1);  color: #2563eb; }
    
    /* تفاصيل القضية داخل الكارت */
    .case-details { display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem; background: var(--primary-bg); border-radius: 8px; padding: 1rem; border: 1px solid var(--border-color); }
    .case-detail-row { display: flex; flex-direction: column; gap: 0.2rem; }
    .case-detail-label { font-size: 0.75rem; color: var(--text-secondary); font-weight: 600; }
    .case-detail-value { font-size: 0.85rem; color: var(--text-primary); font-weight: 700; }
    
    /* أزرار الإجراءات في الكارت */
    .case-card-actions { display: grid; grid-template-columns: 1fr auto auto; gap: 0.5rem; margin-top: auto; padding-top: 1rem; }
    
    .btn-action { 
        display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem; 
        padding: 0.6rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; 
        cursor: pointer; transition: all 0.2s ease; text-decoration: none; border: 1px solid transparent;
    }
    
    .btn-action-view { background: rgba(30, 41, 59, 0.05); color: var(--sidebar-bg); border-color: rgba(30, 41, 59, 0.1); }
    .btn-action-view:hover { background: var(--sidebar-bg); color: #ffffff; }
    
    .btn-action-edit { background: rgba(212, 175, 55, 0.1); color: #9a7b21; border-color: rgba(212, 175, 55, 0.2); }
    .btn-action-edit:hover { background: var(--gold-accent); color: #ffffff; border-color: var(--gold-accent); }
    
    .btn-action-delete { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); border-color: rgba(239, 68, 68, 0.2); }
    .btn-action-delete:hover { background: var(--danger-color); color: #ffffff; border-color: var(--danger-color); }

    /* ===== الـ Empty State ===== */
    .empty-state-container { text-align: center; padding: 5rem 1rem; background: #ffffff; border: 1px solid var(--border-color); border-radius: 12px; }
    .empty-state-icon-wrapper { width: 90px; height: 90px; background-color: rgba(30, 41, 59, 0.03); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: inset 0 0 20px rgba(0,0,0,0.02); }
    .empty-state-icon-wrapper i { font-size: 3.5rem; color: var(--border-color); }

    @keyframes slideIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

    /* Responsive */
    @media (max-width: 900px) { 
        .cases-page-container { padding: 1rem; } 
        .cases-grid { grid-template-columns: 1fr; } 
        .search-filter-section { flex-direction: column; } 
        .search-group { min-width: unset; width: 100%; } 
    }
</style>
@endpush

@section('content')
<div class="cases-page-container">

    <div class="dashboard-header">
        <div>
            <h1 class="welcome-title">إدارة القضايا</h1>
            <p class="date-text">عرض وتتبع جميع القضايا الموكلة للمكتب</p>
        </div>
        
        <a href="{{ route('cases.create') }}" class="btn-add-new">
            <span class="icon-circle">
                <i class="fas fa-plus"></i>
            </span>
            <span>إضافة قضية جديدة</span>
        </a>
    </div>

    <div class="search-filter-section">
        <div class="search-group" style="flex: 2;">
            <label for="searchInput">ابحث باسم العميل أو الرقم القومي أو رقم القضية</label>
            <div class="search-input-wrapper">
                <input type="text" id="searchInput" placeholder="اكتب للبحث..." autocomplete="off">
                <div class="search-icons">
                    <button class="search-icon-btn do-search" onclick="applyFilters()" title="بحث">
                        <i class="fas fa-search"></i>
                    </button>
                    <button class="search-icon-btn do-clear" onclick="clearSearch()" title="مسح البحث">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <button class="btn-clear-filters" onclick="clearFilters()">
            <i class="fas fa-redo"></i> تفريغ الفلاتر
        </button>
    </div>

    <div class="cases-count">
        إجمالي القضايا: <span id="casesCount">{{ $cases->count() }}</span>
    </div>

    @if($cases->isEmpty())
        <div class="empty-state-container">
            <div class="empty-state-icon-wrapper">
                <i class="fas fa-briefcase"></i>
            </div>
            <h3 style="color: var(--text-primary); font-size: 1.4rem; margin-bottom: 0.5rem; font-weight: 800;">لا توجد قضايا مسجلة</h3>
            <p style="color: var(--text-secondary); font-size: 1rem; margin-bottom: 2.5rem;">لم يتم إضافة أي قضايا للنظام حتى الآن. ابدأ بإضافة قضيتك الأولى.</p>
            
            <a href="{{ route('cases.create') }}" class="btn-add-new">
                <span class="icon-circle">
                    <i class="fas fa-plus"></i>
                </span>
                <span>إضافة القضية الأولى</span>
            </a>
        </div>
    @else
        <div class="cases-grid" id="casesGrid">
            @foreach($cases as $case)
                @php
                    $client = $case->clients?->first();
                    $leadLawyer = $case->lawyers->where('pivot.role', 'lead')->first() 
                               ?? $case->lawyers->where('pivot.role', 'محامي رئيسي')->first() 
                               ?? $case->lawyers->first();
                @endphp
                <div class="case-card"
                     data-client="{{ $client->name ?? '' }}"
                     data-national="{{ $client->nid ?? '' }}"
                     data-number="{{ $case->case_number }}">

                    <div class="case-card-header">
                        <div class="case-client-info">
                            <div class="case-client-name">
                                <i class="fas fa-user-circle"></i>
                                {{ $client->name ?? 'غير محدد' }}
                            </div>
                            <div class="case-number"># {{ $case->case_number }}</div>
                        </div>
                        <span class="case-status-badge {{ str_replace(' ', '_', $case->status) }}">{{ $case->status }}</span>
                    </div>

                    <div class="case-details">
                        <div class="case-detail-row">
                            <span class="case-detail-label">نوع الجهة</span>
                            <span class="case-detail-value">{{ $case->court->jurisdiction->name ?? '-' }}</span>
                        </div>
                        <div class="case-detail-row">
                            <span class="case-detail-label">المحكمة</span>
                            <span class="case-detail-value">{{ $case->court->name ?? '-' }}</span>
                        </div>
                        <div class="case-detail-row">
                            <span class="case-detail-label">درجة التقاضي</span>
                            <span class="case-detail-value">{{ $case->court_level ?? '-' }}</span>
                        </div>
                        <div class="case-detail-row">
                            <span class="case-detail-label">المحامي الرئيسي</span>
                            <span class="case-detail-value">{{ $leadLawyer->name ?? '-' }}</span>
                        </div>
                        <div class="case-detail-row" style="grid-column: span 2;">
                            <span class="case-detail-label">اسم الخصم</span>
                            <span class="case-detail-value">{{ $case->rival_name ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="case-card-actions">
                        <a href="{{ route('cases.show', $case) }}" class="btn-action btn-action-view" title="التفاصيل كاملة">
                            <i class="fas fa-folder-open"></i>
                            <span>تفاصيل القضية</span>
                        </a>

                        <a href="{{ route('cases.edit', $case) }}" class="btn-action btn-action-edit" title="تعديل">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('cases.destroy', $case->id) }}" method="POST" style="margin: 0; display: contents;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-action-delete" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذه القضية نهائياً؟')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    const cards       = Array.from(document.querySelectorAll('.case-card'));
    const countEl     = document.getElementById('casesCount');
    const searchInput = document.getElementById('searchInput');

    function normalize(str) {
        return (str || '')
            .toLowerCase()
            .replace(/أ|إ|آ/g, 'ا')
            .replace(/ة/g, 'ه')
            .replace(/ى/g, 'ي')
            .trim();
    }

    function applyFilters() {
        if (!cards.length) return;

        const search = normalize(searchInput.value);
        let visible  = 0;

        cards.forEach(card => {
            const clientName = normalize(card.dataset.client);
            const national   = normalize(card.dataset.national);
            const caseNum    = normalize(card.dataset.number);

            const match = search === '' ||
                          clientName.includes(search) ||
                          national.includes(search) ||
                          caseNum.includes(search);

            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        countEl.textContent = visible;
    }

    function clearSearch() {
        searchInput.value = '';
        applyFilters();
        searchInput.focus();
    }

    function clearFilters() {
        clearSearch();
    }

    if(searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }
</script>
@endpush