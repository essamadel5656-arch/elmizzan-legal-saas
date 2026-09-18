<div class="p-4 md:p-8 max-w-5xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <!-- Header -->
    <div class="panel-header mb-8 flex justify-between items-start flex-wrap gap-4">
        <div>
            <h1 class="panel-title flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                {{ __('تعديل بيانات القضية') }}
            </h1>
            <p class="text-muted mt-2">
                {{ __('تعديل ملف القضية رقم:') }} <strong dir="ltr">{{ $case->case_number }}</strong>
            </p>
        </div>
        <a href="{{ route('cases.show', $case->id) }}" wire:navigate class="btn-secondary flex items-center gap-2 px-6 py-2.5">
            <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('رجوع لملف القضية') }}
        </a>
    </div>

    @if($errors->any())
        <div class="panel-subtle mb-6 p-6 border border-[var(--color-danger)] rounded-2xl">
            <strong class="flex items-center gap-2 text-[var(--color-danger)] font-bold mb-3 text-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ __('يرجى مراجعة الأخطاء التالية:') }}
            </strong>
            <ul class="list-disc mx-5 text-[var(--color-danger)] font-medium space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ __($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">

        {{-- 1. Basic Case Details --}}
        <div class="panel p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b border-[var(--border-color)]">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    {{ __('بيانات القضية الأساسية') }}
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="field">
                    <label class="block mb-2">{{ __('رقم القضية') }} <span class="text-[var(--color-danger)]">*</span></label>
                    <input type="text" wire:model="case_number" class="app-input w-full" required>
                    @error('case_number') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label class="block mb-2">{{ __('حالة القضية') }} <span class="text-[var(--color-danger)]">*</span></label>
                    <div class="select-wrap">
                        <select wire:model="status" class="app-select w-full" required>
                            <option value="">{{ __('-- اختر حالة القضية --') }}</option>
                            @foreach(['مفتوحة', 'متداولة', 'مؤجلة', 'محجوزة للحكم', 'منتهية', 'مستأنفة', 'محفوظة', 'معلقة'] as $s)
                                <option value="{{ $s }}">{{ __($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('status') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label class="block mb-2">{{ __('نوع الجهة') }} <span class="text-[var(--color-danger)]">*</span></label>
                    <div class="select-wrap">
                        <select wire:model.live="jurisdiction_id" class="app-select w-full" required>
                            <option value="">{{ __('-- اختر نوع الجهة --') }}</option>
                            @foreach($jurisdictions as $jurisdiction)
                                <option value="{{ $jurisdiction->id }}">{{ __($jurisdiction->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('jurisdiction_id') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label class="block mb-2">{{ __('المحكمة') }}</label>
                    <div class="select-wrap">
                        <select wire:model="court_id" class="app-select w-full" wire:key="court-{{ $jurisdiction_id }}">
                            <option value="">{{ __('-- اختر المحكمة --') }}</option>
                            @foreach($courts as $court)
                                <option value="{{ $court->id }}">{{ __($court->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('court_id') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label class="block mb-2">{{ __('نوع الهيئة القضائية') }}</label>
                    <input type="text" wire:model="judicial_authority_type" class="app-input w-full" placeholder="{{ __('اختياري') }}">
                    @error('judicial_authority_type') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label class="block mb-2">{{ __('الدائرة') }}</label>
                    <input type="text" wire:model="circuit" class="app-input w-full" placeholder="{{ __('مثال: الدائرة الأولى') }}">
                    @error('circuit') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2 field">
                    <label class="block mb-2">{{ __('درجة التقاضي') }} <span class="text-[var(--color-danger)]">*</span></label>
                    <div class="select-wrap">
                        <select wire:model="court_level_id" class="app-select w-full" required>
                            <option value="">{{ __('-- اختر درجة التقاضي --') }}</option>
                            @foreach($court_levels as $level)
                                <option value="{{ $level->id }}">{{ __($level->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('court_level_id') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- 2. Opponent Details and Summary --}}
        <div class="panel p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b border-[var(--border-color)]">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"/></svg>
                    {{ __('بيانات الخصم وموضوع القضية') }}
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2 field">
                    <label class="block mb-2">{{ __('ملخص وقائع القضية') }} <span class="text-[var(--color-danger)]">*</span></label>
                    <textarea wire:model="description" rows="3" class="app-input w-full resize-y" placeholder="{{ __('أدخل تفاصيل القضية...') }}"></textarea>
                    @error('description') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label class="block mb-2">{{ __('اسم الخصم') }} <span class="text-[var(--color-danger)]">*</span></label>
                    <input type="text" wire:model="rival_name" class="app-input w-full" required>
                    @error('rival_name') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label class="block mb-2">{{ __('رقم الخصم') }} <span class="text-[var(--color-danger)]">*</span></label>
                    <input type="text" wire:model="rival_number" class="app-input w-full" required>
                    @error('rival_number') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label class="block mb-2">{{ __('الرقم القومي للخصم') }} <span class="text-[var(--color-danger)]">*</span></label>
                    <input type="text" wire:model="rival_nid" class="app-input w-full" required>
                    @error('rival_nid') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label class="block mb-2">{{ __('عنوان الخصم') }} <span class="text-[var(--color-danger)]">*</span></label>
                    <input type="text" wire:model="rival_address" class="app-input w-full" required>
                    @error('rival_address') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- 3. Financial Data (Admin Only) --}}
        @if(auth()->user()?->isAdmin())
        <div class="panel p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b border-[var(--border-color)]">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('البيانات المالية (للمدير)') }}
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div class="field">
                    <label class="block mb-2">{{ __('أتعاب المحاماة المتفق عليها') }}</label>
                    <input type="number" wire:model="agreed_legal_fee" class="app-input w-full" step="0.01" placeholder="{{ __('أتعاب القضية المعتمدة') }}">
                    @error('agreed_legal_fee') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label class="block mb-2">{{ __('إجمالي الرسوم والتكاليف') }}</label>
                    <input type="number" wire:model.live="total_costs" class="app-input w-full" step="0.01">
                    @error('total_costs') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label class="block mb-2">{{ __('المدفوع مقدماً (عربون)') }}</label>
                    <input type="number" wire:model.live="deposit" class="app-input w-full" step="0.01">
                    @error('deposit') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="field">
                    <label class="block mb-2">{{ __('المبلغ المتبقي') }}</label>
                    <input type="text" class="app-input w-full cursor-not-allowed opacity-70 font-bold" value="{{ max(0, ((float)($total_costs ?? 0)) - ((float)($deposit ?? 0))) }}" readonly>
                </div>

                <div class="md:col-span-2 field">
                    <label class="block mb-2">{{ __('المصروفات الإدارية') }}</label>
                    <input type="number" wire:model="costs" class="app-input w-full" step="0.01">
                    @error('costs') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>
            </div>
        </div>
        @else
        <div class="panel p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b border-[var(--border-color)]">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('البيانات المالية تخص الإدارة') }}
                </div>
            </div>
            <div class="panel-subtle p-4 rounded-lg border border-[var(--border-color)]">
                <p class="text-muted text-sm">{{ __('البيانات المالية تخص الإدارة فقط. لا يمكنك التعديل عليها.') }}</p>
            </div>
        </div>
        @endif

        {{-- 4. Linked Clients --}}
        <div class="panel p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b border-[var(--border-color)]">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    {{ __('الموكلين المرتبطين بالقضية') }}
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-[280px] overflow-y-auto p-4 panel-subtle border border-[var(--border-color)] rounded-xl">
                @foreach($clients as $client)
                    @php $isClientSelected = in_array((int)$client->id, array_map('intval', $client_ids)); @endphp
                    
                    <label for="client-{{ $client->id }}" class="flex flex-col gap-2 p-3 panel bg-[var(--bg-panel)] border {{ $isClientSelected ? 'border-[var(--color-success)]' : 'border-[var(--border-color)]' }} rounded-xl transition-all cursor-pointer shadow-sm">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="client-{{ $client->id }}" wire:model.live="client_ids" value="{{ $client->id }}" class="w-4 h-4 cursor-pointer accent-[var(--color-success)]">
                            <span class="font-bold text-sm select-none">{{ $client->name }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('client_ids') <div class="text-xs mt-2 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
        </div>

        {{-- 5. Linked Lawyers --}}
        <div class="panel p-6 md:p-8">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-[var(--border-color)]">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ __('المحامين المرتبطين بالقضية') }}
                </div>
            </div>
            
            <div class="text-sm font-bold mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                {{ __('حدد المحامين ودور كل منهم في هذه القضية:') }}
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-[350px] overflow-y-auto p-4 panel-subtle border border-[var(--border-color)] rounded-xl">
                @foreach($lawyers as $lawyer)
                    @php
                        $isSelected = in_array((int)$lawyer->id, array_map('intval', $lawyer_ids));
                    @endphp

                    <div class="flex flex-col gap-2 p-3 panel bg-[var(--bg-panel)] border {{ $isSelected ? 'border-[var(--color-success)]' : 'border-[var(--border-color)]' }} rounded-xl transition-all shadow-sm">
                        <label for="lawyer-{{ $lawyer->id }}" class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="lawyer-{{ $lawyer->id }}" wire:model.live="lawyer_ids" value="{{ $lawyer->id }}" class="w-4 h-4 cursor-pointer accent-[var(--color-success)]">
                            <div class="flex flex-col select-none">
                                <span class="font-bold text-sm">{{ $lawyer->name }}</span>
                                @if($lawyer->specialization)
                                    <span class="text-xs text-muted font-medium">{{ __($lawyer->specialization) }}</span>
                                @endif
                            </div>
                        </label>
                        
                        @if($isSelected)
                            <div class="select-wrap mt-2">
                                <select wire:model="lawyer_roles.{{ $lawyer->id }}" class="app-select w-full text-xs py-1">
                                    <option value="lead">{{ __('محامي رئيسي') }}</option>
                                    <option value="assistant">{{ __('مساعد') }}</option>
                                    <option value="consultant">{{ __('مستشار') }}</option>
                                </select>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            @error('lawyer_ids') <div class="text-xs mt-2 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
        </div>

        {{-- 6. Attachments & Misc --}}
        <div class="panel p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b border-[var(--border-color)]">
                <div class="panel-title flex items-center gap-3 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    {{ __('المرفقات وملاحظات إضافية') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="col-span-1 md:col-span-2 field">
                    <label class="block mb-2">{{ __('القرار السابق') }}</label>
                    <textarea wire:model="Previous_procedure" rows="2" class="app-input w-full resize-y" placeholder="{{ __('أدخل الإجراء السابق...') }}"></textarea>
                    @error('Previous_procedure') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2 field">
                    <label class="block mb-2">{{ __('القرار النهائي') }}</label>
                    <textarea wire:model="final_decision" rows="2" class="app-input w-full resize-y" placeholder="{{ __('أدخل القرار النهائي...') }}"></textarea>
                    @error('final_decision') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2 field">
                    <label class="block mb-2">{{ __('ملاحظات إضافية') }}</label>
                    <textarea wire:model="notes" rows="2" class="app-input w-full resize-y" placeholder="{{ __('أي تفاصيل أخرى...') }}"></textarea>
                    @error('notes') <div class="text-xs mt-1 font-semibold text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
                </div>
            </div>

            {{-- File Upload --}}
            @if($case_file)
                <div class="flex items-center gap-3 p-4 border border-[var(--border-color)] rounded-lg bg-[var(--bg-subtle)] w-full">
                    <svg class="w-6 h-6 text-[var(--color-success)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    <span class="font-semibold flex-1">{{ is_string($case_file) ? basename($case_file) : $case_file->getClientOriginalName() }}</span>
                    <button wire:click="$set('case_file', null)" type="button" class="text-sm text-[var(--color-danger)] hover:underline">
                        {{ __('إزالة') }}
                    </button>
                </div>
            @else
                <label for="case_file" class="panel-subtle cursor-pointer text-center border-dashed border-2 block py-8 hover:border-[var(--color-gold)] transition-colors border-[var(--border-color)] rounded-lg">
                    <svg class="w-8 h-8 mx-auto mb-2 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <p class="font-bold mb-1">{{ __('انقر لرفع المرفقات (PDF, Word, Images)') }}</p>
                    <span class="text-sm text-muted">{{ __('الحد الأقصى 2MB') }}</span>
                    <input wire:model="case_file" id="case_file" type="file" class="hidden" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                </label>
                <div wire:loading wire:target="case_file" class="text-muted text-sm mt-2 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    {{ __('جاري رفع ومعالجة الملف...') }}
                </div>
            @endif
            @if(count($mediaGallery) > 0 && !$case_file)
                <div class="mt-4 p-4 border border-[var(--color-success)] rounded-xl flex items-center justify-between text-sm bg-[var(--color-success)]/10">
                    <span class="flex items-center gap-2 font-bold text-[var(--color-success)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('يوجد مرفقات بالقضية') }} ({{ count($mediaGallery) }})
                    </span>
                    <button type="button" wire:click="openMediaViewer(0)" class="font-bold text-[var(--color-gold)] hover:underline flex items-center gap-1">
                        {{ __('عرض المرفقات') }}
                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            @endif
            @error('case_file') <div class="text-xs mt-2 font-semibold text-center text-[var(--color-danger)]">{{ __($message) }}</div> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-[var(--border-color)]">
            <a href="{{ route('cases.show', $case->id) }}" wire:navigate class="btn-secondary px-6 py-2.5 whitespace-nowrap">
                <svg class="w-5 h-5 inline-block mr-1 rtl:ml-1 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ __('إلغاء وتجاهل') }}
            </a>
            <button type="submit" class="btn-primary inline-flex items-center gap-2 px-8 py-2.5" wire:loading.attr="disabled" wire:target="save">
                <svg class="w-5 h-5" wire:loading.remove wire:target="save" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                <svg class="w-5 h-5 animate-spin hidden" wire:loading.class.remove="hidden" wire:target="save" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span wire:loading.remove wire:target="save">{{ __('حفظ كافة التعديلات') }}</span>
                <span wire:loading wire:target="save">{{ __('جاري الحفظ...') }}</span>
            </button>
        </div>

    </form>

    <!-- Smart Media Viewer Modal -->
    @if($isMediaViewerOpen && count($mediaGallery) > 0)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-5xl bg-[var(--bg-panel)] rounded-2xl shadow-2xl overflow-hidden flex flex-col h-[90vh]">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-[var(--border-color)] bg-[var(--bg-panel)]">
                <div class="flex items-center gap-3">
                    <span class="text-lg font-bold text-[var(--text-color)]">{{ $mediaGallery[$currentMediaIndex]['title'] }}</span>
                    <span class="text-sm px-3 py-1 rounded-full bg-[var(--bg-subtle)] border border-[var(--border-color)] text-muted">
                        {{ $currentMediaIndex + 1 }} / {{ count($mediaGallery) }}
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    @if(auth()->user()?->isAdmin())
                    <button type="button" wire:click="deleteCurrentMedia" onclick="confirm('{{ __('هل أنت متأكد من حذف هذا الملف نهائياً؟') }}') || event.stopImmediatePropagation()" class="btn-danger flex items-center gap-2 px-4 py-2 text-sm rounded-lg bg-[var(--color-danger)] text-white hover:bg-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        {{ __('حذف الملف') }}
                    </button>
                    @endif
                    <button type="button" wire:click="closeMediaViewer" class="p-2 rounded-full hover:bg-[var(--bg-subtle)] transition-colors">
                        <svg class="w-6 h-6 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-hidden flex items-center justify-center relative bg-[var(--bg-body)]">
                
                <!-- Left Nav Arrow -->
                @if($currentMediaIndex > 0)
                <button type="button" wire:click="prevMedia" class="absolute rtl:right-4 ltr:left-4 z-10 p-3 rounded-full bg-black/50 text-white hover:bg-[var(--color-gold)] transition-colors">
                    <svg class="w-6 h-6 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                @endif

                <!-- Media Display -->
                <div class="w-full h-full flex items-center justify-center p-4">
                    @php $media = $mediaGallery[$currentMediaIndex]; @endphp
                    
                    @if($media['type'] === 'image')
                        <img src="{{ $media['url'] }}" alt="{{ $media['title'] }}" class="max-w-full max-h-full object-contain rounded-lg shadow-lg">
                    @elseif($media['type'] === 'pdf')
                        <iframe src="{{ $media['url'] }}" class="w-full h-full rounded-lg shadow-lg border-0"></iframe>
                    @else
                        <!-- Word / Other Files -->
                        <div class="text-center p-8 bg-[var(--bg-panel)] rounded-2xl border border-[var(--border-color)] shadow-sm max-w-md w-full">
                            <svg class="w-20 h-20 mx-auto text-[var(--color-gold)] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <h3 class="text-xl font-bold mb-2">{{ __('لا يمكن معاينة هذا الملف') }}</h3>
                            <p class="text-muted mb-6">{{ __('صيغة الملف غير مدعومة للمعاينة المباشرة. يرجى تحميله لفتحه.') }}</p>
                            <a href="{{ $media['url'] }}" download class="btn-primary inline-flex items-center gap-2 px-6 py-2.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                {{ __('تحميل الملف') }}
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Right Nav Arrow -->
                @if($currentMediaIndex < count($mediaGallery) - 1)
                <button type="button" wire:click="nextMedia" class="absolute rtl:left-4 ltr:right-4 z-10 p-3 rounded-full bg-black/50 text-white hover:bg-[var(--color-gold)] transition-colors">
                    <svg class="w-6 h-6 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                @endif

            </div>
        </div>
    </div>
    @endif
</div>
