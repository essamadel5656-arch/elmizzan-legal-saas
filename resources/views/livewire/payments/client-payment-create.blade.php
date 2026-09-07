<div>
    <style>
        .payment-page { max-width:680px; margin:0 auto; }
        .payment-header { margin-bottom:2rem; }
        .payment-header h1 { font-size:1.5rem; font-weight:800; color:var(--text-primary); }
        .payment-header p  { color:var(--text-secondary); font-size:.9rem; margin-top:.35rem; }

        .mode-switcher { display:flex; gap:.75rem; margin-bottom:2rem; }
        .mode-btn {
            flex:1; padding:1rem; border:2px solid var(--border-color); border-radius:12px;
            background:var(--card-bg,#fff); cursor:pointer; font-family:inherit;
            display:flex; flex-direction:column; align-items:center; gap:.4rem;
            transition:.25s; font-size:.9rem; color:var(--text-secondary);
        }
        .mode-btn:hover { border-color:var(--gold-accent,#d4af37); }
        .mode-btn.active { border-color:var(--gold-accent,#d4af37); background:rgba(212,175,55,.06); color:var(--text-primary); font-weight:700; }
        .mode-btn .mode-icon { font-size:1.8rem; }

        .pay-card { background:var(--card-bg,#fff); border:1px solid var(--border-color); border-radius:14px; padding:1.75rem; box-shadow:0 2px 8px rgba(0,0,0,.04); }
        .pay-section { margin-bottom:1.5rem; }
        .pay-section-title { font-size:.9rem; font-weight:800; color:var(--text-primary); margin-bottom:.85rem; display:flex; align-items:center; gap:.4rem; padding-bottom:.6rem; border-bottom:2px solid var(--primary-bg,#f1f5f9); }

        .pay-info-row { display:flex; align-items:center; justify-content:space-between; padding:.65rem 0; border-bottom:1px solid var(--primary-bg,#f1f5f9); }
        .pay-info-row:last-child { border-bottom:none; }
        .pay-info-label { font-size:.85rem; color:var(--text-secondary); }
        .pay-info-value { font-weight:700; font-size:.92rem; color:var(--text-primary); display:flex; align-items:center; gap:.4rem; }
        .copy-btn { background:none; border:none; color:var(--gold-accent,#d4af37); cursor:pointer; font-size:.85rem; padding:.2rem .4rem; border-radius:5px; transition:.15s; }
        .copy-btn:hover { background:rgba(212,175,55,.1); }

        .form-group-p { margin-bottom:1.25rem; }
        .form-label-p { display:block; font-size:.85rem; font-weight:700; color:var(--text-primary); margin-bottom:.4rem; }
        .form-input-p {
            width:100%; padding:.7rem 1rem; border:1.5px solid var(--border-color); border-radius:8px;
            font-size:.92rem; font-family:'Tajawal',sans-serif; color:var(--text-primary);
            background:var(--input-bg,#f8fafc); outline:none; transition:.2s;
        }
        .form-input-p:focus { border-color:var(--gold-accent); box-shadow:0 0 0 3px rgba(212,175,55,.12); }

        .btn-submit-pay {
            width:100%; padding:.9rem; background:linear-gradient(135deg,#1e293b,#0f172a);
            color:var(--gold-accent,#d4af37); border:none; border-radius:10px;
            font-size:1rem; font-weight:800; cursor:pointer; font-family:inherit;
            display:flex; align-items:center; justify-content:center; gap:.5rem; transition:.2s; margin-top:1.5rem;
        }
        .btn-submit-pay:hover { opacity:.88; transform:translateY(-1px); }
    </style>

    <div class="payment-page">

        @if(session()->has('payment_success'))
            <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);color:#15803d;border-radius:9px;padding:.85rem 1.2rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:.5rem;">
                ✅ {{ session('payment_success') }}
            </div>
        @endif

        <div class="payment-header">
            <h1>{{ __('💳 تسجيل دفعة') }}</h1>
            <p>القضية رقم: <strong>{{ $case->case_number }}</strong></p>
        </div>

        {{-- Mode Switcher --}}
        <div class="mode-switcher">
            <button type="button" wire:click="$set('mode','in_person')" class="mode-btn {{ $mode === 'in_person' ? 'active' : '' }}">
                <span class="mode-icon">🏢</span>
                <strong>{{ __('دفع نقدي بالمكتب') }}</strong>
                <span style="font-size:.78rem;">{{ __('حدد موعداً وادفع بشكل شخصي') }}</span>
            </button>
            <button type="button" wire:click="$set('mode','transfer')" class="mode-btn {{ $mode === 'transfer' ? 'active' : '' }}">
                <span class="mode-icon">🏦</span>
                <strong>{{ __('تحويل بنكي / إلكتروني') }}</strong>
                <span style="font-size:.78rem;">{{ __('أرسل إيصال التحويل للتأكيد') }}</span>
            </button>
        </div>

        <div class="pay-card">
            <form wire:submit="submit">

                {{-- Common: Amount --}}
                <div class="form-group-p">
                    <label class="form-label-p">{{ __('💰 المبلغ (ج.م.)') }}</label>
                    <input type="number" step="0.01" min="0.01" wire:model="amount" class="form-input-p" placeholder="0.00">
                    @error('amount') <span style="color:#dc2626;font-size:.8rem;">{{ $message }}</span> @enderror
                </div>

                @if($mode === 'in_person')
                    {{-- Option A: In-Person --}}
                    <div class="pay-section">
                        <div class="pay-section-title">{{ __('🏢 تفاصيل موعد الدفع بالمكتب') }}</div>
                        <div class="form-group-p">
                            <label class="form-label-p">{{ __('📅 تاريخ الحضور للمكتب') }}</label>
                            <input type="date" wire:model="payment_date" class="form-input-p" min="{{ date('Y-m-d') }}">
                            @error('payment_date') <span style="color:#dc2626;font-size:.8rem;">{{ $message }}</span> @enderror
                        </div>
                        <div style="background:rgba(59,130,246,.06);border:1px solid rgba(59,130,246,.2);border-radius:8px;padding:.85rem 1rem;font-size:.85rem;color:#2563eb;">
                            ℹ️ سيتم إنشاء موعد تلقائياً في تقويم المكتب وإشعار المحامي المسؤول عنك.
                        </div>
                    </div>

                @else
                    {{-- Option B: Transfer — show bank details then form --}}
                    <div class="pay-section">
                        <div class="pay-section-title">{{ __('🏦 بيانات التحويل') }}</div>
                        @if($bankName || $bankIban)
                            <div class="pay-info-row">
                                <span class="pay-info-label">{{ __('🏛 اسم البنك') }}</span>
                                <span class="pay-info-value">{{ $bankName ?: '—' }}</span>
                            </div>
                            <div class="pay-info-row">
                                <span class="pay-info-label">{{ __('🔢 رقم IBAN') }}</span>
                                <span class="pay-info-value">
                                    {{ $bankIban ?: '—' }}
                                    @if($bankIban)<button type="button" class="copy-btn" onclick="navigator.clipboard.writeText('{{ $bankIban }}').then(()=>this.textContent='✓')">{{ __('نسخ') }}</button>@endif
                                </span>
                            </div>
                        @endif
                        @if($instapay)
                            <div class="pay-info-row">
                                <span class="pay-info-label">⚡ InstaPay</span>
                                <span class="pay-info-value">
                                    {{ $instapay }}
                                    <button type="button" class="copy-btn" onclick="navigator.clipboard.writeText('{{ $instapay }}').then(()=>this.textContent='✓')">{{ __('نسخ') }}</button>
                                </span>
                            </div>
                        @endif
                        @if($mobileWallet)
                            <div class="pay-info-row">
                                <span class="pay-info-label">{{ __('📱 محفظة إلكترونية') }}</span>
                                <span class="pay-info-value">
                                    {{ $mobileWallet }}
                                    <button type="button" class="copy-btn" onclick="navigator.clipboard.writeText('{{ $mobileWallet }}').then(()=>this.textContent='✓')">{{ __('نسخ') }}</button>
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="form-group-p">
                        <label class="form-label-p">{{ __('🔢 رقم مرجع العملية / رقم التحويل') }}</label>
                        <input type="text" wire:model="reference" class="form-input-p" placeholder="{{ __('أدخل الرقم المرجعي للعملية') }}">
                        @error('reference') <span style="color:#dc2626;font-size:.8rem;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group-p">
                        <label class="form-label-p">{{ __('📸 صورة إيصال التحويل (اختياري)') }}</label>
                        <input type="file" wire:model="receipt" class="form-input-p" style="padding:.45rem;" accept="image/*,.pdf">
                        @error('receipt') <span style="color:#dc2626;font-size:.8rem;">{{ $message }}</span> @enderror
                    </div>
                @endif

                <div class="form-group-p">
                    <label class="form-label-p">{{ __('📝 ملاحظات (اختياري)') }}</label>
                    <textarea wire:model="notes" class="form-input-p" rows="2" placeholder="{{ __('أي ملاحظة إضافية...') }}"></textarea>
                </div>

                <button type="submit" wire:loading.attr="disabled" class="btn-submit-pay">
                    <span wire:loading.remove>
                        {{ $mode === 'in_person' ? __('📅 تأكيد الموعد وتسجيل الدفعة') : __('📤 إرسال إيصال التحويل') }}
                    </span>
                    <span wire:loading>{{ __('⏳ جاري المعالجة...') }}</span>
                </button>
            </form>
        </div>

        <div style="text-align:center;margin-top:1rem;">
            <a href="{{ route('client-portal.dashboard') }}" wire:navigate style="color:var(--text-secondary);font-size:.85rem;text-decoration:none;">
                ← العودة للبوابة
            </a>
        </div>
    </div>
</div>
