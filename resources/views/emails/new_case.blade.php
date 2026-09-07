<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إشعار قضية جديدة</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333333;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: right;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f6f9;
            padding: 20px 0;
        }
        .email-container {
            max-width: 600px;
            background-color: #ffffff;
            margin: 0 auto;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            border: 1px solid #e1e6eb;
        }
        .header {
            background-color: #1a2a3a;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 3px solid #c9a66b;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }
        .content {
            padding: 30px 25px;
        }
        .welcome-text {
            font-size: 16px;
            line-height: 1.6;
            color: #4a5568;
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            background-color: #f8fafd;
            border-radius: 6px;
            overflow: hidden;
        }
        .info-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eaf0f6;
            font-size: 15px;
        }
        .info-table td.label {
            font-weight: bold;
            color: #1a2a3a;
            width: 30%;
            border-left: 1px solid #eaf0f6;
        }
        .info-table td.value {
            color: #2d3748;
        }
        .info-table tr:last-child td {
            border-bottom: none;
        }
        .btn-container {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .btn {
            background-color: #c9a66b;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 35px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            font-size: 15px;
            box-shadow: 0 2px 4px rgba(201, 166, 107, 0.2);
        }
        .footer {
            background-color: #f8fafd;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #718096;
            border-top: 1px solid #e1e6eb;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="header">
                <h1>⚖️ {{ $firmName }}</h1>
            </div>
            
            <div class="content">
                <p class="welcome-text">مرحباً زميلنا العزيز،</p>
                <p class="welcome-text">نود إحاطتك علماً بأنه قد تم تسجيل قضية جديدة بنجاح في النظام، وتم إسنادها إليك مباشرة لمتابعة الإجراءات القانونية والدفاع. إليك تفاصيل القضية المحفوظة:</p>
                
                <table class="info-table">
                    <tr>
                        <td class="label">🔢 رقم القضية</td>
                        <td class="value"><strong>{{ $case->case_number }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">👤 اسم الخصم</td>
                        <td class="value">{{ $case->rival_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">🏛️ الدائرة القضائية</td>
                        <td class="value">{{ $case->circuit ?? 'غير محددة بعد' }}</td>
                    </tr>
                    <tr>
                        <td class="label">💼 حالة القضية</td>
                        <td class="value">
                            <span style="background-color: #e2e8f0; padding: 4px 10px; border-radius: 4px; font-size: 13px; font-weight: 600; color: #2d3748;">
                                {{ $case->status }}
                            </span>
                        </td>
                    </tr>
                    @if($case->description)
                    <tr>
                        <td class="label">📝 تفاصيل إضافية</td>
                        <td class="value">{{ $case->description }}</td>
                    </tr>
                    @endif
                </table>

                <p class="welcome-text" style="margin-top: 25px;">يمكنك الآن الانتقال إلى النظام لمراجعة مستندات ومرفقات القضية كاملة، أو جدولة الجلسات القادمة عبر الزر التالي:</p>
                
                <div class="btn-container">
                    <a href="{{ url('/cases/' . $case->id) }}" class="btn">عرض ملف القضية بالكامل</a>
                </div>
            </div>
            
            <div class="footer">
                إشعار تلقائي - نظام إدارة مكتب المحاماة الذكي "{{ $firmName }}" &copy; {{ date('Y') }}
            </div>
        </div>
    </div>
</body>
</html>