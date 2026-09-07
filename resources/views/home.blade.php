@extends('layouts.app')

@section('title', 'الرئيسية | ' . $appName)

@push('styles')
<style>
    .dashboard-header { margin-bottom: 2rem; }
    .welcome-title { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; }
    .date-text { color: var(--text-secondary); font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem; }

    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
    @media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 580px) { .stats-grid { grid-template-columns: 1fr; } }
    
    /* ===== تأثيرات الكروت (Stat Cards) ===== */
    .stat-card {
        background-color: #ffffff; 
        border: 1px solid var(--border-color); 
        border-radius: 12px;
        padding: 1.5rem; 
        display: flex; 
        align-items: center; 
        gap: 1.2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .stat-card:hover { 
        transform: translateY(-6px); 
        box-shadow: 0 14px 24px rgba(0, 0, 0, 0.1); 
        border-color: var(--gold-accent, #d4af37);
    }

    .stat-card::before {
        content: "";
        position: absolute;
        top: 0; right: -100%;
        width: 50%; height: 100%;
        background: linear-gradient(to left, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
        transform: skewX(-25deg);
        transition: right 0.6s ease-in-out;
        z-index: 1;
        pointer-events: none;
    }
    .stat-card:hover::before { right: 200%; }

    .stat-icon { 
        width: 54px; height: 54px; border-radius: 12px; 
        display: flex; align-items: center; justify-content: center; 
        font-size: 1.5rem; flex-shrink: 0; 
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        z-index: 2;
    }
    
    .stat-card:hover .stat-icon { transform: scale(1.15) rotate(-8deg); }

    .icon-blue   { background-color: rgba(30, 41, 59, 0.1);   color: var(--sidebar-bg); }
    .icon-gold   { background-color: rgba(212, 175, 55, 0.15); color: var(--gold-accent); }
    .icon-green  { background-color: rgba(21, 128, 61, 0.1);   color: var(--success-color); }
    .icon-red    { background-color: rgba(220, 38, 38, 0.1);   color: var(--danger-color); }
    .icon-purple { background-color: rgba(99, 102, 241, 0.1);  color: #6366f1; }
    .icon-teal   { background-color: rgba(20, 184, 166, 0.1);  color: #14b8a6; }
    
    .stat-details { position: relative; z-index: 2; }
    .stat-details h3 { font-size: 1.8rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.2rem 0; }
    .stat-details p  { color: var(--text-secondary); font-size: 0.9rem; font-weight: 600; margin: 0; }

    .stat-details-wide { flex: 1; position: relative; z-index: 2; }
    .stat-details-wide h3 { font-size: 1.8rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.15rem 0; }
    .stat-details-wide p  { color: var(--text-secondary); font-size: 0.9rem; font-weight: 600; margin: 0 0 0.4rem 0; }
    .collection-bar-wrap {
        width: 100%; background: rgba(0,0,0,0.07);
        border-radius: 20px; height: 6px; overflow: hidden;
    }
    .collection-bar-fill {
        height: 100%; border-radius: 20px;
        background: linear-gradient(90deg, #14b8a6, #6366f1);
        transition: width 1.5s ease-in-out;
    }

    .content-grid { display: grid; grid-template-columns: 2fr 1.2fr; gap: 1.5rem; }
    
    .panel { 
        background-color: #ffffff; border: 1px solid var(--border-color); 
        border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03); 
        transition: box-shadow 0.3s ease;
    }
    .panel:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.06); }
    
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--primary-bg); padding-bottom: 1rem; }
    .panel-title { font-size: 1.1rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 0.5rem; }

    .table-responsive { overflow-x: auto; }
    .custom-table { width: 100%; border-collapse: collapse; text-align: right; }
    .custom-table th { color: var(--text-secondary); font-size: 0.85rem; padding: 0.8rem 0.5rem; border-bottom: 2px solid var(--primary-bg); }
    .custom-table td { padding: 1rem 0.5rem; border-bottom: 1px solid var(--primary-bg); font-size: 0.95rem; color: var(--text-primary); }

    .badge { padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; display: inline-block; }
    .badge-success   { background-color: rgba(21, 128, 61, 0.1);  color: var(--success-color); }
    .badge-warning   { background-color: rgba(212, 175, 55, 0.15); color: #9a7b21; }
    .badge-danger    { background-color: rgba(220, 38, 38, 0.1);  color: var(--danger-color); }
    .badge-secondary { background-color: rgba(100, 116, 139, 0.1); color: var(--text-secondary); }

    .empty-state { text-align: center; padding: 3rem 1rem; color: var(--text-secondary); }
    .empty-state i { font-size: 3rem; color: var(--border-color); margin-bottom: 1rem; display: block; }
    .empty-state p { font-size: 0.95rem; font-weight: 500; margin-bottom: 1rem; }

    .chart-container { position: relative; height: 300px; width: 100%; }
    .chart-empty-state {
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; height: 100%; color: var(--text-secondary);
    }
    .chart-empty-state i { font-size: 2.5rem; color: var(--border-color); margin-bottom: 0.75rem; }
    .chart-empty-state p { font-size: 0.9rem; font-weight: 500; margin: 0; }

    /* ===== ستايل التقويم (Calendar) ===== */
    .mini-calendar { width: 100%; text-align: center; direction: rtl; }
    .cal-header-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
    .cal-header-nav button { background: var(--primary-bg); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; color: var(--sidebar-bg); transition: 0.3s; display: flex; align-items: center; justify-content: center; }
    .cal-header-nav button:hover { background: var(--gold-accent); color: #fff; }
    .cal-month-year { font-weight: 800; color: var(--text-primary); font-size: 1.1rem; }
    
    .cal-days-row { display: grid; grid-template-columns: repeat(7, 1fr); font-weight: 700; margin-bottom: 0.8rem; color: var(--text-secondary); font-size: 0.85rem; }
    .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; }
    
    .cal-cell { 
        aspect-ratio: 1; display: flex; align-items: center; justify-content: center; 
        border-radius: 8px; font-size: 0.95rem; font-weight: 600; position: relative; transition: all 0.3s;
        color: var(--text-primary); background-color: transparent; border: 1px solid transparent;
    }
    
    /* الأيام العادية عند الهوفر */
    .cal-cell:not(.empty):not(.has-event):not(.today):hover { background-color: var(--primary-bg); cursor: pointer; }
    
    /* اليوم الحالي */
    .cal-cell.today { background-color: var(--sidebar-bg); color: #fff; box-shadow: 0 4px 10px rgba(30, 41, 59, 0.3); }
    
    /* أيام بها مواعيد */
    .cal-cell.has-event { 
        background-color: rgba(212, 175, 55, 0.15); 
        color: #9a7b21; 
        border: 1px solid rgba(212, 175, 55, 0.4); 
        cursor: pointer; 
    }
    .cal-cell.has-event:hover { background-color: var(--gold-accent); color: #fff; transform: scale(1.05); border-color: var(--gold-accent); }

    /* نقطة صغيرة تحت الرقم للدلالة على الموعد */
    .cal-cell.has-event::after {
        content: ''; position: absolute; bottom: 4px; left: 50%; transform: translateX(-50%);
        width: 4px; height: 4px; background-color: currentColor; border-radius: 50%;
    }

    /* نافذة المواعيد المنبثقة (Tooltip) */
    .cal-tooltip {
        position: absolute; bottom: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(10px);
        background: #1e293b; color: #fff; padding: 0.8rem; border-radius: 8px; font-size: 0.85rem;
        width: max-content; min-width: 150px; max-width: 220px; text-align: right;
        opacity: 0; visibility: hidden; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
        z-index: 100; box-shadow: 0 8px 16px rgba(0,0,0,0.15); pointer-events: none;
    }
    /* سهم النافذة */
    .cal-tooltip::before {
        content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%);
        border-width: 6px; border-style: solid; border-color: #1e293b transparent transparent transparent;
    }
    
    .cal-cell.has-event:hover .cal-tooltip { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0); }
    
    .event-item { border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 0.5rem; margin-bottom: 0.5rem; }
    .event-item:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
    .event-time { display: block; color: var(--gold-accent); font-size: 0.75rem; font-weight: 700; margin-bottom: 0.1rem; }
    .event-title { white-space: normal; line-height: 1.4; }
    /* أضف هذه السطور في قسم الـ badge في الستايل */
    .badge-primary   { background-color: rgba(59, 130, 246, 0.1); color: #2563eb; }
    .badge-info      { background-color: rgba(14, 165, 233, 0.1); color: #0284c7; }
    .badge-warning   { background-color: rgba(245, 158, 11, 0.1); color: #d97706; }
    .badge-success   { background-color: rgba(34, 197, 94, 0.1);  color: #16a34a; }
    .badge-danger    { background-color: rgba(239, 68, 68, 0.1);  color: #dc2626; }
    .badge-secondary { background-color: rgba(107, 114, 128, 0.1); color: #4b5563; }

    @media (max-width: 1024px) { .content-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')

{{-- ===== الترحيب ===== --}}
<div class="dashboard-header">
    <h1 class="welcome-title">
        مرحباً بك، أستاذة {{ auth()->user()->name }}!
    </h1>
    <div class="date-text">
        <i class="fas fa-calendar-day"></i>
        <span>اليوم: {{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('l، j F Y') }}</span>
    </div>
</div>

{{-- ===== كروت الإحصائيات ===== --}}
<div class="stats-grid">

    {{-- 1. قضية نشطة --}}
    <div class="stat-card">
        <div class="stat-icon icon-blue"><i class="fas fa-briefcase"></i></div>
        <div class="stat-details">
            <h3>{{ $activeCasesCount ?? 0 }}</h3>
            <p>قضية نشطة</p>
        </div>
    </div>

    {{-- 2. جلسات اليوم --}}
    <div class="stat-card">
        <div class="stat-icon icon-gold"><i class="fas fa-gavel"></i></div>
        <div class="stat-details">
            <h3>{{ $todaySessionsCount ?? 0 }}</h3>
            <p>جلسات اليوم</p>
        </div>
    </div>

    {{-- 3. إجمالي الموكلين --}}
    <div class="stat-card">
        <div class="stat-icon icon-green"><i class="fas fa-users"></i></div>
        <div class="stat-details">
            <h3>{{ $totalClientsCount ?? 0 }}</h3>
            <p>إجمالي الموكلين</p>
        </div>
    </div>

    {{-- 4. مهام عاجلة --}}
    <div class="stat-card">
        <div class="stat-icon icon-red"><i class="fas fa-clock"></i></div>
        <div class="stat-details">
            <h3>{{ $urgentTasksCount ?? 0 }}</h3>
            <p>مهام عاجلة</p>
        </div>
    </div>

    {{-- 5. إجمالي الأتعاب --}}
    <div class="stat-card">
        <div class="stat-icon icon-purple"><i class="fas fa-file-invoice-dollar"></i></div>
        <div class="stat-details">
            <h3>{{ number_format($totalFees ?? 0) }}</h3>
            <p>إجمالي الأتعاب (ر.ع.)</p>
        </div>
    </div>

    {{-- 6. نسبة التحصيل --}}
    <div class="stat-card">
        <div class="stat-icon icon-teal"><i class="fas fa-percentage"></i></div>
        <div class="stat-details-wide">
            <h3>{{ $collectionRate ?? 0 }}%</h3>
            <p>
                نسبة التحصيل
                <span style="font-size:0.8rem; color: var(--text-secondary); font-weight:500;">
                    ({{ number_format($totalPaid ?? 0) }} / {{ number_format($totalFees ?? 0) }} ر.ع.)
                </span>
            </p>
            <div class="collection-bar-wrap">
                <div class="collection-bar-fill" style="width: {{ $collectionRate ?? 0 }}%;"></div>
            </div>
        </div>
    </div>

</div>


{{-- ===== المحتوى الأوسط (الجداول والتقويم) ===== --}}
<div class="content-grid">

    {{-- أحدث القضايا --}}
    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title"><i class="fas fa-folder-open" style="color: var(--gold-accent);"></i> أحدث القضايا المضافة</h2>
            <a href="{{ route('cases.index') }}" class="btn btn-secondary" style="padding: 0.4rem 1rem; font-size: 0.85rem;">عرض الكل</a>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>رقم القضية</th>
                        <th>الموكل</th>
                        <th>الخصم</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentCases ?? [] as $case)
                        <tr>
                            <td><strong>{{ $case->case_number ?? 'غير محدد' }}</strong></td>
                            <td>{{ $case->display_client_name }}</td>
                            <td>{{ $case->opponent_name }}</td>
                            <td>
                                <span class="badge badge-{{ $case->status_color }}">
                                    {{ $case->status_name }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-folder-open"></i>
                                    <p>لم يتم تسجيل أي قضايا في النظام حتى الآن.</p>
                                    <a href="{{ route('cases.create') }}" style="background-color: var(--sidebar-bg); color: #fff; padding: 0.5rem 1.25rem; border-radius: 8px; text-decoration: none; font-weight: 700;">
                                        إضافة قضية جديدة
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- التقويم (Calendar) --}}
    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title"><i class="fas fa-calendar-alt" style="color: var(--gold-accent);"></i> التقويم الشهري</h2>
            <a href="{{ route('cases.index') }}" title="إضافة موعد">
                <i class="fas fa-plus-circle" style="color: var(--gold-accent); font-size: 1.2rem;"></i>
            </a>
        </div>

        <div class="mini-calendar">
            <div class="cal-header-nav">
                <button id="cal-prev" title="الشهر السابق"><i class="fas fa-chevron-right"></i></button>
                <div class="cal-month-year" id="cal-month-year">...</div>
                <button id="cal-next" title="الشهر التالي"><i class="fas fa-chevron-left"></i></button>
            </div>
            
            <div class="cal-days-row">
                <div>أحد</div><div>إثنين</div><div>ثلاثاء</div><div>أربعاء</div><div>خميس</div><div>جمعة</div><div>سبت</div>
            </div>
            
            <div class="cal-grid" id="cal-grid">
                </div>
        </div>
    </div>

</div>

{{-- ===== قسم الرسوم البيانية ===== --}}
<div class="content-grid" style="margin-top: 1.5rem;">

    {{-- الرسم البياني الخطي --}}
    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title"><i class="fas fa-chart-area" style="color: var(--gold-accent);"></i> معدل تسجيل القضايا خلال العام</h2>
        </div>
        <div class="chart-container" id="trendChartWrapper">
            <canvas id="casesTrendChart"></canvas>
        </div>
    </div>

    {{-- الرسم البياني الدائري --}}
    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title"><i class="fas fa-chart-pie" style="color: var(--gold-accent);"></i> توزيع القضايا حسب الحالة</h2>
        </div>
        <div class="chart-container" id="statusChartWrapper">
            <canvas id="casesStatusChart"></canvas>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================================
       1. التقويم المصغر (Mini Calendar)
    ========================================= */
    const calEvents = @json($calendarEvents ?? []); // التعديل البسيط تم هنا
    let currentDate = new Date(); // يبدأ بتاريخ اليوم
    
    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        // حساب اليوم الأول وعدد أيام الشهر
        const firstDayObj = new Date(year, month, 1);
        const firstDay = firstDayObj.getDay(); // 0 = الأحد, 1 = الإثنين...
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        const monthNames = ["يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"];
        document.getElementById('cal-month-year').innerText = `${monthNames[month]} ${year}`;
        
        const grid = document.getElementById('cal-grid');
        grid.innerHTML = '';
        
        // إنشاء مربعات فارغة للأيام التي تسبق بداية الشهر
        for (let i = 0; i < firstDay; i++) {
            grid.innerHTML += `<div class="cal-cell empty"></div>`;
        }
        
        // بناء سلسلة نصية لليوم الحالي (بصيغة YYYY-MM-DD)
        const todayObj = new Date();
        const todayStr = `${todayObj.getFullYear()}-${String(todayObj.getMonth() + 1).padStart(2, '0')}-${String(todayObj.getDate()).padStart(2, '0')}`;

        // إنشاء خلايا الأيام
        for (let day = 1; day <= daysInMonth; day++) {
            const m = String(month + 1).padStart(2, '0');
            const d = String(day).padStart(2, '0');
            const dateStr = `${year}-${m}-${d}`;
            
            // جلب المواعيد المطابقة لتاريخ اليوم
            const dayEvents = calEvents.filter(e => e.date === dateStr);
            
            let classList = "cal-cell";
            if (dateStr === todayStr) classList += " today"; // يوم حالي
            if (dayEvents.length > 0) classList += " has-event"; // به موعد
            
          // إنشاء الـ Tooltip إذا كان هناك مواعيد
            let tooltipHtml = '';
            if (dayEvents.length > 0) {
                let items = dayEvents.map(e => `
                    <div class="event-item" style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 0.6rem; margin-bottom: 0.6rem;">
                        <div class="event-title" style="font-weight: 800; font-size: 0.9rem; margin-bottom: 0.3rem;">
                            ${e.title}
                        </div>
                        <div class="event-time" style="color: var(--gold-accent, #d4af37); font-size: 0.8rem; font-weight: bold; margin-bottom: 0.3rem;">
                            <i class="fas fa-clock"></i> ${e.time}
                        </div>
                        ${e.notes ? `
                        <div class="event-notes" style="font-size: 0.75rem; color: #cbd5e1; background: rgba(0,0,0,0.2); padding: 0.4rem; border-radius: 4px; line-height: 1.4;">
                            <i class="fas fa-info-circle"></i> ${e.notes}
                        </div>` : ''}
                    </div>
                `).join('');
                tooltipHtml = `<div class="cal-tooltip">${items}</div>`;
            }
            grid.innerHTML += `<div class="${classList}">${day}${tooltipHtml}</div>`;
        }
    }

    // تشغيل التقويم عند التحميل
    renderCalendar();

    // أزرار التنقل بين الأشهر
    document.getElementById('cal-prev').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });
    document.getElementById('cal-next').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });


    /* =========================================
       2. الرسوم البيانية (Charts)
    ========================================= */
    const colorPrimary = '#1e293b';
    const colorGold    = '#d4af37';
    const colorGreen   = '#15803d';
    const colorRed     = '#dc2626';
    const colorGray    = '#cbd5e1';

    function showEmptyChart(wrapperId, canvasId, iconClass) {
        const wrapper = document.getElementById(wrapperId);
        const canvas  = document.getElementById(canvasId);
        if (canvas) canvas.style.display = 'none';
        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'chart-empty-state';
        emptyDiv.innerHTML = `<i class="${iconClass}"></i><p>لا توجد بيانات لعرضها حتى الآن</p>`;
        wrapper.appendChild(emptyDiv);
    }

    // ===== الرسم الخطي =====
    const trendCasesData = @json($chartCasesCount ?? []);
    const trendLabels    = @json($monthsLabels ?? []);
    const hasTrendData   = trendCasesData.some(v => v > 0);

    if (!hasTrendData) {
        showEmptyChart('trendChartWrapper', 'casesTrendChart', 'fas fa-chart-area');
    } else {
        const trendCtx   = document.getElementById('casesTrendChart').getContext('2d');
        let gradientFill = trendCtx.createLinearGradient(0, 0, 0, 300);
        gradientFill.addColorStop(0, 'rgba(212, 175, 55, 0.4)');
        gradientFill.addColorStop(1, 'rgba(212, 175, 55, 0.0)');

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'عدد القضايا المضافة',
                    data: trendCasesData,
                    borderColor: colorGold,
                    backgroundColor: gradientFill,
                    borderWidth: 3,
                    pointBackgroundColor: colorPrimary,
                    pointBorderColor: colorGold,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: colorPrimary,
                        titleFont: { family: 'inherit', size: 13 },
                        bodyFont: { family: 'inherit', size: 14, weight: 'bold' },
                        padding: 10,
                        displayColors: false,
                        rtl: true
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: 'inherit' } } },
                    y: {
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { font: { family: 'inherit' }, stepSize: 1 },
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // ===== الرسم الدائري =====
    const statusLabels  = @json($chartStatusLabels ?? []);
    const statusData    = @json($chartStatusData ?? []);
    const hasStatusData = statusData.length > 0 && statusData.some(v => v > 0);

    if (!hasStatusData) {
        showEmptyChart('statusChartWrapper', 'casesStatusChart', 'fas fa-chart-pie');
    } else {
        const statusCtx = document.getElementById('casesStatusChart').getContext('2d');
        const palette   = [colorPrimary, colorGreen, colorGold, colorGray, colorRed, '#6366f1', '#f97316'];
        const bgColors  = statusLabels.map((_, i) => palette[i % palette.length]);

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: bgColors,
                    borderWidth: 0,
                    hoverOffset: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom', rtl: true,
                        labels: { font: { family: 'inherit', size: 12, weight: 'bold' }, usePointStyle: true, padding: 20 }
                    },
                    tooltip: { backgroundColor: colorPrimary, bodyFont: { family: 'inherit', size: 13 }, rtl: true }
                }
            }
        });
    }

});
</script>
@endpush