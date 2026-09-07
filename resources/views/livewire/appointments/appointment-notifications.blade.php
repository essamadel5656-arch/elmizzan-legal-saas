<div class="p-4 md:p-8 max-w-6xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2 flex items-center gap-3">
                <i class="fas fa-bell text-amber-500"></i> {{ __('Notifications and Appointments') }}
            </h1>
            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">
                {{ __('Next week\'s appointments —') }} 
                <span class="text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::now()->locale(app()->getLocale())->isoFormat('dddd, D MMMM YYYY') }}</span>
            </p>
        </div>
        <a href="{{ route('appointments.index') }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-900 dark:bg-amber-600 text-white hover:bg-slate-800 dark:hover:bg-amber-700 rounded-xl transition-all font-bold shadow-lg shadow-slate-900/20 dark:shadow-amber-600/20 hover:-translate-y-0.5 whitespace-nowrap self-start md:self-auto">
            <i class="fas fa-calendar-alt"></i> {{ __('All Appointments') }}
        </a>
    </div>

    @if($appointments->isEmpty())
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-16 text-center flex flex-col items-center justify-center min-h-[400px]">
            <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6 border border-slate-100 dark:border-slate-700">
                <i class="fas fa-calendar-check text-4xl text-slate-300 dark:text-slate-600"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-2">
                {{ __('No upcoming appointments') }}
            </h3>
            <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed text-sm">
                {{ __('There are no court sessions or appointments scheduled in the next seven days.') }}
            </p>
        </div>
    @else
        @php
            $appointmentsByDate = $appointments->groupBy(function ($appt) {
                return \Carbon\Carbon::parse($appt->date)->format('Y-m-d');
            })->sortKeys();

            $tomorrow = \Carbon\Carbon::tomorrow()->format('Y-m-d');
            $today    = \Carbon\Carbon::today()->format('Y-m-d');
        @endphp

        {{-- Summary Bar --}}
        <div class="flex flex-wrap gap-4 mb-8">
            <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700/50 rounded-full font-bold text-sm shadow-sm">
                <i class="fas fa-calendar-week text-slate-400 dark:text-slate-500"></i>
                {{ __('Total Appointments:') }} <span class="text-slate-900 dark:text-slate-100">{{ $appointments->count() }}</span>
            </span>
            @if(isset($appointmentsByDate[$tomorrow]))
                <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/50 rounded-full font-bold text-sm shadow-sm">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    {{ __('Tomorrow\'s Appointments:') }} <span class="text-red-900 dark:text-red-300">{{ $appointmentsByDate[$tomorrow]->count() }}</span>
                </span>
            @endif
            <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 rounded-full font-bold text-sm shadow-sm">
                <i class="fas fa-layer-group text-amber-500"></i>
                {{ __('Different Days:') }} <span class="text-amber-900 dark:text-amber-300">{{ $appointmentsByDate->count() }}</span>
            </span>
        </div>

        {{-- Appointments grouped by date --}}
        <div class="space-y-10">
            @foreach($appointmentsByDate as $date => $dateAppointments)
                @php
                    $isToday    = $date === $today;
                    $isTomorrow = $date === $tomorrow;
                    $parsedDate = \Carbon\Carbon::parse($date)->locale(app()->getLocale());
                @endphp

                <div class="flex flex-col gap-4" wire:key="group-{{ $date }}">
                    {{-- Date Header --}}
                    <div class="flex items-center gap-3 px-5 py-3 rounded-xl border-l-4 {{ $isTomorrow ? 'bg-red-50 dark:bg-red-900/10 border-red-500 text-red-700 dark:text-red-400 rtl:border-l-0 rtl:border-r-4' : 'bg-slate-100 dark:bg-slate-800/50 border-slate-400 dark:border-slate-600 text-slate-800 dark:text-slate-200 rtl:border-l-0 rtl:border-r-4' }}">
                        <i class="fas {{ $isTomorrow ? 'fa-exclamation-circle' : 'fa-calendar-day' }}"></i>
                        <span class="font-bold text-lg flex-1">
                            @if($isToday)
                                {{ __('Today') }} — {{ $parsedDate->isoFormat('dddd, D MMMM') }}
                            @elseif($isTomorrow)
                                {{ __('Tomorrow ⚠️') }} — {{ $parsedDate->isoFormat('dddd, D MMMM') }}
                            @else
                                {{ $parsedDate->isoFormat('dddd, D MMMM YYYY') }}
                            @endif
                        </span>
                        <span class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold {{ $isTomorrow ? 'bg-red-200 dark:bg-red-900/50 text-red-800 dark:text-red-300' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                            {{ $dateAppointments->count() }}
                        </span>
                    </div>

                    {{-- Cards Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($dateAppointments as $appointment)
                            @php
                                $timeFormatted = $appointment->time
                                    ? \Carbon\Carbon::parse($appointment->time)->format('h:i A')
                                    : null;
                            @endphp

                            <div class="group bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 border-r-4 {{ $isTomorrow ? 'border-r-red-500 dark:border-r-red-500 rtl:border-r-0 rtl:border-l-4 rtl:border-l-red-500 dark:rtl:border-l-red-500' : 'border-r-amber-500 dark:border-r-amber-500 rtl:border-r-0 rtl:border-l-4 rtl:border-l-amber-500 dark:rtl:border-l-amber-500' }} shadow-sm dark:shadow-none backdrop-blur-sm rounded-xl p-5 md:p-6 transition-all hover:shadow-md dark:hover:bg-slate-800/80" wire:key="appt-card-{{ $appointment->id }}">
                                
                                {{-- Card Header --}}
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 pb-4 border-b border-slate-100 dark:border-slate-800 border-dashed">
                                    <div class="font-bold text-lg text-slate-900 dark:text-slate-100 flex items-center gap-2.5">
                                        <i class="fas fa-gavel text-amber-500"></i>
                                        @if($appointment->case)
                                            <a href="{{ route('cases.show', $appointment->case->id) }}" wire:navigate class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                                                {{ __('Case No:') }} <span class="dir-ltr inline-block">{{ $appointment->case->case_number }}</span>
                                            </a>
                                        @else
                                            <span>{{ __('Case No:') }} —</span>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-bold border border-slate-200 dark:border-slate-700">
                                            <i class="fas fa-calendar"></i>
                                            <span class="dir-ltr inline-block">{{ $parsedDate->isoFormat('D MMMM YYYY') }}</span>
                                        </span>
                                        @if($timeFormatted)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold border {{ $isTomorrow ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800/50' : 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800/50' }}">
                                                <i class="fas fa-clock"></i>
                                                <span class="dir-ltr inline-block">{{ $timeFormatted }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Details --}}
                                <div class="space-y-2 mb-4">
                                    @if($appointment->case?->court?->name)
                                        <div class="flex items-start gap-2 text-sm">
                                            <span class="text-slate-500 dark:text-slate-400 font-bold w-24 shrink-0 flex items-center gap-1.5"><i class="fas fa-landmark text-amber-500 w-4 text-center"></i> {{ __('Court:') }}</span>
                                            <span class="text-slate-900 dark:text-slate-100 font-semibold">{{ $appointment->case->court->name }}</span>
                                        </div>
                                    @endif

                                    @if($appointment->case?->status)
                                        <div class="flex items-start gap-2 text-sm">
                                            <span class="text-slate-500 dark:text-slate-400 font-bold w-24 shrink-0 flex items-center gap-1.5"><i class="fas fa-info-circle text-amber-500 w-4 text-center"></i> {{ __('Status:') }}</span>
                                            <span class="text-slate-900 dark:text-slate-100 font-semibold">{{ __($appointment->case->status) }}</span>
                                        </div>
                                    @endif

                                    @if($appointment->case?->rival_name)
                                        <div class="flex items-start gap-2 text-sm">
                                            <span class="text-slate-500 dark:text-slate-400 font-bold w-24 shrink-0 flex items-center gap-1.5"><i class="fas fa-user-shield text-amber-500 w-4 text-center"></i> {{ __('Rival:') }}</span>
                                            <span class="text-slate-900 dark:text-slate-100 font-semibold">{{ $appointment->case->rival_name }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Notes --}}
                                @if($appointment->notes)
                                    <div class="bg-amber-50/50 dark:bg-amber-900/10 border rtl:border-r-2 ltr:border-l-2 border-amber-500 rounded-lg p-3 text-sm">
                                        <div class="font-bold text-amber-700 dark:text-amber-500 flex items-center gap-1.5 mb-1 text-xs uppercase tracking-wider">
                                            <i class="fas fa-thumbtack"></i> {{ __('Session Notes') }}
                                        </div>
                                        <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ $appointment->notes }}</p>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
