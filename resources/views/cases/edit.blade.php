@extends('layouts.app')

@section('title', __('تعديل القضية') . ' - ' . $case->case_number . ' | ' . $appName)

@section('content')
<div class="p-6" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <!-- Header -->
    <div class="panel mb-6">
        <div class="panel-header mb-8 flex justify-between items-start flex-wrap gap-4">
            <div>
                <div class="panel-title flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    {{ __('تعديل بيانات القضية') }}
                </div>
                <p class="text-muted mt-2">{{ __('تحديث ملف القضية رقم:') }} <strong>{{ $case->case_number }}</strong></p>
            </div>
            <a href="{{ route('cases.show', $case->id) }}" class="btn-secondary flex items-center gap-2">
                <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('رجوع لملف القضية') }}
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="panel mb-6">
            <div class="panel-header">
                <div class="panel-title flex items-center gap-2">
                    <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ __('حدث خطأ في البيانات:') }}
                </div>
            </div>
            <ul class="text-muted mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cases.update', $case->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- 1. بيانات القضية الأساسية --}}
        <div class="panel mb-6">
            <div class="panel-header mb-6">
                <div class="panel-title flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    {{ __('بيانات القضية الأساسية') }}
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="field">
                    <label>{{ __('رقم القضية *') }}</label>
                    <input type="text" name="case_number" class="app-input" value="{{ old('case_number', $case->case_number) }}" required>
                </div>

                <div class="field">
                    <label>{{ __('حالة القضية *') }}</label>
                    <div class="select-wrap">
                        <select name="status" class="app-select" required>
                            <option value="">{{ __('-- اختر حالة القضية --') }}</option>
                            @foreach(['مفتوحة', 'متداولة', 'مؤجلة', 'محجوزة للحكم', 'منتهية', 'مستأنفة', 'محفوظة', 'معلقة'] as $s)
                                <option value="{{ $s }}" {{ old('status', $case->status) == $s ? 'selected' : '' }}>
                                    {{ __($s) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label>{{ __('المحكمة *') }}</label>
                    <div class="select-wrap">
                        <select name="court_id" class="app-select" required>
                            <option value="">{{ __('-- اختر المحكمة --') }}</option>
                            @foreach($courts as $court)
                                <option value="{{ $court->id }}" {{ old('court_id', $case->court_id) == $court->id ? 'selected' : '' }}>
                                    {{ $court->jurisdiction->name ?? '' }} - {{ $court->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label>{{ __('نوع الجهة القضائية *') }}</label>
                    <div class="select-wrap">
                        <select name="judicial_authority_type" class="app-select" required>
                            <option value="">{{ __('-- اختر النوع --') }}</option>
                            @foreach($jurisdictions as $jurisdiction)
                                <option value="{{ $jurisdiction->name }}" {{ old('judicial_authority_type', $case->judicial_authority_type ?? '') == $jurisdiction->name ? 'selected' : '' }}>
                                    {{ $jurisdiction->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="field md:col-span-2">
                    <label>{{ __('درجة التقاضي *') }}</label>
                    @php
                        $currentCourtLevelId = (int) old('court_level_id', $case->court_level_id ?? '');
                    @endphp
                    <div class="select-wrap">
                        <select name="court_level_id" class="app-select" required>
                            <option value="">{{ __('-- اختر درجة التقاضي --') }}</option>
                            @foreach($court_levels as $level)
                                <option value="{{ $level->id }}" {{ $currentCourtLevelId === (int)$level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('court_level_id')
                        <span class="text-muted block mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <input type="hidden" name="jurisdiction_id" value="{{ old('jurisdiction_id', $case->jurisdiction_id ?? '') }}">

                <div class="field md:col-span-2">
                    <label>{{ __('وصف القضية') }}</label>
                    <textarea name="description" class="app-input" rows="3">{{ old('description', $case->description) }}</textarea>
                </div>

                <div class="field md:col-span-2">
                    <label>{{ __('الإجراء السابق') }}</label>
                    <textarea name="Previous_procedure" class="app-input" rows="2">{{ old('Previous_procedure', $case->Previous_procedure) }}</textarea>
                </div>

                <div class="field md:col-span-2">
                    <label>{{ __('الحكم النهائي') }}</label>
                    <textarea name="final_decision" class="app-input" rows="2">{{ old('final_decision', $case->final_decision) }}</textarea>
                </div>

                <div class="field md:col-span-2">
                    <label>{{ __('ملاحظات') }}</label>
                    <textarea name="notes" class="app-input" rows="2">{{ old('notes', $case->notes) }}</textarea>
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
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="field">
                    <label>{{ __('اسم الخصم *') }}</label>
                    <input type="text" name="rival_name" class="app-input" value="{{ old('rival_name', $case->rival_name) }}" required>
                </div>

                <div class="field">
                    <label>{{ __('رقم الخصم *') }}</label>
                    <input type="text" name="rival_number" class="app-input" value="{{ old('rival_number', $case->rival_number) }}" required>
                </div>

                <div class="field">
                    <label>{{ __('الرقم القومي للخصم *') }}</label>
                    <input type="text" name="rival_nid" class="app-input" value="{{ old('rival_nid', $case->rival_nid) }}" required>
                </div>

                <div class="field">
                    <label>{{ __('عنوان الخصم *') }}</label>
                    <input type="text" name="rival_address" class="app-input" value="{{ old('rival_address', $case->rival_address) }}" required>
                </div>
            </div>
        </div>

        {{-- 3. البيانات المالية --}}
        <div class="panel mb-6">
            <div class="panel-header mb-6">
                <div class="panel-title flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('البيانات المالية') }}
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="field">
                    <label>{{ __('التكاليف') }}</label>
                    <input type="number" name="costs" class="app-input" step="0.01" value="{{ old('costs', $case->costs) }}">
                </div>

                <div class="field">
                    <label>{{ __('إجمالي الأتعاب (التكاليف الإجمالية)') }}</label>
                    <input type="number" name="total_costs" class="app-input" step="0.01" value="{{ old('total_costs', $case->total_costs) }}">
                </div>

                <div class="field">
                    <label>{{ __('المبلغ المدفوع (الإيداع)') }}</label>
                    <input type="number" name="deposit" class="app-input" step="0.01" value="{{ old('deposit', $case->deposit) }}">
                </div>
            </div>
        </div>

        {{-- 4. العملاء المرتبطون بالقضية --}}
        <div class="panel mb-6">
            <div class="panel-header mb-6">
                <div class="panel-title flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('العملاء المرتبطون بالقضية') }}
                </div>
            </div>
            
            <div class="panel-subtle grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                @php 
                    $savedClientIds = $case->clients ? array_map('intval', $case->clients->pluck('id')->toArray()) : [];
                    $selectedClients = old('client_ids') ? array_map('intval', old('client_ids')) : $savedClientIds;
                @endphp
                
                @foreach($clients as $client)
                    @php $isClientSelected = in_array((int)$client->id, $selectedClients); @endphp
                    
                    <div class="field p-3 panel">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="client-{{ $client->id }}" name="client_ids[]" value="{{ $client->id }}" {{ $isClientSelected ? 'checked' : '' }}>
                            <label for="client-{{ $client->id }}">{{ $client->name }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('client_ids')
                <span class="text-muted block mt-2">{{ $message }}</span>
            @enderror
        </div>

        {{-- 5. المحامون المرتبطون بالقضية --}}
        <div class="panel mb-6">
            <div class="panel-header mb-6">
                <div class="panel-title flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('المحامون المرتبطون بالقضية') }}
                </div>
            </div>
            
            @php 
                $caseLawyers     = $case->lawyers->keyBy('id');
                $savedLawyerIds  = array_map('intval', $case->lawyers->pluck('id')->toArray());
                $selectedLawyers = old('lawyer_ids') ? array_map('intval', old('lawyer_ids')) : $savedLawyerIds;
            @endphp

            <div class="mb-4 flex items-center gap-2 text-muted">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                {{ __('حدد المحامين وأدوارهم في هذه القضية:') }}
            </div>

            <div class="panel-subtle grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                @foreach($lawyers as $lawyer)
                    @php
                        $isSelected = in_array((int)$lawyer->id, $selectedLawyers);

                        if (old('lawyer_ids')) {
                            $currentRole = old("lawyer_roles.{$lawyer->id}", 'assistant');
                        } else {
                            $currentRole = $caseLawyers->has($lawyer->id)
                                ? $caseLawyers[$lawyer->id]->pivot->role
                                : 'assistant';
                        }
                    @endphp

                    <div class="field flex flex-col gap-2 p-3 panel" id="row-{{ $lawyer->id }}">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="lawyer-{{ $lawyer->id }}" name="lawyer_ids[]" value="{{ $lawyer->id }}" {{ $isSelected ? 'checked' : '' }}>
                            <label for="lawyer-{{ $lawyer->id }}" class="w-full">
                                <span class="block">{{ $lawyer->name }}</span>
                                @if($lawyer->specialization)
                                    <span class="block text-muted">{{ $lawyer->specialization }}</span>
                                @endif
                            </label>
                        </div>
                        
                        <div class="select-wrap mt-2">
                            <select name="lawyer_roles[{{ $lawyer->id }}]" class="app-select">
                                <option value="lead"        {{ $currentRole === 'lead'        ? 'selected' : '' }}>{{ __('محامي رئيسي') }}</option>
                                <option value="assistant"   {{ $currentRole === 'assistant'   ? 'selected' : '' }}>{{ __('مساعد') }}</option>
                                <option value="consultant"  {{ $currentRole === 'consultant'  ? 'selected' : '' }}>{{ __('مستشار') }}</option>
                            </select>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('lawyer_ids')
                <span class="text-muted block mt-2">{{ $message }}</span>
            @enderror
        </div>

        {{-- أزرار التحكم --}}
        <div class="flex justify-end gap-4 mt-8 pt-6">
            <a href="{{ route('cases.show', $case->id) }}" class="btn-secondary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ __('إلغاء والتراجع') }}
            </a>
            <button type="submit" class="btn-primary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                {{ __('حفظ جميع التعديلات') }}
            </button>
        </div>

    </form>
</div>
@endsection