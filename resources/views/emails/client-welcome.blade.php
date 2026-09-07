<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مرحباً بك في {{ $firmName }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; margin: 0; padding: 0; direction: rtl; }
        .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #1e293b, #0f172a); padding: 32px 40px; text-align: center; }
        .header h1 { color: #d4af37; font-size: 1.8rem; margin: 0 0 4px; }
        .header p  { color: #94a3b8; font-size: 0.9rem; margin: 0; }
        .body { padding: 36px 40px; }
        .body h2 { color: #1e293b; font-size: 1.2rem; margin-bottom: 16px; }
        .body p  { color: #475569; line-height: 1.7; font-size: 0.95rem; margin-bottom: 16px; }
        .btn { display: inline-block; background: linear-gradient(135deg, #d4af37, #b8960c); color: #fff; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 1rem; margin: 8px 0 24px; }
        .footer { background: #f1f5f9; padding: 20px 40px; text-align: center; color: #94a3b8; font-size: 0.8rem; }
        .notice { background: #fef3c7; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; color: #92400e; font-size: 0.85rem; margin-top: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚖️ {{ $firmName }}</h1>
            <p>البوابة الإلكترونية للموكلين</p>
        </div>
        <div class="body">
            <h2>مرحباً {{ $clientName }}،</h2>
            <p>
                نود إعلامك بأنه تم إنشاء حساب لك في البوابة الإلكترونية لـ <strong>{{ $firmName }}</strong>.
                من خلال هذه البوابة، يمكنك متابعة تقدم قضاياك، مواعيد الجلسات، ورصيد الأتعاب.
            </p>
            <p>لتفعيل حسابك وتعيين كلمة مرور خاصة بك، يرجى الضغط على الزر أدناه:</p>
            <div style="text-align:center;">
                <a href="{{ $activationUrl }}" class="btn">🔑 تفعيل الحساب وتعيين كلمة المرور</a>
            </div>
            <div class="notice">
                ⏱️ رابط التفعيل صالح لمدة <strong>48 ساعة</strong> من تاريخ إرسال هذا البريد. إذا لم تطلب هذا الحساب، يمكنك تجاهل هذه الرسالة.
            </div>
        </div>
        <div class="footer">
            <p>{{ $firmName }} — جميع الحقوق محفوظة © {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
