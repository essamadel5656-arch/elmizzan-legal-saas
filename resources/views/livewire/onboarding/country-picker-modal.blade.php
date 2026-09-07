<div x-data="{ open: @entangle('show') }" x-show="open" style="position: fixed; inset: 0; z-index: 8888; display: flex; align-items: center; justify-content: center; background: rgba(15, 23, 42, 0.82); backdrop-filter: blur(8px); padding: 1.5rem; transition: opacity 0.3s ease;">
    <div style="background: var(--card-bg, #ffffff); border: 1px solid var(--border-color, #e2e8f0); border-radius: 20px; max-width: 580px; width: 100%; padding: 2.2rem 2rem; box-shadow: 0 25px 60px rgba(0,0,0,0.4); text-align: center; position: relative;">
        
        <div style="width: 64px; height: 64px; background: rgba(212, 175, 55, 0.12); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;">
            <i class="fas fa-globe-asia" style="font-size: 1.8rem; color: var(--gold-accent, #d4af37);"></i>
        </div>

        <h2 style="font-size: 1.45rem; font-weight: 900; color: var(--text-primary); margin-bottom: 0.5rem;">
            مرحباً بك في {{ firm_name() }}
        </h2>
        <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.6; margin-bottom: 1.8rem;">
            يرجى تحديد دولة نشاط المكتب لتخصيص العملة، المحاكم، والمصطلحات القضائية تلقائياً. (يتم التحديد لمرة واحدة ويمكن تعديله لاحقاً من الإعدادات).
        </p>

        {{-- 4 Primary Countries Grid --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <button type="button" wire:click="selectCountry('EG')" wire:loading.attr="disabled"
                    style="display: flex; align-items: center; gap: 0.85rem; padding: 1rem 1.1rem; border-radius: 12px; border: 1.5px solid var(--border-color, #e2e8f0); background: var(--primary-bg, #f8fafc); cursor: pointer; transition: all 0.2s; text-align: right;"
                    onmouseover="this.style.borderColor='var(--gold-accent)'; this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.borderColor='var(--border-color, #e2e8f0)'; this.style.transform='none';">
                <x-flag-icon country="EG" size="2rem" />
                <div>
                    <div style="font-weight: 800; font-size: 1rem; color: var(--text-primary);">جمهورية مصر</div>
                    <div style="font-size: 0.78rem; color: var(--text-secondary);">الجنيه المصري (ج.م.)</div>
                </div>
            </button>

            <button type="button" wire:click="selectCountry('SA')" wire:loading.attr="disabled"
                    style="display: flex; align-items: center; gap: 0.85rem; padding: 1rem 1.1rem; border-radius: 12px; border: 1.5px solid var(--border-color, #e2e8f0); background: var(--primary-bg, #f8fafc); cursor: pointer; transition: all 0.2s; text-align: right;"
                    onmouseover="this.style.borderColor='var(--gold-accent)'; this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.borderColor='var(--border-color, #e2e8f0)'; this.style.transform='none';">
                <x-flag-icon country="SA" size="2rem" />
                <div>
                    <div style="font-weight: 800; font-size: 1rem; color: var(--text-primary);">المملكة العربية السعودية</div>
                    <div style="font-size: 0.78rem; color: var(--text-secondary);">الريال السعودي (ر.س.)</div>
                </div>
            </button>

            <button type="button" wire:click="selectCountry('AE')" wire:loading.attr="disabled"
                    style="display: flex; align-items: center; gap: 0.85rem; padding: 1rem 1.1rem; border-radius: 12px; border: 1.5px solid var(--border-color, #e2e8f0); background: var(--primary-bg, #f8fafc); cursor: pointer; transition: all 0.2s; text-align: right;"
                    onmouseover="this.style.borderColor='var(--gold-accent)'; this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.borderColor='var(--border-color, #e2e8f0)'; this.style.transform='none';">
                <x-flag-icon country="AE" size="2rem" />
                <div>
                    <div style="font-weight: 800; font-size: 1rem; color: var(--text-primary);">الإمارات العربية المتحدة</div>
                    <div style="font-size: 0.78rem; color: var(--text-secondary);">الدرهم الإماراتي (د.إ.)</div>
                </div>
            </button>

            <button type="button" wire:click="selectCountry('OM')" wire:loading.attr="disabled"
                    style="display: flex; align-items: center; gap: 0.85rem; padding: 1rem 1.1rem; border-radius: 12px; border: 1.5px solid var(--border-color, #e2e8f0); background: var(--primary-bg, #f8fafc); cursor: pointer; transition: all 0.2s; text-align: right;"
                    onmouseover="this.style.borderColor='var(--gold-accent)'; this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.borderColor='var(--border-color, #e2e8f0)'; this.style.transform='none';">
                <x-flag-icon country="OM" size="2rem" />
                <div>
                    <div style="font-weight: 800; font-size: 1rem; color: var(--text-primary);">سلطنة عُمان</div>
                    <div style="font-size: 0.78rem; color: var(--text-secondary);">الريال العُماني (ر.ع.)</div>
                </div>
            </button>
        </div>

        {{-- Other Countries Selector --}}
        <div style="padding-top: 1.25rem; border-top: 1px dashed var(--border-color, #e2e8f0); text-align: right;">
            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.5rem;">
                أو اختر دولة أخرى:
            </label>
            <div style="display: flex; gap: 0.5rem;">
                <select wire:model="otherCountry" style="flex: 1; padding: 0.65rem 0.85rem; border-radius: 8px; border: 1.5px solid var(--border-color, #e2e8f0); background: var(--card-bg); color: var(--text-primary); font-family: inherit; font-size: 0.9rem; outline: none;">
                    <option value="">-- اختر الدولة --</option>
                    <option value="KW">الكويت (د.ك.)</option>
                    <option value="BH">البحرين (د.ب.)</option>
                    <option value="QA">قطر (ر.ق.)</option>
                    <option value="JO">الأردن (د.أ.)</option>
                    <option value="MA">المغرب (د.م.)</option>
                    <option value="LY">ليبيا (د.ل.)</option>
                    <option value="IQ">العراق (د.ع.)</option>
                    <option value="LB">لبنان (ل.ل.)</option>
                    <option value="YE">اليمن (ر.ي.)</option>
                    <option value="SY">سوريا (ل.س.)</option>
                </select>
                <button type="button" wire:click="selectOther" wire:loading.attr="disabled"
                        style="padding: 0.65rem 1.3rem; background: var(--sidebar-bg, #1e293b); color: #fff; border: none; border-radius: 8px; font-weight: 700; font-size: 0.9rem; cursor: pointer; font-family: inherit; transition: 0.2s;">
                    تأكيد
                </button>
            </div>
        </div>

        <div wire:loading style="margin-top: 1rem; color: var(--gold-accent, #d4af37); font-weight: bold; font-size: 0.9rem;">
            <i class="fas fa-spinner fa-spin"></i> جاري حفظ وتطبيق إعدادات الدولة...
        </div>

    </div>
</div>
