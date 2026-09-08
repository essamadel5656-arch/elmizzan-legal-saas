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
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2">{{ __('إدارة القضايا') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('عرض وتتبع جميع القضايا المسندة للمكتب مع بحث مباشر') }}</p>
        </div>
        
        <a href="{{ route('cases.create') }}" wire:navigate class="btn-add-new inline-flex items-center gap-3 bg-slate-900 dark:bg-amber-600 text-white py-2 px-6 rounded-full font-bold transition-all hover:-translate-y-1 group">
            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-amber-500 dark:bg-white text-white dark:text-amber-600 transition-transform group-hover:rotate-90">
                <i class="fas fa-plus"></i>
            </span>
            <span>{{ __('إضافة قضية جديدة') }}</span>
        </a>
    </div>

    {{-- Search and Filters --}}
    <div class="panel flex flex-wrap items-end gap-4 mb-6">
        <div class="flex flex-col gap-2 flex-[2] min-w-[220px]">
            <label for="caseSearch" class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ __('البحث برقم القضية، الموكل، أو الخصم') }}</label>
            <div class="input-icon-wrap">
                <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text"
                       id="caseSearch"
                       wire:model.live.debounce.300ms="search"
                       placeholder="{{ __('اكتب للبحث المباشر...') }}"
                       autocomplete="off"
                       class="app-input w-full transition-all text-sm">
                @if($search)
                    <button type="button" class="absolute end-0 px-4 h-full text-slate-400 hover:text-red-500 transition-colors border-s border-slate-200 dark:border-slate-700" wire:click="$set('search', '')" title="{{ __('مسح البحث') }}">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
            </div>
        </div>

        <div class="flex flex-col gap-2 flex-1 min-w-[160px]">
            <label for="statusFilter" class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ __('الحالة') }}</label>
            <div class="select-wrap">
                <select id="statusFilter" wire:model.live="statusFilter" class="app-select w-full transition-all text-sm">
                    <option value="">{{ __('جميع الحالات') }}</option>
                    <option value="مفتوحة">{{ __('مفتوحة') }}</option>
                    <option value="متداولة">{{ __('متداولة') }}</option>
                    <option value="مؤجلة">{{ __('مؤجلة') }}</option>
                    <option value="محجوزة للحكم">{{ __('محجوزة للحكم') }}</option>
                    <option value="منتهية">{{ __('منتهية') }}</option>
                    <option value="مستأنفة">{{ __('مستأنفة') }}</option>
                    <option value="محفوظة">{{ __('محفوظة') }}</option>
                    <option value="معلقة">{{ __('معلقة') }}</option>
                </select>
                <svg class="select-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>

        <div class="flex flex-col gap-2 flex-1 min-w-[160px]">
            <label for="jurisdictionFilter" class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ __('نوع القضية') }}</label>
            <div class="select-wrap">
                <select id="jurisdictionFilter" wire:model.live="jurisdictionFilter" class="app-select w-full transition-all text-sm">
                    <option value="">{{ __('الكل') }}</option>
                    @foreach($jurisdictions as $jurisdiction)
                        <option value="{{ $jurisdiction->id }}">{{ __($jurisdiction->name) }}</option>
                    @endforeach
                </select>
                <svg class="select-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>

        @if($courts->isNotEmpty())
            <div class="flex flex-col gap-2 flex-1 min-w-[160px]">
                <label for="courtFilter" class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ __('المحكمة') }}</label>
                <div class="select-wrap">
                    <select id="courtFilter" wire:model.live="courtFilter" class="app-select w-full transition-all text-sm">
                        <option value="">{{ __('كل المحاكم') }}</option>
                        @foreach($courts as $court)
                            <option value="{{ $court->id }}">{{ __($court->name) }}</option>
                        @endforeach
                    </select>
                    <svg class="select-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        @endif

        @if(auth()->check() && auth()->user()->role === 'lawyer')
            <div class="flex flex-col gap-2 flex-1 min-w-[160px]">
                <label for="lawyerRoleFilter" class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ __('دوري في القضية') }}</label>
                <div class="select-wrap">
                    <select id="lawyerRoleFilter" wire:model.live="lawyerRoleFilter" class="app-select w-full transition-all text-sm">
                        <option value="">{{ __('الكل') }}</option>
                        <option value="lead">{{ __('محامي رئيسي') }}</option>
                        <option value="assistant">{{ __('مساعد') }}</option>
                        <option value="consultant">{{ __('مستشار') }}</option>
                    </select>
                    <svg class="select-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        @endif

        <button type="button" class="inline-flex items-center gap-2 px-6 py-3 bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all font-semibold whitespace-nowrap" wire:click="clearFilters">
            <i class="fas fa-redo text-sm"></i> {{ __('مسح الفلاتر') }}
        </button>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div class="text-sm font-semibold text-slate-500 dark:text-slate-400">
            {{ __('إجمالي النتائج:') }} <span class="text-lg font-bold text-slate-900 dark:text-amber-400 mx-1">{{ $cases->total() }}</span> {{ __('قضية') }}
        </div>
        <div class="select-wrap">
            <select wire:model.live="sortBy" class="app-select transition-all text-sm">
                <option value="created_at">{{ __('المضافة حديثاً') }}</option>
                <option value="case_number">{{ __('رقم القضية') }}</option>
                <option value="status">{{ __('الحالة') }}</option>
            </select>
            <svg class="select-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </div>
    </div>

    @if($cases->isEmpty())
        <div class="panel text-center py-20">
            <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <i class="fas fa-briefcase text-4xl text-slate-300 dark:text-slate-600"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-2">{{ __('لا توجد قضايا مطابقة لمعايير البحث') }}</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-8">{{ __('حاول تغيير الفلاتر أو ابدأ بإضافة قضية جديدة.') }}</p>
            
            <a href="{{ route('cases.create') }}" wire:navigate class="btn-add-new inline-flex items-center gap-3 bg-slate-900 dark:bg-amber-600 text-white py-2 px-6 rounded-full font-bold transition-all hover:-translate-y-1 group">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-amber-500 dark:bg-white text-white dark:text-amber-600 transition-transform group-hover:rotate-90">
                    <i class="fas fa-plus"></i>
                </span>
                <span>{{ __('إضافة قضية جديدة') }}</span>
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
                    
                    // Status Badge
                    $badgeClass = match ($case->status) {
                        'مفتوحة' => 'badge-success',
                        'متداولة' => 'badge-info',
                        'مؤجلة' => 'badge-warning',
                        'مغلقة', 'منتهية' => 'badge-danger',
                        'حكم نهائي' => 'badge-primary',
                        default => 'badge-secondary'
                    };
                @endphp
                <div class="panel flex flex-col gap-4">
                    <!-- Header: Client Name, Case Number, and Status -->
                    <div class="flex justify-between items-start border-b border-slate-200 dark:border-slate-800 pb-3">
                        <div class="flex flex-col">
                            <h3 class="font-bold text-lg text-primary">{{ $client->name ?? __('غير محدد') }}</h3>
                            <span class="text-sm text-muted"># {{ $case->case_number }}</span>
                        </div>
                        <div>
                            <span class="badge {{ $badgeClass }}">{{ __($case->status) }}</span>
                        </div>
                    </div>

                    <!-- Highlighted Top Metadata (The "Important Stuff") -->
                    <div class="grid grid-cols-2 gap-3 bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-700/50">
                        <div class="flex flex-col items-start text-start">
                            <span class="text-xs text-slate-500">{{ __('نوع القضية') }}</span>
                            <span class="font-bold text-slate-900 dark:text-slate-100">{{ __($case->jurisdiction->name ?? ($case->court->jurisdiction->name ?? '-')) }}</span>
                        </div>
                        <div class="flex flex-col items-start text-start">
                            <span class="text-xs text-slate-500">{{ __('المحامي الرئيسي') }}</span>
                            <span class="font-bold text-slate-900 dark:text-slate-100">{{ $leadLawyer->name ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- Secondary Metadata Grid -->
                    <div class="grid grid-cols-2 gap-4 text-sm mt-1">
                        <div class="flex flex-col items-start text-start">
                            <span class="text-xs text-slate-500">{{ __('المحكمة') }}</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ __($case->court->name ?? '-') }}</span>
                        </div>
                        <div class="flex flex-col items-start text-start">
                            <span class="text-xs text-slate-500">{{ __('درجة التقاضي') }}</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ __($case->courtLevel->name ?? '-') }}</span>
                        </div>
                        <div class="flex flex-col items-start text-start col-span-2">
                            <span class="text-xs text-slate-500">{{ __('الخصم') }}</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $case->rival_name ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- Footer: Actions -->
                    <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-200 dark:border-slate-800">
                        <a href="{{ route('cases.show', $case) }}" wire:navigate class="text-sm font-bold text-amber-600 hover:text-amber-500 ml-auto flex items-center gap-1.5 transition-colors">
                            <i class="fas fa-folder-open"></i> {{ __('تفاصيل القضية') }}
                        </a>
                        
                        <a href="{{ route('cases.edit', $case) }}" wire:navigate class="btn-action btn-action-edit" title="{{ __('تعديل') }}">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        @can('delete', $case)
                            <button type="button" wire:click="confirmDelete({{ $case->id }})" class="btn-action btn-action-delete" title="{{ __('حذف') }}">
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
            <div class="panel max-w-md w-full text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">{{ __('تأكيد حذف القضية') }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">{{ __('هل أنت متأكد من رغبتك في حذف هذه القضية نهائياً مع جميع مرفقاتها وسجلاتها؟ لا يمكن التراجع عن هذا الإجراء.') }}</p>
                <div class="flex gap-3 justify-center">
                    <button type="button" wire:click="cancelDelete" class="btn-secondary px-6 py-2 rounded-xl font-bold transition-colors">{{ __('إلغاء') }}</button>
                    <button type="button" wire:click="deleteCase({{ $confirmingDeleteId }})" class="inline-flex items-center gap-2 px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold shadow-lg shadow-red-600/20 transition-colors">
                        <i class="fas fa-trash-alt"></i>
                        <span>{{ __('نعم، احذف القضية') }}</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
