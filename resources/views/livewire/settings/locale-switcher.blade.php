<div class="user-profile" style="position: relative; margin-inline-end: 1rem;" x-data="{ open: false }">
    <div class="profile-info flex items-center gap-2 px-3 py-1.5 rounded-xl cursor-pointer transition-all duration-300 border border-[var(--border-color)] bg-[var(--card-bg)] shadow-sm hover:bg-[var(--primary-bg)] hover:-translate-y-[1px]" @click="open = !open" @click.away="open = false">
        @php
            $locale = app_locale();
            $flagMap = ['ar' => 'EG', 'en' => 'US'];
            $currentFlag = $flagMap[$locale] ?? 'EG';
            $currentLabel = $locale === 'en' ? 'EN' : 'العربية';
        @endphp
        <div class="w-5 h-5 rounded-full overflow-hidden flex items-center justify-center">
            <x-flag-icon :country="$currentFlag" size="20px"/>
        </div>
        <span class="text-[0.82rem] font-bold text-[var(--text-primary)] tracking-wide">{{ $currentLabel }}</span>
        <i class="fas fa-chevron-down text-[0.75rem] text-[var(--text-secondary)] transition-transform duration-300" :style="open ? 'transform: rotate(180deg);' : ''"></i>
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
         class="absolute top-full mt-2 w-40 z-[100] rounded-xl overflow-hidden border border-[var(--border-color)] bg-[var(--card-bg)] shadow-lg rtl:left-0 ltr:right-0" style="display: none;">
        <button wire:click="setLocale('ar', 'EG')" 
                class="w-full text-start px-4 py-3 bg-transparent border-b border-[var(--border-color)] flex items-center gap-3 cursor-pointer transition-colors duration-200 hover:bg-black/5 dark:hover:bg-white/5">
            <div class="w-[18px] h-[18px] rounded-full overflow-hidden"><x-flag-icon country="EG" size="18px"/></div>
            <span class="text-[0.9rem] font-cairo {{ app_locale() === 'ar' ? 'font-extrabold text-[var(--gold-accent)]' : 'font-medium text-[var(--text-primary)]' }}">{{ __('العربية') }}</span>
            @if(app_locale() === 'ar')
                <i class="fas fa-check ms-auto text-[0.8rem] text-[var(--gold-accent)]"></i>
            @endif
        </button>
        <button wire:click="setLocale('en', 'US')" 
                class="w-full text-start px-4 py-3 bg-transparent border-none flex items-center gap-3 cursor-pointer transition-colors duration-200 hover:bg-black/5 dark:hover:bg-white/5">
            <div class="w-[18px] h-[18px] rounded-full overflow-hidden"><x-flag-icon country="US" size="18px"/></div>
            <span class="text-[0.9rem] font-sans {{ app_locale() === 'en' ? 'font-extrabold text-[var(--gold-accent)]' : 'font-medium text-[var(--text-primary)]' }}">English</span>
            @if(app_locale() === 'en')
                <i class="fas fa-check ms-auto text-[0.8rem] text-[var(--gold-accent)]"></i>
            @endif
        </button>
    </div>
</div>
