@extends('layouts.app')

@section('title', 'قائمة المحامين | ' . $appName)

@push('styles')
<style>
    /* ===== الحاوية والترويسة ===== */
    .lawyers-page-container { padding: 2rem; }
    
    .dashboard-header { 
        display: flex; justify-content: space-between; align-items: center; 
        flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem; 
    }
    
    .welcome-title { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; }
    .date-text { color: var(--text-secondary); font-size: 0.95rem; }

    /* ===== زر الإضافة الرئيسي (الموحد) ===== */
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
        display: flex; align-items: flex-end; flex-wrap: wrap; gap: 1.5rem; 
        background: #ffffff; border: 1px solid var(--border-color); 
        border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; 
        box-shadow: var(--shadow-sm);
    }
    
    .filter-group { display: flex; flex-direction: column; gap: 0.5rem; flex: 1; min-width: 200px; }
    .filter-group label { font-size: 0.85rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 0.4rem; }
    .filter-group select { 
        width: 100%; padding: 0.8rem 1rem; background: var(--primary-bg); 
        border: 1px solid var(--border-color); border-radius: 8px; 
        color: var(--text-primary); font-size: 0.95rem; font-family: inherit; 
        cursor: pointer; transition: all 0.2s ease; outline: none;
    }
    .filter-group select:focus, .filter-group select:hover { border-color: var(--gold-accent); }

    .search-form-group { display: flex; flex-direction: column; gap: 0.5rem; flex: 2; min-width: 280px; }
    .search-form-group label { font-size: 0.85rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 0.4rem; }
    .search-input-wrapper { display: flex; gap: 0.5rem; }
    .search-input-wrapper input { 
        flex: 1; padding: 0.8rem 1rem; background: var(--primary-bg); 
        border: 1px solid var(--border-color); border-radius: 8px; 
        color: var(--text-primary); font-size: 0.95rem; font-family: inherit; 
        transition: all 0.2s ease; outline: none;
    }
    .search-input-wrapper input:focus { border-color: var(--gold-accent); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); background: #ffffff; }
    
    .btn-search {
        padding: 0.8rem 1.5rem; background-color: var(--sidebar-bg); color: #fff;
        border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; font-family: inherit; display: flex; align-items: center; gap: 0.5rem;
    }
    .btn-search:hover { background-color: var(--gold-accent); color: var(--sidebar-bg); }
    
    .btn-clear {
        padding: 0.8rem 1rem; background-color: transparent; color: var(--text-secondary);
        border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none;
        font-weight: 600; transition: 0.2s; display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-clear:hover { background-color: var(--primary-bg); color: var(--danger-color); border-color: var(--danger-color); }

    /* ===== البانل والجدول ===== */
    .panel { background-color: #ffffff; border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--shadow-sm); }
    .table-responsive { overflow-x: auto; }
    .custom-table { width: 100%; border-collapse: collapse; text-align: right; }
    .custom-table th { color: var(--text-secondary); font-size: 0.85rem; padding: 1rem 0.75rem; border-bottom: 2px solid var(--primary-bg); white-space: nowrap; }
    .custom-table td { padding: 1rem 0.75rem; border-bottom: 1px solid var(--primary-bg); font-size: 0.95rem; color: var(--text-primary); vertical-align: middle; }
    .custom-table tbody tr { transition: background-color 0.2s ease; }
    .custom-table tbody tr:hover { background-color: rgba(244, 246, 249, 0.5); }

    /* Badges المحامين */
    .badge-degree { padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700; display: inline-block; text-align: center;}
    .badge-نقض { background-color: rgba(212, 175, 55, 0.15); color: #9a7b21; border: 1px solid rgba(212, 175, 55, 0.3); }
    .badge-استئناف { background-color: rgba(30, 41, 59, 0.1); color: var(--sidebar-bg); border: 1px solid rgba(30, 41, 59, 0.2); }
    .badge-ابتدائي { background-color: rgba(21, 128, 61, 0.1); color: var(--success-color); border: 1px solid rgba(21, 128, 61, 0.2); }
    .badge-جدول-عام { background-color: var(--primary-bg); color: var(--text-secondary); border: 1px solid var(--border-color); }

    /* ===== أزرار الإجراءات (الأيقونات) ===== */
    .btn-action { 
        display: inline-flex; align-items: center; justify-content: center; 
        width: 34px; height: 34px; border-radius: 8px; font-size: 0.9rem; 
        cursor: pointer; transition: all 0.2s ease; border: 1px solid transparent; 
        text-decoration: none;
    }
    .btn-action-view { background: rgba(30, 41, 59, 0.05); color: var(--sidebar-bg); border-color: rgba(30, 41, 59, 0.1); }
    .btn-action-view:hover { background: var(--sidebar-bg); color: #ffffff; }
    
    .btn-action-edit { background: rgba(212, 175, 55, 0.1); color: #9a7b21; border-color: rgba(212, 175, 55, 0.2); }
    .btn-action-edit:hover { background: var(--gold-accent); color: #ffffff; }
    
    .btn-action-delete { background: rgba(239, 68, 68, 0.08); color: var(--danger-color); border-color: rgba(239, 68, 68, 0.2); }
    .btn-action-delete:hover { background: var(--danger-color); color: #ffffff; border-color: var(--danger-color); }

    /* ===== الـ Empty State ===== */
    .empty-state-container { text-align: center; padding: 4rem 1rem; }
    .empty-state-icon-wrapper { 
        width: 90px; height: 90px; background-color: rgba(30, 41, 59, 0.03); 
        border-radius: 50%; display: flex; align-items: center; justify-content: center; 
        margin: 0 auto 1.5rem; box-shadow: inset 0 0 20px rgba(0,0,0,0.02); 
    }
    .empty-state-icon-wrapper i { font-size: 3.5rem; color: var(--border-color); }

    @media (max-width: 768px) {
        .search-filter-section { flex-direction: column; align-items: stretch; }
        .lawyers-page-container { padding: 1rem; }
    }
</style>
@endpush

@section('content')
<div class="lawyers-page-container" dir="rtl">
    
    <div class="dashboard-header">
        <div class="header-info">
            <h1 class="welcome-title"><i class="fas fa-users-cog" style="color: var(--gold-accent); margin-left: 8px;"></i> قائمة المحامين</h1>
            <p class="date-text">عرض وتتبع وإدارة بيانات جميع المحامين العاملين بالمكتب</p>
        </div>
        
        <a href="{{ route('lawyers.create') }}" class="btn-add-new">
            <span class="icon-circle">
                <i class="fas fa-user-plus"></i>
            </span>
            <span>إضافة محامي جديد</span>
        </a>
    </div>

    <div class="search-filter-section">
        
        <div class="filter-group">
            <label for="degree-filter"><i class="fas fa-balance-scale"></i> درجة المحامي</label>
            <select id="degree-filter">
                <option value="">الكل</option>
                <option value="نقض">نقض</option>
                <option value="استئناف">استئناف</option>
                <option value="ابتدائي">ابتدائي</option>
                <option value="جدول عام">جدول عام</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="specialization-filter"><i class="fas fa-briefcase"></i> التخصص</label>
            <select id="specialization-filter">
                <option value="">الكل</option>
                @php
                    $specializations = $lawyers->pluck('specialization')->unique()->filter()->sort()->values();
                @endphp
                @foreach($specializations as $spec)
                    <option value="{{ $spec }}">{{ $spec }}</option>
                @endforeach
            </select>
        </div>

        <div class="search-form-group">
            <label for="searchInput"><i class="fas fa-search"></i> البحث بالاسم أو المعرف</label>
            <form action="{{ route('lawyers.index') }}" method="GET" class="search-input-wrapper">
                <input type="text" id="searchInput" name="search" placeholder="اكتب للبحث..." value="{{ request('search') }}">
                <button type="submit" class="btn-search" title="بحث">
                    <i class="fas fa-search"></i>
                </button>
                @if(request('search'))
                    <a href="{{ route('lawyers.index') }}" class="btn-clear" title="إلغاء البحث">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="panel">
        @if($lawyers->count() > 0)
            <div class="table-responsive">
                <table class="custom-table" id="lawyersTable">
                    <thead>
                        <tr>
                            <th style="width: 10%;">ID</th>
                            <th style="width: 30%;">اسم المحامي</th>
                            <th style="width: 20%;">الدرجة</th>
                            <th style="width: 25%;">التخصص</th>
                            <th style="width: 15%; text-align: center;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lawyers as $lawyer)
                            <tr data-degree="{{ $lawyer->degree ?? '' }}" data-specialization="{{ $lawyer->specialization ?? '' }}">
                                <td><strong>#{{ $lawyer->id }}</strong></td>
                                
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 36px; height: 36px; border-radius: 50%; background-color: rgba(30, 41, 59, 0.05); color: var(--sidebar-bg); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <span style="font-weight: 700; color: var(--text-primary);">{{ $lawyer->name }}</span>
                                    </div>
                                </td>
                                
                                <td>
                                    @php
                                        $degreeClass = str_replace(' ', '-', $lawyer->degree ?? 'جدول عام');
                                    @endphp
                                    <span class="badge-degree badge-{{ $degreeClass }}">
                                        {{ $lawyer->degree ?? 'غير محدد' }}
                                    </span>
                                </td>

                                <td>
                                    <span style="color: var(--text-secondary); font-weight: 500;">
                                        {{ $lawyer->specialization ?? '—' }}
                                    </span>
                                </td>
                                
                                <td style="text-align: center;">
                                    <div style="display: flex; justify-content: center; gap: 0.4rem;">
                                        <a href="{{ route('lawyers.show', $lawyer->id) }}" class="btn-action btn-action-view" title="عرض الملف">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if(Route::has('lawyers.edit'))
                                        <a href="{{ route('lawyers.edit', $lawyer->id) }}" class="btn-action btn-action-edit" title="تعديل البيانات">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        
                                        <form action="{{ route('lawyers.destroy', $lawyer->id) }}" method="POST" style="margin: 0; display: inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المحامي نهائياً؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete" title="حذف المحامي">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="jsNoResults" style="display: none; text-align: center; padding: 3rem 1rem;">
                <i class="fas fa-filter" style="font-size: 3rem; color: var(--border-color); margin-bottom: 1rem;"></i>
                <p style="color: var(--text-secondary); font-weight: 600; font-size: 1rem;">لا يوجد محامين يطابقون الفلاتر المحددة.</p>
            </div>

        @else
            <div class="empty-state-container">
                <div class="empty-state-icon-wrapper">
                    <i class="fas {{ request('search') ? 'fa-search-minus' : 'fa-users-slash' }}"></i>
                </div>
                <h3 style="color: var(--text-primary); font-size: 1.4rem; margin-bottom: 0.5rem; font-weight: 800;">
                    {{ request('search') ? 'لا توجد نتائج للبحث' : 'لا يوجد محامين مسجلين' }}
                </h3>
                <p style="color: var(--text-secondary); font-size: 1rem; margin-bottom: 2.5rem;">
                    {{ request('search') ? 'لم نجد أي محامٍ يطابق كلمة البحث.' : 'لم تقم بإضافة أي محامين للنظام حتى الآن.' }}
                </p>
                
                @if(request('search'))
                    <a href="{{ route('lawyers.index') }}" class="btn-clear" style="padding: 10px 20px;">
                        <i class="fas fa-redo"></i> العودة للقائمة
                    </a>
                @else
                    <a href="{{ route('lawyers.create') }}" class="btn-add-new">
                        <span class="icon-circle">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span>إضافة المحامي الأول</span>
                    </a>
                @endif
            </div>
        @endif
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const degreeFilter = document.getElementById('degree-filter');
        const specializationFilter = document.getElementById('specialization-filter');
        const tableRows = document.querySelectorAll('table tbody tr');
        const table = document.getElementById('lawyersTable');
        const jsNoResults = document.getElementById('jsNoResults');

        function applyFilters() {
            if (!tableRows.length) return; 

            const selectedDegree = degreeFilter.value;
            const selectedSpecialization = specializationFilter.value;
            let visibleCount = 0;

            tableRows.forEach(row => {
                const rowDegree = row.getAttribute('data-degree');
                const rowSpecialization = row.getAttribute('data-specialization');

                const degreeMatch = !selectedDegree || rowDegree === selectedDegree;
                const specializationMatch = !selectedSpecialization || rowSpecialization === selectedSpecialization;

                if (degreeMatch && specializationMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (visibleCount === 0 && table) {
                table.style.display = 'none';
                if (jsNoResults) jsNoResults.style.display = 'block';
            } else if (table) {
                table.style.display = '';
                if (jsNoResults) jsNoResults.style.display = 'none';
            }
        }

        if(degreeFilter && specializationFilter) {
            degreeFilter.addEventListener('change', applyFilters);
            specializationFilter.addEventListener('change', applyFilters);
        }
    });
</script>
@endsection