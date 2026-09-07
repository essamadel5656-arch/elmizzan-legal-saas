<div dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Flash message --}}
    @if(session()->has('expense_success'))
        <div class="mb-4 p-3 flex items-center gap-2 bg-emerald-50/80 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl font-bold text-sm backdrop-blur-sm" wire:key="flash-exp">
            <i class="fas fa-check-circle"></i> {{ __(session('expense_success')) }}
        </div>
    @endif

    <div class="flex flex-wrap justify-between items-center mb-5 gap-4">
        <div class="flex items-center gap-3 text-lg font-bold text-slate-900 dark:text-amber-500">
            <i class="fas fa-money-bill-wave text-amber-500"></i> {{ __('Case Expenses') }}
            <span class="bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 px-3 py-1 rounded-full text-xs font-bold ms-2">
                {{ __('Total Approved:') }} {{ number_format($totalApproved, 2) }} {{ __('CUR') }}
            </span>
        </div>

        @if(!auth()->user()->isClient())
            <button type="button" wire:click="$toggle('showForm')" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 dark:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                @if($showForm)
                    <i class="fas fa-times"></i> {{ __('Close Form') }}
                @else
                    <i class="fas fa-plus"></i> {{ __('Add Expense') }}
                @endif
            </button>
        @endif
    </div>

    {{-- Add Expense Form --}}
    @if($showForm)
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-5 md:p-6 mb-6 transition-all" wire:key="expense-form">
            <form wire:submit="submitExpense" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-xs text-slate-900 dark:text-slate-100 mb-1.5">{{ __('Amount (CUR)') }}</label>
                        <input type="number" step="0.01" min="0.01" wire:model="amount" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-3 py-2.5" placeholder="0.00">
                        @error('amount') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                    </div>
                    <div>
                        <label class="block font-semibold text-xs text-slate-900 dark:text-slate-100 mb-1.5">{{ __('Category') }}</label>
                        <select wire:model="category" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-3 py-2.5">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ __($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block font-semibold text-xs text-slate-900 dark:text-slate-100 mb-1.5">{{ __('Notes (Optional)') }}</label>
                        <textarea wire:model="notes" rows="2" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-3 py-2.5 resize-y" placeholder="{{ __('Expense description...') }}"></textarea>
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block font-semibold text-xs text-slate-900 dark:text-slate-100 mb-1.5">{{ __('Receipt / Image (Optional)') }}</label>
                        <input type="file" wire:model="receipt" accept="image/*,.pdf" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-3 py-2 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 dark:file:bg-amber-900/30 dark:file:text-amber-400">
                        @error('receipt') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-900 dark:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition-all hover:-translate-y-0.5">
                        <i class="fas fa-paper-plane" wire:loading.remove></i>
                        <i class="fas fa-spinner fa-spin" wire:loading></i>
                        <span wire:loading.remove>{{ __('Submit Request') }}</span>
                        <span wire:loading>{{ __('Sending...') }}</span>
                    </button>
                    <button type="button" wire:click="$set('showForm', false)" class="px-5 py-2.5 bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-sm font-bold transition-all">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Expenses Table --}}
    @if($expenses->isEmpty())
        <div class="text-center text-sm font-bold italic text-slate-400 dark:text-slate-500 py-8 bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl">
            <i class="fas fa-money-bill-wave mb-2 text-2xl opacity-50 block"></i>
            {{ __('No expenses recorded for this case yet.') }}
        </div>
    @else
        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
            <table class="w-full text-sm text-start">
                <thead class="bg-slate-100/80 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 text-xs uppercase tracking-wider font-semibold border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-4 py-3 rtl:text-right ltr:text-left">{{ __('Amount') }}</th>
                        <th class="px-4 py-3 rtl:text-right ltr:text-left">{{ __('Category') }}</th>
                        <th class="px-4 py-3 rtl:text-right ltr:text-left">{{ __('Submitted By') }}</th>
                        <th class="px-4 py-3 rtl:text-right ltr:text-left">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('Receipt') }}</th>
                        @if(auth()->user()->isAdmin())
                            <th class="px-4 py-3 text-center">{{ __('Action') }}</th>
                        @endif
                        <th class="px-4 py-3 text-center"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                    @foreach($expenses as $expense)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors" wire:key="exp-{{ $expense->id }}">
                            <td class="px-4 py-3 font-bold text-slate-900 dark:text-slate-100">{{ number_format($expense->amount, 2) }}</td>
                            <td class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">{{ __($expense->category) }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $expense->submittedBy?->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($expense->isPending())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50 whitespace-nowrap">
                                        <i class="fas fa-hourglass-half"></i> {{ __('Pending') }}
                                    </span>
                                @elseif($expense->isApproved())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800/50 whitespace-nowrap">
                                        <i class="fas fa-check"></i> {{ __('Approved') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50 whitespace-nowrap">
                                        <i class="fas fa-times"></i> {{ __('Rejected') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($expense->receipt_path)
                                    <a href="{{ asset('storage/' . $expense->receipt_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 hover:underline">
                                        <i class="fas fa-paperclip"></i> {{ __('View') }}
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 dark:text-slate-500 italic">—</span>
                                @endif
                            </td>
                            @if(auth()->user()->isAdmin())
                                <td class="px-4 py-3 text-center">
                                    @if($expense->isPending())
                                        <div class="flex items-center justify-center gap-2">
                                            <button wire:click="approveExpense({{ $expense->id }})" wire:loading.attr="disabled" class="px-2 py-1 bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 rounded-lg text-[10px] font-bold transition-colors">
                                                <i class="fas fa-check"></i> {{ __('Approve') }}
                                            </button>
                                            <button wire:click="rejectExpense({{ $expense->id }})" wire:loading.attr="disabled" class="px-2 py-1 bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 rounded-lg text-[10px] font-bold transition-colors">
                                                <i class="fas fa-times"></i> {{ __('Reject') }}
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold whitespace-nowrap">{{ $expense->approvedBy?->name ?? '—' }}</span>
                                    @endif
                                </td>
                            @endif
                            <td class="px-4 py-3 text-center">
                                @if(auth()->user()->isAdmin() || (auth()->id() === $expense->user_id && $expense->isPending()))
                                    <button wire:click="deleteExpense({{ $expense->id }})" class="text-slate-400 hover:text-red-500 dark:text-slate-500 dark:hover:text-red-400 transition-colors p-1" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($totalApproved > 0)
            <div class="mt-4 p-4 flex items-center justify-end gap-3 bg-green-50/50 dark:bg-green-900/10 border border-green-200 dark:border-green-800/50 rounded-xl text-sm font-bold text-green-700 dark:text-green-400">
                <i class="fas fa-wallet text-lg"></i>
                <span>{{ __('Total Approved Expenses:') }}</span>
                <span class="text-lg font-black">{{ number_format($totalApproved, 2) }} {{ __('CUR') }}</span>
            </div>
        @endif
    @endif
</div>
