<div class="p-4 md:p-8 max-w-4xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2 flex items-center gap-3">
                <i class="fas fa-user-plus text-amber-500"></i> {{ __('Add New Client') }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('Register a new client data in the office database') }}
            </p>
        </div>
        <a href="{{ route('clients.index') }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-2.5 bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-red-600 dark:hover:text-red-500 rounded-xl transition-colors font-bold whitespace-nowrap">
            <i class="fas fa-arrow-right rtl:rotate-180"></i> {{ __('Back to List') }}
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-6 bg-red-50/80 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl backdrop-blur-sm">
            <strong class="flex items-center gap-2 text-red-700 dark:text-red-400 font-bold mb-3 text-lg">
                <i class="fas fa-exclamation-triangle"></i> {{ __('Please review the following errors:') }}
            </strong>
            <ul class="list-disc mx-5 text-red-600 dark:text-red-400 font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ __($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit="save" novalidate class="space-y-6">

        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                    <i class="fas fa-id-card text-amber-500"></i> {{ __('Personal and Contact Information') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label for="clientName" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Full Client Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="clientName" wire:model="name" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" placeholder="{{ __('Enter full name...') }}" required>
                    @error('name') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label for="clientNid" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('National ID / Identity') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="clientNid" wire:model="nid" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" placeholder="{{ __('14 Digits') }}" required>
                    @error('nid') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label for="clientPhone" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Primary Phone Number') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="clientPhone" wire:model="phone" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 dir-ltr text-end ltr:text-left" placeholder="01XXXXXXXXX" required>
                    @error('phone') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label for="clientEmail" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Email Address') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="clientEmail" wire:model="email" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 dir-ltr text-end ltr:text-left" placeholder="client@example.com" required>
                    @error('email') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label for="clientAddress" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Detailed Address') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="clientAddress" wire:model="address" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" placeholder="{{ __('State, City, Street, Building No...') }}" required>
                    @error('address') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label for="clientNote" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Administrative Notes (Optional)') }}
                    </label>
                    <textarea id="clientNote" wire:model="note" rows="3" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 resize-y" placeholder="{{ __('Any special details about communication methods or general summary...') }}"></textarea>
                    @error('note') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('clients.index') }}" wire:navigate class="px-6 py-3 bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all font-bold">
                <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-slate-900 dark:bg-amber-600 hover:bg-slate-800 dark:hover:bg-amber-700 text-white font-bold rounded-xl shadow-lg shadow-slate-900/20 dark:shadow-amber-600/20 transition-all hover:-translate-y-0.5 focus:ring-4 focus:ring-amber-500/40 disabled:opacity-70 disabled:cursor-not-allowed" wire:loading.attr="disabled">
                <i class="fas fa-save" wire:loading.remove wire:target="save"></i>
                <i class="fas fa-spinner fa-spin" wire:loading wire:target="save"></i>
                <span wire:loading.remove wire:target="save">{{ __('Save Client Data') }}</span>
                <span wire:loading wire:target="save">{{ __('Saving...') }}</span>
            </button>
        </div>

    </form>
</div>
