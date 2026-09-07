<div class="user-profile" style="position: relative; margin-inline-end: 1rem;" x-data="{ open: false }">
    <div class="profile-info" @click="open = !open" @click.away="open = false" 
         style="cursor: pointer; display: flex; align-items: center; gap: 0.5rem; background: rgba(30, 41, 59, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(51, 65, 85, 0.6); padding: 0.35rem 0.75rem; border-radius: 12px; transition: all 0.3s ease; box-shadow: 0 2px 10px rgba(0,0,0,0.1);"
         onmouseover="this.style.boxShadow='0 4px 15px rgba(212, 175, 55, 0.15)'; this.style.borderColor='rgba(212, 175, 55, 0.4)';"
         onmouseout="this.style.boxShadow='0 2px 10px rgba(0,0,0,0.1)'; this.style.borderColor='rgba(51, 65, 85, 0.6)';">
        @php
            $locale = app_locale();
            $flagMap = ['ar' => 'EG', 'en' => 'US'];
            $currentFlag = $flagMap[$locale] ?? 'EG';
            $currentLabel = $locale === 'en' ? 'EN' : 'العربية';
        @endphp
        <div style="width: 20px; height: 20px; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center;">
            <x-flag-icon :country="$currentFlag" size="20px"/>
        </div>
        <span style="font-size: 0.82rem; font-weight: 700; color: #ffffff; letter-spacing: 0.5px;">{{ $currentLabel }}</span>
        <i class="fas fa-chevron-down" style="font-size: 0.75rem; color: #94a3b8; transition: transform 0.3s;" :style="open ? 'transform: rotate(180deg);' : ''"></i>
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
         style="display: none; position: absolute; inset-block-start: 100%; inset-inline-end: 0; margin-top: 0.5rem; background: rgba(30, 41, 59, 0.95); backdrop-filter: blur(16px); border: 1px solid rgba(51, 65, 85, 0.8); border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); width: 160px; z-index: 100; overflow: hidden;">
        <button wire:click="setLocale('ar', 'EG')" 
                style="width: 100%; text-align: start; padding: 0.85rem 1rem; background: transparent; border: none; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; gap: 0.75rem; cursor: pointer; transition: background 0.2s;"
                onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
            <div style="width: 18px; height: 18px; border-radius: 50%; overflow: hidden;"><x-flag-icon country="EG" size="18px"/></div>
            <span style="font-size: 0.9rem; font-family: 'Cairo', sans-serif; {{ app_locale() === 'ar' ? 'font-weight: 800; color: #d4af37;' : 'font-weight: 500; color: #e2e8f0;' }}">العربية</span>
            @if(app_locale() === 'ar')
                <i class="fas fa-check" style="margin-inline-start: auto; font-size: 0.8rem; color: #d4af37;"></i>
            @endif
        </button>
        <button wire:click="setLocale('en', 'US')" 
                style="width: 100%; text-align: start; padding: 0.85rem 1rem; background: transparent; border: none; display: flex; align-items: center; gap: 0.75rem; cursor: pointer; transition: background 0.2s;"
                onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
            <div style="width: 18px; height: 18px; border-radius: 50%; overflow: hidden;"><x-flag-icon country="US" size="18px"/></div>
            <span style="font-size: 0.9rem; font-family: 'Plus Jakarta Sans', sans-serif; {{ app_locale() === 'en' ? 'font-weight: 800; color: #d4af37;' : 'font-weight: 500; color: #e2e8f0;' }}">English</span>
            @if(app_locale() === 'en')
                <i class="fas fa-check" style="margin-inline-start: auto; font-size: 0.8rem; color: #d4af37;"></i>
            @endif
        </button>
    </div>
</div>
