{{-- Livewire Component View: livewire.demo.demo-access --}}
<div>
    <style>
        .demo-page {
            position: fixed; inset: 0; z-index: 9999; overflow-y: auto;
            min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            padding: 6rem 1rem 2rem 1rem;
        }
        .demo-page::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse at 30% 40%, rgba(212,175,55,.08) 0%, transparent 60%),
                        radial-gradient(ellipse at 70% 70%, rgba(99,102,241,.06) 0%, transparent 60%);
        }

        .demo-topbar {
            position: fixed; top: 0; inset-inline-start: 0; inset-inline-end: 0;
            z-index: 10000; padding: 0.75rem 2rem;
            display: flex; align-items: center; justify-content: flex-end;
            background: rgba(15,23,42,0.7); backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .demo-topbar .brand { display: flex; align-items: center; gap: 0.6rem; text-decoration: none; }
        .demo-topbar .brand i { color: #d4af37; font-size: 1.3rem; }
        .demo-topbar .brand span { color: #fff; font-size: 1.1rem; font-weight: 800; letter-spacing: 0.5px; }
        .demo-topbar-inner { display: flex; align-items: center; justify-content: space-between; width: 100%; max-width: 620px; margin: 0 auto; }

        .demo-card {
            position: relative; z-index: 1; max-width: 580px; width: 100%;
            background: rgba(255,255,255,.04); backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.1); border-radius: 24px; padding: 3rem 2.5rem;
            text-align: center; box-shadow: 0 32px 80px rgba(0,0,0,.5);
            margin: auto;
        }
        .demo-badge {
            display: inline-flex; align-items: center; gap: .4rem;
            background: rgba(212,175,55,.12); border: 1px solid rgba(212,175,55,.25);
            color: #fbbf24; padding: .4rem 1rem; border-radius: 20px; font-size: .85rem;
            font-weight: 700; margin-bottom: 1.5rem; letter-spacing: .5px;
        }
        .demo-icon { font-size: 4rem; margin-bottom: 1rem; display: block; }
        .demo-title { font-size: 2rem; font-weight: 900; color: #fff; margin-bottom: .5rem; line-height: 1.2; }
        .demo-subtitle { color: #94a3b8; font-size: .95rem; line-height: 1.7; margin-bottom: 2rem; }
        .demo-features {
            display: grid; grid-template-columns: 1fr 1fr; gap: .6rem; margin-bottom: 2rem;
            text-align: {{ app_locale() === 'en' ? 'left' : 'right' }};
        }
        .demo-feature {
            background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
            border-radius: 9px; padding: .65rem .85rem; font-size: .83rem;
            color: #cbd5e1; display: flex; align-items: center; gap: .5rem;
        }
        .demo-feature .f-icon { font-size: 1.1rem; flex-shrink: 0; }

        .btn-demo-country {
            width: 100%; padding: 1rem; font-size: 1.05rem; font-weight: 800;
            border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; cursor: pointer; font-family: inherit;
            background: rgba(255,255,255,0.05); color: #fff;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            transition: 0.3s;
        }
        .btn-demo-country:hover {
            background: rgba(212,175,55,0.2); border-color: rgba(212,175,55,0.5); transform: translateY(-2px);
        }
        .btn-demo-country:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
        .btn-demo-country .flag { font-size: 1.4rem; }

        .demo-disclaimer {
            font-size: .78rem; color: rgba(148,163,184,.6); margin-top: 1.25rem;
            line-height: 1.6;
        }

        .demo-loading-text { color: #d4af37; font-weight: bold; font-size: 0.95rem; }
    </style>

    {{-- Fixed top bar with locale switcher --}}
    <div class="demo-topbar">
        <div class="demo-topbar-inner">
            <a href="/" class="brand">
                <i class="fas fa-scale-balanced"></i>
                <span>{{ firm_name() }}</span>
            </a>
            <livewire:settings.locale-switcher />
        </div>
    </div>

    <div class="demo-page">
        <div class="demo-card">
            <div class="demo-badge">{{ __('Interactive Demo Showcase') }}</div>
            <span class="demo-icon">⚖️</span>
            <h1 class="demo-title">{{ __('Discover the Power of') }}<br>{{ firm_name() }}</h1>
            <p class="demo-subtitle">
                {{ __('Explore the most advanced law firm management system with no registration or personal data required. Full interactive experience in seconds.') }}
            </p>

            <div class="demo-features">
                <div class="demo-feature"><span class="f-icon">📁</span> {{ __('Case Management') }}</div>
                <div class="demo-feature"><span class="f-icon">📅</span> {{ __('Court Sessions') }}</div>
                <div class="demo-feature"><span class="f-icon">👥</span> {{ __('Client Portals') }}</div>
                <div class="demo-feature"><span class="f-icon">💰</span> {{ __('Expense Tracking') }}</div>
                <div class="demo-feature"><span class="f-icon">🤖</span> {{ __('Smart Legal Assistant') }}</div>
                <div class="demo-feature"><span class="f-icon">📊</span> {{ __('Analytics Dashboard') }}</div>
            </div>

            <h3 style="color: #fff; font-size: 1.1rem; margin-bottom: 1rem; font-weight: 700;">{{ __('Select your country to customize the experience:') }}</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 0.8rem; margin-bottom: 1rem;">
                <button wire:click="enterDemo('EG')" wire:loading.attr="disabled" class="btn-demo-country">
                    <x-flag-icon country="EG" size="1.5rem" />
                    <span>{{ __('Egypt') }}</span>
                </button>
                <button wire:click="enterDemo('SA')" wire:loading.attr="disabled" class="btn-demo-country">
                    <x-flag-icon country="SA" size="1.5rem" />
                    <span>{{ __('Saudi Arabia') }}</span>
                </button>
                <button wire:click="enterDemo('AE')" wire:loading.attr="disabled" class="btn-demo-country">
                    <x-flag-icon country="AE" size="1.5rem" />
                    <span>{{ __('UAE') }}</span>
                </button>
                <button wire:click="enterDemo('OM')" wire:loading.attr="disabled" class="btn-demo-country">
                    <x-flag-icon country="OM" size="1.5rem" />
                    <span>{{ __('Oman') }}</span>
                </button>
                <button wire:click="enterDemo('US')" wire:loading.attr="disabled" class="btn-demo-country">
                    <x-flag-icon country="US" size="1.5rem" />
                    <span>{{ __('United States') }}</span>
                </button>
                <button wire:click="enterDemo('GB')" wire:loading.attr="disabled" class="btn-demo-country">
                    <x-flag-icon country="GB" size="1.5rem" />
                    <span>{{ __('United Kingdom') }}</span>
                </button>
            </div>

            <div wire:loading class="demo-loading-text">
                ⏳ {{ __('Preparing demo environment...') }}
            </div>

            <p class="demo-disclaimer">
                {{ __('No registration or personal data required · Data shown is for demonstration only') }}<br>
                {{ __('For full version enquiries:') }} <a href="mailto:info@elmizzan.app" style="color:#fbbf24;">info@elmizzan.app</a>
            </p>
        </div>
    </div>
</div>
