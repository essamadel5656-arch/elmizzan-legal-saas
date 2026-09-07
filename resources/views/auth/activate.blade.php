<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفعيل الحساب | {{ firm_name() }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; background: linear-gradient(135deg,#0f172a,#1e293b); min-height:100vh; display:flex; align-items:center; justify-content:center; margin:0; padding:2rem; }
        .card { background:rgba(255,255,255,.04); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,.1); border-radius:20px; padding:2.5rem; max-width:460px; width:100%; box-shadow:0 32px 80px rgba(0,0,0,.5); }
        h1 { color:#d4af37; font-size:1.5rem; font-weight:800; text-align:center; margin-bottom:.4rem; }
        .subtitle { color:#94a3b8; font-size:.9rem; text-align:center; margin-bottom:2rem; }
        label { display:block; font-size:.85rem; font-weight:700; color:#e2e8f0; margin-bottom:.4rem; }
        input { width:100%; padding:.75rem 1rem; border:1.5px solid rgba(255,255,255,.1); border-radius:8px; font-size:.95rem; color:#e2e8f0; background:rgba(255,255,255,.06); font-family:inherit; outline:none; margin-bottom:1rem; box-sizing:border-box; transition:.2s; }
        input:focus { border-color:#d4af37; box-shadow:0 0 0 3px rgba(212,175,55,.15); }
        button { width:100%; padding:.85rem; background:linear-gradient(135deg,#d4af37,#b8960c); color:#0f172a; border:none; border-radius:10px; font-size:1rem; font-weight:800; cursor:pointer; font-family:inherit; transition:.2s; margin-top:.5rem; }
        button:hover { opacity:.88; transform:translateY(-1px); }
        .error { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3); color:#f87171; border-radius:8px; padding:.65rem 1rem; margin-bottom:1rem; font-size:.85rem; }
        @if($errors->any()) .err-show { display:block; } @endif
    </style>
</head>
<body>
    <div class="card">
        <h1>⚖️ {{ firm_name() }}</h1>
        <p class="subtitle">مرحباً {{ $user->name }} — تفعيل بوابة الموكل</p>

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('activate.set-password', $token) }}">
            @csrf
            <label for="password">كلمة المرور الجديدة</label>
            <input type="password" id="password" name="password" required minlength="8" placeholder="8 أحرف على الأقل">

            <label for="password_confirmation">تأكيد كلمة المرور</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="أعد إدخال كلمة المرور">

            <button type="submit">🔑 تفعيل الحساب والدخول</button>
        </form>
    </div>
</body>
</html>
