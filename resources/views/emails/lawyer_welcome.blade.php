<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مرحباً بك في منصة {{ $firmName }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; direction: rtl; text-align: right;">

    <table width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f6f9; padding: 20px 0;">
        <tr>
            <td align="center">
                
                <table width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                    
                    <tr>
                        <td style="background-color: #0f172a; padding: 30px; text-align: center;">
                            <h1 style="color: #f59e0b; margin: 0; font-size: 28px; font-weight: 800; letter-spacing: 1px;">⚖️ {{ $firmName }}</h1>
                            <p style="color: #94a3b8; margin: 5px 0 0 0; font-size: 14px;">لنظام إدارة المحاماة والاستشارات القانونية</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px; color: #334155;">
                            <h2 style="color: #0f172a; font-size: 22px; margin-bottom: 20px;">الأستاذ الفاضل / {{ $lawyer->name }}</h2>
                            
                            <p style="font-size: 16px; line-height: 1.8; margin-bottom: 25px;">
                                تحية طيبة وبعد،،<br>
                                يسعدنا جداً انضمامكم إلى منصة <strong>{{ $firmName }}</strong>. لقد قام مدير النظام بإنشاء حساب احترافي خاص بسيادتكم للبدء في إدارة قضاياكم وموكلينكم بكل سهولة وذكاء.
                            </p>

                            <div style="background-color: #f8fafc; border-right: 4px solid #f59e0b; padding: 20px; border-radius: 6px; margin-bottom: 30px;">
                                <h3 style="margin-top: 0; color: #0f172a; font-size: 16px; margin-bottom: 15px;">🔒 بيانات تسجيل الدخول الخاصة بك:</h3>
                                
                                <table width="100%" cellspacing="0" cellpadding="5" style="font-size: 15px;">
                                    <tr>
                                        <td width="30%" style="font-weight: bold; color: #64748b;">البريد الإلكتروني:</td>
                                        <td style="color: #0f172a; font-family: monospace; font-size: 16px;"><strong>{{ $lawyer->email }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; color: #64748b;">كلمة المرور المؤقتة:</td>
                                        <td style="color: #d97706; font-family: monospace; font-size: 16px;"><strong>{{ $plainPassword }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; color: #64748b;">الدرجة المقيدة:</td>
                                        <td style="color: #0f172a;">{{ $lawyer->degree }}</td>
                                    </tr>
                                </table>
                            </div>

                            <table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 30px;">
                                <tr>
                                    <td align="center">
                                        {{-- Dynamic login URL — no more hardcoded localhost --}}
                                        <a href="{{ url('/login') }}" style="background-color: #0f172a; color: #ffffff; padding: 14px 35px; font-size: 16px; font-weight: bold; text-decoration: none; border-radius: 8px; display: inline-block; box-shadow: 0 4px 10px rgba(15,23,42,0.2);">
                                            دخول إلى لوحة التحكم
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 14px; color: #64748b; line-height: 1.6; background-color: #fffbeb; padding: 12px; border-radius: 6px; border: 1px dashed #fef3c7;">
                                💡 <strong>ملاحظة أمنية هامّة:</strong> حرصاً على أمان بيانات القضايا والموكلين، يرجى تغيير كلمة المرور المؤقتة فور تسجيل دخولك الأول من خلال إعدادات حسابك.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #e2e8f0; color: #94a3b8; font-size: 13px;">
                            <p style="margin: 0 0 5px 0;">هذا البريد الإلكتروني تم إنشاؤه تلقائياً، برجاء عدم الرد عليه.</p>
                            <p style="margin: 0;">&copy; {{ date('Y') }} منصة {{ $firmName }}. جميع الحقوق محفوظة.</p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>