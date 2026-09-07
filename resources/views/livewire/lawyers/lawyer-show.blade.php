<div class="p-4 md:p-8 max-w-5xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div class="flex items-center gap-6">
            @if($lawyer->profile_image)
                <img src="{{ asset('storage/' . str_replace('public/', '', $lawyer->profile_image)) }}" alt="{{ $lawyer->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-slate-50 dark:border-slate-800 shadow-lg ring-2 ring-amber-500/50">
            @else
                <div class="w-24 h-24 rounded-full bg-slate-900 dark:bg-slate-800 text-amber-500 border-4 border-slate-50 dark:border-slate-800 shadow-lg ring-2 ring-amber-500/50 flex items-center justify-center text-4xl font-black">
                    {{ mb_substr($lawyer->name, 0, 1) }}
                </div>
            @endif
            
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-slate-100 mb-2">{{ $lawyer->name }}</h1>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 rounded-full text-sm font-bold">
                    <i class="fas fa-briefcase"></i> {{ $lawyer->specialization }}
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 self-start md:self-auto">
            @if(auth()->user()->role !== 'admin')
                <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl font-bold transition-all shadow-sm">
                    <i class="fas fa-arrow-right rtl:rotate-180"></i> {{ __('Back to Home') }}
                </a>
            @else
                <a href="{{ route('lawyers.index') }}" wire:navigate class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl font-bold transition-all shadow-sm">
                    <i class="fas fa-arrow-right rtl:rotate-180"></i> {{ __('Back to List') }}
                </a>
            @endif
        </div>
    </div>

    {{-- 1. Contact Information --}}
    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8 mb-6 transition-all hover:border-amber-500/50">
        <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800 text-lg font-bold text-slate-900 dark:text-amber-500">
            <i class="fas fa-address-card text-amber-500"></i> {{ __('Contact Information') }}
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-2"><i class="fas fa-phone-alt text-amber-500"></i> {{ __('Phone Number') }}</span>
                <div class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px]">
                    <a href="tel:{{ $lawyer->phone }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors w-full text-end ltr:text-left dir-ltr">{{ $lawyer->phone }}</a>
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-2"><i class="fas fa-envelope text-amber-500"></i> {{ __('Email Address') }}</span>
                <div class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px]">
                    <a href="mailto:{{ $lawyer->email }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors w-full text-end ltr:text-left dir-ltr">{{ $lawyer->email }}</a>
                </div>
            </div>

            <div class="flex flex-col gap-1.5 col-span-1 md:col-span-2">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-2"><i class="fas fa-map-marker-alt text-amber-500"></i> {{ __('Address') }}</span>
                <div class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px]">
                    {{ $lawyer->address }}
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Degree and License --}}
    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8 mb-6 transition-all hover:border-amber-500/50">
        <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800 text-lg font-bold text-slate-900 dark:text-amber-500">
            <i class="fas fa-graduation-cap text-amber-500"></i> {{ __('Degree and License') }}
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-2"><i class="fas fa-award text-amber-500"></i> {{ __('Degree') }}</span>
                <div class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px]">
                    {{ __($lawyer->degree) }}
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-2"><i class="fas fa-id-badge text-amber-500"></i> {{ __('Registration / License Number') }}</span>
                <div class="text-sm font-semibold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 flex items-center min-h-[46px]">
                    {{ $lawyer->license_number }}
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Bio --}}
    @if($lawyer->bio)
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8 mb-6 transition-all hover:border-amber-500/50">
            <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800 text-lg font-bold text-slate-900 dark:text-amber-500">
                <i class="fas fa-user-edit text-amber-500"></i> {{ __('Personal Bio') }}
            </div>
            
            <div class="text-sm text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-5 py-4 min-h-[80px] whitespace-pre-wrap leading-relaxed">
                {{ $lawyer->bio }}
            </div>
        </div>
    @endif

    {{-- 4. Uploaded Documents --}}
    @if($lawyer->national_id_image || $lawyer->bar_card_image)
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8 mb-6 transition-all hover:border-amber-500/50">
            <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800 text-lg font-bold text-slate-900 dark:text-amber-500">
                <i class="fas fa-images text-amber-500"></i> {{ __('Uploaded Documents') }}
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @if($lawyer->national_id_image)
                    <div class="flex flex-col gap-2">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2"><i class="fas fa-id-card text-slate-400"></i> {{ __('National ID Image') }}</span>
                        <a href="{{ asset('storage/' . str_replace('public/', '', $lawyer->national_id_image)) }}" target="_blank" class="group relative block w-full h-56 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden hover:border-amber-500 transition-all">
                            <img src="{{ asset('storage/' . str_replace('public/', '', $lawyer->national_id_image)) }}" alt="{{ __('National ID') }}" class="w-full h-full object-contain p-2 transition-transform duration-300 group-hover:scale-105">
                            <div class="absolute inset-x-0 bottom-0 bg-slate-900/90 backdrop-blur-sm text-white p-3 text-sm font-bold flex items-center justify-center gap-2 translate-y-full transition-transform duration-300 group-hover:translate-y-0">
                                <i class="fas fa-search-plus text-amber-500"></i> {{ __('View Full Size') }}
                            </div>
                        </a>
                    </div>
                @endif

                @if($lawyer->bar_card_image)
                    <div class="flex flex-col gap-2">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2"><i class="fas fa-id-badge text-slate-400"></i> {{ __('Bar Association Card') }}</span>
                        <a href="{{ asset('storage/' . str_replace('public/', '', $lawyer->bar_card_image)) }}" target="_blank" class="group relative block w-full h-56 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden hover:border-amber-500 transition-all">
                            <img src="{{ asset('storage/' . str_replace('public/', '', $lawyer->bar_card_image)) }}" alt="{{ __('Bar Card') }}" class="w-full h-full object-contain p-2 transition-transform duration-300 group-hover:scale-105">
                            <div class="absolute inset-x-0 bottom-0 bg-slate-900/90 backdrop-blur-sm text-white p-3 text-sm font-bold flex items-center justify-center gap-2 translate-y-full transition-transform duration-300 group-hover:translate-y-0">
                                <i class="fas fa-search-plus text-amber-500"></i> {{ __('View Full Size') }}
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Actions --}}
    @can('update', $lawyer)
        <div class="flex flex-wrap items-center justify-center gap-4 mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('lawyers.edit', $lawyer->id) }}" wire:navigate class="inline-flex items-center gap-2 px-8 py-3 bg-slate-900 dark:bg-amber-600 hover:bg-slate-800 dark:hover:bg-amber-700 text-white font-bold rounded-xl shadow-lg shadow-slate-900/20 dark:shadow-amber-600/20 transition-all hover:-translate-y-0.5 w-full sm:w-auto justify-center">
                <i class="fas fa-edit"></i> {{ __('Edit Data') }}
            </a>

            @can('delete', $lawyer)
                <button type="button" wire:click="delete" 
                    wire:confirm="{{ __('Are you sure you want to permanently delete this lawyer from the system? This action cannot be undone.') }}" 
                    class="inline-flex items-center gap-2 px-8 py-3 bg-red-50 dark:bg-red-900/10 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/30 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white rounded-xl font-bold transition-all w-full sm:w-auto justify-center">
                    <i class="fas fa-trash-alt"></i> {{ __('Delete Lawyer') }}
                </button>
            @endcan
        </div>
    @endcan

</div>
