<div class="p-4 md:p-8 max-w-5xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <!-- Header -->
    <div class="flex flex-wrap justify-between items-start gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2 flex items-center gap-3">
                <i class="fas fa-folder-plus text-amber-500"></i> {{ __('Add New Case File') }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('Record case details, assigned client, opponent, and financials using interactive system') }}
            </p>
        </div>
        <a href="{{ route('cases.index') }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-2.5 bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-red-600 dark:hover:text-red-500 rounded-xl transition-colors font-bold whitespace-nowrap">
            <i class="fas fa-arrow-right rtl:rotate-180"></i> {{ __('Back to List') }}
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-6 bg-red-50/80 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl backdrop-blur-sm">
            <strong class="flex items-center gap-2 text-red-700 dark:text-red-400 font-bold mb-3 text-lg">
                <i class="fas fa-exclamation-triangle"></i> {{ __('Please review the following errors:') }}
            </strong>
            <ul class="list-disc mx-5 text-red-600 dark:text-red-400 font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ __($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit="save" novalidate class="space-y-6">

        {{-- 1. Client Details --}}
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                    <i class="fas fa-user-tie text-amber-500"></i> {{ __('Assigned Client Details') }}
                </div>
                <a href="{{ route('add-client') }}" wire:navigate class="inline-flex items-center gap-2 px-3 py-1.5 border border-amber-500 text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/10 hover:bg-amber-500 hover:text-white rounded-lg text-sm font-bold transition-colors" target="_blank">
                    <i class="fas fa-plus"></i> {{ __('New Client') }}
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label for="client_id" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Select Client') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="client_id" wire:model.live="client_id" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                        <option value="">{{ __('-- Select Client from List --') }}</option>
                        @foreach ($clients as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone ?: __('No Phone') }})</option>
                        @endforeach
                    </select>
                    @error('client_id') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                @php
                    $selectedClient = collect($clients)->firstWhere('id', $client_id);
                @endphp

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Phone Number') }}</label>
                    <input type="text" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-500 dark:text-slate-400 rounded-xl px-4 py-3 text-sm cursor-not-allowed border-dashed" value="{{ $selectedClient->phone ?? __('Will be fetched automatically') }}" readonly>
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('National ID') }}</label>
                    <input type="text" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-500 dark:text-slate-400 rounded-xl px-4 py-3 text-sm cursor-not-allowed border-dashed" value="{{ $selectedClient->nid ?? __('Will be fetched automatically') }}" readonly>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Registered Address') }}</label>
                    <input type="text" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-500 dark:text-slate-400 rounded-xl px-4 py-3 text-sm cursor-not-allowed border-dashed" value="{{ $selectedClient->address ?? __('Will be fetched automatically') }}" readonly>
                </div>
            </div>
        </div>

        {{-- 2. Legal Team --}}
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                    <i class="fas fa-users-cog text-amber-500"></i> {{ __('Responsible Legal Team') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label for="lawyer_id" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Lead Lawyer') }} <span class="text-red-500">*</span>
                    </label>
                    @if(auth()->user()->role === 'admin')
                        <select id="lawyer_id" wire:model.live="lawyer_id" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                            <option value="">{{ __('Select Responsible Lawyer...') }}</option>
                            @foreach ($lawyers as $lawyer)
                                <option value="{{ $lawyer->id }}">{{ $lawyer->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-500 dark:text-slate-400 rounded-xl px-4 py-3 text-sm cursor-not-allowed border-dashed" value="{{ auth()->user()->name }}" disabled>
                    @endif
                    @error('lawyer_id') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- 3. Case Details & Financials --}}
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                    <i class="fas fa-gavel text-amber-500"></i> {{ __('Case Details and Financials') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="case_number" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Case Number') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="case_number" wire:model="case_number" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" placeholder="{{ __('e.g. 2525 or 2026/123') }}">
                    @error('case_number') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label for="status" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Current Status') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="status" wire:model="status" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                        <option value="مفتوحة">{{ __('Open') }}</option>
                        <option value="متداولة">{{ __('Ongoing') }}</option>
                        <option value="مؤجلة">{{ __('Postponed') }}</option>
                        <option value="محجوزة للحكم">{{ __('Reserved for Judgment') }}</option>
                        <option value="منتهية">{{ __('Finished') }}</option>
                        <option value="مستأنفة">{{ __('Appealed') }}</option>
                        <option value="محفوظة">{{ __('Archived') }}</option>
                        <option value="معلقة">{{ __('Suspended') }}</option>
                    </select>
                </div>

                <div>
                    <label for="jurisdiction_id" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Jurisdiction') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="jurisdiction_id" wire:model.live="jurisdiction_id" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                        <option value="">{{ __('Select Jurisdiction...') }}</option>
                        @foreach ($jurisdictions as $j)
                            <option value="{{ $j->id }}">{{ __($j->name) }}</option>
                        @endforeach
                    </select>
                    @error('jurisdiction_id') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label for="court_level_id" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Court Level') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="court_level_id" wire:model="court_level_id" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                        <option value="">{{ __('Select Court Level...') }}</option>
                        @foreach ($court_levels as $level)
                            <option value="{{ $level->id }}">{{ __($level->name) }}</option>
                        @endforeach
                    </select>
                    @error('court_level_id') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label for="court_id" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Court') }}</label>
                    <select id="court_id" wire:model="court_id" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                        <option value="">{{ __('Select Court...') }}</option>
                        @foreach ($courts as $court)
                            <option value="{{ $court->id }}">{{ __($court->name) }}</option>
                        @endforeach
                    </select>
                    @error('court_id') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label for="circuit" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Circuit') }}</label>
                    <input type="text" id="circuit" wire:model="circuit" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" placeholder="{{ __('e.g. Third Civil Circuit') }}">
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label for="Previous_procedure" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Previous Procedure or Current Status') }}</label>
                    <input type="text" id="Previous_procedure" wire:model="Previous_procedure" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" placeholder="{{ __('e.g. Submitted documents, Re-announcement...') }}">
                </div>
            </div>

            @if(auth()->user()?->isAdmin())
            <div class="mt-8 pt-8 border-t border-slate-200 border-dashed dark:border-slate-800">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <div>
                        <label for="agreed_legal_fee" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Agreed Legal Fee (Official)') }}</label>
                        <input type="number" id="agreed_legal_fee" wire:model="agreed_legal_fee" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" min="0" placeholder="{{ __('e.g. 1500') }}">
                    </div>

                    <div>
                        <label for="total_costs" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Total Costs') }}</label>
                        <input type="number" id="total_costs" wire:model.live="total_costs" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" min="0" placeholder="{{ __('e.g. 1000') }}">
                    </div>

                    <div>
                        <label for="deposit" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Deposit Paid') }}</label>
                        <input type="number" id="deposit" wire:model.live="deposit" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" min="0" placeholder="{{ __('e.g. 300') }}">
                    </div>

                    <div>
                        <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Remaining Amount') }}</label>
                        <input type="text" class="w-full bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-900/30 text-red-600 dark:text-red-400 font-bold rounded-xl px-4 py-3 text-sm cursor-not-allowed" value="{{ max(0, ((float)($total_costs ?: 0)) - ((float)($deposit ?: 0))) }}" readonly>
                    </div>

                    <div class="md:col-span-2">
                        <label for="costs" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Administrative Expenses and Fees') }}</label>
                        <input type="number" id="costs" wire:model="costs" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" min="0" placeholder="{{ __('e.g. 50') }}">
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- 4. Opponent and Case Subject --}}
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                    <i class="fas fa-user-shield text-amber-500"></i> {{ __('Opponent and Case Subject') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label for="description" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Summary of Case Facts') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" wire:model="description" rows="4" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 resize-y" placeholder="{{ __('Detailed explanation of the facts...') }}"></textarea>
                    @error('description') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label for="rival_name" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Full Opponent Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="rival_name" wire:model="rival_name" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                    @error('rival_name') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label for="rival_number" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Opponent Phone Number') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="rival_number" wire:model="rival_number" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                    @error('rival_number') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label for="rival_nid" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Opponent National ID') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="rival_nid" wire:model="rival_nid" maxlength="14" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                    @error('rival_nid') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label for="rival_address" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Opponent Address') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="rival_address" wire:model="rival_address" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                    @error('rival_address') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- 5. Attachments --}}
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                    <i class="fas fa-paperclip text-amber-500"></i> {{ __('Attachments and Procurations') }}
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 mb-6">
                <div>
                    <label for="procuration" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Procuration Data or Number') }}</label>
                    <input type="text" id="procuration" wire:model="procuration" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" placeholder="{{ __('e.g. Procuration No. 1234') }}">
                </div>

                <div>
                    <label for="final_decision" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Final Decision (if finished)') }}</label>
                    <input type="text" id="final_decision" wire:model="final_decision" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" placeholder="{{ __('e.g. Case accepted...') }}">
                </div>

                <div>
                    <label for="notes" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Additional Notes') }}</label>
                    <textarea id="notes" wire:model="notes" rows="3" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 resize-y" placeholder="{{ __('Any other details...') }}"></textarea>
                </div>
            </div>

            {{-- Livewire File Upload --}}
            <div class="border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-2xl p-10 text-center bg-slate-50 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 hover:border-amber-500 dark:hover:border-amber-500 transition-all cursor-pointer group" onclick="document.getElementById('livewire_case_file').click()">
                @if ($case_file)
                    <div class="flex flex-col items-center gap-3 text-green-600 dark:text-green-400">
                        <i class="fas fa-check-circle text-5xl"></i>
                        <p class="font-bold text-lg m-0">{{ __('File Ready:') }} {{ $case_file->getClientOriginalName() }}</p>
                        <span class="text-sm text-slate-500 dark:text-slate-400">{{ __('Click to change attached file') }}</span>
                    </div>
                @else
                    <i class="fas fa-cloud-upload-alt text-5xl text-slate-400 dark:text-slate-500 group-hover:text-amber-500 transition-colors mb-4"></i>
                    <p class="text-slate-600 dark:text-slate-300 font-bold mb-1">{{ __('Click to upload attachments (PDF, Word, Images)') }}</p>
                    <span class="text-sm text-slate-500 dark:text-slate-400">{{ __('Maximum 2MB') }}</span>
                @endif
                <div wire:loading wire:target="case_file" class="mt-4 text-amber-600 dark:text-amber-400 font-bold">
                    <i class="fas fa-spinner fa-spin me-2"></i> {{ __('Uploading and processing file...') }}
                </div>
            </div>
            <input type="file" id="livewire_case_file" wire:model="case_file" class="hidden" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
            @error('case_file') <div class="text-xs text-red-500 mt-2 font-semibold text-center">{{ __($message) }}</div> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('cases.index') }}" wire:navigate class="px-6 py-3 bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all font-bold">
                {{ __('Cancel') }}
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-slate-900 dark:bg-amber-600 hover:bg-slate-800 dark:hover:bg-amber-700 text-white font-bold rounded-xl shadow-lg shadow-slate-900/20 dark:shadow-amber-600/20 transition-all hover:-translate-y-0.5 focus:ring-4 focus:ring-amber-500/40 disabled:opacity-70 disabled:cursor-not-allowed" wire:loading.attr="disabled">
                <i class="fas fa-save" wire:loading.remove></i>
                <i class="fas fa-spinner fa-spin" wire:loading></i>
                <span wire:loading.remove>{{ __('Save Case in System') }}</span>
                <span wire:loading>{{ __('Saving...') }}</span>
            </button>
        </div>

    </form>

</div>
