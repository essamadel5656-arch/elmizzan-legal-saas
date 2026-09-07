<div class="p-4 md:p-8 max-w-6xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="mb-6 p-4 flex items-center gap-3 bg-emerald-50/80 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl backdrop-blur-sm">
            <i class="fas fa-check-circle text-lg"></i>
            <span class="font-bold">{{ __(session('success')) }}</span>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-wrap justify-between items-start gap-6 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-folder-open text-2xl text-amber-500"></i>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 m-0">{{ __('Case File') }}</h1>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <span class="text-lg font-bold text-slate-900 dark:text-amber-500 font-mono tracking-wider"># {{ $case->case_number }}</span>
                
                @php
                    $statusColorClasses = match ($case->status) {
                        'مفتوحة' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800/50',
                        'متداولة' => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50',
                        'مؤجلة' => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50',
                        'مغلقة', 'منتهية', 'معلقة' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50',
                        'حكم نهائي' => 'bg-indigo-100 text-indigo-700 border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800/50',
                        'محفوظة' => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
                        'مستأنفة' => 'bg-orange-100 text-orange-700 border-orange-200 dark:bg-orange-900/30 dark:text-orange-400 dark:border-orange-800/50',
                        default => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700'
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusColorClasses }}">
                    {{ __($case->status) }}
                </span>
                
                @can('update', $case)
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('Quick Status Change:') }}</label>
                        <select class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-full text-xs font-bold px-3 py-1 focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all cursor-pointer" wire:change="updateStatus($event.target.value)">
                            @foreach(['مفتوحة', 'متداولة', 'مؤجلة', 'محجوزة للحكم', 'منتهية', 'مستأنفة', 'محفوظة', 'معلقة'] as $st)
                                <option value="{{ $st }}" @selected($case->status === $st)>{{ __($st) }}</option>
                            @endforeach
                        </select>
                    </div>
                @endcan
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            @can('update', $case)
                <a href="{{ route('cases.edit', $case->id) }}" wire:navigate class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 hover:bg-amber-500 hover:text-white dark:hover:bg-amber-500 dark:hover:text-white rounded-xl font-bold transition-all shadow-sm">
                    <i class="fas fa-edit"></i> {{ __('Edit Data') }}
                </a>
            @endcan
            <a href="{{ route('cases.index') }}" wire:navigate class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl font-bold transition-all shadow-sm">
                <i class="fas fa-arrow-right rtl:rotate-180"></i> {{ __('Back') }}
            </a>
        </div>
    </div>

    {{-- 1. Basic Details --}}
    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8 mb-6 transition-all hover:border-amber-500/50">
        <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800 text-lg font-bold text-slate-900 dark:text-amber-500">
            <i class="fas fa-file-alt text-amber-500"></i> {{ __('Basic Details') }}
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Court') }}</span>
                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px]">{{ __($case->court->name ?? '—') }}</span>
            </div>
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Jurisdiction Type') }}</span>
                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px]">{{ __($case->court->jurisdiction->name ?? '—') }}</span>
            </div>
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Court Level') }}</span>
                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px] {{ !$case->court_level ? 'text-slate-400 dark:text-slate-500 italic' : '' }}">{{ __($case->courtLevel->name ?? 'Not Specified') }}</span>
            </div>
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Opponent Name') }}</span>
                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px] {{ !$case->rival_name ? 'text-slate-400 dark:text-slate-500 italic' : '' }}">{{ $case->rival_name ?? __('Not Specified') }}</span>
            </div>
            <div class="col-span-1 md:col-span-2 lg:col-span-4 flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Case Description and Facts Summary') }}</span>
                <span class="text-sm text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 min-h-[80px] whitespace-pre-wrap leading-relaxed {{ !$case->description ? 'text-slate-400 dark:text-slate-500 italic font-semibold' : '' }}">{{ $case->description ?? __('No description provided') }}</span>
            </div>
            <div class="col-span-1 md:col-span-2 lg:col-span-4 flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Previous Procedure') }}</span>
                <span class="text-sm text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 min-h-[80px] whitespace-pre-wrap leading-relaxed {{ !$case->Previous_procedure ? 'text-slate-400 dark:text-slate-500 italic font-semibold' : '' }}">{{ $case->Previous_procedure ?? __('None') }}</span>
            </div>
            <div class="col-span-1 md:col-span-2 lg:col-span-4 flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Final Decision') }}</span>
                <span class="text-sm text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 min-h-[80px] whitespace-pre-wrap leading-relaxed {{ !$case->final_decision ? 'text-slate-400 dark:text-slate-500 italic font-semibold' : '' }}">{{ $case->final_decision ?? __('None') }}</span>
            </div>
            <div class="col-span-1 md:col-span-2 lg:col-span-4 flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Administrative Notes') }}</span>
                <span class="text-sm text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 min-h-[80px] whitespace-pre-wrap leading-relaxed {{ !$case->notes ? 'text-slate-400 dark:text-slate-500 italic font-semibold' : '' }}">{{ $case->notes ?? __('No notes') }}</span>
            </div>
        </div>
    </div>

    {{-- 2. Opponent Details --}}
    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8 mb-6 transition-all hover:border-amber-500/50">
        <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800 text-lg font-bold text-slate-900 dark:text-amber-500">
            <i class="fas fa-user-times text-amber-500"></i> {{ __('Opponent Details') }}
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Opponent Name') }}</span>
                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px] {{ !$case->rival_name ? 'text-slate-400 dark:text-slate-500 italic' : '' }}">{{ $case->rival_name ?? __('—') }}</span>
            </div>
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Phone Number') }}</span>
                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px] {{ !$case->rival_number ? 'text-slate-400 dark:text-slate-500 italic' : '' }}">{{ $case->rival_number ?? __('—') }}</span>
            </div>
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('National ID') }}</span>
                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px] {{ !$case->rival_nid ? 'text-slate-400 dark:text-slate-500 italic' : '' }}">{{ $case->rival_nid ?? __('—') }}</span>
            </div>
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Address') }}</span>
                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px] {{ !$case->rival_address ? 'text-slate-400 dark:text-slate-500 italic' : '' }}">{{ $case->rival_address ?? __('—') }}</span>
            </div>
        </div>
    </div>

    {{-- 3. Financials --}}
    @can('update', $case)
    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8 mb-6 transition-all hover:border-amber-500/50">
        <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800 text-lg font-bold text-slate-900 dark:text-amber-500">
            <i class="fas fa-coins text-amber-500"></i> {{ __('Financial Accounts') }}
        </div>
        @php
            $remaining = ($case->total_costs ?? 0) - ($case->deposit ?? 0);
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-5 text-center transition-all hover:shadow-md">
                <div class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">{{ __('Administrative Expenses') }}</div>
                <div class="text-2xl font-black text-slate-900 dark:text-slate-100">{{ number_format($case->costs ?? 0, 2) }} <span class="text-sm font-bold">{{ __('CUR') }}</span></div>
            </div>
            <div class="bg-amber-50/50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/50 rounded-xl p-5 text-center transition-all hover:shadow-md">
                <div class="text-xs font-bold text-amber-600 dark:text-amber-500 mb-2 uppercase tracking-wider">{{ __('Total Fees') }}</div>
                <div class="text-2xl font-black text-amber-700 dark:text-amber-400">{{ number_format($case->total_costs ?? 0, 2) }} <span class="text-sm font-bold">{{ __('CUR') }}</span></div>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-5 text-center transition-all hover:shadow-md">
                <div class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">{{ __('Paid in Advance') }}</div>
                <div class="text-2xl font-black text-slate-900 dark:text-slate-100">{{ number_format($case->deposit ?? 0, 2) }} <span class="text-sm font-bold">{{ __('CUR') }}</span></div>
            </div>
            <div class="{{ $remaining > 0 ? 'bg-red-50/50 dark:bg-red-900/10 border-red-200 dark:border-red-800/50' : 'bg-green-50/50 dark:bg-green-900/10 border-green-200 dark:border-green-800/50' }} border rounded-xl p-5 text-center transition-all hover:shadow-md">
                <div class="text-xs font-bold {{ $remaining > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }} mb-2 uppercase tracking-wider">{{ __('Remaining Amount') }}</div>
                <div class="text-2xl font-black {{ $remaining > 0 ? 'text-red-700 dark:text-red-400' : 'text-green-700 dark:text-green-400' }}">{{ number_format($remaining, 2) }} <span class="text-sm font-bold">{{ __('CUR') }}</span></div>
            </div>
        </div>
    </div>
    @endcan

    {{-- 4. Clients and Lawyers --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8 transition-all hover:border-amber-500/50">
            <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3 text-lg font-bold text-slate-900 dark:text-amber-500">
                    <i class="fas fa-users text-amber-500"></i> {{ __('Assigned Clients') }}
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-full">{{ $case->clients->count() }}</span>
                </div>
            </div>
            @if($case->clients->isEmpty())
                <div class="text-center text-sm font-bold italic text-slate-400 dark:text-slate-500 py-6">{{ __('No clients linked to this case.') }}</div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2 gap-4">
                    @foreach($case->clients as $client)
                        <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl transition-all hover:shadow-md hover:border-slate-300 dark:hover:border-slate-600">
                            <div class="w-12 h-12 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center text-lg font-bold flex-shrink-0 border border-slate-300 dark:border-slate-600">
                                {{ mb_substr($client->name, 0, 1) }}
                            </div>
                            <div class="flex flex-col overflow-hidden">
                                <span class="font-bold text-sm text-slate-900 dark:text-slate-100 truncate" title="{{ $client->name }}">{{ $client->name }}</span>
                                <span class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5"><i class="fas fa-phone-alt text-[10px]"></i> {{ $client->phone ?? __('—') }}</span>
                                <span class="text-xs font-medium text-slate-500 dark:text-slate-400 flex items-center gap-1.5"><i class="fas fa-id-card text-[10px]"></i> {{ $client->nid ?? __('—') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8 transition-all hover:border-amber-500/50">
            <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3 text-lg font-bold text-slate-900 dark:text-amber-500">
                    <i class="fas fa-user-tie text-amber-500"></i> {{ __('Legal Team') }}
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-full">{{ $case->lawyers->count() }}</span>
                </div>
            </div>
            @if($case->lawyers && $case->lawyers->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2 gap-4">
                    @foreach($case->lawyers as $lawyer)
                        <div class="flex items-center gap-4 p-4 bg-amber-50/30 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800/30 rounded-xl transition-all hover:shadow-md hover:border-amber-300 dark:hover:border-amber-700">
                            <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg font-bold flex-shrink-0 border border-amber-200 dark:border-amber-800/50">
                                {{ mb_substr($lawyer->name, 0, 1) }}
                            </div>
                            <div class="flex flex-col overflow-hidden items-start">
                                <span class="font-bold text-sm text-slate-900 dark:text-slate-100 truncate w-full" title="{{ $lawyer->name }}">{{ $lawyer->name }}</span>
                                @if(!empty($lawyer->specialization))
                                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">{{ $lawyer->specialization }}</span>
                                @endif
                                @php
                                    $roleLabel = __('Assistant');
                                    if(isset($lawyer->pivot->role)) {
                                        $roleLabel = match($lawyer->pivot->role) {
                                            'lead' => __('Lead Lawyer'),
                                            'assistant' => __('Assistant'),
                                            'consultant' => __('Consultant'),
                                            default => __('Assistant')
                                        };
                                    }
                                @endphp
                                <span class="mt-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-900 text-white dark:bg-amber-500 dark:text-slate-900">{{ $roleLabel }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-sm font-bold italic text-slate-400 dark:text-slate-500 py-6">{{ __('No lawyers linked to this case.') }}</div>
            @endif
        </div>
    </div>

    {{-- 5. Appointments --}}
    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8 mb-6 transition-all hover:border-amber-500/50">
        <div class="flex flex-wrap justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800 gap-4">
            <div class="flex items-center gap-3 text-lg font-bold text-slate-900 dark:text-amber-500">
                <i class="fas fa-calendar-check text-amber-500"></i> {{ __('Hearings and Appointments') }}
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-full">{{ $case->appointments->count() }}</span>
            </div>
            <a href="{{ route('appointments.create', $case->id) }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 dark:bg-amber-600 text-white text-xs font-bold rounded-lg shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                <i class="fas fa-plus"></i> {{ __('Add Appointment') }}
            </a>
        </div>

        @if($case->appointments && $case->appointments->count() > 0)
            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                <table class="w-full text-sm text-start">
                    <thead class="bg-slate-100/80 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 text-xs uppercase tracking-wider font-semibold border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[15%]">{{ __('Appointment No.') }}</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[25%]">{{ __('Date and Time') }}</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[60%]">{{ __('Session Details or Appointment') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                        @foreach($case->appointments as $appointment)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-slate-100">#{{ $appointment->id }}</td>
                                <td class="px-6 py-4 font-bold text-amber-600 dark:text-amber-400 dir-ltr text-end ltr:text-left">{{ $appointment->date }}</td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $appointment->notes ?? __('—') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-sm font-bold italic text-slate-400 dark:text-slate-500 py-6">{{ __('No appointments or hearings recorded for this case yet.') }}</div>
        @endif
    </div>

    {{-- 6. Documents --}}
    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8 transition-all hover:border-amber-500/50">
        <div class="flex flex-wrap justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800 gap-4">
            <div class="flex items-center gap-3 text-lg font-bold text-slate-900 dark:text-amber-500">
                <i class="fas fa-paperclip text-amber-500"></i> {{ __('Documents and Attachments') }}
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-full">{{ $case->documents->count() }}</span>
            </div>
            <a href="{{ route('document.add_documents', ['case' => $case->id]) }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 dark:bg-amber-600 text-white text-xs font-bold rounded-lg shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                <i class="fas fa-upload"></i> {{ __('Upload Document') }}
            </a>
        </div>

        @if($case->documents && $case->documents->count() > 0)
            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                <table class="w-full text-sm text-start">
                    <thead class="bg-slate-100/80 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 text-xs uppercase tracking-wider font-semibold border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[10%]">#</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[40%]">{{ __('Document Name') }}</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[20%]">{{ __('Upload Date') }}</th>
                            <th class="px-6 py-4 text-center w-[30%]">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                        @foreach($case->documents as $document)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-slate-100">{{ $document->id }}</td>
                                <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                    <i class="fas fa-file-alt text-amber-500 ms-1 rtl:ml-2"></i> {{ $document->title }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400 dir-ltr text-end ltr:text-left font-mono text-xs">
                                    {{ $document->created_at ? $document->created_at->format('Y-m-d') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($document->file_path)
                                        <a href="{{ asset('storage/' . ltrim(str_replace('public/', '', $document->file_path), '/')) }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white dark:hover:bg-amber-500 dark:hover:text-white border border-amber-200 dark:border-amber-800/50 rounded-lg text-xs font-bold transition-colors">
                                            <i class="fas fa-external-link-alt"></i> {{ __('View File') }}
                                        </a>
                                    @else
                                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500 italic">{{ __('No file') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-sm font-bold italic text-slate-400 dark:text-slate-500 py-6">{{ __('No documents uploaded in this file.') }}</div>
        @endif
    </div>

</div>
