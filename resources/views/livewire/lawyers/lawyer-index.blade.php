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
                <i class="fas fa-users-cog text-amber-500"></i> {{ __('Lawyers List') }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('View, track, and manage data of all lawyers working in the office') }}
            </p>
        </div>
        
        @can('create', App\Models\Lawyer::class)
            <a href="{{ route('lawyers.create') }}" wire:navigate class="inline-flex items-center gap-3 bg-slate-900 dark:bg-amber-600 text-white pl-6 pr-2 py-2 rtl:pr-6 rtl:pl-2 rounded-full font-bold transition-all hover:-translate-y-1 hover:shadow-lg shadow-sm border border-slate-800 dark:border-amber-500 group">
                <span>{{ __('Add New Lawyer') }}</span>
                <span class="w-8 h-8 flex items-center justify-center bg-amber-500 dark:bg-slate-900 text-slate-900 dark:text-amber-500 rounded-full transition-transform group-hover:rotate-90">
                    <i class="fas fa-user-plus"></i>
                </span>
            </a>
        @endcan
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 mb-6 flex flex-col md:flex-row gap-6 transition-all hover:border-amber-500/50">
        <div class="flex flex-col gap-2 md:w-1/3">
            <label for="degree-filter" class="text-sm font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                <i class="fas fa-balance-scale text-amber-500"></i> {{ __('Lawyer Degree') }}
            </label>
            <select id="degree-filter" wire:model.live="degree" class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                <option value="">{{ __('All') }}</option>
                <option value="نقض">{{ __('Cassation') }}</option>
                <option value="استئناف">{{ __('Appeal') }}</option>
                <option value="ابتدائي">{{ __('Primary') }}</option>
                <option value="جدول_عام">{{ __('General Table') }}</option>
            </select>
        </div>

        <div class="flex flex-col gap-2 md:w-2/3">
            <label for="searchInput" class="text-sm font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                <i class="fas fa-search text-amber-500"></i> {{ __('Search by Name, ID, or Registration Number') }}
            </label>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute rtl:right-4 ltr:left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500"></i>
                    <input type="text" id="searchInput" wire:model.live.debounce.300ms="search" placeholder="{{ __('Type for instant search...') }}" class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 rtl:pr-10 ltr:pl-10">
                </div>
                @if($search || $degree)
                    <button type="button" wire:click="clearFilters" class="px-6 py-3 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/50 rounded-xl text-sm font-bold transition-all hover:bg-red-100 dark:hover:bg-red-900/40 whitespace-nowrap flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> {{ __('Clear') }}
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Lawyers Table --}}
    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl overflow-hidden transition-all hover:border-amber-500/50">
        @if($lawyers->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-start" id="lawyersTable">
                    <thead class="bg-slate-100/80 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 text-xs uppercase tracking-wider font-bold border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[10%]">ID</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[30%]">{{ __('Lawyer Name') }}</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[20%]">{{ __('Degree') }}</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[25%]">{{ __('Specialization') }}</th>
                            <th class="px-6 py-4 text-center w-[15%]">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                        @foreach($lawyers as $lawyer)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors group" wire:key="lawyer-{{ $lawyer->id }}">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-slate-100">#{{ $lawyer->id }}</td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-lg flex-shrink-0 group-hover:border-amber-500 group-hover:text-amber-500 transition-colors">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <span class="font-bold text-slate-900 dark:text-slate-100 truncate w-full" title="{{ $lawyer->name }}">{{ $lawyer->name }}</span>
                                            @if($lawyer->email)
                                                <span class="text-xs text-slate-500 dark:text-slate-400 truncate w-full" title="{{ $lawyer->email }}">{{ $lawyer->email }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    @php
                                        $degreeClass = str_replace([' ', '_'], '-', $lawyer->degree ?? 'جدول-عام');
                                        $degreeColorClasses = match ($degreeClass) {
                                            'نقض' => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50',
                                            'استئناف' => 'bg-slate-200 text-slate-800 border-slate-300 dark:bg-slate-700 dark:text-slate-200 dark:border-slate-600',
                                            'ابتدائي' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800/50',
                                            default => 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700',
                                        };
                                    @endphp
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold border {{ $degreeColorClasses }} whitespace-nowrap">
                                        {{ __(str_replace('_', ' ', $lawyer->degree ?? 'Not Specified')) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="font-semibold text-slate-600 dark:text-slate-400">
                                        {{ $lawyer->specialization ?? __('—') }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('lawyers.show', $lawyer->id) }}" wire:navigate class="w-8 h-8 flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-900 hover:text-white dark:hover:bg-slate-700 rounded-lg text-sm font-bold transition-colors" title="{{ __('View Profile') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @can('update', $lawyer)
                                            <a href="{{ route('lawyers.edit', $lawyer->id) }}" wire:navigate class="w-8 h-8 flex items-center justify-center bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white dark:hover:bg-amber-500 dark:hover:text-white border border-amber-200 dark:border-amber-800/50 rounded-lg text-sm font-bold transition-colors" title="{{ __('Edit Data') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        
                                        @can('delete', $lawyer)
                                            <button type="button" wire:click="delete({{ $lawyer->id }})"
                                                wire:confirm="{{ __('Are you sure you want to permanently delete this lawyer?') }}"
                                                class="w-8 h-8 flex items-center justify-center bg-red-50 dark:bg-red-900/10 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/30 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white rounded-lg text-sm font-bold transition-colors" title="{{ __('Delete Lawyer') }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center flex flex-col items-center justify-center min-h-[400px]">
                <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6 border border-slate-100 dark:border-slate-700">
                    <i class="fas fa-user-slash text-4xl text-slate-300 dark:text-slate-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-3">
                    {{ __('No lawyers found') }}
                </h3>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8 leading-relaxed">
                    {{ __('No records match your selected search or filter.') }}
                </p>
                @if($search || $degree)
                    <button type="button" wire:click="clearFilters" class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl font-bold transition-all shadow-sm">
                        <i class="fas fa-redo"></i> {{ __('Clear Filters and Return') }}
                    </button>
                @endif
            </div>
        @endif
    </div>

</div>
