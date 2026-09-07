<div class="p-4 md:p-8 max-w-5xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-user-shield text-2xl text-amber-500"></i>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 m-0">{{ __('Client Profile:') }} <span class="text-amber-600 dark:text-amber-500">{{ $client->name }}</span></h1>
            </div>
            <div class="flex flex-wrap items-center gap-4 mt-3">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-bold border border-slate-200 dark:border-slate-700">
                    <i class="fas fa-fingerprint text-slate-400"></i> {{ __('System ID:') }} #{{ $client->id }}
                </span>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-bold border border-slate-200 dark:border-slate-700">
                    <i class="fas fa-calendar-alt text-slate-400"></i> {{ __('Registration Date:') }} {{ $client->created_at ? $client->created_at->format('Y-m-d') : __('Not Registered') }}
                </span>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('clients.index') }}" wire:navigate class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl font-bold transition-all shadow-sm">
                <i class="fas fa-arrow-right rtl:rotate-180"></i> {{ __('Back to List') }}
            </a>
        </div>
    </div>

    {{-- 1. Contact and ID Data --}}
    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8 mb-6 transition-all hover:border-amber-500/50">
        <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800 text-lg font-bold text-slate-900 dark:text-amber-500">
            <i class="fas fa-address-card text-amber-500"></i> {{ __('Contact and Identity Information') }}
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-2"><i class="fas fa-phone-alt text-amber-500"></i> {{ __('Mobile Number') }}</span>
                <div class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px] {{ !$client->phone ? 'text-slate-400 dark:text-slate-500 italic font-medium' : '' }}">
                    @if($client->phone)
                        <a href="tel:{{ $client->phone }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors w-full text-end ltr:text-left dir-ltr">{{ $client->phone }}</a>
                    @else
                        {{ __('— Not Registered —') }}
                    @endif
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-2"><i class="fas fa-envelope text-amber-500"></i> {{ __('Email Address') }}</span>
                <div class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px] {{ !$client->email ? 'text-slate-400 dark:text-slate-500 italic font-medium' : '' }}">
                    @if($client->email)
                        <a href="mailto:{{ $client->email }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors truncate">{{ $client->email }}</a>
                    @else
                        {{ __('— Not Registered —') }}
                    @endif
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-2"><i class="fas fa-id-card text-amber-500"></i> {{ __('National ID / Commercial Register') }}</span>
                <div class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px] {{ !$client->nid ? 'text-slate-400 dark:text-slate-500 italic font-medium' : '' }}">
                    @if($client->nid)
                        {{ $client->nid }}
                    @else
                        {{ __('— Not Registered —') }}
                    @endif
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-2"><i class="fas fa-map-marker-alt text-amber-500"></i> {{ __('Current Detailed Address') }}</span>
                <div class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px] {{ !$client->address ? 'text-slate-400 dark:text-slate-500 italic font-medium' : '' }}">
                    @if($client->address)
                        <span class="line-clamp-2" title="{{ $client->address }}">{{ $client->address }}</span>
                    @else
                        {{ __('— Not Registered —') }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Client Cases --}}
    @if($client->cases && $client->cases->count() > 0)
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8 mb-6 transition-all hover:border-amber-500/50">
            <div class="flex items-center justify-between mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                    <i class="fas fa-folder-open text-amber-500"></i> {{ __('Cases Linked to Client') }}
                    <span class="bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 px-2 py-0.5 rounded-full text-xs font-bold">{{ $client->cases->count() }}</span>
                </div>
            </div>
            
            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                <table class="w-full text-sm text-start">
                    <thead class="bg-slate-100/80 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 text-xs uppercase tracking-wider font-semibold border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left">{{ __('Case Number') }}</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left">{{ __('Court') }}</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left">{{ __('Status') }}</th>
                            <th class="px-6 py-4 text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                        @foreach($client->cases as $case)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-slate-100"># {{ $case->case_number }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300">{{ $case->court?->name ?? __('—') }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap">
                                        {{ __($case->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('cases.show', $case->id) }}" wire:navigate class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white dark:hover:bg-amber-500 dark:hover:text-white border border-amber-200 dark:border-amber-800/50 rounded-lg text-xs font-bold transition-colors whitespace-nowrap">
                                        <i class="fas fa-eye"></i> {{ __('View Case') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- 3. Administrative Notes --}}
    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8 transition-all hover:border-amber-500/50">
        <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800 text-lg font-bold text-slate-900 dark:text-amber-500">
            <i class="fas fa-sticky-note text-amber-500"></i> {{ __('Internal Office Report and Notes') }}
        </div>
        
        <div class="text-sm text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-5 py-4 min-h-[100px] whitespace-pre-wrap leading-relaxed {{ !$client->note ? 'text-slate-400 dark:text-slate-500 italic font-semibold flex items-center justify-center' : '' }}">
            @if($client->note)
                {{ $client->note }}
            @else
                {{ __('No administrative notes added to this client\'s file yet.') }}
            @endif
        </div>
    </div>

</div>
