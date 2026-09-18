<div class="p-4 md:p-8 max-w-5xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <!-- Header -->
    <div class="panel-header mb-8 flex justify-between items-start flex-wrap gap-4">
        <div>
            <h1 class="panel-title flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ __('إضافة قضية جديدة') }}
            </h1>
            <p class="text-muted mt-2">
                {{ __('سجل بيانات القضية والطرف الموكل، الخصم، والماليات المترتبة عليها') }}
            </p>
        </div>
        <a href="{{ route('cases.index') }}" wire:navigate class="btn-secondary flex items-center gap-2 px-6 py-2.5">
            <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('رجوع للقائمة') }}
        </a>
    </div>

    @if ($errors->any())
        <div class="panel-subtle mb-6 p-6 border border-[var(--color-danger)] rounded-2xl">
            <strong class="flex items-center gap-2 text-[var(--color-danger)] font-bold mb-3 text-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ __('يرجى مراجعة الأخطاء التالية:') }}
            </strong>
            <ul class="list-disc mx-5 text-[var(--color-danger)] font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ __($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit="save" novalidate class="space-y-6">

        {{-- 1. Client Details --}}
        <div class="panel p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b border-[var(--border-color)]">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('تفاصيل الموكل') }}
                </div>
                <a href="{{ route('add-client') }}" wire:navigate class="btn-secondary flex items-center gap-2 px-3 py-1.5 text-sm" target="_blank">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('موكل جديد') }}
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2 field relative">
                    <label class="block mb-2">{{ __('اختر الموكل') }} <span class="text-[var(--color-danger)]">*</span></label>
                    
                    @if($selectedClient)
                        <div class="flex items-center justify-between p-3 border border-[var(--border-color)] rounded-lg bg-[var(--bg-subtle)]">
                            <div>
                                <span class="font-bold text-[var(--text-primary)]">{{ $selectedClient['name'] }}</span>
                                <span class="text-sm text-muted ms-2">({{ $selectedClient['phone'] }})</span>
                            </div>
                            <button type="button" wire:click="clearClient" class="text-sm text-[var(--color-danger)] hover:underline">
                                {{ __('تغيير العميل') }}
                            </button>
                        </div>
                    @else
                        <input type="text" wire:model.live.debounce.300ms="clientSearch" class="app-input w-full" placeholder="{{ __('ابحث باسم العميل...') }}">
                        
                        @if(strlen($clientSearch) >= 2)
                            <div class="absolute z-10 w-full mt-1 bg-[var(--bg-panel)] border border-[var(--border-color)] rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                @php $results = $this->searchClients(); @endphp
                                @forelse($results as $c)
                                    <div wire:click="selectClient({{ $c['id'] }}, '{{ addslashes($c['name']) }}', '{{ addslashes($c['phone']) }}', '{{ addslashes($c['nid']) }}', '{{ addslashes($c['address']) }}')" 
                                         class="p-3 hover:bg-[var(--bg-subtle)] cursor-pointer border-b border-[var(--border-color)] last:border-0">
                                        <div class="font-bold">{{ $c['name'] }}</div>
                                        <div class="text-xs text-muted">{{ $c['phone'] }} - {{ $c['nid'] }}</div>
                                    </div>
                                @empty
                                    <div class="p-3 text-muted text-sm">{{ __('لا توجد نتائج مطابقة.') }}</div>
                                @endforelse
                            </div>
                        @endif
                    @endif
                    @error('client_id') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label class="block mb-2">{{ __('رقم الهاتف') }}</label>
                    <input type="text" class="app-input w-full cursor-not-allowed opacity-70" value="{{ $selectedClient['phone'] ?? __('يتم جلبه تلقائياً') }}" readonly>
                </div>

                <div class="field">
                    <label class="block mb-2">{{ __('الرقم القومي') }}</label>
                    <input type="text" class="app-input w-full cursor-not-allowed opacity-70" value="{{ $selectedClient['nid'] ?? __('يتم جلبه تلقائياً') }}" readonly>
                </div>

                <div class="col-span-1 md:col-span-2 field">
                    <label class="block mb-2">{{ __('العنوان المسجل') }}</label>
                    <input type="text" class="app-input w-full cursor-not-allowed opacity-70" value="{{ $selectedClient['address'] ?? __('يتم جلبه تلقائياً') }}" readonly>
                </div>
            </div>
        </div>

        {{-- 2. Legal Team --}}
        <div class="panel p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b border-[var(--border-color)]">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('الفريق القانوني المسؤول') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2 field">
                    <label for="lawyer_id" class="block mb-2">
                        {{ __('المحامي الرئيسي') }} <span class="text-[var(--color-danger)]">*</span>
                    </label>
                    @if(auth()->user()?->role === 'admin')
                        <div class="select-wrap">
                            <select id="lawyer_id" wire:model="lawyer_id" class="app-select w-full">
                                <option value="">{{ __('اختر المحامي المسؤول...') }}</option>
                                @foreach ($lawyers as $lawyer)
                                    <option value="{{ $lawyer->id }}">{{ $lawyer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="text" class="app-input w-full cursor-not-allowed opacity-70" value="{{ auth()->user()->name }}" disabled>
                    @endif
                    @error('lawyer_id') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- 3. Case Details & Financials --}}
        <div class="panel p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b border-[var(--border-color)]">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    {{ __('بيانات القضية والماليات') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="field">
                    <label for="case_number" class="block mb-2">
                        {{ __('رقم القضية') }} <span class="text-[var(--color-danger)]">*</span>
                    </label>
                    <input type="text" id="case_number" wire:model="case_number" class="app-input w-full" placeholder="{{ __('مثال: 2525 أو 2026/123') }}">
                    @error('case_number') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label for="status" class="block mb-2">
                        {{ __('الحالة الحالية') }} <span class="text-[var(--color-danger)]">*</span>
                    </label>
                    <div class="select-wrap">
                        <select id="status" wire:model="status" class="app-select w-full">
                            <option value="{{ __('مفتوحة') }}">{{ __('مفتوحة') }}</option>
                            <option value="{{ __('متداولة') }}">{{ __('متداولة') }}</option>
                            <option value="{{ __('مؤجلة') }}">{{ __('مؤجلة') }}</option>
                            <option value="{{ __('محجوزة للحكم') }}">{{ __('محجوزة للحكم') }}</option>
                            <option value="{{ __('منتهية') }}">{{ __('منتهية') }}</option>
                            <option value="{{ __('مستأنفة') }}">{{ __('مستأنفة') }}</option>
                            <option value="{{ __('محفوظة') }}">{{ __('محفوظة') }}</option>
                            <option value="{{ __('معلقة') }}">{{ __('معلقة') }}</option>
                        </select>
                    </div>
                    @error('status') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label for="jurisdiction_id" class="block mb-2">
                        {{ __('نوع الجهة') }} <span class="text-[var(--color-danger)]">*</span>
                    </label>
                    <div class="select-wrap">
                        <select id="jurisdiction_id" wire:model.live="jurisdiction_id" class="app-select w-full">
                            <option value="">{{ __('اختر نوع الجهة...') }}</option>
                            @foreach ($jurisdictions as $j)
                                <option value="{{ $j->id }}">{{ __($j->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('jurisdiction_id') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label for="court_level_id" class="block mb-2">
                        {{ __('درجة التقاضي') }} <span class="text-[var(--color-danger)]">*</span>
                    </label>
                    <div class="select-wrap">
                        <select id="court_level_id" wire:model="court_level_id" class="app-select w-full">
                            <option value="">{{ __('اختر درجة التقاضي...') }}</option>
                            @foreach ($court_levels as $level)
                                <option value="{{ $level->id }}">{{ __($level->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('court_level_id') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label for="court_id" class="block mb-2">{{ __('المحكمة') }}</label>
                    <div class="select-wrap">
                        <select id="court_id" wire:model="court_id" class="app-select w-full" wire:key="court-{{ $jurisdiction_id }}">
                            <option value="">{{ __('اختر المحكمة...') }}</option>
                            @foreach ($courts as $court)
                                <option value="{{ $court->id }}">{{ __($court->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('court_id') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label for="circuit" class="block mb-2">{{ __('الدائرة') }}</label>
                    <input type="text" id="circuit" wire:model="circuit" class="app-input w-full" placeholder="{{ __('مثال: الدائرة الثالثة مدني') }}">
                    @error('circuit') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2 field">
                    <label for="Previous_procedure" class="block mb-2">{{ __('القرار السابق أو الموقف الحالي') }}</label>
                    <input type="text" id="Previous_procedure" wire:model="Previous_procedure" class="app-input w-full" placeholder="{{ __('مثال: تقديم مستندات، إعادة إعلان...') }}">
                    @error('Previous_procedure') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>
            </div>

            @if(auth()->user()?->isAdmin())
            <div class="mt-8 pt-8 border-t border-[var(--border-color)]">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <div class="field">
                        <label for="agreed_legal_fee" class="block mb-2">{{ __('أتعاب المحاماة المتفق عليها') }}</label>
                        <input type="number" id="agreed_legal_fee" wire:model="agreed_legal_fee" class="app-input w-full" min="0" placeholder="{{ __('مثال: 1500') }}">
                        @error('agreed_legal_fee') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="total_costs" class="block mb-2">{{ __('إجمالي الرسوم والتكاليف') }}</label>
                        <input type="number" id="total_costs" wire:model.live="total_costs" class="app-input w-full" min="0" placeholder="{{ __('مثال: 1000') }}">
                        @error('total_costs') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="deposit" class="block mb-2">{{ __('المدفوع مقدماً (عربون)') }}</label>
                        <input type="number" id="deposit" wire:model.live="deposit" class="app-input w-full" min="0" placeholder="{{ __('مثال: 300') }}">
                        @error('deposit') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                    </div>

                    <div class="field">
                        <label class="block mb-2">{{ __('المبلغ المتبقي') }}</label>
                        <input type="text" class="app-input w-full cursor-not-allowed opacity-70 font-bold" value="{{ max(0, ((float)($total_costs ?? 0)) - ((float)($deposit ?? 0))) }}" readonly>
                    </div>

                    <div class="md:col-span-2 field">
                        <label for="costs" class="block mb-2">{{ __('مصروفات إدارية') }}</label>
                        <input type="number" id="costs" wire:model="costs" class="app-input w-full" min="0" placeholder="{{ __('مثال: 50') }}">
                        @error('costs') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                    </div>
                </div>
            </div>
            @else
            <div class="mt-8 pt-8 border-t border-[var(--border-color)]">
                <div class="panel-subtle p-4 rounded-lg">
                    <p class="text-muted text-sm">{{ __('البيانات المالية تخص الإدارة فقط. سيتم تسجيل المبالغ كأصفار مؤقتاً حتى يقوم المسؤول بتعديلها.') }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- 4. Opponent and Case Subject --}}
        <div class="panel p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b border-[var(--border-color)]">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"/></svg>
                    {{ __('بيانات الخصم وموضوع القضية') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2 field">
                    <label for="description" class="block mb-2">
                        {{ __('ملخص وقائع القضية') }} <span class="text-[var(--color-danger)]">*</span>
                    </label>
                    <textarea id="description" wire:model.lazy="description" rows="4" class="app-input w-full resize-y" placeholder="{{ __('شرح مفصل للوقائع...') }}"></textarea>
                    @error('description') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label for="rival_name" class="block mb-2">
                        {{ __('اسم الخصم بالكامل') }} <span class="text-[var(--color-danger)]">*</span>
                    </label>
                    <input type="text" id="rival_name" wire:model="rival_name" class="app-input w-full">
                    @error('rival_name') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label for="rival_number" class="block mb-2">
                        {{ __('رقم هاتف الخصم') }} <span class="text-[var(--color-danger)]">*</span>
                    </label>
                    <input type="text" id="rival_number" wire:model="rival_number" class="app-input w-full">
                    @error('rival_number') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label for="rival_nid" class="block mb-2">
                        {{ __('الرقم القومي للخصم') }} <span class="text-[var(--color-danger)]">*</span>
                    </label>
                    <input type="text" id="rival_nid" wire:model="rival_nid" maxlength="14" class="app-input w-full">
                    @error('rival_nid') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label for="rival_address" class="block mb-2">
                        {{ __('عنوان الخصم') }} <span class="text-[var(--color-danger)]">*</span>
                    </label>
                    <input type="text" id="rival_address" wire:model="rival_address" class="app-input w-full">
                    @error('rival_address') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- 5. Attachments --}}
        <div class="panel p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b border-[var(--border-color)]">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    {{ __('المرفقات والتوكيلات') }}
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 mb-6">
                <div class="field">
                    <label for="procuration" class="block mb-2">{{ __('بيانات التوكيل أو رقمه') }}</label>
                    <input type="text" id="procuration" wire:model="procuration" class="app-input w-full" placeholder="{{ __('مثال: توكيل رقم 1234') }}">
                    @error('procuration') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label for="final_decision" class="block mb-2">{{ __('القرار النهائي (في حال الانتهاء)') }}</label>
                    <input type="text" id="final_decision" wire:model="final_decision" class="app-input w-full" placeholder="{{ __('مثال: تم قبول الدعوى...') }}">
                    @error('final_decision') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label for="notes" class="block mb-2">{{ __('ملاحظات إضافية') }}</label>
                    <textarea id="notes" wire:model.lazy="notes" rows="3" class="app-input w-full resize-y" placeholder="{{ __('أي تفاصيل أخرى...') }}"></textarea>
                    @error('notes') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>
            </div>

            {{-- Livewire File Upload --}}
            @if($case_file)
                <div class="flex items-center gap-3 p-4 border border-[var(--border-color)] rounded-lg bg-[var(--bg-subtle)] w-full">
                    <svg class="w-6 h-6 text-[var(--color-success)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    <span class="font-semibold flex-1">{{ $case_file->getClientOriginalName() }}</span>
                    <button wire:click="$set('case_file', null)" type="button" class="text-sm text-[var(--color-danger)] hover:underline">
                        {{ __('إزالة') }}
                    </button>
                </div>
            @else
                <label for="case_file" class="panel-subtle cursor-pointer text-center border-dashed border-2 block py-8 hover:border-[var(--color-gold)] transition-colors border-[var(--border-color)] rounded-lg">
                    <svg class="w-8 h-8 mx-auto mb-2 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <p class="font-bold mb-1">{{ __('اضغط لرفع التوكيل (PDF, Word, Images)') }}</p>
                    <span class="text-sm text-muted">{{ __('الحد الأقصى 2MB') }}</span>
                    <input wire:model="case_file" id="case_file" type="file" class="hidden" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                </label>
                <div wire:loading wire:target="case_file" class="text-muted text-sm mt-2 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    {{ __('جاري رفع الملف...') }}
                </div>
            @endif
            @error('case_file') <div class="text-xs mt-2 font-semibold text-center text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-[var(--border-color)]">
            <button type="button" wire:click="resetForm" class="text-sm text-muted hover:text-[var(--color-danger)] me-auto">
                {{ __('إفراغ الحقول') }}
            </button>
            <a href="{{ route('cases.index') }}" wire:navigate class="btn-secondary px-6 py-2.5">
                {{ __('إلغاء') }}
            </a>
            <button type="submit" class="btn-primary inline-flex items-center gap-2 px-8 py-2.5" wire:loading.attr="disabled" wire:target="save">
                <svg class="w-5 h-5" wire:loading.remove wire:target="save" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                <svg class="w-5 h-5 animate-spin hidden" wire:loading.class.remove="hidden" wire:target="save" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span wire:loading.remove wire:target="save">{{ __('حفظ القضية في النظام') }}</span>
                <span wire:loading wire:target="save">{{ __('جاري الحفظ...') }}</span>
            </button>
        </div>

    </form>

</div>
