@extends('layouts.app')

@section('title', __('إضافة قضية جديدة') . ' | ' . ($appName ?? 'El-Mizzan'))

@section('content')
<div class="p-4 md:p-8 max-w-5xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <!-- Header -->
    <div class="panel-header mb-8">
        <div>
            <h1 class="panel-title flex items-center gap-2 text-xl md:text-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ __('إضافة ملف قضية جديد') }}
            </h1>
            <p class="text-muted mt-2">{{ __('سجل بيانات القضية والطرف الموكل، الخصم، والماليات المترتبة عليها') }}</p>
        </div>
        <a href="{{ route('cases.index') }}" class="btn-secondary flex items-center gap-2">
            <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('رجوع للقائمة') }}
        </a>
    </div>

    @if ($errors->any())
        <div class="panel-subtle mb-6">
            <div class="flex items-center gap-2 font-bold mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> 
                {{ __('يرجى مراجعة الأخطاء التالية:') }}
            </div>
            <ul class="list-disc mx-5 font-medium text-muted">
                @foreach ($errors->all() as $error)
                    <li>{{ __($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="caseForm" action="{{ route('cases.store') }}" method="POST" enctype="multipart/form-data" novalidate class="space-y-6">
        @csrf

        {{-- 1. Client Details --}}
        <div class="panel">
            <div class="panel-header mb-6 pb-3 border-b">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('بيانات العميل الموكل') }}
                </div>
                <a href="{{ route('add-client') }}" class="btn-secondary flex items-center gap-2 px-3 py-1.5 text-sm" target="_blank">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('موكل جديد') }}
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="field md:col-span-2">
                    <label for="client_search">{{ __('ابحث عن اسم العميل الموكل *') }}</label>
                    <div class="input-icon-wrap">
                        <input type="text" id="client_search" list="clients_list" class="app-input w-full" placeholder="{{ __('ابدأ بكتابة اسم العميل للربط التلقائي...') }}" autocomplete="off" required>
                    </div>
                    <datalist id="clients_list">
                        @foreach ($clients as $client)
                            <option value="{{ $client->name }}" 
                                    data-id="{{ $client->id }}" 
                                    data-phone="{{ $client->phone ?? __('غير مسجل') }}" 
                                    data-national="{{ $client->nid ?? __('غير مسجل') }}" 
                                    data-address="{{ $client->address ?? __('غير مسجل') }}">
                            </option>
                        @endforeach
                    </datalist>
                    <input type="hidden" id="client_id" name="client_id" value="{{ old('client_id') }}">
                </div>

                <div class="field">
                    <label>{{ __('رقم الهاتف') }}</label>
                    <input type="text" id="display_phone" class="app-input w-full opacity-70 cursor-not-allowed" placeholder="{{ __('سيتم السحب تلقائياً') }}" readonly disabled>
                </div>

                <div class="field">
                    <label>{{ __('الرقم القومي') }}</label>
                    <input type="text" id="display_national_id" class="app-input w-full opacity-70 cursor-not-allowed" placeholder="{{ __('سيتم السحب تلقائياً') }}" readonly disabled>
                </div>

                <div class="field md:col-span-2">
                    <label>{{ __('العنوان المسجل') }}</label>
                    <input type="text" id="display_address" class="app-input w-full opacity-70 cursor-not-allowed" placeholder="{{ __('سيتم السحب تلقائياً') }}" readonly disabled>
                </div>
            </div>
        </div>

        {{-- 2. Legal Team --}}
        <div class="panel">
            <div class="panel-header mb-6 pb-3 border-b">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('الفريق القانوني المسؤول') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="field">
                    <label for="lawyer_id">{{ __('المحامي المسؤول عن القضية *') }}</label>
                    @if(auth()->user()->role === 'admin')
                        <div class="select-wrap">
                            <select id="lawyer_id" name="lawyer_id" class="app-select w-full" required>
                                <option value="" disabled selected>{{ __('اختر المحامي المسؤول...') }}</option>
                                @foreach ($lawyers as $lawyer)
                                    <option value="{{ $lawyer->id }}" {{ old('lawyer_id') == $lawyer->id ? 'selected' : '' }}>{{ $lawyer->name }}</option>
                                @endforeach
                            </select>
                            <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    @else
                        <input type="hidden" name="lawyer_id" value="{{ auth()->user()->lawyer_id }}">
                        <input type="text" class="app-input w-full opacity-70 cursor-not-allowed" value="{{ auth()->user()->name }}" disabled readonly>
                    @endif
                </div>
            </div>
        </div>

        {{-- 3. Case Details & Financials --}}
        <div class="panel">
            <div class="panel-header mb-6 pb-3 border-b">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    {{ __('تفاصيل القضية والماليات') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="field">
                    <label for="case_number">{{ __('رقم القضية *') }}</label>
                    <input type="text" id="case_number" name="case_number" class="app-input w-full" value="{{ old('case_number') }}" placeholder="{{ __('مثال: 2525 أو 2026/123') }}" required>
                </div>

                <div class="field">
                    <label for="status">{{ __('حالة القضية الحالية *') }}</label>
                    <div class="select-wrap">
                        <select id="status" name="status" class="app-select w-full" required>
                            <option value="{{ __('مفتوحة') }}" {{ old('status', 'مفتوحة') == 'مفتوحة' ? 'selected' : '' }}>{{ __('مفتوحة') }}</option>
                            <option value="{{ __('متداولة') }}" {{ old('status') == 'متداولة' ? 'selected' : '' }}>{{ __('متداولة') }}</option>
                            <option value="{{ __('مؤجلة') }}" {{ old('status') == 'مؤجلة' ? 'selected' : '' }}>{{ __('مؤجلة') }}</option>
                            <option value="{{ __('محجوزة للحكم') }}" {{ old('status') == 'محجوزة للحكم' ? 'selected' : '' }}>{{ __('محجوزة للحكم') }}</option>
                            <option value="{{ __('منتهية') }}" {{ old('status') == 'منتهية' ? 'selected' : '' }}>{{ __('منتهية') }}</option>
                            <option value="{{ __('مستأنفة') }}" {{ old('status') == 'مستأنفة' ? 'selected' : '' }}>{{ __('مستأنفة') }}</option>
                            <option value="{{ __('محفوظة') }}" {{ old('status') == 'محفوظة' ? 'selected' : '' }}>{{ __('محفوظة') }}</option>
                            <option value="{{ __('معلقة') }}" {{ old('status') == 'معلقة' ? 'selected' : '' }}>{{ __('معلقة') }}</option>
                        </select>
                        <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <div class="field">
                    <label for="jurisdiction_id">{{ __('جهة التقاضي *') }}</label>
                    <div class="select-wrap">
                        <select id="jurisdiction_id" name="jurisdiction_id" class="app-select w-full" required>
                            <option value="" disabled selected>{{ __('اختر الجهة...') }}</option>
                            @foreach ($jurisdictions as $jurisdiction)
                                <option value="{{ $jurisdiction->id }}" {{ old('jurisdiction_id') == $jurisdiction->id ? 'selected' : '' }}>{{ __($jurisdiction->name) }}</option>
                            @endforeach
                        </select>
                        <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <div class="field">
                    <label for="court_level_id">{{ __('درجة التقاضي / المحكمة *') }}</label>
                    <div class="select-wrap">
                        <select id="court_level_id" name="court_level_id" class="app-select w-full" required>
                            <option value="" disabled selected>{{ __('اختر درجة التقاضي...') }}</option>
                            @foreach ($court_levels as $level)
                                <option value="{{ $level->id }}" {{ old('court_level_id') == $level->id ? 'selected' : '' }}>{{ __($level->name) }}</option>
                            @endforeach
                        </select>
                        <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <div class="field">
                    <label for="court_id">{{ __('المحكمة *') }}</label>
                    <div class="select-wrap">
                        <select id="court_id" name="court_id" class="app-select w-full" required>
                            <option value="" disabled selected>{{ __('اختر المحكمة...') }}</option>
                            @foreach ($courts as $court)
                                <option value="{{ $court->id }}" data-jurisdiction="{{ $court->jurisdiction_id }}" {{ old('court_id') == $court->id ? 'selected' : '' }}>
                                    {{ __($court->name) }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <div class="field">
                    <label for="circuit">{{ __('الدائرة') }}</label>
                    <input type="text" id="circuit" name="circuit" class="app-input w-full" value="{{ old('circuit') }}" placeholder="{{ __('مثال: الدائرة الثالثة مدني') }}">
                </div>

                <div class="field lg:col-span-3">
                    <label for="Previous_procedure">{{ __('الإجراء السابق أو الموقف الحالي للدعوى') }}</label>
                    <input type="text" id="Previous_procedure" name="Previous_procedure" class="app-input w-full" value="{{ old('Previous_procedure', __('لا يوجد إشعار سابق')) }}" placeholder="{{ __('مثال: تقديم مستندات، إعادة إعلان...') }}">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6 pt-6 border-t border-dashed">
                <div class="field">
                    <label for="total_costs">{{ __('إجمالي الأتعاب (ج.م)') }}</label>
                    <input type="number" id="total_costs" name="total_costs" class="app-input w-full" value="{{ old('total_costs') }}" min="0" placeholder="{{ __('مثال: 10000') }}">
                </div>

                <div class="field">
                    <label for="deposit">{{ __('المدفوع مقدماً (ج.م)') }}</label>
                    <input type="number" id="deposit" name="deposit" class="app-input w-full" value="{{ old('deposit') }}" min="0" placeholder="{{ __('مثال: 1000') }}">
                </div>

                <div class="field">
                    <label>{{ __('المبلغ المتبقي (ج.م)') }}</label>
                    <input type="number" id="remaining_amount" class="app-input w-full opacity-70 cursor-not-allowed font-bold" value="" readonly disabled>
                </div>

                <div class="field">
                    <label for="costs">{{ __('المصاريف الإدارية والرسوم (ج.م)') }}</label>
                    <input type="number" id="costs" name="costs" class="app-input w-full" value="{{ old('costs') }}" min="0" placeholder="{{ __('مثال: 500') }}">
                </div>
            </div> 
        </div>

        {{-- 4. Opponent & Case Subject --}}
        <div class="panel">
            <div class="panel-header mb-6 pb-3 border-b">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    {{ __('موضوع الدعوى والخصم') }}
                </div>
            </div>

            <div class="field mb-6">
                <label for="description">{{ __('ملخص وقائع الدعوى *') }}</label>
                <textarea id="description" name="description" class="app-input w-full" rows="4" placeholder="{{ __('شرح تفصيلي للوقائع...') }}" required>{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="field">
                    <label for="rival_name">{{ __('اسم الخصم بالكامل *') }}</label>
                    <input type="text" id="rival_name" name="rival_name" class="app-input w-full" value="{{ old('rival_name') }}" required>
                </div>
                <div class="field">
                    <label for="rival_number">{{ __('رقم هاتف الخصم') }}</label>
                    <input type="text" id="rival_number" name="rival_number" class="app-input w-full" value="{{ old('rival_number') }}" dir="ltr">
                </div>
                <div class="field">
                    <label for="rival_nid">{{ __('الرقم القومي للخصم') }}</label>
                    <input type="text" id="rival_nid" name="rival_nid" class="app-input w-full" value="{{ old('rival_nid') }}" maxlength="14" dir="ltr">
                </div>
                <div class="field">
                    <label for="rival_address">{{ __('عنوان الخصم') }}</label>
                    <input type="text" id="rival_address" name="rival_address" class="app-input w-full" value="{{ old('rival_address') }}">
                </div>
            </div>
        </div>

        {{-- 5. Attachments --}}
        <div class="panel">
            <div class="panel-header mb-6 pb-3 border-b">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    {{ __('المرفقات والتوكيلات') }}
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div class="field">
                    <label for="procuration">{{ __('بيانات أو رقم التوكيل الرسمي الخاص بالقضية') }}</label>
                    <input type="text" id="procuration" name="procuration" class="app-input w-full" value="{{ old('procuration', __('لا يوجد')) }}" placeholder="{{ __('مثال: توكيل رقم 1234 ص توثيق أسوان النموذجي') }}">
                </div>

                <div class="field">
                    <label for="final_decision">{{ __('الحكم النهائي أو القرار (في حال كانت منتهية)') }}</label>
                    <input type="text" id="final_decision" name="final_decision" class="app-input w-full opacity-70 cursor-not-allowed" value="{{ old('final_decision', __('لم يصدر حكم بعد')) }}" placeholder="{{ __('مثال: قبول الدعوى شكلاً وفي الموضوع...') }}" readonly disabled>
                </div>

                <div class="field">
                    <label for="notes">{{ __('ملاحظات إضافية على القضية') }}</label>
                    <textarea id="notes" name="notes" class="app-input w-full" rows="3" placeholder="{{ __('أي تفاصيل أو ملاحظات أخرى للمكتب...') }}">{{ old('notes', __('لا توجد ملاحظات')) }}</textarea>
                </div>
            </div>

            <div class="panel-subtle mt-6 cursor-pointer text-center border-dashed border-2 hover:bg-black/5 transition-colors" id="uploadBox">
                <div id="uploadDefault" class="py-8">
                    <svg class="w-12 h-12 mb-4 mx-auto text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <p class="font-bold mb-1">{{ __('اضغط لرفع التوكيل (PDF, Word, Images)') }}</p>
                    <span class="text-sm text-muted">{{ __('الحد الأقصى 2MB') }}</span>
                </div>

                <div id="uploadPreview" class="hidden flex-col items-center gap-3 py-6"></div>
            </div>
            <input type="file" id="case_file" name="case_file" class="hidden" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t">
            <button type="button" class="btn-secondary px-6 py-3 flex items-center gap-2" id="resetFormBtn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                {{ __('مسح المدخلات') }}
            </button>
            <button type="submit" class="btn-primary px-8 py-3 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                {{ __('حفظ ملف القضية') }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('client_search');
    const datalist = document.getElementById('clients_list');
    const hiddenClientId = document.getElementById('client_id');

    const displayPhone = document.getElementById('display_phone');
    const displayNationalId = document.getElementById('display_national_id');
    const displayAddress = document.getElementById('display_address');

    const totalCostsInput = document.getElementById('total_costs');
    const depositInput = document.getElementById('deposit');
    const remainingInput = document.getElementById('remaining_amount');

    /* حساب المتبقي تلقائياً */
    function calculateRemaining() {
        const totalCosts = parseFloat(totalCostsInput.value) || 0;
        const deposit = parseFloat(depositInput.value) || 0;
        const remaining = totalCosts - deposit;
        remainingInput.value = remaining;
    }

    totalCostsInput.addEventListener('input', calculateRemaining);
    depositInput.addEventListener('input', calculateRemaining);
    calculateRemaining();

    /* البحث عن عميل والربط التلقائي */
    searchInput.addEventListener('input', function () {
        const inputValue = this.value.trim();
        const options = datalist.options;
        let found = false;

        for (let i = 0; i < options.length; i++) {
            if (options[i].value === inputValue) {
                hiddenClientId.value = options[i].getAttribute('data-id');
                displayPhone.value = options[i].getAttribute('data-phone');
                displayNationalId.value = options[i].getAttribute('data-national');
                displayAddress.value = options[i].getAttribute('data-address');
                found = true;
                break;
            }
        }

        if (!found) {
            hiddenClientId.value = '';
            displayPhone.value = '';
            displayNationalId.value = '';
            displayAddress.value = '';
        }
    });

    /* تفريغ ومسح الفورم بالكامل وعودة القيم الافتراضية */
    document.getElementById('resetFormBtn').addEventListener('click', function() {
        if(confirm('{{ __('هل أنت متأكد من مسح جميع البيانات المدخلة؟') }}')) {
            document.getElementById('caseForm').reset();
            hiddenClientId.value = '';
            displayPhone.value = '';
            displayNationalId.value = '';
            displayAddress.value = '';
            setTimeout(() => {
                filterCourts();
                resetUploadBox();
                calculateRemaining();
            }, 20);
        }
    });

    /* فلترة المحاكم بناءً على جهة التقاضي المحددة */
    const judicialSelect = document.getElementById('jurisdiction_id');
    const courtSelect = document.getElementById('court_id');

    if (judicialSelect && courtSelect) {
        const allCourtOptions = Array.from(courtSelect.querySelectorAll('option')).filter(opt => opt.value !== '');
        const oldCourtValue = "{{ old('court_id') }}";

        function filterCourts() {
            const selectedJurisdictionId = judicialSelect.value;
            courtSelect.innerHTML = '';

            const placeholderOpt = document.createElement('option');
            placeholderOpt.value = '';
            placeholderOpt.textContent = '{{ __('اختر المحكمة...') }}';
            placeholderOpt.disabled = true;
            placeholderOpt.selected = true;
            courtSelect.appendChild(placeholderOpt);

            let hasMatches = false;

            allCourtOptions.forEach(opt => {
                const courtJurisdiction = opt.getAttribute('data-jurisdiction');
                if (!selectedJurisdictionId || courtJurisdiction == selectedJurisdictionId) {
                    const clone = opt.cloneNode(true);
                    if (clone.value === oldCourtValue) {
                        clone.selected = true;
                    }
                    courtSelect.appendChild(clone);
                    hasMatches = true;
                }
            });

            if (selectedJurisdictionId && !hasMatches) {
                const noMatchesOpt = document.createElement('option');
                noMatchesOpt.value = '';
                noMatchesOpt.textContent = '{{ __('لا توجد محاكم تابعة لهذه الجهة') }}';
                noMatchesOpt.disabled = true;
                courtSelect.appendChild(noMatchesOpt);
            }
        }

        filterCourts();
        judicialSelect.addEventListener('change', filterCourts);
    }

    /* واجهة رفع المرفقات المخصصة بصرياً */
    const fileInput = document.getElementById('case_file');
    const uploadBox = document.getElementById('uploadBox');
    const uploadDefault = document.getElementById('uploadDefault');
    const uploadPreview = document.getElementById('uploadPreview');

    uploadBox.addEventListener('click', function (e) {
        if (e.target.closest('.remove-file-btn')) return;
        fileInput.click();
    });

    fileInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            uploadDefault.style.display = 'none';
            uploadPreview.style.display = 'flex';
            uploadPreview.classList.remove('hidden');
            uploadPreview.innerHTML = '';

            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    uploadPreview.innerHTML = `
                        <div class="panel p-2">
                            <img src="${e.target.result}" class="max-w-[140px] max-h-[140px]">
                        </div>
                        <p class="badge-item badge-success mt-2">
                            {{ __('تم اختيار:') }} <span>${file.name}</span> (${fileSizeMB} {{ __('ميجابايت') }})
                        </p>
                        <span class="remove-file-btn cursor-pointer text-muted underline">
                            {{ __('حذف واختيار ملف آخر') }}
                        </span>
                    `;
                }
                reader.readAsDataURL(file);
            } else {
                uploadPreview.innerHTML = `
                    <svg class="w-16 h-16 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <p class="badge-item badge-success mt-2">
                        {{ __('تم اختيار:') }} <span>${file.name}</span> (${fileSizeMB} {{ __('ميجابايت') }})
                    </p>
                    <span class="remove-file-btn cursor-pointer text-muted underline">
                        {{ __('حذف واختيار ملف آخر') }}
                    </span>
                `;
            }
        } else {
            resetUploadBox();
        }
    });

    function resetUploadBox() {
        fileInput.value = '';
        uploadDefault.style.display = 'block';
        uploadPreview.style.display = 'none';
        uploadPreview.classList.add('hidden');
        uploadPreview.innerHTML = '';
    }

    uploadPreview.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.remove-file-btn');
        if (removeBtn) {
            e.stopPropagation();
            resetUploadBox();
        }
    });
});
</script>
@endpush