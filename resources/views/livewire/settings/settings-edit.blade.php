<div class="max-w-5xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8">
        <i class="fas fa-cog text-2xl text-amber-600 dark:text-amber-400"></i>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 m-0">{{ __('Office Settings') }}</h1>
    </div>

    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="mb-6 p-4 flex items-center gap-3 bg-green-50/80 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 rounded-xl backdrop-blur-sm">
            <i class="fas fa-check-circle"></i>
            <span class="font-medium">{{ __(session('success')) }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50/80 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-xl backdrop-blur-sm">
            <ul class="list-disc mx-4 m-0 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ __($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main Container with Alpine Tabs --}}
    <div x-data="{ activeTab: 'general' }" class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl overflow-hidden">
        
        {{-- Tab Headers --}}
        <div class="flex overflow-x-auto border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
            <button @click="activeTab = 'general'" 
                    :class="activeTab === 'general' ? 'border-amber-500 text-amber-600 dark:text-amber-400 bg-white dark:bg-slate-900' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'" 
                    class="px-6 py-4 border-b-2 font-bold text-sm tracking-wide transition-all whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-building"></i> {{ __('General Office Settings') }}
            </button>
            <button @click="activeTab = 'preferences'" 
                    :class="activeTab === 'preferences' ? 'border-amber-500 text-amber-600 dark:text-amber-400 bg-white dark:bg-slate-900' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'" 
                    class="px-6 py-4 border-b-2 font-bold text-sm tracking-wide transition-all whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-palette"></i> {{ __('System Preferences') }}
            </button>
            <button @click="activeTab = 'payments'" 
                    :class="activeTab === 'payments' ? 'border-amber-500 text-amber-600 dark:text-amber-400 bg-white dark:bg-slate-900' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'" 
                    class="px-6 py-4 border-b-2 font-bold text-sm tracking-wide transition-all whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-credit-card"></i> {{ __('Payment Settings') }}
            </button>
            <button @click="activeTab = 'advanced'" 
                    :class="activeTab === 'advanced' ? 'border-amber-500 text-amber-600 dark:text-amber-400 bg-white dark:bg-slate-900' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'" 
                    class="px-6 py-4 border-b-2 font-bold text-sm tracking-wide transition-all whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-robot"></i> {{ __('Notifications & AI') }}
            </button>
        </div>

        <form wire:submit="save" class="p-6 md:p-8">
            
            {{-- TAB: General --}}
            <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" style="display: none;">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">{{ __('Office Identity (White-Labeling)') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-1 md:col-span-2">
                            <label for="app_name" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Office / App Name') }}</label>
                            <input type="text" id="app_name" wire:model.live="app_name" 
                                   class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" 
                                   placeholder="{{ __('e.g., El-Mizzan, Ahmed Law Firm...') }}" maxlength="100">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i> {{ __('This name appears in the browser title, sidebar, footer, and AI assistant.') }}
                            </p>
                        </div>

                        {{-- Live Preview --}}
                        <div class="col-span-1 md:col-span-2 p-4 bg-slate-100/70 dark:bg-slate-800/60 border border-slate-200 border-dashed dark:border-slate-700 rounded-xl text-sm text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fas fa-eye text-amber-500"></i>
                            <span>{{ __('Preview:') }} <strong class="text-slate-900 dark:text-slate-100">{{ $app_name ?: __('El-Mizzan') }}</strong></span>
                            <span class="text-slate-300 dark:text-slate-600">|</span>
                            <span>{{ __('Browser Title:') }} <strong class="text-slate-900 dark:text-slate-100">{{ __('Home') }} | {{ $app_name ?: __('El-Mizzan') }}</strong></span>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label for="firm_country" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2 flex items-center gap-2">
                                <i class="fas fa-flag text-amber-500"></i> {{ __('Country of Operation (Currency & Courts)') }}
                            </label>
                            <div class="flex items-center gap-3">
                                <select id="firm_country" wire:model.live="firm_country" 
                                        class="flex-1 w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3">
                                    <option value="EG">{{ __('Egypt (EG) — Egyptian Pound') }}</option>
                                    <option value="SA">{{ __('Saudi Arabia (SA) — Saudi Riyal') }}</option>
                                    <option value="AE">{{ __('United Arab Emirates (AE) — UAE Dirham') }}</option>
                                    <option value="OM">{{ __('Oman (OM) — Omani Rial') }}</option>
                                    <option value="KW">{{ __('Kuwait (KW) — Kuwaiti Dinar') }}</option>
                                    <option value="BH">{{ __('Bahrain (BH) — Bahraini Dinar') }}</option>
                                    <option value="QA">{{ __('Qatar (QA) — Qatari Riyal') }}</option>
                                    <option value="JO">{{ __('Jordan (JO) — Jordanian Dinar') }}</option>
                                    <option value="MA">{{ __('Morocco (MA) — Moroccan Dirham') }}</option>
                                    <option value="LY">{{ __('Libya (LY) — Libyan Dinar') }}</option>
                                    <option value="IQ">{{ __('Iraq (IQ) — Iraqi Dinar') }}</option>
                                    <option value="LB">{{ __('Lebanon (LB) — Lebanese Pound') }}</option>
                                    <option value="YE">{{ __('Yemen (YE) — Yemeni Rial') }}</option>
                                    <option value="SY">{{ __('Syria (SY) — Syrian Pound') }}</option>
                                </select>
                                <div class="p-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl flex items-center justify-center">
                                    <x-flag-icon :country="$firm_country" size="2rem" />
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i> {{ __('Determines the default currency, court lists, and legal terminologies in the system.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: Preferences --}}
            <div x-show="activeTab === 'preferences'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" style="display: none;">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">{{ __('Theme Customization') }}</h3>
                    
                    <div class="mb-6">
                        <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-3">{{ __('Primary Accent Color') }}</label>
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <button type="button" wire:click="$set('primary_color', '#d4af37')" class="w-10 h-10 rounded-xl bg-[#d4af37] border-2 transition-all hover:scale-110 shadow-sm {{ $primary_color === '#d4af37' ? 'border-slate-900 dark:border-white ring-2 ring-[#d4af37]/50' : 'border-transparent' }}" title="{{ __('Classic Gold') }}"></button>
                            <button type="button" wire:click="$set('primary_color', '#2563eb')" class="w-10 h-10 rounded-xl bg-blue-600 border-2 transition-all hover:scale-110 shadow-sm {{ $primary_color === '#2563eb' ? 'border-slate-900 dark:border-white ring-2 ring-blue-600/50' : 'border-transparent' }}" title="{{ __('Royal Blue') }}"></button>
                            <button type="button" wire:click="$set('primary_color', '#059669')" class="w-10 h-10 rounded-xl bg-emerald-600 border-2 transition-all hover:scale-110 shadow-sm {{ $primary_color === '#059669' ? 'border-slate-900 dark:border-white ring-2 ring-emerald-600/50' : 'border-transparent' }}" title="{{ __('Emerald Green') }}"></button>
                            <button type="button" wire:click="$set('primary_color', '#7c3aed')" class="w-10 h-10 rounded-xl bg-violet-600 border-2 transition-all hover:scale-110 shadow-sm {{ $primary_color === '#7c3aed' ? 'border-slate-900 dark:border-white ring-2 ring-violet-600/50' : 'border-transparent' }}" title="{{ __('Midnight Purple') }}"></button>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="color" id="primary_color" wire:model="primary_color" class="h-12 w-20 rounded-xl cursor-pointer p-1 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700">
                            <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Custom Hex:') }} <span x-text="$wire.primary_color"></span></span>
                        </div>
                    </div>

                    <hr class="border-slate-200 dark:border-slate-800 my-6">

                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">{{ __('PWA & Web App Settings') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="pwa_short_name" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('PWA Short Name') }}</label>
                            <input type="text" id="pwa_short_name" wire:model="pwa_short_name" 
                                   class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" 
                                   placeholder="{{ __('e.g., El-Mizzan') }}" maxlength="255">
                        </div>
                        <div>
                            <label for="pwa_description" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('App Description') }}</label>
                            <input type="text" id="pwa_description" wire:model="pwa_description" 
                                      class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" 
                                      placeholder="{{ __('e.g., Integrated Legal Case Management System') }}" maxlength="500">
                        </div>
                        <div>
                            <label for="pwa_theme_color" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Theme Color (PWA)') }}</label>
                            <input type="color" id="pwa_theme_color" wire:model="pwa_theme_color" class="w-full h-12 rounded-xl cursor-pointer p-1 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700">
                        </div>
                        <div>
                            <label for="pwa_background_color" class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">{{ __('Background Color (PWA)') }}</label>
                            <input type="color" id="pwa_background_color" wire:model="pwa_background_color" class="w-full h-12 rounded-xl cursor-pointer p-1 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700">
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: Payments --}}
            <div x-show="activeTab === 'payments'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" style="display: none;">
                <div>
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-1 flex items-center gap-2">
                            <i class="fas fa-university text-amber-500"></i> {{ __('Payment & Transfer Details') }}
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ __('These details will be shown to clients when recording a bank transfer payment. Leave empty if you do not use this method.') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2 flex items-center gap-2">
                                <i class="fas fa-landmark text-slate-400"></i> {{ __('Bank Name') }}
                            </label>
                            <input type="text" wire:model="bank_name" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" placeholder="{{ __('e.g., National Bank of Egypt') }}">
                        </div>
                        <div>
                            <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2 flex items-center gap-2">
                                <i class="fas fa-money-check text-slate-400"></i> {{ __('IBAN Number') }}
                            </label>
                            <input type="text" wire:model="bank_iban" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" placeholder="EG000000000000000000000000">
                        </div>
                        <div>
                            <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2 flex items-center gap-2">
                                <i class="fas fa-bolt text-amber-400"></i> {{ __('InstaPay Address') }}
                            </label>
                            <input type="text" wire:model="instapay_address" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" placeholder="{{ __('Phone number or InstaPay code') }}">
                        </div>
                        <div>
                            <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2 flex items-center gap-2">
                                <i class="fas fa-mobile-alt text-slate-400"></i> {{ __('Mobile Wallet (e.g., Vodafone Cash)') }}
                            </label>
                            <input type="text" wire:model="mobile_wallet" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" placeholder="01xxxxxxxxx">
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: Advanced & AI --}}
            <div x-show="activeTab === 'advanced'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" style="display: none;">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-2">
                        <i class="fas fa-robot text-amber-500"></i> {{ __('Smart Legal Assistant (AI)') }}
                    </h3>
                    
                    <label class="flex items-center justify-between p-5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <div class="pe-4">
                            <span class="block font-bold text-slate-900 dark:text-slate-100 text-base mb-1">{{ __('Enable Smart Legal Assistant') }}</span>
                            <span class="block text-sm text-slate-500 dark:text-slate-400">{{ __('When enabled, the smart chat window appears for all registered users.') }}</span>
                            <div class="mt-2 text-xs font-medium px-2 py-1 inline-flex items-center gap-1 rounded-md {{ $ai_feature_enabled ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                <i class="fas {{ $ai_feature_enabled ? 'fa-check' : 'fa-times' }}"></i>
                                {{ $ai_feature_enabled ? __('Currently Enabled') : __('Currently Disabled') }}
                            </div>
                        </div>
                        <div class="relative inline-flex items-center">
                            <input type="checkbox" wire:model.live="ai_feature_enabled" class="sr-only peer">
                            <div class="w-14 h-7 bg-slate-300 dark:bg-slate-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-500/30 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-amber-500"></div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Submit Actions --}}
            <div class="mt-10 pt-6 border-t border-slate-200 dark:border-slate-800 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl shadow-lg shadow-amber-600/20 transition-all hover:-translate-y-0.5 focus:ring-4 focus:ring-amber-500/40 disabled:opacity-70 disabled:cursor-not-allowed" wire:loading.attr="disabled">
                    <i class="fas fa-save" wire:loading.remove></i>
                    <i class="fas fa-spinner fa-spin" wire:loading></i>
                    <span wire:loading.remove>{{ __('Save All Settings') }}</span>
                    <span wire:loading>{{ __('Saving...') }}</span>
                </button>
            </div>

        </form>
    </div>
</div>
