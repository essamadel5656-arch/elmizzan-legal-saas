<div class="p-4 md:p-8 max-w-5xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <!-- Header -->
    <div class="flex flex-wrap justify-between items-start gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2 flex items-center gap-3">
                <i class="fas fa-edit text-amber-500"></i> {{ __('Edit Case Details') }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('Updating case file number:') }} <strong class="text-slate-900 dark:text-amber-500">{{ $case->case_number }}</strong>
            </p>
        </div>
        <a href="{{ route('cases.show', $case->id) }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-2.5 bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-red-600 dark:hover:text-red-500 rounded-xl transition-colors font-bold whitespace-nowrap">
            <i class="fas fa-arrow-right rtl:rotate-180"></i> {{ __('Back to Case File') }}
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 p-6 bg-red-50/80 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl backdrop-blur-sm">
            <strong class="flex items-center gap-2 text-red-700 dark:text-red-400 font-bold mb-3 text-lg">
                <i class="fas fa-exclamation-triangle"></i> {{ __('An error occurred in the data:') }}
            </strong>
            <ul class="list-disc mx-5 text-red-600 dark:text-red-400 font-medium space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ __($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">

        {{-- 1. Basic Case Details --}}
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                    <i class="fas fa-file-alt text-amber-500"></i> {{ __('Basic Case Details') }}
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Case Number') }} <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="case_number" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" required>
                    @error('case_number') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Case Status') }} <span class="text-red-500">*</span></label>
                    <select wire:model="status" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" required>
                        <option value="">{{ __('-- Select Case Status --') }}</option>
                        @foreach(['مفتوحة', 'متداولة', 'مؤجلة', 'محجوزة للحكم', 'منتهية', 'مستأنفة', 'محفوظة', 'معلقة'] as $s)
                            <option value="{{ $s }}">{{ __($s) }}</option>
                        @endforeach
                    </select>
                    @error('status') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Jurisdiction') }} <span class="text-red-500">*</span></label>
                    <select wire:model.live="jurisdiction_id" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" required>
                        <option value="">{{ __('-- Select Jurisdiction --') }}</option>
                        @foreach($jurisdictions as $jurisdiction)
                            <option value="{{ $jurisdiction->id }}">{{ __($jurisdiction->name) }}</option>
                        @endforeach
                    </select>
                    @error('jurisdiction_id') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Court') }} <span class="text-red-500">*</span></label>
                    <select wire:model="court_id" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" required>
                        <option value="">{{ __('-- Select Court --') }}</option>
                        @foreach($courts as $court)
                            <option value="{{ $court->id }}">{{ __($court->name) }}</option>
                        @endforeach
                    </select>
                    @error('court_id') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Judicial Authority Type') }}</label>
                    <input type="text" wire:model="judicial_authority_type" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" placeholder="{{ __('Optional') }}">
                    @error('judicial_authority_type') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Circuit') }}</label>
                    <input type="text" wire:model="circuit" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" placeholder="{{ __('e.g. First Circuit') }}">
                    @error('circuit') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Court Level') }} <span class="text-red-500">*</span></label>
                    <select wire:model="court_level_id" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" required>
                        <option value="">{{ __('-- Select Court Level --') }}</option>
                        @foreach($court_levels as $level)
                            <option value="{{ $level->id }}">{{ __($level->name) }}</option>
                        @endforeach
                    </select>
                    @error('court_level_id') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Case Description') }}</label>
                    <textarea wire:model="description" rows="3" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 resize-y"></textarea>
                    @error('description') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Previous Procedure') }}</label>
                    <textarea wire:model="Previous_procedure" rows="2" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 resize-y"></textarea>
                    @error('Previous_procedure') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Final Decision') }}</label>
                    <textarea wire:model="final_decision" rows="2" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 resize-y"></textarea>
                    @error('final_decision') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Notes') }}</label>
                    <textarea wire:model="notes" rows="2" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 resize-y"></textarea>
                    @error('notes') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Case File / Attachment') }}</label>
                    <input type="file" wire:model="case_file" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 dark:file:bg-amber-900/30 dark:file:text-amber-400" accept=".pdf,.doc,.docx,.png,.jpg">
                    <div wire:loading wire:target="case_file" class="text-sm text-amber-500 mt-2 font-bold">
                        <i class="fas fa-spinner fa-spin"></i> {{ __('Uploading file...') }}
                    </div>
                    @if($case->case_file)
                        <div class="mt-3 p-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl flex items-center justify-between text-sm">
                            <span class="text-slate-700 dark:text-slate-300"><i class="fas fa-paperclip text-amber-500"></i> {{ __('A file is currently attached') }}</span>
                            <a href="{{ asset('storage/' . $case->case_file) }}" target="_blank" class="text-amber-600 dark:text-amber-400 font-bold hover:underline">{{ __('View Current File') }}</a>
                        </div>
                    @endif
                    @error('case_file') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- 2. Opponent Details --}}
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                    <i class="fas fa-user-times text-amber-500"></i> {{ __('Opponent Details') }}
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Opponent Name') }} <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="rival_name" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" required>
                    @error('rival_name') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Opponent Number') }} <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="rival_number" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" required>
                    @error('rival_number') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Opponent National ID') }} <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="rival_nid" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" required>
                    @error('rival_nid') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Opponent Address') }} <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="rival_address" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" required>
                    @error('rival_address') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- 3. Financial Data (Admin Only) --}}
        @if(auth()->user()?->isAdmin())
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                    <i class="fas fa-coins text-amber-500"></i> {{ __('Financial Data (Admin)') }}
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Agreed Legal Fee (Official)') }}</label>
                    <input type="number" wire:model="agreed_legal_fee" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" step="0.01" placeholder="{{ __('Approved case fees') }}">
                    @error('agreed_legal_fee') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Total Costs (Recorded)') }}</label>
                    <input type="number" wire:model="total_costs" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" step="0.01">
                    @error('total_costs') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Amount Paid (Deposit)') }}</label>
                    <input type="number" wire:model="deposit" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" step="0.01">
                    @error('deposit') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Administrative Expenses') }}</label>
                    <input type="number" wire:model="costs" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" step="0.01">
                    @error('costs') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>
            </div>
        </div>
        @endif

        {{-- 4. Linked Clients --}}
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                    <i class="fas fa-users text-amber-500"></i> {{ __('Clients Linked to Case') }}
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-[280px] overflow-y-auto p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl">
                @foreach($clients as $client)
                    @php $isClientSelected = in_array((int)$client->id, array_map('intval', $client_ids)); @endphp
                    
                    <label for="client-{{ $client->id }}" class="flex flex-col gap-2 p-3 bg-white dark:bg-slate-800 border {{ $isClientSelected ? 'border-green-500 bg-green-50/50 dark:bg-green-900/20' : 'border-slate-200 dark:border-slate-700 hover:border-amber-500 dark:hover:border-amber-500' }} rounded-xl transition-all cursor-pointer">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="client-{{ $client->id }}" wire:model.live="client_ids" value="{{ $client->id }}" class="w-4 h-4 text-green-600 focus:ring-green-500 border-gray-300 rounded cursor-pointer">
                            <span class="font-bold text-sm text-slate-900 dark:text-slate-100 select-none">{{ $client->name }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('client_ids') <div class="text-xs text-red-500 mt-2 font-semibold">{{ __($message) }}</div> @enderror
        </div>

        {{-- 5. Linked Lawyers --}}
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
            <div class="flex justify-between items-center mb-4 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                    <i class="fas fa-user-tie text-amber-500"></i> {{ __('Lawyers Linked to Case') }}
                </div>
            </div>
            
            <div class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-2">
                <i class="fas fa-star text-amber-500"></i> {{ __('Select lawyers and their roles in this case:') }}
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-[350px] overflow-y-auto p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl">
                @foreach($lawyers as $lawyer)
                    @php
                        $isSelected = in_array((int)$lawyer->id, array_map('intval', $lawyer_ids));
                    @endphp

                    <div class="flex flex-col gap-2 p-3 bg-white dark:bg-slate-800 border {{ $isSelected ? 'border-green-500 bg-green-50/50 dark:bg-green-900/20' : 'border-slate-200 dark:border-slate-700 hover:border-amber-500 dark:hover:border-amber-500' }} rounded-xl transition-all">
                        <label for="lawyer-{{ $lawyer->id }}" class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="lawyer-{{ $lawyer->id }}" wire:model.live="lawyer_ids" value="{{ $lawyer->id }}" class="w-4 h-4 text-green-600 focus:ring-green-500 border-gray-300 rounded cursor-pointer">
                            <div class="flex flex-col select-none">
                                <span class="font-bold text-sm text-slate-900 dark:text-slate-100">{{ $lawyer->name }}</span>
                                @if($lawyer->specialization)
                                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ $lawyer->specialization }}</span>
                                @endif
                            </div>
                        </label>
                        
                        @if($isSelected)
                            <select wire:model="lawyer_roles.{{ $lawyer->id }}" class="mt-2 w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-xs px-2 py-2">
                                <option value="lead">{{ __('Lead Lawyer') }}</option>
                                <option value="assistant">{{ __('Assistant') }}</option>
                                <option value="consultant">{{ __('Consultant') }}</option>
                            </select>
                        @endif
                    </div>
                @endforeach
            </div>
            @error('lawyer_ids') <div class="text-xs text-red-500 mt-2 font-semibold">{{ __($message) }}</div> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('cases.show', $case->id) }}" wire:navigate class="px-6 py-3 bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-red-600 dark:hover:text-red-500 rounded-xl transition-all font-bold whitespace-nowrap">
                <i class="fas fa-times"></i> {{ __('Cancel and Discard') }}
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-slate-900 dark:bg-amber-600 hover:bg-slate-800 dark:hover:bg-amber-700 text-white font-bold rounded-xl shadow-lg shadow-slate-900/20 dark:shadow-amber-600/20 transition-all hover:-translate-y-0.5 focus:ring-4 focus:ring-amber-500/40 disabled:opacity-70 disabled:cursor-not-allowed" wire:loading.attr="disabled">
                <i class="fas fa-save" wire:loading.remove wire:target="save"></i>
                <i class="fas fa-spinner fa-spin" wire:loading wire:target="save"></i>
                <span wire:loading.remove wire:target="save">{{ __('Save All Changes') }}</span>
                <span wire:loading wire:target="save">{{ __('Saving...') }}</span>
            </button>
        </div>

    </form>
</div>
