<div class="p-4 md:p-8 max-w-7xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Flash Message --}}
    @if (session()->has('success'))
        <div class="mb-6 p-4 flex items-center gap-3 bg-emerald-50/80 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl backdrop-blur-sm">
            <i class="fas fa-check-circle text-lg"></i>
            <span class="font-bold">{{ __(session('success')) }}</span>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-wrap justify-between items-start gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2 flex items-center gap-3">
                <i class="fas fa-calendar-alt text-amber-500"></i> {{ __('Appointments & Sessions Management') }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('View and track all scheduled appointments in the system in real time') }}
            </p>
        </div>
    </div>

    {{-- Search Panel --}}
    <div class="bg-slate-100/50 dark:bg-slate-800/30 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 mb-6">
        <div class="max-w-xl">
            <label for="search-input" class="block text-sm font-bold text-slate-900 dark:text-slate-100 mb-2">
                {{ __('Search by Case Number') }}
            </label>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute rtl:right-4 ltr:left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 pointer-events-none"></i>
                    <input 
                        type="text" 
                        id="search-input" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="{{ __('Enter case number for instant search...') }}" 
                        class="w-full bg-white dark:bg-slate-900/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 rtl:pr-11 ltr:pl-11"
                    >
                </div>
                @if($search)
                    <button type="button" wire:click="clearSearch" class="px-6 py-3 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700 rounded-xl text-sm font-bold transition-all hover:bg-slate-300 dark:hover:bg-slate-700 flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="fas fa-times"></i> {{ __('Clear Search') }}
                    </button>
                @endif
            </div>

            @if($search)
                <div class="mt-4 p-3 bg-blue-50/80 dark:bg-blue-900/20 border rtl:border-r-4 ltr:border-l-4 border-blue-500 dark:border-blue-500/50 rounded-lg text-sm text-blue-800 dark:text-blue-300 font-semibold flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    {{ __('Live search results for:') }} <strong class="text-slate-900 dark:text-slate-100">"{{ $search }}"</strong>
                </div>
            @endif
        </div>
    </div>

    {{-- Main Panel --}}
    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl transition-all hover:border-amber-500/50">
        @if($appointments->isEmpty())
            @if($search)
                <div class="py-16 text-center flex flex-col items-center justify-center min-h-[400px]">
                    <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6 border border-slate-100 dark:border-slate-700">
                        <i class="fas fa-search text-4xl text-slate-300 dark:text-slate-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('No results found') }}
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8 leading-relaxed text-sm">
                        {{ __('No appointments match the case number') }} "{{ $search }}".
                    </p>
                    <button type="button" wire:click="clearSearch" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl font-bold transition-all shadow-sm">
                        <i class="fas fa-arrow-right rtl:rotate-180"></i> {{ __('View all appointments') }}
                    </button>
                </div>
            @else
                <div class="py-16 text-center flex flex-col items-center justify-center min-h-[400px]">
                    <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6 border border-slate-100 dark:border-slate-700">
                        <i class="fas fa-calendar-times text-4xl text-slate-300 dark:text-slate-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('No appointments registered') }}
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed text-sm">
                        {{ __('You can add a new appointment from within the respective case profile.') }}
                    </p>
                </div>
            @endif
        @else
            <div class="overflow-x-auto rounded-xl">
                <table class="w-full text-sm text-start">
                    <thead class="bg-slate-100/80 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 text-xs uppercase tracking-wider font-bold border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[5%]">#</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[20%]">{{ __('Case Number') }}</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[25%]">{{ __('Date and Time') }}</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[40%]">{{ __('Notes') }}</th>
                            <th class="px-6 py-4 text-center w-[10%]">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                        @foreach($appointments as $appointment)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors group" wire:key="appt-{{ $appointment->id }}">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-slate-100">{{ $loop->iteration }}</td>

                                <td class="px-6 py-4">
                                    @if($appointment->case)
                                        <a href="{{ route('cases.show', $appointment->case->id) }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 dark:bg-slate-800/80 text-slate-900 dark:text-amber-500 border border-slate-200 dark:border-slate-700/50 rounded-lg text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors">
                                            <i class="fas fa-folder text-amber-500"></i>
                                            {{ $appointment->case->case_number }}
                                        </a>
                                    @else
                                        <span class="inline-block px-3 py-1 bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700/50 rounded-lg text-xs font-bold">
                                            —
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1.5">
                                        <span class="font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                            <i class="fas fa-calendar-day text-slate-400 dark:text-slate-500"></i>
                                            <span class="dir-ltr inline-block">{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d') }}</span>
                                        </span>
                                        <span class="font-bold text-amber-600 dark:text-amber-500 text-xs flex items-center gap-2">
                                            <i class="fas fa-clock"></i>
                                            <span class="dir-ltr inline-block">{{ $appointment->time ? \Carbon\Carbon::parse($appointment->time)->format('h:i A') : __('Not Specified') }}</span>
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="max-w-[320px] truncate text-slate-600 dark:text-slate-400 font-medium" title="{{ $appointment->notes }}">
                                        {{ $appointment->notes ?: __('No Notes') }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <button type="button" wire:click="delete({{ $appointment->id }})" 
                                        wire:confirm="{{ __('Are you sure you want to permanently delete this appointment?') }}"
                                        class="inline-flex w-9 h-9 items-center justify-center bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white rounded-lg transition-all" title="{{ __('Delete Appointment') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
