<div class="p-4 md:p-8">

    {{-- Global loading overlay for full re-renders --}}
    <div wire:loading.delay.long class="fixed inset-0 z-40 bg-black/20 flex items-center justify-center">
        <div class="panel p-4">{{ __('جاري التحميل...') }}</div>
    </div>

    {{-- Success Flash Banner --}}
    @if (session()->has('success'))
        <div class="panel-subtle mb-6 p-4 flex items-center gap-3 border text-[var(--color-success)]" style="border-color: var(--color-success);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="panel-header mb-8 flex justify-between items-start flex-wrap gap-4">
        <div>
            <h1 class="panel-title flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ __('إدارة القضايا') }}
            </h1>
            <p class="text-muted mt-2">{{ __('عرض وتتبع جميع القضايا المسندة للمكتب مع بحث مباشر') }}</p>
        </div>
        
        <a href="{{ route('cases.create') }}" wire:navigate class="btn-add-new flex items-center gap-2">
            <span class="icon-circle">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14M5 12h14"/></svg>
            </span>
            <span>{{ __('إضافة قضية جديدة') }}</span>
        </a>
    </div>

    {{-- Search and Filters --}}
    <div class="panel flex flex-wrap items-end gap-4 mb-6">
        <div class="field flex-[2] min-w-[220px]">
            <label for="caseSearch" class="mb-2 block">{{ __('البحث برقم القضية، الموكل، أو الخصم') }}</label>
            <div class="input-icon-wrap">
                <input type="text"
                       id="caseSearch"
                       wire:model.live.debounce.400ms="search"
                       placeholder="{{ __('اكتب للبحث المباشر...') }}"
                       autocomplete="off"
                       class="app-input w-full">
                @if($search)
                    <button type="button" class="btn-secondary flex items-center justify-center absolute end-1 top-1 bottom-1 px-3 rounded-lg transition-all duration-200 hover:bg-black/5 dark:hover:bg-white/5" wire:click="$set('search', '')" title="{{ __('مسح البحث') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                @endif
            </div>
        </div>

        <div class="field flex-1 min-w-[160px]">
            <label for="statusFilter" class="mb-2 block">{{ __('الحالة') }}</label>
            <div class="select-wrap">
                <select id="statusFilter" wire:model.live="statusFilter" class="app-select w-full">
                    <option value="">{{ __('جميع الحالات') }}</option>
                    @foreach(['مفتوحة', 'متداولة', 'مؤجلة', 'محجوزة للحكم', 'منتهية', 'مستأنفة', 'محفوظة', 'معلقة'] as $s)
                        <option value="{{ $s }}">{{ __($s) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="field flex-1 min-w-[160px]">
            <label for="jurisdictionFilter" class="mb-2 block">{{ __('نوع القضية') }}</label>
            <div class="select-wrap">
                <select id="jurisdictionFilter" wire:model.live="jurisdictionFilter" class="app-select w-full">
                    <option value="">{{ __('الكل') }}</option>
                    @foreach($jurisdictions as $jurisdiction)
                        <option value="{{ $jurisdiction->id }}">{{ __($jurisdiction->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($courts->isNotEmpty())
            <div class="field flex-1 min-w-[160px]">
                <label for="courtFilter" class="mb-2 block">{{ __('المحكمة') }}</label>
                <div class="select-wrap">
                    <select id="courtFilter" wire:model.live="courtFilter" class="app-select w-full">
                        <option value="">{{ __('كل المحاكم') }}</option>
                        @foreach($courts as $court)
                            <option value="{{ $court->id }}">{{ __($court->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        @if(auth()->check() && auth()->user()->role === 'lawyer')
            <div class="field flex-1 min-w-[160px]">
                <label for="lawyerRoleFilter" class="mb-2 block">{{ __('دوري في القضية') }}</label>
                <div class="select-wrap">
                    <select id="lawyerRoleFilter" wire:model.live="lawyerRoleFilter" class="app-select w-full">
                        <option value="">{{ __('الكل') }}</option>
                        <option value="lead">{{ __('محامي رئيسي') }}</option>
                        <option value="assistant">{{ __('مساعد') }}</option>
                        <option value="consultant">{{ __('مستشار') }}</option>
                    </select>
                </div>
            </div>
        @endif

        <button type="button" class="btn-secondary flex items-center justify-center gap-2 px-6 py-3 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md" wire:click="clearFilters">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            {{ __('مسح الفلاتر') }}
        </button>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div>
            <span class="text-muted">{{ __('إجمالي النتائج:') }}</span> 
            <span class="badge-item mx-1">{{ $cases->total() }}</span> 
            <span class="text-muted">{{ __('قضية') }}</span>
        </div>
        <div class="select-wrap min-w-[150px]">
            <select wire:model.live="sortBy" class="app-select">
                <option value="created_at">{{ __('المضافة حديثاً') }}</option>
                <option value="case_number">{{ __('رقم القضية') }}</option>
                <option value="status">{{ __('الحالة') }}</option>
            </select>
        </div>
    </div>

    @if($cases->isEmpty())
        <div class="panel text-center py-20">
            <div class="panel-subtle flex items-center justify-center mx-auto mb-6 w-24 h-24 rounded-full">
                <svg class="w-12 h-12 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="panel-title justify-center mb-2">{{ __('لا توجد قضايا مطابقة لمعايير البحث') }}</h3>
            <p class="text-muted mb-8">{{ __('حاول تغيير الفلاتر أو ابدأ بإضافة قضية جديدة.') }}</p>
            
            <a href="{{ route('cases.create') }}" wire:navigate class="btn-add-new inline-flex items-center gap-3">
                <span class="icon-circle">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14M5 12h14"/></svg>
                </span>
                <span>{{ __('إضافة قضية جديدة') }}</span>
            </a>
        </div>
    @else
        <div wire:loading.delay class="text-center py-4 text-muted w-full">{{ __('جاري التحميل...') }}</div>
        <div wire:loading.remove class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cases as $case)
                @php
                    $client = $case->clients?->first();
                    $leadLawyer = $case->lawyers->where('pivot.role', 'lead')->first() 
                                ?? $case->lawyers->where('pivot.role', 'محامي رئيسي')->first();
                    
                    $badgeClass = match ($case->status) {
                        'مفتوحة', 'متداولة' => 'badge-success',
                        'مؤجلة', 'محجوزة للحكم', 'معلقة' => 'badge-warning',
                        'منتهية', 'محفوظة' => 'badge-danger',
                        'مستأنفة' => 'badge-appeal',
                        default => 'badge-info',
                    };
                @endphp
                <div class="panel flex flex-col gap-4">
                    <!-- Header -->
                    <div class="flex justify-between items-start pb-3 border-b border-[var(--border-color)]">
                        <div class="flex flex-col gap-1">
                            <h3 class="panel-title flex items-center gap-2">
                                <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $client->name ?? __('غير محدد') }}
                            </h3>
                            <span class="text-muted text-sm" dir="ltr"># {{ $case->case_number }}</span>
                        </div>
                        <span class="badge-item {{ $badgeClass }}">{{ __($case->status) }}</span>
                    </div>

                    <!-- Details -->
                    <div class="panel-subtle grid grid-cols-2 gap-3 p-4 rounded-xl">
                        <div class="flex flex-col gap-1">
                            <span class="text-muted text-xs">{{ __('نوع القضية') }}</span>
                            <span class="font-bold">{{ $case->court?->jurisdiction?->name ?? '—' }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-muted text-xs">{{ __('المحامي الرئيسي') }}</span>
                            <span class="font-bold">{{ $leadLawyer->name ?? '—' }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-muted text-xs">{{ __('المحكمة') }}</span>
                            <span class="font-bold">{{ $case->court?->name ?? '—' }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-muted text-xs">{{ __('درجة التقاضي') }}</span>
                            <span class="font-bold">{{ $case->courtLevel?->name ?? '—' }}</span>
                        </div>
                        <div class="flex flex-col gap-1 col-span-2">
                            <span class="text-muted text-xs">{{ __('الخصم') }}</span>
                            <span class="font-bold">{{ $case->rival_name ?? '—' }}</span>
                        </div>
                    </div>

                    <!-- Thumbnail Preview -->
                    @php
                        $hasMainFile = $case->case_file && \Illuminate\Support\Facades\Storage::disk('public')->exists($case->case_file);
                        $validDocs = $case->documents->filter(fn($d) => $d->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($d->file_path));
                        $totalAttachments = ($hasMainFile ? 1 : 0) + $validDocs->count();
                        
                        $firstMediaUrl = null;
                        $firstMediaType = null;
                        
                        if ($totalAttachments > 0) {
                            if ($hasMainFile) {
                                $ext = strtolower(pathinfo($case->case_file, PATHINFO_EXTENSION));
                                $firstMediaUrl = asset('storage/' . $case->case_file);
                                $firstMediaType = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']) ? 'image' : ($ext === 'pdf' ? 'pdf' : 'other');
                            } else {
                                $firstDoc = $validDocs->first();
                                $ext = strtolower(pathinfo($firstDoc->file_path, PATHINFO_EXTENSION));
                                $firstMediaUrl = asset('storage/' . $firstDoc->file_path);
                                $firstMediaType = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']) ? 'image' : ($ext === 'pdf' ? 'pdf' : 'other');
                            }
                        }
                    @endphp

                    @if($totalAttachments > 0)
                        <div class="pt-2">
                            <div wire:click="openMediaViewer({{ $case->id }})" class="relative group h-24 rounded-lg overflow-hidden border border-[var(--border-color)] bg-[var(--bg-subtle)] cursor-pointer flex items-center justify-center">
                                @if($firstMediaType === 'image')
                                    <img src="{{ $firstMediaUrl }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" alt="{{ __('مرفق') }}">
                                @elseif($firstMediaType === 'pdf')
                                    <svg class="w-10 h-10 text-[var(--color-danger)] transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                @else
                                    <svg class="w-10 h-10 text-[var(--color-gold)] transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                @endif
                                
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white font-bold flex items-center gap-2 text-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ __('عرض المرفقات') }} ({{ $totalAttachments }})
                                    </span>
                                </div>
                                
                                @if($totalAttachments > 1)
                                <div class="absolute top-2 right-2 bg-black/70 text-white text-xs font-bold px-2 py-1 rounded-md">
                                    +{{ $totalAttachments - 1 }}
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2 pt-4 mt-auto">
                        <a href="{{ route('cases.show', $case) }}" wire:navigate class="btn-primary flex items-center justify-center gap-2 flex-1 py-2 px-4 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                            {{ __('تفاصيل القضية') }}
                        </a>
                        
                        <a href="{{ route('cases.edit', $case) }}" wire:navigate class="btn-action-edit flex items-center justify-center p-2 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md" title="{{ __('تعديل') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </a>
                        
                        @can('delete', $case)
                            <button type="button" wire:click="confirmDelete({{ $case->id }})" class="btn-action-delete flex items-center justify-center p-2 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md" title="{{ __('حذف') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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

    {{-- Reactive Delete Confirmation Modal per Pillar 2.4 --}}
    @if($confirmingDeleteId)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background: rgba(0,0,0,.65);"
         wire:click.self="cancelDelete">
        <div class="panel max-w-sm w-full">
            <div class="panel-title mb-4">{{ __('تأكيد حذف القضية') }}</div>
            <p class="text-muted mb-6">
                {{ __('هل أنت متأكد من رغبتك في حذف هذه القضية نهائياً مع جميع مرفقاتها وسجلاتها؟ لا يمكن التراجع عن هذا الإجراء.') }}
            </p>
            <div class="flex gap-3 justify-end">
                <button wire:click="cancelDelete" class="btn-secondary">{{ __('إلغاء') }}</button>
                <button wire:click="deleteCase({{ $confirmingDeleteId }})"
                        wire:loading.attr="disabled"
                        class="btn-primary">
                    <span wire:loading.remove wire:target="deleteCase({{ $confirmingDeleteId }})">{{ __('نعم، احذف القضية') }}</span>
                    <span wire:loading wire:target="deleteCase({{ $confirmingDeleteId }})">{{ __('جاري الحذف...') }}</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Smart Media Viewer Modal -->
    @if($isMediaViewerOpen && count($mediaGallery) > 0)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm p-4" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="relative w-full max-w-5xl bg-[var(--bg-panel)] rounded-2xl shadow-2xl overflow-hidden flex flex-col h-[90vh]">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-[var(--border-color)] bg-[var(--bg-panel)]">
                <div class="flex items-center gap-3">
                    <span class="text-lg font-bold text-[var(--text-color)]">{{ $mediaGallery[$currentMediaIndex]['title'] }}</span>
                    <span class="text-sm px-3 py-1 rounded-full bg-[var(--bg-subtle)] border border-[var(--border-color)] text-muted" dir="ltr">
                        {{ $currentMediaIndex + 1 }} / {{ count($mediaGallery) }}
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" wire:click="closeMediaViewer" class="p-2 rounded-full hover:bg-[var(--bg-subtle)] transition-colors">
                        <svg class="w-6 h-6 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-hidden flex items-center justify-center relative bg-[var(--bg-body)]">
                
                <!-- Left Nav Arrow -->
                @if($currentMediaIndex > 0)
                <button type="button" wire:click="prevMedia" class="absolute rtl:right-4 ltr:left-4 z-10 p-3 rounded-full bg-black/50 text-white hover:bg-[var(--color-gold)] transition-colors">
                    <svg class="w-6 h-6 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                @endif

                <!-- Media Display -->
                <div class="w-full h-full flex items-center justify-center p-4">
                    @php $media = $mediaGallery[$currentMediaIndex]; @endphp
                    
                    @if($media['type'] === 'image')
                        <img src="{{ $media['url'] }}" alt="{{ $media['title'] }}" class="max-w-full max-h-full object-contain rounded-lg shadow-lg">
                    @elseif($media['type'] === 'pdf')
                        <iframe src="{{ $media['url'] }}" class="w-full h-full rounded-lg shadow-lg border-0"></iframe>
                    @else
                        <!-- Word / Other Files -->
                        <div class="text-center p-8 bg-[var(--bg-panel)] rounded-2xl border border-[var(--border-color)] shadow-sm max-w-md w-full">
                            <svg class="w-20 h-20 mx-auto text-[var(--color-gold)] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <h3 class="text-xl font-bold mb-2">{{ __('لا يمكن معاينة هذا الملف') }}</h3>
                            <p class="text-muted mb-6">{{ __('صيغة الملف غير مدعومة للمعاينة المباشرة. يرجى تحميله لفتحه.') }}</p>
                            <a href="{{ $media['url'] }}" download class="btn-primary inline-flex items-center gap-2 px-6 py-2.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                {{ __('تحميل الملف') }}
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Right Nav Arrow -->
                @if($currentMediaIndex < count($mediaGallery) - 1)
                <button type="button" wire:click="nextMedia" class="absolute rtl:left-4 ltr:right-4 z-10 p-3 rounded-full bg-black/50 text-white hover:bg-[var(--color-gold)] transition-colors">
                    <svg class="w-6 h-6 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                @endif

            </div>
        </div>
    </div>
    @endif

</div>
