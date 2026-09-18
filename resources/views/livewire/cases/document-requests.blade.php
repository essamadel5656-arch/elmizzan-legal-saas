<div class="space-y-6" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <div class="flex justify-between items-center mb-4">
        <h3 class="panel-title m-0 text-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            {{ __('المستندات والمرفقات') }}
        </h3>
        
        <button wire:click="openModal" class="btn-add-new flex items-center gap-2 text-sm px-4 py-2">
            <span class="icon-circle">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14M5 12h14"/></svg>
            </span>
            <span>{{ __('طلب / إضافة مستند') }}</span>
        </button>
    </div>

    <!-- Alert Success -->
    @if (session()->has('success'))
        <div class="panel-subtle mb-4 p-4 flex items-center gap-3 border border-green-500/20 text-green-600 rounded-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ __(session('success')) }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="panel-subtle mb-4 p-4 flex items-center gap-3 border border-red-500/20 text-red-600 rounded-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span class="font-bold">{{ __(session('error')) }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($documents as $doc)
            @php
                $ext = $doc->file_path ? strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION)) : '';
                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
                $isPdf = $ext === 'pdf';
                $isWord = in_array($ext, ['doc', 'docx']);
            @endphp
            <div class="panel-subtle border p-4 flex flex-col gap-3 rounded-xl transition-all hover:-translate-y-1 hover:shadow-md border relative">
                <!-- Thumbnail Area -->
                <div class="h-32 w-full bg-[var(--bg-panel)] rounded-lg border border-[var(--border-color)] flex items-center justify-center overflow-hidden cursor-pointer" 
                    @if($doc->file_path) wire:click="openMediaViewerForDocument({{ $doc->id }})" @endif>
                    @if(!$doc->file_path)
                        <svg class="w-10 h-10 text-muted opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    @elseif($isImage)
                        <img src="{{ asset('storage/' . $doc->file_path) }}" alt="{{ $doc->title }}" class="w-full h-full object-cover">
                    @elseif($isPdf)
                        <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6M9 17h6M12 9V5"/></svg>
                    @elseif($isWord)
                        <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6M9 17h6M12 9V5"/></svg>
                    @else
                        <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    @endif
                </div>

                <div class="flex justify-between items-start mt-2">
                    <h4 class="font-bold text-base truncate pr-2 cursor-pointer hover:text-[var(--color-gold)] transition-colors" title="{{ $doc->title }}" @if($doc->file_path) wire:click="openMediaViewerForDocument({{ $doc->id }})" @endif>
                        {{ $doc->title }}
                    </h4>
                    <span class="shrink-0 text-xs text-muted" dir="ltr">{{ $doc->created_at->format('Y-m-d') }}</span>
                </div>
                
                <p class="text-sm text-muted line-clamp-2">{{ $doc->description ?? __('لا توجد ملاحظات') }}</p>
                
            </div>
        @empty
            <div class="col-span-full panel text-center py-10">
                <svg class="w-12 h-12 mx-auto mb-3 text-muted opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                <h4 class="font-bold mb-1">{{ __('لا توجد مستندات مسجلة') }}</h4>
                <p class="text-sm text-muted">{{ __('انقر على زر "طلب / إضافة مستند" لإضافة المرفقات المطلوبة.') }}</p>
            </div>
        @endforelse
    </div>

    {{-- Add/Edit Modal --}}
    @if($isModalOpen)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[3000] p-4">
            <div class="panel max-w-md w-full">
                <div class="panel-header mb-4 flex justify-between items-center pb-3 border-b">
                    <h3 class="panel-title m-0">{{ __('طلب / رفع مستند') }}</h3>
                    <button wire:click="closeModal" class="text-muted hover:text-primary transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <form wire:submit="save" class="space-y-4">
                    @if($clients->count() > 1)
                    <div class="field">
                        <label class="block mb-2">{{ __('الموكل المعني بالمستند') }} <span class="text-red-500">*</span></label>
                        <select wire:model="client_id" class="app-select w-full" required>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id') <div class="text-xs mt-1 font-semibold text-red-500">{{ __($message) }}</div> @enderror
                    </div>
                    @endif

                    <div class="field">
                        <label class="block mb-2">{{ __('اسم المستند') }} <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="document_name" class="app-input w-full" required placeholder="{{ __('مثال: صورة البطاقة، عقد الإيجار...') }}">
                        @error('document_name') <div class="text-xs mt-1 font-semibold text-red-500">{{ __($message) }}</div> @enderror
                    </div>

                    <div class="field">
                        <label class="block mb-2">{{ __('ملاحظات') }}</label>
                        <textarea wire:model="notes" rows="2" class="app-input w-full resize-y" placeholder="{{ __('اختياري...') }}"></textarea>
                        @error('notes') <div class="text-xs mt-1 font-semibold text-red-500">{{ __($message) }}</div> @enderror
                    </div>

                    <div class="field">
                        <label class="block mb-2">{{ __('الملف المرفق') }}</label>
                        <input type="file" wire:model="file" class="app-input w-full" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx">
                        <div wire:loading wire:target="file" class="text-xs mt-2 font-bold text-primary">
                            {{ __('جاري رفع الملف...') }}
                        </div>
                        <p class="text-xs text-muted mt-1">{{ __('اختياري: يمكن ترك الحقل فارغاً إذا كان المستند مطلوباً فقط ولم يتوفر بعد.') }}</p>
                        @error('file') <div class="text-xs mt-1 font-semibold text-red-500">{{ __($message) }}</div> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 mt-6 border-t">
                        <button type="button" wire:click="closeModal" class="btn-secondary px-4 py-2">{{ __('إلغاء') }}</button>
                        <button type="submit" class="btn-primary px-6 py-2 flex items-center gap-2">
                            <span wire:loading.remove wire:target="save">{{ __('حفظ المستند') }}</span>
                            <span wire:loading wire:target="save">{{ __('جاري الحفظ...') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Modal --}}
    @if($confirmingDeleteId)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[3000] p-4">
            <div class="panel max-w-sm w-full text-center">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="panel-title justify-center mb-2">{{ __('تأكيد الحذف') }}</h3>
                <p class="text-muted mb-6">{{ __('هل أنت متأكد من حذف هذا المستند؟ سيتم حذف الملف المرفق إن وجد.') }}</p>
                <div class="flex gap-3 justify-center">
                    <button type="button" wire:click="cancelDelete" class="btn-secondary px-4 py-2">{{ __('إلغاء') }}</button>
                    <button type="button" wire:click="deleteDocument" class="btn-action-delete px-4 py-2">
                        {{ __('تأكيد الحذف') }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Smart Media Viewer Modal -->
    @if($isMediaViewerOpen && count($mediaGallery) > 0)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm p-4">
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
                    @if(auth()->user()?->isAdmin() && !$mediaGallery[$currentMediaIndex]['is_main_file'])
                    <button type="button" wire:click="deleteCurrentMedia" onclick="confirm('{{ __('هل أنت متأكد من حذف هذا الملف نهائياً؟') }}') || event.stopImmediatePropagation()" class="btn-danger flex items-center gap-2 px-4 py-2 text-sm rounded-lg bg-[var(--color-danger)] text-white hover:bg-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        {{ __('حذف') }}
                    </button>
                    @endif
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
