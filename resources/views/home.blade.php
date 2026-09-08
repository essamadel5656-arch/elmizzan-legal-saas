@extends('layouts.app')

@section('title', __('الرئيسية') . ' | ' . $appName)

@section('content')

{{-- ===== الترحيب ===== --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2">
        {{ __('مرحباً بك، أستاذ :name!', ['name' => auth()->user()->name]) }}
    </h1>
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
        <i class="fas fa-calendar-day"></i>
        <span>{{ __('اليوم:') }} {{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('l، j F Y') }}</span>
    </div>
</div>

{{-- ===== كروت الإحصائيات ===== --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

    {{-- 1. قضية نشطة --}}
    <div class="panel flex items-center gap-4 transition-transform hover:-translate-y-1 cursor-pointer group">
        <div class="w-14 h-14 shrink-0 flex items-center justify-center rounded-xl text-2xl transition-transform group-hover:scale-110" style="background-color: rgba(30, 41, 59, 0.1); color: var(--sidebar-bg);">
            <i class="fas fa-briefcase"></i>
        </div>
        <div class="flex flex-col items-start text-start">
            <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-1">{{ $activeCasesCount ?? 0 }}</h3>
            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 m-0">{{ __('قضية نشطة') }}</p>
        </div>
    </div>

    {{-- 2. جلسات اليوم --}}
    <div class="panel flex items-center gap-4 transition-transform hover:-translate-y-1 cursor-pointer group">
        <div class="w-14 h-14 shrink-0 flex items-center justify-center rounded-xl text-2xl transition-transform group-hover:scale-110" style="background-color: rgba(212, 175, 55, 0.15); color: var(--gold-accent);">
            <i class="fas fa-gavel"></i>
        </div>
        <div class="flex flex-col items-start text-start">
            <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-1">{{ $todaySessionsCount ?? 0 }}</h3>
            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 m-0">{{ __('جلسات اليوم') }}</p>
        </div>
    </div>

    {{-- 3. إجمالي الموكلين --}}
    <div class="panel flex items-center gap-4 transition-transform hover:-translate-y-1 cursor-pointer group">
        <div class="w-14 h-14 shrink-0 flex items-center justify-center rounded-xl text-2xl transition-transform group-hover:scale-110" style="background-color: rgba(21, 128, 61, 0.1); color: var(--success-color);">
            <i class="fas fa-users"></i>
        </div>
        <div class="flex flex-col items-start text-start">
            <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-1">{{ $totalClientsCount ?? 0 }}</h3>
            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 m-0">{{ __('إجمالي الموكلين') }}</p>
        </div>
    </div>

    {{-- 4. مهام عاجلة --}}
    <div class="panel flex items-center gap-4 transition-transform hover:-translate-y-1 cursor-pointer group">
        <div class="w-14 h-14 shrink-0 flex items-center justify-center rounded-xl text-2xl transition-transform group-hover:scale-110" style="background-color: rgba(220, 38, 38, 0.1); color: var(--danger-color);">
            <i class="fas fa-clock"></i>
        </div>
        <div class="flex flex-col items-start text-start">
            <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-1">{{ $urgentTasksCount ?? 0 }}</h3>
            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 m-0">{{ __('مهام عاجلة') }}</p>
        </div>
    </div>

    {{-- 5. إجمالي الأتعاب --}}
    <div class="panel flex items-center gap-4 transition-transform hover:-translate-y-1 cursor-pointer group">
        <div class="w-14 h-14 shrink-0 flex items-center justify-center rounded-xl text-2xl transition-transform group-hover:scale-110" style="background-color: rgba(99, 102, 241, 0.1); color: #6366f1;">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        <div class="flex flex-col items-start text-start">
            <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-1">{{ number_format($totalFees ?? 0) }}</h3>
            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 m-0">{{ __('إجمالي الأتعاب (ر.ع.)') }}</p>
        </div>
    </div>

    {{-- 6. نسبة التحصيل --}}
    <div class="panel flex items-center gap-4 transition-transform hover:-translate-y-1 cursor-pointer group">
        <div class="w-14 h-14 shrink-0 flex items-center justify-center rounded-xl text-2xl transition-transform group-hover:scale-110" style="background-color: rgba(20, 184, 166, 0.1); color: #14b8a6;">
            <i class="fas fa-percentage"></i>
        </div>
        <div class="flex-1 flex flex-col items-start text-start w-full">
            <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-1">{{ $collectionRate ?? 0 }}%</h3>
            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 m-0 mb-2">
                {{ __('نسبة التحصيل') }}
                <span class="text-xs">
                    ({{ number_format($totalPaid ?? 0) }} / {{ number_format($totalFees ?? 0) }} {{ __('ر.ع.') }})
                </span>
            </p>
            <div class="w-full bg-slate-200 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-teal-500 to-indigo-500" style="width: {{ $collectionRate ?? 0 }}%;"></div>
            </div>
        </div>
    </div>

</div>


{{-- ===== المحتوى الأوسط (الجداول والتقويم) ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    {{-- أحدث القضايا --}}
    <div class="panel lg:col-span-2">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-lg font-bold flex items-center gap-2 text-slate-900 dark:text-slate-100"><i class="fas fa-folder-open text-amber-500"></i> {{ __('أحدث القضايا المضافة') }}</h2>
            <a href="{{ route('cases.index') }}" class="btn-secondary px-4 py-2 text-sm">{{ __('عرض الكل') }}</a>
        </div>
        <div class="overflow-x-auto">
            <table class="custom-table w-full text-right">
                <thead>
                    <tr>
                        <th class="py-3 px-2 text-sm font-semibold text-slate-500 dark:text-slate-400 border-b-2 border-slate-100 dark:border-slate-800">{{ __('رقم القضية') }}</th>
                        <th class="py-3 px-2 text-sm font-semibold text-slate-500 dark:text-slate-400 border-b-2 border-slate-100 dark:border-slate-800">{{ __('الموكل') }}</th>
                        <th class="py-3 px-2 text-sm font-semibold text-slate-500 dark:text-slate-400 border-b-2 border-slate-100 dark:border-slate-800">{{ __('الخصم') }}</th>
                        <th class="py-3 px-2 text-sm font-semibold text-slate-500 dark:text-slate-400 border-b-2 border-slate-100 dark:border-slate-800">{{ __('الحالة') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentCases ?? [] as $case)
                        <tr>
                            <td class="py-4 px-2 border-b border-slate-100 dark:border-slate-800"><strong class="text-slate-900 dark:text-slate-100">{{ $case->case_number ?? __('غير محدد') }}</strong></td>
                            <td class="py-4 px-2 border-b border-slate-100 dark:border-slate-800 text-slate-700 dark:text-slate-300">{{ $case->display_client_name }}</td>
                            <td class="py-4 px-2 border-b border-slate-100 dark:border-slate-800 text-slate-700 dark:text-slate-300">{{ $case->opponent_name }}</td>
                            <td class="py-4 px-2 border-b border-slate-100 dark:border-slate-800">
                                <span class="badge badge-{{ $case->status_color }} py-1 px-3 rounded-full text-xs font-bold inline-block">
                                    {{ $case->status_name }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center">
                                <div class="text-slate-500 dark:text-slate-400">
                                    <i class="fas fa-folder-open text-4xl mb-4 opacity-50 block"></i>
                                    <p class="font-medium mb-4">{{ __('لم يتم تسجيل أي قضايا في النظام حتى الآن.') }}</p>
                                    <a href="{{ route('cases.create') }}" class="btn-add-new inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 dark:bg-amber-600 text-white rounded-lg font-bold hover:bg-slate-800 dark:hover:bg-amber-500 transition-colors">
                                        <i class="fas fa-plus"></i> {{ __('إضافة قضية جديدة') }}
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
    <div class="panel lg:col-span-1">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-lg font-bold flex items-center gap-2 text-slate-900 dark:text-slate-100"><i class="fas fa-calendar-alt text-amber-500"></i> {{ __('التقويم الشهري') }}</h2>
            <a href="{{ route('cases.index') }}" title="{{ __('إضافة موعد') }}" class="text-amber-500 hover:text-amber-600 transition-colors">
                <i class="fas fa-plus-circle text-xl"></i>
            </a>
        </div>

        <div class="w-full text-center" dir="rtl">
            <div class="flex justify-between items-center mb-4">
                <button id="cal-prev" title="{{ __('الشهر السابق') }}" class="w-8 h-8 flex items-center justify-center bg-slate-100 dark:bg-slate-800 rounded-lg hover:bg-amber-500 hover:text-white transition-colors"><i class="fas fa-chevron-right"></i></button>
                <div class="font-extrabold text-lg text-slate-900 dark:text-slate-100" id="cal-month-year">...</div>
                <button id="cal-next" title="{{ __('الشهر التالي') }}" class="w-8 h-8 flex items-center justify-center bg-slate-100 dark:bg-slate-800 rounded-lg hover:bg-amber-500 hover:text-white transition-colors"><i class="fas fa-chevron-left"></i></button>
            </div>
            
            <div class="grid grid-cols-7 font-bold text-slate-500 dark:text-slate-400 text-xs mb-3">
                <div>{{ __('أحد') }}</div><div>{{ __('إثنين') }}</div><div>{{ __('ثلاثاء') }}</div><div>{{ __('أربعاء') }}</div><div>{{ __('خميس') }}</div><div>{{ __('جمعة') }}</div><div>{{ __('سبت') }}</div>
            </div>
            
            <div class="grid grid-cols-7 gap-1" id="cal-grid">
            </div>
        </div>
    </div>

</div>

{{-- ===== قسم الرسوم البيانية ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- الرسم البياني الخطي --}}
    <div class="panel">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-lg font-bold flex items-center gap-2 text-slate-900 dark:text-slate-100"><i class="fas fa-chart-area text-amber-500"></i> {{ __('معدل تسجيل القضايا خلال العام') }}</h2>
        </div>
        <div class="relative w-full h-72" id="trendChartWrapper">
            <canvas id="casesTrendChart"></canvas>
        </div>
    </div>

    {{-- الرسم البياني الدائري --}}
    <div class="panel">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-lg font-bold flex items-center gap-2 text-slate-900 dark:text-slate-100"><i class="fas fa-chart-pie text-amber-500"></i> {{ __('توزيع القضايا حسب الحالة') }}</h2>
        </div>
        <div class="relative w-full h-72" id="statusChartWrapper">
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
    const calEvents = @json($calendarEvents ?? []);
    let currentDate = new Date();
    
    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        const firstDayObj = new Date(year, month, 1);
        const firstDay = firstDayObj.getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        const monthNames = ["{{ __('يناير') }}", "{{ __('فبراير') }}", "{{ __('مارس') }}", "{{ __('أبريل') }}", "{{ __('مايو') }}", "{{ __('يونيو') }}", "{{ __('يوليو') }}", "{{ __('أغسطس') }}", "{{ __('سبتمبر') }}", "{{ __('أكتوبر') }}", "{{ __('نوفمبر') }}", "{{ __('ديسمبر') }}"];
        document.getElementById('cal-month-year').innerText = `${monthNames[month]} ${year}`;
        
        const grid = document.getElementById('cal-grid');
        grid.innerHTML = '';
        
        for (let i = 0; i < firstDay; i++) {
            grid.innerHTML += `<div class="aspect-square"></div>`;
        }
        
        const todayObj = new Date();
        const todayStr = `${todayObj.getFullYear()}-${String(todayObj.getMonth() + 1).padStart(2, '0')}-${String(todayObj.getDate()).padStart(2, '0')}`;

        for (let day = 1; day <= daysInMonth; day++) {
            const m = String(month + 1).padStart(2, '0');
            const d = String(day).padStart(2, '0');
            const dateStr = `${year}-${m}-${d}`;
            
            const dayEvents = calEvents.filter(e => e.date === dateStr);
            
            let classList = "aspect-square flex items-center justify-center rounded-lg text-sm font-semibold relative transition-colors border border-transparent";
            
            if (dateStr === todayStr) {
                classList += " bg-slate-900 dark:bg-amber-500 text-white shadow-md";
            } else if (dayEvents.length > 0) {
                classList += " bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800 hover:bg-amber-500 hover:text-white cursor-pointer group";
            } else {
                classList += " hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-900 dark:text-slate-100 cursor-pointer";
            }
            
            let tooltipHtml = '';
            let dotHtml = '';
            if (dayEvents.length > 0) {
                dotHtml = `<div class="absolute bottom-1 w-1 h-1 bg-current rounded-full"></div>`;
                let items = dayEvents.map(e => `
                    <div class="border-b border-white/10 pb-2 mb-2 last:border-0 last:pb-0 last:mb-0 text-right">
                        <div class="font-bold text-sm mb-1">${e.title}</div>
                        <div class="text-amber-400 text-xs font-bold mb-1"><i class="fas fa-clock"></i> ${e.time}</div>
                        ${e.notes ? `<div class="text-[10px] text-slate-300 bg-black/20 p-1.5 rounded leading-snug"><i class="fas fa-info-circle"></i> ${e.notes}</div>` : ''}
                    </div>
                `).join('');
                tooltipHtml = `
                    <div class="absolute bottom-[calc(100%+0.5rem)] left-1/2 -translate-x-1/2 bg-slate-900 text-white p-3 rounded-lg w-max min-w-[150px] max-w-[220px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 shadow-xl pointer-events-none">
                        ${items}
                        <div class="absolute top-100 left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-900"></div>
                    </div>`;
            }
            grid.innerHTML += `<div class="${classList}">${day}${dotHtml}${tooltipHtml}</div>`;
        }
    }

    renderCalendar();

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
        emptyDiv.className = 'flex flex-col items-center justify-center h-full text-slate-400';
        emptyDiv.innerHTML = `<i class="${iconClass} text-4xl mb-3 opacity-50"></i><p class="text-sm font-medium">{{ __('لا توجد بيانات لعرضها حتى الآن') }}</p>`;
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
                    label: '{{ __('عدد القضايا المضافة') }}',
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