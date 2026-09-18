@extends('layouts.app')
@section('title', __('تفاصيل القضية') . ' - ' . $case->case_number . ' | ' . $appName)

@section('content')
<div class="p-6" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Header --}}
    <div class="panel mb-6">
        <div class="panel-header mb-8 flex justify-between items-start flex-wrap gap-4">
            <div>
                <div class="panel-title flex items-center gap-2">
                    <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                    {{ __('ملف القضية') }}
                </div>
                <div class="text-muted mt-2 flex items-center gap-4">
                    <span dir="ltr"># {{ $case->case_number }}</span>
                    <span class="badge-item">{{ __($case->status) }}</span>
                </div>
            </div>
            
            <div class="flex gap-3 items-center flex-wrap">
                <a href="{{ route('cases.edit', $case->id) }}" class="btn-action-edit flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    {{ __('تعديل البيانات') }}
                </a>
                <a href="{{ route('cases.index') }}" class="btn-secondary flex items-center gap-2">
                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ __('رجوع للقائمة') }}
                </a>
            </div>
        </div>
    </div>

    {{-- 1. بيانات القضية الأساسية --}}
    <div class="panel mb-6">
        <div class="panel-header mb-6">
            <div class="panel-title flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ __('البيانات الأساسية') }}
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="info-row flex flex-col gap-1">
                <span class="text-muted">{{ __('المحكمة') }}</span>
                <span class="panel-subtle p-4 flex items-center">{{ $case->court->name ?? '—' }}</span>
            </div>
            <div class="info-row flex flex-col gap-1">
                <span class="text-muted">{{ __('نوع الجهة') }}</span>
                <span class="panel-subtle p-4 flex items-center">{{ $case->court->jurisdiction->name ?? '—' }}</span>
            </div>
            <div class="info-row flex flex-col gap-1">
                <span class="text-muted">{{ __('درجة التقاضي') }}</span>
                <span class="panel-subtle p-4 flex items-center {{ !$case->court_level ? 'text-muted' : '' }}">
                    {{ $case->courtLevel->name ?? __('غير محدد') }}
                </span>
            </div>
            <div class="info-row flex flex-col gap-1">
                <span class="text-muted">{{ __('اسم الخصم') }}</span>
                <span class="panel-subtle p-4 flex items-center {{ !$case->rival_name ? 'text-muted' : '' }}">
                    {{ $case->rival_name ?? __('غير محدد') }}
                </span>
            </div>
            <div class="info-row flex flex-col gap-1 lg:col-span-4">
                <span class="text-muted">{{ __('وصف القضية وملخص الوقائع') }}</span>
                <span class="panel-subtle p-4 whitespace-pre-wrap flex items-start {{ !$case->description ? 'text-muted' : '' }}">
                    {{ $case->description ?? __('لا يوجد وصف') }}
                </span>
            </div>
            <div class="info-row flex flex-col gap-1 lg:col-span-4">
                <span class="text-muted">{{ __('الإجراء السابق') }}</span>
                <span class="panel-subtle p-4 whitespace-pre-wrap flex items-start {{ !$case->Previous_procedure ? 'text-muted' : '' }}">
                    {{ $case->Previous_procedure ?? __('لا يوجد') }}
                </span>
            </div>
            <div class="info-row flex flex-col gap-1 lg:col-span-4">
                <span class="text-muted">{{ __('الحكم النهائي') }}</span>
                <span class="panel-subtle p-4 whitespace-pre-wrap flex items-start {{ !$case->final_decision ? 'text-muted' : '' }}">
                    {{ $case->final_decision ?? __('لا يوجد') }}
                </span>
            </div>
            <div class="info-row flex flex-col gap-1 lg:col-span-4">
                <span class="text-muted">{{ __('ملاحظات إدارية') }}</span>
                <span class="panel-subtle p-4 whitespace-pre-wrap flex items-start {{ !$case->notes ? 'text-muted' : '' }}">
                    {{ $case->notes ?? __('لا توجد ملاحظات') }}
                </span>
            </div>
        </div>
    </div>

    {{-- 2. بيانات الخصم --}}
    <div class="panel mb-6">
        <div class="panel-header mb-6">
            <div class="panel-title flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                {{ __('بيانات الخصم') }}
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="info-row flex flex-col gap-1">
                <span class="text-muted">{{ __('اسم الخصم') }}</span>
                <span class="panel-subtle p-4 flex items-center {{ !$case->rival_name ? 'text-muted' : '' }}">{{ $case->rival_name ?? '—' }}</span>
            </div>
            <div class="info-row flex flex-col gap-1">
                <span class="text-muted">{{ __('رقم الهاتف') }}</span>
                <span class="panel-subtle p-4 flex items-center {{ !$case->rival_number ? 'text-muted' : '' }}">{{ $case->rival_number ?? '—' }}</span>
            </div>
            <div class="info-row flex flex-col gap-1">
                <span class="text-muted">{{ __('الرقم القومي') }}</span>
                <span class="panel-subtle p-4 flex items-center {{ !$case->rival_nid ? 'text-muted' : '' }}">{{ $case->rival_nid ?? '—' }}</span>
            </div>
            <div class="info-row flex flex-col gap-1">
                <span class="text-muted">{{ __('العنوان') }}</span>
                <span class="panel-subtle p-4 flex items-center {{ !$case->rival_address ? 'text-muted' : '' }}">{{ $case->rival_address ?? '—' }}</span>
            </div>
        </div>
    </div>

    {{-- 3. البيانات المالية --}}
    <div class="panel mb-6">
        <div class="panel-header mb-6">
            <div class="panel-title flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __('الحسابات المالية') }}
            </div>
        </div>
        @php
            $remaining = ($case->total_costs ?? 0) - ($case->deposit ?? 0);
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="stat-card panel-subtle p-5 text-center">
                <div class="stat-label text-muted mb-2">{{ __('المصاريف الإدارية') }}</div>
                <div class="stat-value">{{ number_format($case->costs ?? 0, 2) }} {{ __('ر.ع.') }}</div>
            </div>
            <div class="stat-card panel-subtle p-5 text-center">
                <div class="stat-label text-muted mb-2">{{ __('إجمالي الأتعاب') }}</div>
                <div class="stat-value">{{ number_format($case->total_costs ?? 0, 2) }} {{ __('ر.ع.') }}</div>
            </div>
            <div class="stat-card panel-subtle p-5 text-center">
                <div class="stat-label text-muted mb-2">{{ __('المدفوع مقدماً') }}</div>
                <div class="stat-value">{{ number_format($case->deposit ?? 0, 2) }} {{ __('ر.ع.') }}</div>
            </div>
            <div class="stat-card panel-subtle p-5 text-center">
                <div class="stat-label text-muted mb-2">{{ __('المبلغ المتبقي') }}</div>
                <div class="stat-value">{{ number_format($remaining, 2) }} {{ __('ر.ع.') }}</div>
            </div>
        </div>
    </div>

    {{-- 4. العملاء والمحامون --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        
        <div class="panel">
            <div class="panel-header mb-6">
                <div class="panel-title flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('العملاء الموكلين') }} 
                    <span class="badge-item">{{ $case->clients->count() }}</span>
                </div>
            </div>
            @if($case->clients->isEmpty())
                <span class="text-muted block">{{ __('لا يوجد عملاء مرتبطون بهذه القضية.') }}</span>
            @else
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                    @foreach($case->clients as $client)
                        <div class="flex items-center gap-3 p-3 panel-subtle">
                            <div class="w-12 h-12 flex items-center justify-center panel">{{ mb_substr($client->name, 0, 1) }}</div>
                            <div class="overflow-hidden">
                                <div class="truncate" title="{{ $client->name }}">{{ $client->name }}</div>
                                <div class="text-muted mt-1 flex items-center gap-2"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg> {{ $client->phone ?? '—' }}</div>
                                <div class="text-muted mt-1 flex items-center gap-2"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg> {{ $client->nid ?? '—' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="panel">
            <div class="panel-header mb-6">
                <div class="panel-title flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('الفريق القانوني') }}
                    <span class="badge-item">{{ $case->lawyers->count() }}</span>
                </div>
            </div>
            @if($case->lawyers && $case->lawyers->count() > 0)
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                    @foreach($case->lawyers as $lawyer)
                        <div class="flex items-center gap-3 p-3 panel-subtle">
                            <div class="w-12 h-12 flex items-center justify-center panel">{{ mb_substr($lawyer->name, 0, 1) }}</div>
                            <div class="overflow-hidden">
                                <div class="truncate" title="{{ $lawyer->name }}">{{ $lawyer->name }}</div>
                                @if(!empty($lawyer->specialization))
                                    <div class="text-muted mt-1">{{ $lawyer->specialization }}</div>
                                @endif
                                @php
                                    $roleLabel = __('مساعد');
                                    if(isset($lawyer->pivot->role)) {
                                        $roleLabel = $lawyer->pivot->role === 'lead' ? __('محامي رئيسي') : __('مساعد');
                                    }
                                @endphp
                                <span class="badge-item mt-2 block w-fit">{{ $roleLabel }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <span class="text-muted block">{{ __('لا يوجد محامون مرتبطون بهذه القضية.') }}</span>
            @endif
        </div>

    </div>

    {{-- 5. المواعيد --}}
    <div class="panel mb-6">
        <div class="panel-header mb-6 flex justify-between items-center">
            <div class="panel-title flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ __('الجلسات والمواعيد') }}
                <span class="badge-item">{{ $case->appointments->count() }}</span>
            </div>
            <a href="{{ route('appointments.create', $case->id) }}" class="btn-primary flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> {{ __('إضافة موعد') }}</a>
        </div>

        @if($case->appointments && $case->appointments->count() > 0)
            <div class="panel-subtle overflow-x-auto">
                <table class="custom-table w-full">
                    <thead>
                        <tr>
                            <th>{{ __('رقم الموعد') }}</th>
                            <th>{{ __('التاريخ والوقت') }}</th>
                            <th>{{ __('تفاصيل الجلسة أو الموعد') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($case->appointments as $appointment)
                            <tr>
                                <td>#{{ $appointment->id }}</td>
                                <td dir="ltr">{{ $appointment->date }}</td>
                                <td class="text-muted">{{ $appointment->notes ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <span class="text-muted block mt-2">{{ __('لا توجد مواعيد أو جلسات مسجلة لهذه القضية حتى الآن.') }}</span>
        @endif
    </div>

    {{-- 6. المستندات والمرفقات --}}
    <div class="panel mb-6">
        <div class="panel-header mb-6 flex justify-between items-center">
            <div class="panel-title flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                {{ __('المستندات والمرفقات') }}
                <span class="badge-item">{{ $case->documents->count() }}</span>
            </div>
            <a href="{{ route('document.add_documents', ['case' => $case->id]) }}" class="btn-primary flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg> {{ __('رفع مستند') }}</a>
        </div>

        @if($case->documents && $case->documents->count() > 0)
            <div class="panel-subtle overflow-x-auto">
                <table class="custom-table w-full">
                    <thead>
                        <tr>
                            <th>{{ __('#') }}</th>
                            <th>{{ __('اسم المستند') }}</th>
                            <th>{{ __('تاريخ الرفع') }}</th>
                            <th>{{ __('إجراءات') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($case->documents as $document)
                            <tr>
                                <td>{{ $document->id }}</td>
                                <td><div class="flex items-center gap-2"><svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> {{ $document->title }}</div></td>
                                <td dir="ltr">{{ $document->created_at ? $document->created_at->format('Y-m-d') : '-' }}</td>
                                <td>
                                    @if($document->file_path)
                                        <a href="{{ asset('storage/' . ltrim(str_replace('public/', '', $document->file_path), '/')) }}" target="_blank" class="btn-action-edit flex items-center justify-center gap-2 w-max">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg> {{ __('عرض الملف') }}
                                        </a>
                                    @else
                                        <span class="text-muted">{{ __('لا يوجد ملف') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <span class="text-muted block mt-2">{{ __('لا توجد مستندات مرفوعة في هذا الملف.') }}</span>
        @endif
    </div>

</div>
@endsection