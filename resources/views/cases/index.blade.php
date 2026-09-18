@extends('layouts.app')

@section('title', __('إدارة القضايا') . ' | ' . $appName)

@section('content')
<div class="p-6" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <!-- Header -->
    <div class="panel mb-6">
        <div class="panel-header mb-8 flex justify-between items-start flex-wrap gap-4">
            <div>
                <div class="panel-title flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    {{ __('إدارة القضايا') }}
                </div>
                <p class="text-muted mt-2">{{ __('عرض وتتبع جميع القضايا الموكلة للمكتب') }}</p>
            </div>
            
            <a href="{{ route('cases.create') }}" class="btn-add-new flex items-center gap-2">
                <span class="icon-circle flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </span>
                <span>{{ __('قضية جديدة') }}</span>
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="panel mb-6">
        <form method="GET" action="{{ route('cases.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            
            <div class="field lg:col-span-2">
                <label for="search" class="mb-2 block">{{ __('ابحث باسم العميل أو الرقم القومي أو رقم القضية') }}</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="app-input" placeholder="{{ __('اكتب للبحث...') }}">
            </div>

            <div class="field">
                <label for="court_id" class="mb-2 block">{{ __('المحكمة') }}</label>
                <div class="select-wrap">
                    <select name="court_id" id="court_id" class="app-select">
                        <option value="">{{ __('الكل') }}</option>
                        @foreach(\App\Models\Court::all() as $court)
                            <option value="{{ $court->id }}" {{ request('court_id') == $court->id ? 'selected' : '' }}>
                                {{ $court->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="field">
                <label for="court_level_id" class="mb-2 block">{{ __('درجة التقاضي') }}</label>
                <div class="select-wrap">
                    <select name="court_level_id" id="court_level_id" class="app-select">
                        <option value="">{{ __('الكل') }}</option>
                        @foreach(\App\Models\CourtLevel::all() as $level)
                            <option value="{{ $level->id }}" {{ request('court_level_id') == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="field lg:col-span-4 flex gap-2 w-full mt-2">
                <button type="submit" class="btn-secondary flex-1 flex items-center justify-center gap-2" title="{{ __('بحث') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>{{ __('بحث') }}</span>
                </button>
                <a href="{{ route('cases.index') }}" class="btn-secondary flex items-center justify-center px-6" title="{{ __('تفريغ الفلاتر') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            </div>

        </form>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <span class="text-muted">{{ __('إجمالي القضايا:') }}</span> 
            <span class="badge-item">{{ method_exists($cases, 'total') ? $cases->total() : $cases->count() }}</span>
        </div>
    </div>

    @if($cases->isEmpty())
        <div class="panel text-center">
            <div class="panel-subtle flex items-center justify-center mx-auto mb-6" style="width: 64px; height: 64px; border-radius: 50%;">
                <svg class="w-8 h-8 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div class="panel-header justify-center mb-2">
                <div class="panel-title">{{ __('لا توجد قضايا مسجلة') }}</div>
            </div>
            <p class="mb-10 text-muted">{{ __('لم يتم إضافة أي قضايا للنظام حتى الآن. ابدأ بإضافة قضيتك الأولى.') }}</p>
            
            <a href="{{ route('cases.create') }}" class="btn-add-new flex items-center justify-center gap-2 w-max mx-auto">
                <span class="icon-circle flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </span>
                <span>{{ __('قضية جديدة') }}</span>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cases as $case)
                @php
                    $client = $case->clients?->first();
                    $leadLawyer = $case->lawyers->where('pivot.role', 'lead')->first() 
                               ?? $case->lawyers->where('pivot.role', 'محامي رئيسي')->first() 
                               ?? $case->lawyers->first();
                               
                    $statusClass = 'badge-info'; // Default
                    if (in_array($case->status, ['مفتوحة', 'متداولة', 'جارية'])) {
                        $statusClass = 'badge-success';
                    } elseif (in_array($case->status, ['منتهية', 'محفوظة'])) {
                        $statusClass = 'badge-danger';
                    } elseif (in_array($case->status, ['مؤجلة', 'محجوزة للحكم', 'معلقة'])) {
                        $statusClass = 'badge-warning';
                    }
                @endphp
                <div class="panel flex flex-col gap-4">

                    <div class="flex items-start justify-between gap-3">
                        <div class="flex flex-col gap-1">
                            <div class="panel-title flex items-center gap-2">
                                <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $client->name ?? __('غير محدد') }}
                            </div>
                            <div class="badge-item" dir="ltr"># {{ $case->case_number }}</div>
                        </div>
                        <span class="badge-item {{ $statusClass }}">{{ __($case->status) }}</span>
                    </div>

                    <div class="panel-subtle p-4 grid grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1">
                            <span class="text-muted">{{ __('نوع الجهة') }}</span>
                            <span>{{ $case->court->jurisdiction->name ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-muted">{{ __('المحكمة') }}</span>
                            <span>{{ $case->court->name ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-muted">{{ __('درجة التقاضي') }}</span>
                            <span>{{ $case->court_level ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-muted">{{ __('المحامي الرئيسي') }}</span>
                            <span>{{ $leadLawyer->name ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col gap-1 col-span-2">
                            <span class="text-muted">{{ __('اسم الخصم') }}</span>
                            <span>{{ $case->rival_name ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-auto pt-4">
                        <a href="{{ route('cases.show', $case) }}" class="btn-primary flex items-center justify-center gap-2 w-full" title="{{ __('التفاصيل كاملة') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                            <span>{{ __('تفاصيل القضية') }}</span>
                        </a>

                        <a href="{{ route('cases.edit', $case) }}" class="btn-action-edit flex items-center justify-center" title="{{ __('تعديل') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </a>

                        <form action="{{ route('cases.destroy', $case->id) }}" method="POST" style="margin: 0; display: contents;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action-delete flex items-center justify-center" title="{{ __('حذف') }}" onclick="return confirm('{{ __('هل أنت متأكد من حذف هذه القضية نهائياً؟') }}')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>

        @if(method_exists($cases, 'links'))
            <div class="mt-6">
                {{ $cases->links() }}
            </div>
        @endif
    @endif

</div>
@endsection