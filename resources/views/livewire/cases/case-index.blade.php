<div class="p-4 md:p-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Success Flash Banner --}}
    @if (session()->has('success'))
        <div class="mb-6 p-4 flex items-center gap-3 bg-green-50/80 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 rounded-xl backdrop-blur-sm">
            <i class="fas fa-check-circle"></i>
            <span class="font-medium">{{ __(session('success')) }}</span>
        </div>
    @endif

    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2">{{ __('Case Management') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('View and track all cases assigned to the firm with live search') }}</p>
        </div>
        
        <a href="{{ route('cases.create') }}" wire:navigate class="inline-flex items-center gap-3 bg-slate-900 dark:bg-amber-600 text-white py-2 px-6 rounded-full font-bold shadow-lg shadow-slate-900/20 dark:shadow-amber-600/20 transition-all hover:-translate-y-1 hover:shadow-xl group border-2 border-transparent">
            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-amber-500 dark:bg-white text-white dark:text-amber-600 transition-transform group-hover:rotate-90">
                <i class="fas fa-plus"></i>
            </span>
            <span>{{ __('Add New Case') }}</span>
        </a>
    </div>

    {{-- Search and Filters --}}
    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 mb-6 flex flex-wrap items-end gap-4">
        <div class="flex flex-col gap-2 flex-[2] min-w-[220px]">
            <label for="caseSearch" class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ __('Search by case number, client, or opponent') }}</label>
            <div class="relative flex items-center">
                <input type="text"
                       id="caseSearch"
                       wire:model.live.debounce.300ms="search"
                       placeholder="{{ __('Type to search live...') }}"
                       autocomplete="off"
                       class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 ps-12 rtl:ps-4 rtl:pe-12">
                <div class="absolute start-0 flex items-center h-full px-3 text-slate-400 dark:text-slate-500">
                    <i class="fas fa-search" wire:loading.remove wire:target="search"></i>
                    <i class="fas fa-spinner fa-spin text-amber-500" wire:loading wire:target="search"></i>
                </div>
                @if($search)
                    <button type="button" class="absolute end-0 px-4 h-full text-slate-400 hover:text-red-500 transition-colors border-s border-slate-200 dark:border-slate-700" wire:click="$set('search', '')" title="{{ __('Clear Search') }}">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
            </div>
        </div>

        <div class="flex flex-col gap-2 flex-1 min-w-[160px]">
            <label for="statusFilter" class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ __('Status') }}</label>
            <select id="statusFilter" wire:model.live="statusFilter" class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                <option value="">{{ __('All Statuses') }}</option>
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

        <div class="flex flex-col gap-2 flex-1 min-w-[160px]">
            <label for="jurisdictionFilter" class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ __('Jurisdiction') }}</label>
            <select id="jurisdictionFilter" wire:model.live="jurisdictionFilter" class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                <option value="">{{ __('All Jurisdictions') }}</option>
                @foreach($jurisdictions as $jurisdiction)
                    <option value="{{ $jurisdiction->id }}">{{ __($jurisdiction->name) }}</option>
                @endforeach
            </select>
        </div>

        @if($courts->isNotEmpty())
            <div class="flex flex-col gap-2 flex-1 min-w-[160px]">
                <label for="courtFilter" class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ __('Court') }}</label>
                <select id="courtFilter" wire:model.live="courtFilter" class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                    <option value="">{{ __('All Courts') }}</option>
                    @foreach($courts as $court)
                        <option value="{{ $court->id }}">{{ __($court->name) }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if(auth()->check() && auth()->user()->role === 'lawyer')
            <div class="flex flex-col gap-2 flex-1 min-w-[160px]">
                <label for="lawyerRoleFilter" class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ __('My Role in Case') }}</label>
                <select id="lawyerRoleFilter" wire:model.live="lawyerRoleFilter" class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                    <option value="">{{ __('All') }}</option>
                    <option value="lead">{{ __('Lead Lawyer') }}</option>
                    <option value="assistant">{{ __('Assistant') }}</option>
                    <option value="consultant">{{ __('Consultant') }}</option>
                </select>
            </div>
        @endif

        <button type="button" class="inline-flex items-center gap-2 px-6 py-3 bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all font-semibold whitespace-nowrap" wire:click="clearFilters">
            <i class="fas fa-redo text-sm"></i> {{ __('Clear Filters') }}
        </button>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div class="text-sm font-semibold text-slate-500 dark:text-slate-400">
            {{ __('Total Results:') }} <span class="text-lg font-bold text-slate-900 dark:text-amber-400 mx-1">{{ $cases->total() }}</span> {{ __('Cases') }}
        </div>
        <div>
            <select wire:model.live="sortBy" class="bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-2">
                <option value="created_at">{{ __('Recently Added') }}</option>
                <option value="case_number">{{ __('Case Number') }}</option>
                <option value="status">{{ __('Status') }}</option>
            </select>
        </div>
    </div>

    @if($cases->isEmpty())
        <div class="text-center py-20 bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl">
            <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <i class="fas fa-briefcase text-4xl text-slate-300 dark:text-slate-600"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-2">{{ __('No cases match the search criteria') }}</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-8">{{ __('Try adjusting the filters or start by adding a new case.') }}</p>
            
            <a href="{{ route('cases.create') }}" wire:navigate class="inline-flex items-center gap-3 bg-slate-900 dark:bg-amber-600 text-white py-2 px-6 rounded-full font-bold shadow-lg shadow-slate-900/20 dark:shadow-amber-600/20 transition-all hover:-translate-y-1 hover:shadow-xl group">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-amber-500 dark:bg-white text-white dark:text-amber-600 transition-transform group-hover:rotate-90">
                    <i class="fas fa-plus"></i>
                </span>
                <span>{{ __('Add New Case') }}</span>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cases as $case)
                @php
                    $client = $case->clients?->first();
                    $leadLawyer = $case->lawyers->where('pivot.role', 'lead')->first() 
                               ?? $case->lawyers->where('pivot.role', 'محامي رئيسي')->first() 
                               ?? $case->lawyer;
                    
                    // Status Badge Coloring logic inside blade template purely
                    $statusColorClasses = match ($case->status) {
                        'مفتوحة' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                        'متداولة' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                        'مؤجلة' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                        'مغلقة', 'منتهية' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        'حكم نهائي' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
                        default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300'
                    };
                @endphp
                <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 flex flex-col gap-4 shadow-sm dark:shadow-none backdrop-blur-sm transition-all hover:-translate-y-1 hover:shadow-xl hover:border-amber-500 dark:hover:border-amber-500 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 left-0 h-1 bg-amber-500 origin-left scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></div>
                    
                    <div class="flex justify-between items-start gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                        <div class="flex flex-col gap-1">
                            <div class="text-lg font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                <i class="fas fa-user-circle text-amber-500"></i>
                                {{ $client->name ?? __('Not Specified') }}
                            </div>
                            <div class="text-xs font-mono font-semibold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded w-fit dir-ltr">
                                # {{ $case->case_number }}
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap {{ $statusColorClasses }}">
                            {{ __($case->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-100 dark:border-slate-800">
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('Jurisdiction Type') }}</span>
                            <span class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ __($case->jurisdiction->name ?? ($case->court->jurisdiction->name ?? '-')) }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('Court') }}</span>
                            <span class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ __($case->court->name ?? '-') }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('Court Level') }}</span>
                            <span class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ __($case->courtLevel->name ?? '-') }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('Lead Lawyer') }}</span>
                            <span class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $leadLawyer->name ?? '-' }}</span>
                        </div>
                        <div class="col-span-2 flex flex-col gap-1">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('Opponent Name') }}</span>
                            <span class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $case->rival_name ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-auto pt-2">
                        <a href="{{ route('cases.show', $case) }}" wire:navigate class="flex-1 inline-flex justify-center items-center gap-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-900 hover:text-white dark:hover:bg-slate-700 py-2 rounded-xl text-sm font-bold transition-colors">
                            <i class="fas fa-folder-open"></i> {{ __('Case Details') }}
                        </a>
                        <a href="{{ route('cases.edit', $case) }}" wire:navigate class="w-10 h-10 inline-flex justify-center items-center bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white dark:hover:bg-amber-500 dark:hover:text-white rounded-xl text-sm transition-colors border border-amber-200 dark:border-amber-800/50" title="{{ __('Edit') }}">
                            <i class="fas fa-edit"></i>
                        </a>
                        @can('delete', $case)
                            <button type="button" wire:click="confirmDelete({{ $case->id }})" class="w-10 h-10 inline-flex justify-center items-center bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white rounded-xl text-sm transition-colors border border-red-200 dark:border-red-800/50" title="{{ __('Delete') }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        @endcan
                    </div>

                </div>
            @endforeach
        </div>

        <div class="mt-8 flex justify-center">
            {{ $cases->links() }}
        </div>
    @endif

    {{-- Reactive Delete Confirmation Modal --}}
    @if($confirmingDeleteId)
        <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-md flex items-center justify-center z-[3000] p-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-100 rounded-2xl shadow-2xl p-8 max-w-md w-full text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">{{ __('Confirm Case Deletion') }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">{{ __('Are you sure you want to permanently delete this case along with all its attachments and records? This action cannot be undone.') }}</p>
                <div class="flex gap-3 justify-center">
                    <button type="button" wire:click="cancelDelete" class="px-6 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl font-bold transition-colors">{{ __('Cancel') }}</button>
                    <button type="button" wire:click="deleteCase({{ $confirmingDeleteId }})" class="inline-flex items-center gap-2 px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold shadow-lg shadow-red-600/20 transition-colors">
                        <i class="fas fa-trash-alt"></i>
                        <span>{{ __('Yes, Delete Case') }}</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
