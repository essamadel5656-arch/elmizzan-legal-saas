<div class="space-y-6" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <div class="flex justify-between items-center mb-4">
        <h3 class="panel-title m-0 text-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ __('سجل المصروفات') }}
        </h3>
        
        <button wire:click="openModal" class="btn-add-new flex items-center gap-2 text-sm px-4 py-2">
            <span class="icon-circle">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14M5 12h14"/></svg>
            </span>
            <span>{{ __('إضافة مصروف جديد') }}</span>
        </button>
    </div>

    <!-- Alert Success -->
    @if (session()->has('success'))
        <div class="panel-subtle mb-4 p-4 flex items-center gap-3 border border-green-500/20 text-green-600 rounded-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ __(session('success')) }}</span>
        </div>
    @endif

    <div class="panel-subtle rounded-xl overflow-hidden border border">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead class="text-xs uppercase border-b panel-subtle">
                    <tr>
                        <th class="px-4 py-3">{{ __('التاريخ') }}</th>
                        <th class="px-4 py-3">{{ __('البند / الوصف') }}</th>
                        <th class="px-4 py-3">{{ __('المبلغ') }}</th>
                        <th class="px-4 py-3">{{ __('بواسطة') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('الحالة') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y border">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap" dir="ltr">{{ $expense->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $expense->notes }}</td>
                            <td class="px-4 py-3 font-mono font-bold" dir="ltr">{{ number_format($expense->amount, 2) }}</td>
                            <td class="px-4 py-3">{{ $expense->submittedBy?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($expense->status === 'approved')
                                    <span class="badge-item badge-success">{{ __('معتمد') }}</span>
                                @else
                                    <span class="badge-item badge-warning">{{ __('قيد المراجعة') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                @if($expense->status !== 'approved' && auth()->user()->isAdmin())
                                    <button wire:click="approve({{ $expense->id }})" class="btn-primary px-2 py-1 text-xs mx-1">
                                        {{ __('اعتماد') }}
                                    </button>
                                @endif
                                
                                @if($expense->status !== 'approved' || auth()->user()->isAdmin())
                                    <button wire:click="confirmDelete({{ $expense->id }})" class="btn-action-delete p-1 mx-1" title="{{ __('حذف') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-muted italic">
                                {{ __('لا توجد مصروفات مسجلة لهذه القضية حتى الآن.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($expenses->count() > 0)
                <tfoot class="font-bold border-t panel-subtle">
                    <tr>
                        <td colspan="2" class="px-4 py-3 text-left">{{ __('الإجمالي المعتمد:') }}</td>
                        <td colspan="4" class="px-4 py-3 font-mono text-primary" dir="ltr">
                            {{ number_format($expenses->where('status', 'approved')->sum('amount'), 2) }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- Add/Edit Modal --}}
    @if($isModalOpen)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[3000] p-4">
            <div class="panel max-w-md w-full">
                <div class="panel-header mb-4 flex justify-between items-center pb-3 border-b">
                    <h3 class="panel-title m-0">{{ __('إضافة مصروف جديد') }}</h3>
                    <button wire:click="closeModal" class="text-muted hover:text-primary transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <form wire:submit="save" class="space-y-4">
                    <div class="field">
                        <label class="block mb-2">{{ __('المبلغ') }} <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="amount" step="0.01" class="app-input w-full" required placeholder="{{ __('مثال: 50.00') }}">
                        @error('amount') <div class="text-xs mt-1 font-semibold text-red-500">{{ __($message) }}</div> @enderror
                    </div>

                    <div class="field">
                        <label class="block mb-2">{{ __('البيان / الوصف') }} <span class="text-red-500">*</span></label>
                        <textarea wire:model="description" rows="3" class="app-input w-full resize-y" required placeholder="{{ __('تفاصيل المصروف، مثل رسوم استخراج مستند...') }}"></textarea>
                        @error('description') <div class="text-xs mt-1 font-semibold text-red-500">{{ __($message) }}</div> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 mt-6 border-t">
                        <button type="button" wire:click="closeModal" class="btn-secondary px-4 py-2">{{ __('إلغاء') }}</button>
                        <button type="submit" class="btn-primary px-6 py-2 flex items-center gap-2">
                            <span wire:loading.remove wire:target="save">{{ __('حفظ المصروف') }}</span>
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
                <p class="text-muted mb-6">{{ __('هل أنت متأكد من حذف هذا المصروف؟ لا يمكن التراجع عن هذا الإجراء.') }}</p>
                <div class="flex gap-3 justify-center">
                    <button type="button" wire:click="cancelDelete" class="btn-secondary px-4 py-2">{{ __('إلغاء') }}</button>
                    <button type="button" wire:click="deleteExpense" class="btn-action-delete px-4 py-2">
                        {{ __('تأكيد الحذف') }}
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
