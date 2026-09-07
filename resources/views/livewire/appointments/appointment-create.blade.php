<div class="p-4 md:p-8 max-w-4xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2 flex items-center gap-3">
                <i class="fas fa-calendar-plus text-amber-500"></i> {{ __('Add New Appointment') }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('Schedule a new appointment for a court session or a client meeting') }}
            </p>
        </div>
        <a href="{{ route('cases.show', $case->id) }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-2.5 bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-amber-600 dark:hover:text-amber-500 rounded-xl transition-colors font-bold whitespace-nowrap">
            <i class="fas fa-arrow-right rtl:rotate-180"></i> {{ __('Back to Case') }}
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-6 bg-red-50/80 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl backdrop-blur-sm">
            <strong class="flex items-center gap-2 text-red-700 dark:text-red-400 font-bold mb-3 text-lg">
                <i class="fas fa-exclamation-triangle"></i> {{ __('Please review the following errors to save:') }}
            </strong>
            <ul class="list-disc mx-5 text-red-600 dark:text-red-400 font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ __($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
        <form wire:submit="save" novalidate class="space-y-6">

            {{-- Case Readonly --}}
            <div>
                <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                    {{ __('Case') }}
                </label>
                <div class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl px-4 py-3 flex items-center gap-2 cursor-not-allowed">
                    <i class="fas fa-folder-open text-amber-500"></i>
                    <strong class="font-bold"># {{ $case->case_number }}</strong>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2" for="date">
                        {{ __('Date') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="date" wire:model="date" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" required>
                    @error('date') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2" for="time">
                        {{ __('Time') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="time" id="time" wire:model="time" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 dir-ltr text-end ltr:text-left" required>
                    @error('time') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>
            </div>

            <div>
                <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2" for="notes">
                    {{ __('Notes or Session Details') }}
                </label>
                <textarea id="notes" wire:model="notes" rows="4" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 resize-y" placeholder="{{ __('Write any specific notes for the session or required preparations...') }}"></textarea>
                @error('notes') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
                <a href="{{ route('cases.show', $case->id) }}" wire:navigate class="px-6 py-3 bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all font-bold">
                    <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-slate-900 dark:bg-amber-600 hover:bg-slate-800 dark:hover:bg-amber-700 text-white font-bold rounded-xl shadow-lg shadow-slate-900/20 dark:shadow-amber-600/20 transition-all hover:-translate-y-0.5 focus:ring-4 focus:ring-amber-500/40 disabled:opacity-70 disabled:cursor-not-allowed" wire:loading.attr="disabled">
                    <i class="fas fa-save" wire:loading.remove wire:target="save"></i>
                    <i class="fas fa-spinner fa-spin" wire:loading wire:target="save"></i>
                    <span wire:loading.remove wire:target="save">{{ __('Save Appointment') }}</span>
                    <span wire:loading wire:target="save">{{ __('Saving...') }}</span>
                </button>
            </div>

        </form>
    </div>

</div>
