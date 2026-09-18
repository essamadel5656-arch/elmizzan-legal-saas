<div class="p-4 md:p-8 max-w-7xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    @php
        $badgeClass = match ($case->status) {
            'مفتوحة', 'جارية', 'متداولة' => 'badge-success',
            'مؤجلة', 'محجوزة للحكم', 'معلقة' => 'badge-warning',
            'مغلقة', 'منتهية', 'محفوظة' => 'badge-danger',
            default => 'badge-info'
        };
    @endphp

    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('cases.index') }}" wire:navigate class="btn-secondary px-4 py-2 flex items-center gap-2">
                <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('رجوع للقائمة') }}
            </a>
            <h1 class="panel-title m-0 text-xl md:text-2xl flex items-center gap-2">
                {{ __('ملف القضية:') }} <span dir="ltr" class="mx-1">#{{ $case->case_number }}</span>
            </h1>
            <span class="badge-item {{ $badgeClass }} font-bold text-sm">{{ __($case->status) }}</span>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('cases.edit', $case) }}" wire:navigate class="btn-action-edit px-4 py-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                {{ __('تعديل البيانات') }}
            </a>
            @can('delete', $case)
                <button type="button" wire:click="confirmDelete" class="btn-action-delete px-4 py-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    {{ __('حذف القضية') }}
                </button>
            @endcan
        </div>
    </div>

    <!-- Alert Success -->
    @if (session()->has('success'))
        <div class="panel-subtle mb-6 p-4 flex items-center gap-3 border border-green-500/20 text-green-600 rounded-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ __(session('success')) }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- MAIN CONTENT (Left/Right depending on RTL) -->
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Essential Case Info --}}
            <div class="panel p-6">
                <div class="panel-header mb-4 pb-3 border-b">
                    <h2 class="panel-title flex items-center gap-2">
                        <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        {{ __('تفاصيل القضية') }}
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6">
                    <div class="flex flex-col gap-1">
                        <span class="text-xs text-muted font-bold uppercase">{{ __('المحكمة المختصة') }}</span>
                        <span class="text-base font-semibold">{{ __($case->court->name ?? __('غير محدد')) }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs text-muted font-bold uppercase">{{ __('نوع القضية') }}</span>
                        <span class="text-base font-semibold">{{ __($case->jurisdiction->name ?? ($case->court->jurisdiction->name ?? '-')) }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs text-muted font-bold uppercase">{{ __('الدائرة') }}</span>
                        <span class="text-base font-semibold">{{ $case->circuit ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs text-muted font-bold uppercase">{{ __('درجة التقاضي') }}</span>
                        <span class="text-base font-semibold">{{ __($case->courtLevel->name ?? '-') }}</span>
                    </div>
                </div>

                @if($case->description)
                <div class="mt-6 pt-4 border-t">
                    <span class="text-xs text-muted font-bold uppercase block mb-2">{{ __('وصف القضية / الوقائع') }}</span>
                    <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $case->description }}</p>
                </div>
                @endif
            </div>

            {{-- People Involved (Clients & Opponent) --}}
            <div class="panel p-0 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 divide-x rtl:divide-x-reverse border">
                    
                    {{-- Clients --}}
                    <div class="p-6">
                        <h3 class="panel-title text-base mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            {{ __('موكلين المكتب') }}
                        </h3>
                        @if($case->clients->count() > 0)
                            <div class="space-y-3">
                                @foreach($case->clients as $client)
                                    <div class="flex items-center gap-3 p-3 panel-subtle border rounded-xl">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-primary/10 text-primary">
                                            {{ mb_substr($client->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col flex-1">
                                            <a href="{{ route('clients.show', $client) }}" class="font-bold hover:underline">{{ $client->name }}</a>
                                            <span class="text-xs text-muted" dir="ltr">{{ $client->phone ?? __('بدون رقم') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-sm italic">{{ __('لا يوجد موكلين مسجلين') }}</p>
                        @endif
                    </div>

                    {{-- Opponent --}}
                    <div class="p-6">
                        <h3 class="panel-title text-base mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"/></svg>
                            {{ __('الخصم') }}
                        </h3>
                        <div class="flex flex-col gap-3">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs text-muted font-bold uppercase">{{ __('اسم الخصم') }}</span>
                                <span class="text-sm font-semibold">{{ $case->rival_name ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-xs text-muted font-bold uppercase">{{ __('رقم الهاتف') }}</span>
                                <span class="text-sm font-semibold" dir="ltr">{{ $case->rival_number ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-xs text-muted font-bold uppercase">{{ __('الرقم القومي') }}</span>
                                <span class="text-sm font-semibold" dir="ltr">{{ $case->rival_nid ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-xs text-muted font-bold uppercase">{{ __('العنوان') }}</span>
                                <span class="text-sm font-semibold">{{ $case->rival_address ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs Section (Livewire) --}}
            <div class="panel p-0 overflow-hidden">
                <div class="flex overflow-x-auto border-b border">
                    <button wire:click="setTab('tasks')" class="px-6 py-4 font-bold text-sm whitespace-nowrap transition-colors {{ $activeTab === 'tasks' ? 'border-b-2' : 'text-muted' }} border">
                        {{ __('المهام والجلسات') }}
                    </button>
                    <button wire:click="setTab('documents')" class="px-6 py-4 font-bold text-sm whitespace-nowrap transition-colors {{ $activeTab === 'documents' ? 'border-b-2' : 'text-muted' }} border">
                        {{ __('المستندات والمرفقات') }}
                    </button>
                    <button wire:click="setTab('expenses')" class="px-6 py-4 font-bold text-sm whitespace-nowrap transition-colors {{ $activeTab === 'expenses' ? 'border-b-2' : 'text-muted' }} border">
                        {{ __('المصروفات') }}
                    </button>
                </div>
                
                <div class="p-6">
                    @if($activeTab === 'tasks')
                        {{-- Replace with Livewire Task Component for this case --}}
                        <div class="text-center py-10 text-muted">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            <p>{{ __('لا توجد جلسات مسجلة حالياً لهذه القضية') }}</p>
                        </div>
                    @elseif($activeTab === 'documents')
                        @livewire('cases.document-requests', ['case' => $case])
                    @elseif($activeTab === 'expenses')
                        @livewire('cases.case-expenses', ['case' => $case])
                    @endif
                </div>
            </div>
        </div>

        <!-- SIDEBAR CONTENT -->
        <div class="space-y-6">
            
            {{-- Lawyers & Roles --}}
            <div class="panel p-6">
                <h2 class="panel-title text-base mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ __('فريق العمل الموكل') }}
                </h2>
                
                @if($case->lawyers->count() > 0)
                    <div class="space-y-4">
                        @foreach($case->lawyers as $lawyer)
                            @php
                                $roleLabel = match($lawyer->pivot->role) {
                                    'lead', 'محامي رئيسي' => 'محامي رئيسي',
                                    'assistant', 'مساعد' => 'مساعد',
                                    'consultant', 'مستشار' => 'مستشار',
                                    default => 'محامي'
                                };
                            @endphp
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs bg-primary/10 text-primary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="flex flex-col flex-1">
                                    <span class="font-bold text-sm">{{ $lawyer->name }}</span>
                                    <span class="text-xs text-muted">{{ __($roleLabel) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-sm italic">{{ __('لم يتم تعيين محامين') }}</p>
                @endif
            </div>

            {{-- Progress & Decisions --}}
            <div class="panel p-6">
                <h2 class="panel-title text-base mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    {{ __('الموقف القضائي') }}
                </h2>
                
                <div class="space-y-4">
                    <div class="flex flex-col gap-1">
                        <span class="text-xs text-muted font-bold uppercase">{{ __('القرار السابق') }}</span>
                        <p class="text-sm font-semibold p-3 panel-subtle border rounded-lg whitespace-pre-wrap">{{ $case->Previous_procedure ?? __('لا يوجد') }}</p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-xs text-muted font-bold uppercase">{{ __('رقم التوكيل') }}</span>
                        <span class="text-sm font-semibold" dir="ltr">{{ $case->procuration ?? '-' }}</span>
                    </div>

                    @if($case->status == 'منتهية' || $case->status == 'مغلقة' || $case->final_decision)
                        <div class="flex flex-col gap-1 mt-4">
                            <span class="text-xs text-muted font-bold uppercase">{{ __('القرار النهائي (منطوق الحكم)') }}</span>
                            <p class="text-sm font-semibold p-3 rounded-lg border whitespace-pre-wrap">{{ $case->final_decision ?? __('لم يسجل بعد') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Financial Summary (Admins only) --}}
            @if(auth()->user()->isAdmin())
            <div class="panel p-6">
                <h2 class="panel-title text-base mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('الملخص المالي') }}
                </h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-opacity-10 border">
                        <span class="text-sm text-muted">{{ __('الأتعاب المتفق عليها') }}</span>
                        <span class="font-bold font-mono" dir="ltr">{{ number_format($case->agreed_legal_fee ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-opacity-10 border">
                        <span class="text-sm text-muted">{{ __('إجمالي التكاليف') }}</span>
                        <span class="font-bold font-mono text-red-500" dir="ltr">{{ number_format($case->total_costs ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-opacity-10 border">
                        <span class="text-sm text-muted">{{ __('المدفوع مقدماً') }}</span>
                        <span class="font-bold font-mono text-green-500" dir="ltr">{{ number_format($case->deposit ?? 0, 2) }}</span>
                    </div>
                    
                    @php
                        $remaining = max(0, ($case->total_costs ?? 0) - ($case->deposit ?? 0));
                    @endphp
                    <div class="flex justify-between items-center py-3 mt-2 font-bold text-lg">
                        <span>{{ __('المتبقي:') }}</span>
                        <span class="font-mono" dir="ltr">{{ number_format($remaining, 2) }}</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- Case File Attachment --}}
            @if($case->case_file)
            <div class="panel p-6">
                <h2 class="panel-title text-base mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    {{ __('الملف الأساسي للقضية') }}
                </h2>
                
                @php
                    $ext = strtolower(pathinfo($case->case_file, PATHINFO_EXTENSION));
                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
                    $isPdf = $ext === 'pdf';
                    $isWord = in_array($ext, ['doc', 'docx']);
                @endphp

                <div wire:click="openMediaViewer(0)" class="cursor-pointer group flex flex-col items-center justify-center p-4 panel-subtle border border-[var(--border-color)] rounded-xl hover:border-[var(--color-gold)] transition-all">
                    <div class="h-40 w-full mb-3 flex items-center justify-center overflow-hidden rounded-lg bg-[var(--bg-panel)]">
                        @if($isImage)
                            <img src="{{ asset('storage/' . $case->case_file) }}" alt="Main Case File" class="w-full h-full object-cover">
                        @elseif($isPdf)
                            <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6M9 17h6M12 9V5"/></svg>
                        @elseif($isWord)
                            <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6M9 17h6M12 9V5"/></svg>
                        @else
                            <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 w-full truncate">
                        <span class="text-sm font-semibold truncate group-hover:text-[var(--color-gold)] transition-colors text-center w-full">{{ basename($case->case_file) }}</span>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- Delete Modal --}}
    @if($confirmingDelete)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[3000] p-4">
            <div class="panel max-w-md w-full text-center">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="panel-title justify-center mb-2">{{ __('تأكيد حذف القضية') }}</h3>
                <p class="text-muted mb-8">{{ __('هل أنت متأكد من رغبتك في حذف هذه القضية نهائياً مع جميع مرفقاتها وسجلاتها؟ لا يمكن التراجع عن هذا الإجراء.') }}</p>
                <div class="flex gap-3 justify-center">
                    <button type="button" wire:click="cancelDelete" class="btn-secondary px-6 py-2">{{ __('إلغاء') }}</button>
                    <button type="button" wire:click="deleteCase" class="btn-action-delete px-6 py-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>{{ __('نعم، احذف القضية') }}</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Smart Media Viewer Modal -->
    @if($isMediaViewerOpen && count($mediaGallery) > 0)
    <div class="fixed inset-0 z-[4000] flex items-center justify-center bg-black/90 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-5xl bg-[var(--bg-panel)] rounded-2xl shadow-2xl overflow-hidden flex flex-col h-[90vh]">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-[var(--border-color)] bg-[var(--bg-panel)]">
                <div class="flex items-center gap-3">
                    <span class="text-lg font-bold text-[var(--text-color)]">{{ $mediaGallery[$currentMediaIndex]['title'] }}</span>
                    <span class="text-sm px-3 py-1 rounded-full bg-[var(--bg-subtle)] border border-[var(--border-color)] text-muted">
                        {{ $currentMediaIndex + 1 }} / {{ count($mediaGallery) }}
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ $mediaGallery[$currentMediaIndex]['url'] }}" download class="p-2 rounded-full hover:bg-[var(--bg-subtle)] transition-colors text-muted" title="{{ __('تحميل الملف') }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </a>
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
