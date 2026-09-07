@php $firmName = firm_name(); @endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Login') }} | {{ $firmName }}</title>
    
    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @livewireStyles
    
    <style>
        :root {
            --primary-dark: #1e293b; /* أزرق داكن */
            --primary-darker: #0f172a; /* أزرق ليلي للعمق */
            --gold-accent:  #d4af37; /* ذهبي الميزان */
            --bg-light:     #f8fafc;
            --text-main:    #1e293b;
            --text-muted:   #64748b;
            --border-color: #e2e8f0;
            --danger-color: #ef4444;
            --transition:   0.3s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: {{ app()->getLocale() === 'ar' ? "'Tajawal', sans-serif" : "'Plus Jakarta Sans', sans-serif" }};
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1.5rem;
            
            /* خلفية احترافية: تدرج لوني داكن مع إضاءة خفيفة من المنتصف */
            background: radial-gradient(circle at center, #2a3b52 0%, var(--primary-darker) 100%);
            position: relative;
            z-index: 1;
        }

        /* شبكة زخرفية شفافة جداً في الخلفية لإضافة لمسة فخامة (اختياري) */
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(rgba(212, 175, 55, 0.05) 1px, transparent 1px);
            background-size: 30px 30px;
            z-index: -1;
        }

        /* ===== بوكس تسجيل الدخول (Centered Card) ===== */
        .login-card {
            background-color: #ffffff;
            width: 100%;
            max-width: 440px;
            border-radius: 20px;
            padding: 3rem 2.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== رأس البوكس (اللوجو والعنوان) ===== */
        .card-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .brand-icon {
            font-size: 3.5rem;
            color: var(--gold-accent);
            margin-bottom: 1rem;
            display: inline-block;
        }

        .brand-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-dark);
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .brand-subtitle {
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        /* ===== عناصر الفورم ===== */
        .form-group { margin-bottom: 1.5rem; position: relative; }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-main);
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            font-family: inherit;
            font-size: 1rem;
            color: var(--text-main);
            background-color: var(--bg-light);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--gold-accent);
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.15);
        }

        /* حقل الباسورد والأيقونة */
        .password-input-wrapper { position: relative; }
        .toggle-password {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 1.1rem;
            transition: 0.2s;
            padding: 0.2rem;
        }
        .toggle-password:hover { color: var(--primary-dark); }
        .form-control.has-icon { padding-left: 45px; }

        /* رسائل الخطأ */
        .invalid-feedback {
            display: block;
            color: var(--danger-color);
            font-size: 0.85rem;
            margin-top: 0.5rem;
            font-weight: 600;
        }
        .is-invalid { border-color: var(--danger-color); }
        .is-invalid:focus { box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15); }

        /* خيارات إضافية */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        
        .remember-me {
            display: flex; align-items: center; gap: 0.5rem;
            cursor: pointer; color: var(--text-muted); font-weight: 500;
        }
        .remember-me input { cursor: pointer; accent-color: var(--gold-accent); width: 16px; height: 16px; }
        
        .forgot-password { color: var(--primary-dark); text-decoration: none; font-weight: 700; transition: 0.2s; }
        .forgot-password:hover { color: var(--gold-accent); }

        /* زر الدخول */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-dark);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.75rem;
        }
        
        .btn-submit:hover {
            background-color: var(--gold-accent); /* تغيير للذهبي عند الوقوف يعطي فخامة */
            color: var(--primary-darker);
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(212, 175, 55, 0.2);
        }

        /* ===== زر التجربة المجانية (نسخة تجريبية) ===== */
        .demo-access-wrapper {
            margin-top: 1.5rem;
            text-align: center;
        }

        .demo-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin-bottom: 1.25rem;
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 600;
        }

        .demo-divider::before,
        .demo-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }

        .demo-divider span {
            padding: 0 0.75rem;
        }

        .btn-demo-access {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            width: 100%;
            padding: 13px 16px;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.08) 0%, rgba(212, 175, 55, 0.18) 100%);
            color: #9a7b21;
            border: 1.5px solid rgba(212, 175, 55, 0.45);
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.98rem;
            font-weight: 700;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
            box-sizing: border-box;
        }

        .btn-demo-access:hover {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.22) 0%, rgba(212, 175, 55, 0.35) 100%);
            color: #785e13;
            border-color: var(--gold-accent);
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(212, 175, 55, 0.18);
        }

        .btn-demo-access i.fa-arrow-left {
            font-size: 0.85rem;
            transition: transform 0.2s ease;
        }

        .btn-demo-access:hover i.fa-arrow-left {
            transform: translateX(-4px);
        }

        /* حقوق الملكية تحت البوكس */
        .login-footer {
            position: absolute;
            bottom: -2.5rem;
            left: 0;
            right: 0;
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
        }

        /* ===== Responsive ===== */
        @media (max-width: 480px) {
            .login-card { padding: 2.5rem 1.5rem; }
            .brand-title { font-size: 1.6rem; }
        }
    </style>
</head>
<body>

    <div style="position: absolute; top: 1.5rem; inset-inline-end: 1.5rem; z-index: 50;">
        <livewire:settings.locale-switcher />
    </div>

    <div class="login-card">
        
        <div class="card-header">
            <i class="fas fa-scale-balanced brand-icon"></i>
            <h1 class="brand-title">{{ $firmName }}</h1>
            <p class="brand-subtitle">{{ __('Enter your credentials to access the dashboard') }}</p>
        </div>

        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input type="email" id="email" name="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" 
                       placeholder="{{ __('example@domain.com') }}" required autofocus autocomplete="email">
                
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <i class="fas fa-exclamation-circle" style="margin-left: 4px;"></i>
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <div class="password-input-wrapper">
                    <input type="password" id="password" name="password" 
                           class="form-control has-icon @error('password') is-invalid @enderror" 
                           placeholder="{{ __('••••••••') }}" required autocomplete="current-password">
                    
                    <button type="button" class="toggle-password" id="togglePasswordBtn" aria-label="{{ __('Show Password') }}">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <i class="fas fa-exclamation-circle" style="margin-left: 4px;"></i>
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    {{ __('Remember me') }}
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ url('/password/reset') }}" class="forgot-password">{{ __('Forgot password?') }}</a>
                @endif
            </div>

            <button type="submit" class="btn-submit">
                <span>{{ __('Sign In') }}</span>
                <i class="fas fa-sign-in-alt"></i>
            </button>
        </form>

        {{-- زر التجربة المجانية (نسخة تجريبية) --}}
        <div class="demo-access-wrapper">
            <div class="demo-divider">
                <span>{{ __('or') }}</span>
            </div>
            <a href="{{ route('demo.access') }}" wire:navigate class="btn-demo-access">
                <i class="fas fa-sparkles" style="color: var(--gold-accent);"></i>
                <span>{{ __('Try Demo Free (Interactive)') }}</span>
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>

        <div class="login-footer">
            © {{ date('Y') }} {{ __('All rights reserved for') }} {{ $firmName }}.
        </div>
    </div>

    <script>
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');

        togglePasswordBtn.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    </script>

    @livewireScripts
</body>
</html>